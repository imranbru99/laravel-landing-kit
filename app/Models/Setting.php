<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'is_encrypted',
        'type',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get typed value.
     */
    public function getParsedValueAttribute(): mixed
    {
        if ($this->value === null) {
            return null;
        }

        $raw = $this->value;
        if ($this->is_encrypted) {
            try {
                $raw = Crypt::decryptString($raw);
            } catch (\Throwable) {
                // If decryption fails, return as-is
            }
        }

        return match ($this->type) {
            'boolean', 'bool' => filter_var($raw, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $raw,
            'float', 'double' => (float) $raw,
            'json', 'array' => json_decode($raw, true) ?? [],
            default => $raw,
        };
    }
}
