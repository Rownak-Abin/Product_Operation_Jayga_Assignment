<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $category = Category::factory()->create();

        $attribute1 = Attribute::factory()->create([
            'name' => 'Color',
            'value' => 'Red',
        ]);

        $attribute2 = Attribute::factory()->create([
            'name' => 'Size',
            'value' => 'Large',
        ]);

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
            'attributes' => [
                ['Color' => 'Red'],
                ['Size' => 'Large'],
            ],
        ]);

        $response->assertStatus(201);

        $product = Product::where('name', 'Test Product')->first();

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertDatabaseHas('attribute_product', ['product_id' => $product->id, 'attribute_id' => $attribute1->id]);
        $this->assertDatabaseHas('attribute_product', ['product_id' => $product->id, 'attribute_id' => $attribute2->id]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $attribute = Attribute::factory()->create(['name' => 'Color', 'value' => 'Blue']);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'category_id' => $product->category_id,
            'attributes' => [
                ['Color' => 'Blue'],
            ],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
        $this->assertDatabaseHas('attribute_product', ['product_id' => $product->id, 'attribute_id' => $attribute->id]);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

