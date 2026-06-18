<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/** OWASP ASVS V2 - Password Management | SSDF PW.5 */
class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $recentActivity = AuditLog::where('user_id',$user->id)->orderBy('created_at','desc')->limit(10)->get();
        return view('profile.show', compact('user','recentActivity'));
    }

    public function update(Request $request)
    {
        $request->validate(['name' => ['required','string','min:2','max:100','regex:/^[\pL\s\-\']+$/u']]);
        Auth::user()->update(['name' => $request->name]);
        AuditLog::record('profile_updated','User updated their profile name.', Auth::id());
        return back()->with('success','Profile updated.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required','string'],
            'password'         => ['required','confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        Auth::user()->update(['password' => $request->password]);
        AuditLog::record('password_changed','User changed their password.', Auth::id());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Password changed. Please log in again.');
    }
}
