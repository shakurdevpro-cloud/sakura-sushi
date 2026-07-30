<?php

namespace Tests\Feature\Catalog;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_active_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->count(3)->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_inactive_products_not_visible_to_public(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->create(['is_active' => true, 'name' => 'Visible Roll']);
        Product::factory()->for($category)->create(['is_active' => false, 'name' => 'Hidden Roll']);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $names = collect($response->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Visible Roll'));
        $this->assertFalse($names->contains('Hidden Roll'));
    }

    public function test_search_filter_works_on_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->create(['name' => 'Dragon Roll Spécial']);
        Product::factory()->for($category)->create(['name' => 'Sashimi Saumon']);

        $response = $this->getJson('/api/v1/products?search=Dragon');

        $response->assertStatus(200);
        $names = collect($response->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Dragon Roll Spécial'));
        $this->assertFalse($names->contains('Sashimi Saumon'));
    }

    public function test_products_are_cached_in_redis(): void
    {
        Config::set('cache.default', 'redis');
        Cache::store('redis')->flush();

        $category = Category::factory()->create();
        Product::factory()->for($category)->count(2)->create(['is_active' => true]);

        $key = 'products:' . md5(serialize([]));

        $this->assertFalse(Cache::store('redis')->has($key));

        $this->getJson('/api/v1/products')->assertStatus(200);

        $this->assertTrue(Cache::store('redis')->has($key));

        Cache::store('redis')->flush();
    }
}