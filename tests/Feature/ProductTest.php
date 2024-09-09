<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_paginated_response(): void
    {
        Product::factory(100)->create();
        
        $response = $this->get('/api/products');
        
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'data',
            'links',
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);

        $this->assertCount(40, $response->json('data'));
    }
    
    public function test_it_filters_products_by_properties(): void
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        $product1->properties()->createMany([
            ['name' => 'prop1', 'value' => 'value1'],
            ['name' => 'prop2', 'value' => 'value2'],
        ]);
        
        $product2->properties()->createMany([
            ['name' => 'prop1', 'value' => 'other_value'],
        ]);
        
        $response = $this->getJson('/api/products?properties[prop1][]=value1');
        
        $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $product1->id]);
        
        $response = $this->getJson('/api/products?properties[prop2][]=value2');
        
        $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $product1->id]);
    }
    
}
