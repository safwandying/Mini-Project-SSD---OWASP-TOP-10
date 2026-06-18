<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/** OWASP ASVS V2 - Authentication & Session Management | SSDF PW.5 */
class AuthController extends Controller
{
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(LoginRequest $request)
    {
        $key = 'login.' . Str::lower($request->email) . '|' . $request->ip();

        // Rate limit: 5 attempts per 60 seconds
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $secs = RateLimiter::availableIn($key);
            $this->auditSafe('login_rate_limited', "Rate limit hit for: {$request->email}");
            return back()
                ->withErrors(['email' => "Too many attempts. Try again in {$secs}s."])
                ->withInput($request->only('email'));
        }

        // Check credentials
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            $this->auditSafe('login_failed', "Failed login for email: {$request->email}");
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->withInput($request->only('email'));
        }

        // Check if account is banned
        if (!Auth::user()->is_active) {
            Auth::logout();
            $this->auditSafe('login_banned', "Banned user attempted login: {$request->email}");
            return back()
                ->withErrors(['email' => 'Your account has been suspended. Please contact support.'])
                ->withInput($request->only('email'));
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        $user = Auth::user();
        $this->auditSafe('login_success', "User logged in: {$user->email}", $user->id);

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('products.index');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
            'role'      => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->auditSafe('user_registered', "New user registered: {$user->email}", $user->id);

        return redirect()->route('products.index')->with('success', 'Welcome to SecureShop!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) { $this->auditSafe('logout', "User logged out: {$user->email}", $user->id); }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out securely.');
    }

    // Silent audit — never crashes the request if audit_logs table missing
    private function auditSafe(string $event, string $desc, ?int $userId = null): void
    {
        try {
            AuditLog::record($event, $desc, $userId);
        } catch (\Exception $e) {}
    }
}
