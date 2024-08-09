<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_attribute()
    {
        $attribute = Attribute::create([
            'name' => 'Color',
            'value' => 'Red',
        ]);

        $this->assertDatabaseHas('attributes', ['name' => 'Color', 'value' => 'Red']);
        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertEquals('Color', $attribute->name);
        $this->assertEquals('Red', $attribute->value);
    }

    public function test_can_update_attribute()
    {
        $attribute = Attribute::factory()->create();

        $attribute->update([
            'name' => 'Size',
            'value' => 'Large',
        ]);

        $this->assertDatabaseHas('attributes', ['name' => 'Size', 'value' => 'Large']);
        $this->assertEquals('Size', $attribute->name);
        $this->assertEquals('Large', $attribute->value);
    }

    public function test_can_delete_attribute()
    {
        $attribute = Attribute::factory()->create();

        $attribute->delete();

        $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
    }

    public function test_can_find_attribute()
    {
        $attribute = Attribute::factory()->create();

        $foundAttribute = Attribute::find($attribute->id);

        $this->assertInstanceOf(Attribute::class, $foundAttribute);
        $this->assertEquals($attribute->id, $foundAttribute->id);
    }

    public function test_can_list_attributes()
    {
        $attributes = Attribute::factory(5)->create();

        $this->assertCount(5, Attribute::all());
    }
}
