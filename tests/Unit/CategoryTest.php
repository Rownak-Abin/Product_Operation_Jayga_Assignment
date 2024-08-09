<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $category = Category::create([
            'name' => 'Test Category',
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $category->update([
            'name' => 'Updated Category',
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
        $this->assertEquals('Updated Category', $category->name);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $category->delete();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_can_find_category()
    {
        $category = Category::factory()->create();

        $foundCategory = Category::find($category->id);

        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    public function test_can_list_categories()
    {
        Category::factory(5)->create();

        $this->assertCount(5, Category::all());
    }
}
