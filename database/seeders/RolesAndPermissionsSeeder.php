<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Products
            'view_any_product', 'view_product', 'create_product', 'update_product', 'delete_product', 'adjust_stock',
            // Categories
            'view_any_category', 'view_category', 'create_category', 'update_category', 'delete_category',
            // Suppliers
            'view_any_supplier', 'view_supplier', 'create_supplier', 'update_supplier', 'delete_supplier',
            // Stock Logs
            'view_any_stock_log', 'view_stock_log',
            // Users
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            // Activity Logs
            'view_any_activity', 'view_activity',
            // Import / Export
            'import_products', 'export_products',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin: all permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Supplier: scoped permissions
        $supplier = Role::firstOrCreate(['name' => 'supplier']);
        $supplier->syncPermissions([
            'view_any_product', 'view_product', 'create_product', 'update_product', 'adjust_stock',
            'view_any_category', 'view_category',
            'view_any_stock_log', 'view_stock_log',
        ]);
    }
}
