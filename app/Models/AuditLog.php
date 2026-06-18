<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

/** OWASP ASVS V7 - Logging & Monitoring | SSDF PW.6 — NO passwords/tokens ever stored */
class AuditLog extends Model
{
    protected $fillable = ['user_id','event','description','ip_address','user_agent'];
    public function user() { return $this->belongsTo(User::class)->withDefault(['name'=>'Guest']); }

    public static function record(string $event, string $description, ?int $userId = null): void
    {
        static::create([
            'user_id'     => $userId,
            'event'       => $event,
            'description' => $description,
            'ip_address'  => request()->ip() ?? '0.0.0.0',
            'user_agent'  => substr(Request::userAgent() ?? 'unknown', 0, 255),
        ]);
    }
}
