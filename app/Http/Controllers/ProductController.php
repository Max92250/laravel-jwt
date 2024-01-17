<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function insertProduct(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',

        ]);

        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ]);

        return response()->json(['status' => 'success', 'product_id' => $product->id], 201);
    }

    public function insertItem(Request $request)
    {

        $request->validate([
            'price' => 'required|numeric',
            'size' => 'required|string',
            'color' => 'required|string',
            'product_id' => 'required|exists:products,id',

        ]);
        $product = $this->findProductOrFail($request->input('product_id'));

        $sku = 'SKU_' . Carbon::now()->timestamp . Str::random(5);

        $item = $product->items()->create([
            'sku' => $sku,
            'price' => $request->input('price'),
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ]);

        return response()->json(['status' => 'success', 'item_id' => $item->id], 201);
    }

    public function uploadImage(Request $request)
    {

        $request->validate([
            'image_1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_2' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = $this->findProductOrFail($request->input('product_id'));

        $image1 = $request->file('image_1');
        $image2 = $request->file('image_2');

        $imageName1 = time() . '_image1.' . $image1->extension();
        $imageName2 = time() . '_image2.' . $image2->extension();

        $imagePath1 = 'images/' . $imageName1;
        $imagePath2 = 'images/' . $imageName2;

        $image = $product->images()->create([
            'product_id' => $request->input('product_id'),
            'image_1' => asset($imagePath1),
            'image_2' => asset($imagePath2),
            'created_at' => now(),
            'updated_at' => null,
        ]);

        $image->image_1 = asset($image->image_1);
        $image->image_2 = asset($image->image_2);

        $image1->move(public_path('images'), $imageName1);
        $image2->move(public_path('images'), $imageName2);

        return response()->json(['status' => 'success', 'image_id' => $image->id], 201);
    }
    

    public function getProducts()
    {
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->get();

        return response()->json(['products' => $products]);
    }

    public function getProductById($id)
    {
        $product = Product::with(['items', 'images'])
            ->where('id', $id)
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function updateProductName(Request $request, $productId)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $product = Product::findOrFail($productId);
            $product->update([
                'name' => $request->input('name'),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Product name updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
    }

    public function updateItemPrice(Request $request, $productId, $itemId)
{
    $request->validate([
        'price' => 'required|numeric',
    ]);

    try {
        $product = $this->findProductOrFail($productId);

        $item = $product->items()->findOrFail($itemId);
        $item->update([
            'price' => $request->input('price'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Item price updated successfully']);
    } catch (ModelNotFoundException $exception) {
        return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
    }
}
public function updateItemSize(Request $request, $productId, $itemId)
{
    $request->validate([
        'size' => 'required|string',
    ]);

    try {
        $product = $this->findProductOrFail($productId);

        $item = $product->items()->findOrFail($itemId);
        $item->update([
            'size' => $request->input('size'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Item size updated successfully']);
    } catch (ModelNotFoundException $exception) {
        return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
    }
}

public function updateItemColor(Request $request, $productId, $itemId)
{
    $request->validate([
        'color' => 'required|string',
    ]);

    try {
        $product = $this->findProductOrFail($productId);

        $item = $product->items()->findOrFail($itemId);
        $item->update([
            'color' => $request->input('color'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Item color updated successfully']);
    } catch (ModelNotFoundException $exception) {
        return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
    }
}



    private function findProductOrFail($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            return $product;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
    }


}
