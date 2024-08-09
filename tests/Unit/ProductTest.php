<?php
namespace Tests\Unit;

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

        $product = Product::create([
            'name' => 'Test Product',
            'category_id' => $category->id,
        ]);

        $product->attributes()->sync([$attribute1->id, $attribute2->id]);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertDatabaseHas('attribute_product', ['product_id' => $product->id, 'attribute_id' => $attribute1->id]);
        $this->assertDatabaseHas('attribute_product', ['product_id' => $product->id, 'attribute_id' => $attribute2->id]);
    }
}
