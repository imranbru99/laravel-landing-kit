<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Database\Seeders\BangladeshLocationSeeder;
use Database\Seeders\DemoStoreSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TemplateSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InstallLandingKitCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'llk:install {--force : Overwrite existing setup without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Fresh install of Laravel Landing Kit (migrations, seeders, templates, admin user, storage link)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  🚀 Installing Laravel Landing Kit (BD E-Commerce) ');
        $this->info('====================================================');

        // 1. Run Migrations
        $this->comment('1. Running Database Migrations...');
        Artisan::call('migrate', ['--force' => true], $this->output);
        $this->info('✓ Migrations completed.');

        // 2. Seed Default Settings
        $this->comment('2. Seeding Default Settings...');
        Artisan::call('db:seed', ['--class' => SettingSeeder::class, '--force' => true], $this->output);
        $this->info('✓ Default settings seeded.');

        // 3. Seed Bangladesh Locations
        $this->comment('3. Seeding Bangladesh Districts & Delivery Zones...');
        Artisan::call('db:seed', ['--class' => BangladeshLocationSeeder::class, '--force' => true], $this->output);
        $this->info('✓ 64 districts & delivery zones seeded.');

        // 4. Seed Roles & Permissions
        $this->comment('4. Seeding Roles & Permissions...');
        Artisan::call('db:seed', ['--class' => RoleAndPermissionSeeder::class, '--force' => true], $this->output);
        $this->info('✓ Roles and permissions seeded.');

        // 5. Seed 107 Templates
        $this->comment('5. Seeding 107+ Ready-Made Bangladesh Shop Templates...');
        Artisan::call('db:seed', ['--class' => TemplateSeeder::class, '--force' => true], $this->output);
        $this->info('✓ 107 templates & section blueprints seeded.');

        // 6. Seed Demo Store Products
        $this->comment('6. Seeding Demo Store Products & Landing Pages...');
        Artisan::call('db:seed', ['--class' => DemoStoreSeeder::class, '--force' => true], $this->output);
        $this->info('✓ Demo products seeded.');

        // 6. Create Super Admin User
        $this->comment('6. Creating Default Super Admin User...');
        $adminEmail = 'admin@amaronline.com';
        $adminPassword = 'password';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($adminPassword),
            ]
        );

        if (class_exists(Role::class)) {
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            if ($superAdminRole) {
                $admin->assignRole($superAdminRole);
            }
        }
        $this->info("✓ Admin user ready: {$adminEmail}");

        // 7. Storage Link
        $this->comment('7. Linking Public Storage...');
        try {
            Artisan::call('storage:link');
        } catch (\Throwable $e) {
            // Handled if link already exists
        }
        $this->info('✓ Storage linked.');

        $this->newLine();
        $this->info('====================================================');
        $this->info('  🎉 Installation Complete! Application is Ready. ');
        $this->info('====================================================');
        $this->newLine();
        $this->line('• Admin URL: <fg=yellow>'.url('/admin').'</>');
        $this->line("• Email:     <fg=yellow>{$adminEmail}</>");
        $this->line("• Password:  <fg=yellow>{$adminPassword}</>");
        $this->line('• Storefront:<fg=yellow>'.url('/').'</>');
        $this->newLine();

        return Command::SUCCESS;
    }
}
