<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blocklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'reason',
    ];

    public static function isBlocked(string $phone, ?string $ip = null): bool
    {
        $normalizedPhone = llk_normalize_phone($phone);

        $blocked = self::where(function ($q) use ($normalizedPhone, $ip) {
            $q->where('type', 'phone')->where('value', $normalizedPhone);
            if ($ip) {
                $q->orWhere(function ($q2) use ($ip) {
                    $q2->where('type', 'ip')->where('value', $ip);
                });
            }
        })->exists();

        return $blocked;
    }
}
