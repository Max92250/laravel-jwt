<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
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

        $imageName1 = $this->generateImageName($image1);
        $imageName2 = $this->generateImageName($image2);

        $imagePath1 = $this->storeImage($image1, 'images', $imageName1);
        $imagePath2 = $this->storeImage($image2, 'images', $imageName2);

        $image = $product->images()->create([
            'product_id' => $request->input('product_id'),
            'image_1' => asset($imagePath1),
            'image_2' => asset($imagePath2),
            'created_at' => now(),
        ]);

        $image->image_1 = asset($image->image_1);
        $image->image_2 = asset($image->image_2);

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
        $this->validateProductName($request);

        try {
            $product = $this->findProductOrFail($productId);
            $product->update(['name' => $request->input('name')]);

            return response()->json(['status' => 'success', 'message' => 'Product name updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
    }

    public function updateItemPrice(Request $request, $productId, $itemId)
    {
        $this->validateItemPrice($request);

        try {
            $product = $this->findProductOrFail($productId);
            $item = $this->findItemOrFail($product, $itemId);
            $item->update(['price' => $request->input('price')]);

            return response()->json(['status' => 'success', 'message' => 'Item price updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
        }
    }

    public function updateItemSize(Request $request, $productId, $itemId)
    {
        $this->validateItemSize($request);

        try {
            $product = $this->findProductOrFail($productId);
            $item = $this->findItemOrFail($product, $itemId);
            $item->update(['size' => $request->input('size')]);

            return response()->json(['status' => 'success', 'message' => 'Item size updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
        }
    }

    public function updateItemColor(Request $request, $productId, $itemId)
    {
        $this->validateItemColor($request);

        try {
            $product = $this->findProductOrFail($productId);
            $item = $this->findItemOrFail($product, $itemId);
            $item->update(['color' => $request->input('color')]);

            return response()->json(['status' => 'success', 'message' => 'Item color updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or item not found'], 404);
        }
    }

    public function updateImage(Request $request, $productId, $imageId)
    {
        $this->validateImage($request);

        try {
            $product = $this->findProductOrFail($productId);
            $image = $this->findImageOrFail($imageId);

            $oldImagePath1 = public_path(str_replace(asset(''), '', $image->image_1));
            $oldImagePath2 = public_path(str_replace(asset(''), '', $image->image_2));

            $newImage1 = $request->file('image_1');
            $newImage2 = $request->file('image_2');

            $imageName1 = $this->generateImageName($newImage1);
            $imageName2 = $this->generateImageName($newImage2);

            $imagePath1 = $this->storeImage($newImage1, 'images', $imageName1);
            $imagePath2 = $this->storeImage($newImage2, 'images', $imageName2);

            $image->update([
                'image_1' => asset($imagePath1),
                'image_2' => asset($imagePath2),
                'updated_at' => now(),
            ]);

            $this->deleteImage($oldImagePath1);
            $this->deleteImage($oldImagePath2);

            return response()->json(['status' => 'success', 'message' => 'Images updated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or Image not found'], 404);
        }
    }

    private function findProductOrFail($productId)
    {
        return Product::findOrFail($productId);
    }

    private function findItemOrFail($product, $itemId)
    {
        return $product->items()->findOrFail($itemId);
    }

    private function findImageOrFail($imageId)
    {
        return Image::findOrFail($imageId);
    }

    private function validateProductName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
    }

    private function validateItemPrice(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric',
        ]);
    }

    private function validateItemSize(Request $request)
    {
        $request->validate([
            'size' => 'required|string',
        ]);
    }

    private function validateItemColor(Request $request)
    {
        $request->validate([
            'color' => 'required|string',
        ]);
    }

    private function validateImage(Request $request)
    {
        $request->validate([
            'image_1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_2' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function generateImageName($image)
    {
        return time() . '_' . $image->getClientOriginalName();
    }

    private function storeImage($image, $directory, $imageName)
    {
        $imagePath = $directory . '/' . $imageName;
        $image->move(public_path($directory), $imageName);
        return $imagePath;
    }

    private function deleteImage($imagePath)
    {
        File::delete($imagePath);
    }
}
