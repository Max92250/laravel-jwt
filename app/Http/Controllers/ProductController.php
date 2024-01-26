<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    /*public function edit($productId)
    {
    $product = Product::findOrFail($productId);
    return view('edit', compact('product'));
    }*/
    public function createProductWithItemsAndImages(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.size' => 'required|string',
            'items.*.color' => 'required|string',
            'images.*.image_1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.image_2' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_at' => now(),
            'updated_at' => null,
        ]);
        $sku = 'SKU_' . now()->timestamp . Str::random(5);

        foreach ($request->input('items') as $key => $itemData) {
            $item = $product->items()->create([
                'sku' => $sku,
                'price' => $itemData['price'],
                'size' => $itemData['size'],
                'color' => $itemData['color'],
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }

        $images = $request->file('images');

        foreach ($images as $key => $image) {
            $imageName1 = $this->generateImageName($image['image_1']);
            $imageName2 = $this->generateImageName($image['image_2']);

            $imagePath1 = $this->storeImage($image['image_1'], 'images', $imageName1);
            $imagePath2 = $this->storeImage($image['image_2'], 'images', $imageName2);

            $product->images()->create([
                'product_id' => $product->id,
                'image_1' => $imageName1,
                'image_2' => $imageName2,
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }
        return response()->json(['status' => 'success', 'product_id' => $product->id], 201);
    }

    public function updateEntity(Request $request, $productId)
    {
        $request->validate([
            'name' => 'sometimes|required|string|regex:/^[^0-9]*$/',
            'description' => 'sometimes|required|string',
            'items' => 'sometimes|required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.price' => 'sometimes|required|numeric',
            'items.*.size' => 'sometimes|required|string',
            'items.*.color' => 'sometimes|required|string',
            'image_ids' => 'nullable|exists:images,id',
            'image_1' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_2' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        try {
            $product = Product::findOrFail($productId);

            $productData = [
                'name' => $request->input('name', $product->name),
                'description' => $request->input('description', $product->description),
            ];

            $product->update($productData);

            if ($request->has('items')) {
                foreach ($request->input('items') as $itemData) {
                    $itemId = $itemData['id'];

                    try {
                        $item = Item::where('product_id', $product->id)->findOrFail($itemId);
                    } catch (\Exception $exception) {
                        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                            return response()->json([
                                'status' => 'error',
                                'message' => "Item with ID $itemId not found for the specified product",
                            ], 404);
                        }

                    }

                    $updateFields = [
                        'price' => $itemData['price'] ?? $item->price,
                        'size' => $itemData['size'] ?? $item->size,
                        'color' => $itemData['color'] ?? $item->color,
                    ];

                    $item->update($updateFields);
                }
            }

            $imageId = $request->input('image_ids');

            if ($imageId) {
                $image = $this->findImageOrFail($imageId);

                $newImage1 = $request->file("image_1");
                $newImage2 = $request->file("image_2");

                if ($newImage1) {
                    $oldImagePath1 = public_path(str_replace(asset(''), '', $image->image_1));
                    $imageName1 = $this->generateImageName($newImage1);
                    $imagePath1 = $this->storeImage($newImage1, 'images', $imageName1);

                    $image->update([
                        'image_1' => $imageName1,
                        'updated_at' => now(),
                    ]);

                    $this->deleteImage($oldImagePath1);
                }

                if ($newImage2) {
                    $oldImagePath2 = public_path(str_replace(asset(''), '', $image->image_2));
                    $imageName2 = $this->generateImageName($newImage2);
                    $imagePath2 = $this->storeImage($newImage2, 'images', $imageName2);

                    $image->update([
                        'image_2' => $imageName2,
                        'updated_at' => now(),
                    ]);

                    $this->deleteImage($oldImagePath2);
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Product updated successfully']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => "product Id not found"], 404);
        }
    }

    protected function findProductOrFail($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            return $product;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException("Product not found with ID: $productId");
        }
    }

    protected function findImageOrFail($imageId)
    {
        try {
            $image = Image::findOrFail($imageId);
            return $image;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException("Image not found with ID: $imageId");
        }
    }

    private function generateImageName($image)
    {
        if ($image && $image->isValid() && $image->getClientOriginalName()) {
            return now()->timestamp . '_' . $image->getClientOriginalName();
        }

        return null;
    }

    private function storeImage($image, $directory, $imageName)
    {
        if ($image) {
            if ($image->move(public_path($directory), $imageName)) {
                $imagePath = $directory . '/' . $imageName;
                return $imagePath;
            } else {
                return null;
            }
        }

        return null;
    }

    private function deleteImage($imagePath)
    {
        File::delete($imagePath);
    }

    public function getAllProducts()
    {
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->get();
        return response()->json(['products' => $products]);
    }

    public function getProductById($productId)
    {
        try {
            $product = Product::with(['items', 'images', 'reviews'])->findOrFail($productId);

            return response()->json(['product' => $product]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found for the specified ID'], 404);
        }
    }

    public function deactivateItem($productId, $itemId)
    {
        try {
            $product = Product::findOrFail($productId);

            $product->items()->where('id', $itemId)->update(['status' => 'inactive']);

            return response()->json(['status' => 'success', 'message' => 'Item deactivated successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or item not found for the specified ID'], 404);
        }
    }

    public function hardDeleteProduct($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->forceDelete();

            return response()->json(['status' => 'success', 'message' => 'Product and associated items/images deleted successfully']);
        } catch (\Exception $exception) {
            \Log::error('Exception: ' . $exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }

    /* public function postReview(Request $request, $productId)
{
$request->validate([
'content' => 'required|string',
'rating' => 'required|integer|min:1|max:5',
]);

$product = Product::findOrFail($productId);

$review = new Review($request->all());
$product->reviews()->save($review);

return response()->json(['status' => 'success', 'message' => 'Review posted successfully']);
}
 */

}
