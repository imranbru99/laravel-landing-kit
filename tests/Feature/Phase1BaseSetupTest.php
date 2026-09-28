<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Models\User;
use App\Services\SettingService;
use Spatie\Permission\Models\Role;

test('phone normalization handles prefixes and bengali digits correctly', function () {
    expect(llk_normalize_phone('01712345678'))->toBe('01712345678')
        ->and(llk_normalize_phone('+8801712345678'))->toBe('01712345678')
        ->and(llk_normalize_phone('8801712345678'))->toBe('01712345678')
        ->and(llk_normalize_phone('017-1234-5678'))->toBe('01712345678')
        ->and(llk_normalize_phone('০১৭১২৩৪৫৬৭৮'))->toBe('01712345678')
        ->and(llk_is_valid_bd_phone('01712345678'))->toBeTrue()
        ->and(llk_is_valid_bd_phone('01300000000'))->toBeTrue()
        ->and(llk_is_valid_bd_phone('01999999999'))->toBeTrue()
        ->and(llk_is_valid_bd_phone('01200000000'))->toBeFalse() // Invalid BD telco prefix (012)
        ->and(llk_is_valid_bd_phone('12345'))->toBeFalse();
});

test('currency helper formats BDT and bangla numerals properly', function () {
    expect(llk_bn_number('1250'))->toBe('১২৫০')
        ->and(llk_en_number('১২৫০'))->toBe('1250');

    expect(llk_currency(1500, forceEnglish: true))->toBe('৳ 1,500');
});

test('setting service stores, encrypts, and caches typed settings', function () {
    $service = app(SettingService::class);

    $service->set('test_plain_key', 'Hello World', 'general', 'string');
    expect($service->get('test_plain_key'))->toBe('Hello World');

    $service->set('test_secret_token', 'my-super-secret-token-123', 'security', 'string', isEncrypted: true);
    expect($service->get('test_secret_token'))->toBe('my-super-secret-token-123');

    // Verify in raw DB row it is encrypted
    $rawRow = Setting::where('key', 'test_secret_token')->first();
    expect($rawRow)->not->toBeNull()
        ->and($rawRow->is_encrypted)->toBeTrue()
        ->and($rawRow->value)->not->toBe('my-super-secret-token-123');
});

test('roles and permissions are seeded correctly and users can have roles', function () {
    expect(Role::where('name', 'Super Admin')->exists())->toBeTrue()
        ->and(Role::where('name', 'Manager')->exists())->toBeTrue()
        ->and(Role::where('name', 'Order Handler')->exists())->toBeTrue()
        ->and(Role::where('name', 'Content Editor')->exists())->toBeTrue();

    $user = User::factory()->create([
        'email' => 'admin_test@amarshopbd.com',
    ]);
    $user->assignRole('Super Admin');

    expect($user->hasRole('Super Admin'))->toBeTrue()
        ->and($user->hasPermissionTo('view settings'))->toBeTrue();
});
