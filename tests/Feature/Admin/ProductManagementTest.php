<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin())->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Maki Avocat',
            'description' => 'Un délicieux maki.',
            'price' => 1200,
            'stock' => 50,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Maki Avocat', 'category_id' => $category->id]);
    }

    public function test_admin_can_upload_product_images(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create();

        $response = $this->actingAs($this->admin())->post(
            route('admin.products.images.store', $product),
            ['images' => [UploadedFile::fake()->image('photo1.jpg'), UploadedFile::fake()->image('photo2.jpg')]]
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('product_images', 2);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id, 'is_primary' => true]);

        Storage::disk('public')->assertExists($product->fresh()->images->first()->path);
    }
}