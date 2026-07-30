<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'customer']);

        $permissions = [
            // Produits
            'products.view', 'products.create', 'products.edit', 'products.delete',
            // Commandes
            'orders.view', 'orders.validate', 'orders.cancel', 'orders.refund',
            // Réservations
            'reservations.view', 'reservations.confirm', 'reservations.cancel',
            // Clients
            'customers.view', 'customers.edit',
            // Promotions
            'promotions.manage',
            // Newsletter
            'newsletter.manage',
            // Avis
            'reviews.moderate',
            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin->syncPermissions(Permission::all());

        $staff->syncPermissions([
            'products.view',
            'orders.view',
            'orders.validate',
            'reservations.view',
            'reservations.confirm',
            'dashboard.view',
        ]);

        // customer : aucune permission explicite par défaut
    }
}