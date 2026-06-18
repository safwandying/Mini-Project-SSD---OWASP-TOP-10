<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** OWASP ASVS V7 - Logging & Monitoring | ASVS V4 - Access Control | SSDF PW.6 */
class AdminController extends Controller
{
    public function dashboard()
    {
        // Each stat wrapped individually so one missing table won't kill the page
        $stats = [
            'total_users'    => $this->safe(fn() => User::where('role','user')->count(), 0),
            'total_products' => $this->safe(fn() => Product::count(), 0),
            'total_orders'   => $this->safe(fn() => Order::count(), 0),
            'pending_orders' => $this->safe(fn() => Order::where('status','pending')->count(), 0),
            'revenue'        => $this->safe(fn() => Order::where('status','completed')->sum('total_amount'), 0),
        ];

        $recentLogs = $this->safe(
            fn() => AuditLog::with('user')->orderBy('created_at','desc')->limit(10)->get(),
            collect()
        );

        return view('admin.dashboard', compact('stats','recentLogs'));
    }

    public function auditLogs(Request $request)
    {
        $allowed = [
            'login_success','login_failed','login_rate_limited','login_banned',
            'logout','user_registered','product_created','product_updated',
            'product_deleted','order_placed','order_cancelled','order_status_updated',
            'unauthorized_access','profile_updated','password_changed','user_status_changed',
        ];

        $query = AuditLog::with('user')->orderBy('created_at','desc');

        if ($e = $request->input('event')) {
            if (in_array($e, $allowed)) $query->where('event', $e);
        }
        if ($from = $request->input('from')) { $query->whereDate('created_at','>=',$from); }
        if ($to   = $request->input('to'))  { $query->whereDate('created_at','<=',$to); }

        $logs = $query->paginate(50);

        return view('admin.audit-logs', compact('logs','allowed'));
    }

    public function users()
    {
        $users = User::orderBy('created_at','desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot modify admin accounts.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $action = $user->is_active ? 'activated' : 'banned';

        // Delete sessions so banned user is kicked out immediately
        if (!$user->is_active) {
            try {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            } catch (\Exception $e) {}
        }

        try {
            AuditLog::record(
                'user_status_changed',
                "Admin {$action} user [{$user->id}]: {$user->email}",
                auth()->id()
            );
        } catch (\Exception $e) {}

        return back()->with('success', "User \"{$user->name}\" has been {$action}.");
    }

    public function orders(Request $request)
    {
        $statuses = ['pending','processing','shipped','delivered','completed','cancelled'];

        $query = Order::with('user')->orderBy('created_at','desc');

        if ($s = $request->input('status')) {
            if (in_array($s, $statuses)) { $query->where('status', $s); }
        }

        $orders = $query->paginate(20);

        return view('admin.orders', compact('orders','statuses'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required','in:pending,processing,shipped,delivered,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        try {
            AuditLog::record(
                'order_status_updated',
                "Order #{$order->id} status set to: {$request->status}",
                auth()->id()
            );
        } catch (\Exception $e) {}

        return back()->with('success', 'Order status updated.');
    }

    // Helper: run a callable, return $default on any exception
    private function safe(callable $fn, mixed $default): mixed
    {
        try {
            return $fn();
        } catch (\Exception $e) {
            return $default;
        }
    }
}
