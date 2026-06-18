<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** OWASP ASVS V4 - RBAC enforcement | SSDF PW.5 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            // Log silently — don't crash if audit_logs table missing
            try {
                \App\Models\AuditLog::record(
                    'unauthorized_access',
                    "Non-admin user [{$user->id}] attempted: {$request->path()}",
                    $user->id
                );
            } catch (\Exception $e) {}

            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
