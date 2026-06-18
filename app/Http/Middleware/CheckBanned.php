<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckBanned Middleware
 * OWASP ASVS V4 — Access Control
 * Blocks banned users. Uses fresh DB lookup to avoid stale session cache.
 */
class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Fresh DB query — avoids stale cached user right after login
            $fresh = \App\Models\User::find(Auth::id());
            if ($fresh && !$fresh->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }
        }
        return $next($request);
    }
}
