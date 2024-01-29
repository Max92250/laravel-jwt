<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetAllProductsTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testGetAllProducts()
    {

        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $products = Product::with(['items', 'images'])
            ->whereHas('items', function ($query) {
                $query->where('status', '=', 'active');
            })
            ->get();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(count($products), 'products');

        foreach ($products as $product) {
            $response->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
            ]);

            foreach ($product->items as $item) {
                $response->assertJsonFragment([
                    'id' => $item->id,
                    'color' => $item->color,
                    'price' => number_format($item->price, 2),
                    'size' => $item->size,
                    'sku' => $item->sku,
                    'status' => $item->status,
                ]);
            }
            foreach ($product->images as $image) {
                $response->assertJsonFragment([
                    'id' => $image->id,
                    'image_path' =>  basename($image->image_path),
                ]);
                
            }

        }
    }
}
