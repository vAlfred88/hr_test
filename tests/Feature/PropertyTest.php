<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    
    public function it_can_create_a_property()
    {
        $product = Product::factory()->create();
        
        Property::create([
            'name' => 'Color',
            'value' => 'Red',
            'product_id' => $product->id,
        ]);
        
        $this->assertDatabaseHas('properties', [
            'name' => 'Color',
            'value' => 'Red',
            'product_id' => $product->id,
        ]);
    }
    
    public function it_belongs_to_a_product()
    {
        $product = Product::factory()->create();
        $property = Property::factory()->create([
            'product_id' => $product->id,
        ]);
        
        $relatedProduct = $property->product;
        
        $this->assertInstanceOf(Product::class, $relatedProduct);
        $this->assertEquals($product->id, $relatedProduct->id);
    }
    
    public function it_can_update_a_property()
    {
        $product = Product::factory()->create();
        
        $property = Property::factory()->create([
            'name' => 'Size',
            'value' => 'Medium',
            'product_id' => $product->id,
        ]);
        
        $property->update(['value' => 'Large']);
        
        $this->assertDatabaseHas('properties', [
            'name' => 'Size',
            'value' => 'Large',
        ]);
    }
    
    public function it_can_delete_a_property()
    {
        $product = Product::factory()->create();
        
        $property = Property::factory()->create([
            'product_id' => $product->id,
        ]);
        
        $property->delete();
        
        $this->assertDatabaseMissing('properties', [
            'id' => $property->id,
        ]);
    }
    
    public function it_cannot_create_a_property_without_a_name()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Property::create([
            'value' => 'Red',
        ]);
    }
}
