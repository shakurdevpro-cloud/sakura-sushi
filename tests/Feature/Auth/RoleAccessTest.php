<?php
// tests/Feature/Auth/RoleAccessTest.php
namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_customer_cannot_access_admin_panel(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');
        $token = $customer->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_staff_cannot_refund_orders(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $this->assertFalse($staff->hasPermissionTo('orders.refund'));
    }
}