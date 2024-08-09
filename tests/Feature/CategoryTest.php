<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
