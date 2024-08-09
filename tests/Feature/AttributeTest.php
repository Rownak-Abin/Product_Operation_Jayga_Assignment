<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_attribute()
    {
        $response = $this->postJson('/api/attributes', [
            'name' => 'Color',
            'value' => 'Red',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('attributes', ['name' => 'Color', 'value' => 'Red']);
    }

    public function test_can_update_attribute()
    {
        $attribute = Attribute::factory()->create();

        $response = $this->putJson("/api/attributes/{$attribute->id}", [
            'name' => 'Size',
            'value' => 'Large',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('attributes', ['name' => 'Size', 'value' => 'Large']);
    }

    public function test_can_delete_attribute()
    {
        $attribute = Attribute::factory()->create();

        $response = $this->deleteJson("/api/attributes/{$attribute->id}");

        $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
    }
}
