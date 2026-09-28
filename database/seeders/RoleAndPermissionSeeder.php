<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Products & Catalog
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            // Orders & Customers
            'view orders', 'create orders', 'edit orders', 'delete orders', 'change order status',
            'view customers', 'edit customers', 'delete customers',
            'view leads', 'edit leads', 'delete leads',
            // Landing Pages & Builder
            'view landing pages', 'create landing pages', 'edit landing pages', 'delete landing pages',
            'use ai builder', 'manage templates', 'manage saved sections',
            // Courier & Logistics
            'send to courier', 'manage couriers',
            // Marketing & Tracking
            'view tracking', 'edit tracking settings',
            // Settings & Administration
            'view settings', 'edit settings',
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'edit roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 1. Super Admin: all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Manager
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view orders', 'create orders', 'edit orders', 'change order status',
            'view customers', 'edit customers',
            'view leads', 'edit leads',
            'view landing pages', 'create landing pages', 'edit landing pages',
            'use ai builder', 'manage templates', 'manage saved sections',
            'send to courier', 'manage couriers',
            'view tracking', 'edit tracking settings',
            'view settings',
        ]);

        // 3. Order Handler
        $orderHandler = Role::firstOrCreate(['name' => 'Order Handler', 'guard_name' => 'web']);
        $orderHandler->syncPermissions([
            'view orders', 'create orders', 'edit orders', 'change order status',
            'view customers', 'edit customers',
            'view leads', 'edit leads',
            'send to courier',
        ]);

        // 4. Content Editor
        $contentEditor = Role::firstOrCreate(['name' => 'Content Editor', 'guard_name' => 'web']);
        $contentEditor->syncPermissions([
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view landing pages', 'create landing pages', 'edit landing pages',
            'use ai builder', 'manage templates', 'manage saved sections',
        ]);
    }
}
