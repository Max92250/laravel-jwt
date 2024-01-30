<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    /*public function edit($productId)
    {
    $product = Product::findOrFail($productId);
    return view('edit', compact('product'));
    }*/
    public function createProductWithItems(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'items.*.price' => 'required|numeric',
                'items.*.size' => 'required|string',
                'items.*.color' => 'required|string',
                'items.*.sku' => 'required|string',
            ]);

            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),

            ]);

            $itemData = collect($request->input('items'))->map(function ($item) {
                return [
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'size' => $item['size'],
                    'color' => $item['color'],

                ];
            });

            $product->items()->createMany($itemData->toArray());

            DB::commit();

            return response()->json(['status' => 'success', 'product_id' => $product->id], 201);
        } catch (QueryException $exception) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to create product with items'], 500);
        }
    }
    public function createProductWithImages(Request $request)
    {
        try {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_id' => 'required|exists:products,id',
            ]);

            DB::beginTransaction();

            $product = $this->findProductOrFail($request->input('product_id'));

            // Ensure that the 'images' key exists in the request
            if ($request->hasFile('images')) {
                $imageData = collect($request->file('images'))->map(function ($image) use ($product) {
                    $imageName = $this->generateImageName($image);
                    $this->storeImage($image, 'images', $imageName);

                    return [
                        'product_id' => $product->id,
                        'image_path' => $imageName,

                    ];
                });

                $product->images()->createMany($imageData->toArray());
            }

            DB::commit();

            return response()->json(['status' => 'success', 'product_id' => $product->id], 201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to create product with images'], 500);
        }
    }public function updateEntity(Request $request, $productId)
    {
        try {
            $request->validate([
                'name' => 'sometimes|required|string|regex:/^[^0-9]*$/',
                'description' => 'sometimes|required|string',
                'items' => 'sometimes|required|array',
                'items.*.id' => 'sometimes|required|exists:items,id',
                'items.*.price' => 'sometimes|required|numeric',
                'items.*.size' => 'sometimes|required|string',
                'items.*.color' => 'sometimes|required|string',
                'items.*.sku' => 'sometimes|required|string',
            ]);
    
            $product = Product::find($productId);
    
            if ($product) {
                $productData = [
                    'name' => $request->input('name', $product->name),
                    'description' => $request->input('description', $product->description),
                ];
    
                $product->update($productData);
    
                if ($request->has('items')) {
                    collect($request->input('items'))->each(function ($itemData) use ($product) {
                        $itemId = $itemData['id'] ?? null;
    
                        if ($itemId) {
                            $item = Item::where('product_id', $product->id)->find($itemId);
    
                            $updateFields = [
                                'price' => $itemData['price'] ?? $item->price,
                                'size' => $itemData['size'] ?? $item->size,
                                'color' => $itemData['color'] ?? $item->color,
                            ];
    
                            $item->update($updateFields);
    
                        } else {
                            Item::create([
                                'product_id' => $product->id,
                                'price' => $itemData['price'],
                                'size' => $itemData['size'],
                                'color' => $itemData['color'],
                                'sku' => $itemData['sku'],
                            ]);
                        }
                    });
                }
    
                return response()->json(['status' => 'success', 'message' => 'Product updated successfully']);
            }
    
            return response()->json(['status' => 'error', 'message' => "Product ID not found"], 404);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update product. Please try again.'], 500);
        }
    }
    
    public function updateImages(Request $request, $productId)
    {

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::find($productId);

        $product->images->each(function ($oldImage) {
            $oldImagePath = public_path(str_replace(asset(''), '', $oldImage->image_path));
            $this->deleteImage($oldImagePath);
        });

        $imagesData = collect($request->file('images'))->map(function ($image) use ($productId) {
            $imageName = $this->generateImageName($image);
            $imagePath = $this->storeImage($image, 'images', $imageName);

            return [
                'product_id' => $productId,
                'image_path' => $imageName,
            ];
        });

        foreach ($imagesData as $index => $image) {
            $product->images[$index]->update($image);
        }

        return response()->json(['status' => 'success', 'product_id' => $productId], 201);

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

    private function findProductOrFail($productId)
    {
        return Product::findOrFail($productId);
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
            $product->Delete();

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
