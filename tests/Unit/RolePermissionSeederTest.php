<?php
// tests/Unit/RolePermissionSeederTest.php
namespace Tests\Unit;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_correct_roles_and_permissions(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'staff']);
        $this->assertDatabaseHas('roles', ['name' => 'customer']);

        $this->assertSame(17, Permission::count());

        $admin = Role::findByName('admin');
        $this->assertSame(17, $admin->permissions->count());

        $staff = Role::findByName('staff');
        $this->assertEqualsCanonicalizing(
            ['products.view', 'orders.view', 'orders.validate', 'reservations.view', 'reservations.confirm', 'dashboard.view'],
            $staff->permissions->pluck('name')->toArray()
        );
    }
}
