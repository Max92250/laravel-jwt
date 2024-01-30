<?php
// app/Services/ProductService.php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProductWithItems(array $productData, array $itemsData)
    {
        try {
            DB::beginTransaction();

            $product = Product::create($productData);

            $itemData = collect($itemsData)->map(function ($item) {
                return [
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                ];
            });

            $product->items()->createMany($itemData->toArray());

            DB::commit();

            return ['status' => 'success', 'product_id' => $product->id];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to create product with items'];
        }
    }
}
