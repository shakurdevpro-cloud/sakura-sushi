<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_slug_is_generated_automatically(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->for($category)->create([
            'name' => 'Sushi Saumon Deluxe',
        ]);

        $this->assertSame('sushi-saumon-deluxe', $product->slug);
    }

    public function test_price_formatted_accessor_returns_dollar_format(): void
    {
        $product = Product::factory()->create(['price' => 1599]);

        $this->assertSame('$15.99', $product->price_formatted);
    }
}