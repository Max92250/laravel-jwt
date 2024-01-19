<?php




namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function createProductWithItemsAndImages(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|array',
            'size' => 'required|array',
            'color' => 'required|array',
            'image_1.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_2.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
    
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_at' => now(),
            'updated_at' => null,
        ]);

        $sku = 'SKU_' . now()->timestamp . Str::random(5);

        foreach ($request->input('price') as $key => $price) {
            $item = $product->items()->create([
                'sku' => $sku,
                'price' => $price,
                'size' => $request->input('size')[$key],
                'color' => $request->input('color')[$key],
                'created_at' => now(),
                'updated_at' => null,
            ]);
            $imageName1 = $this->generateImageName($request->file("image_1.$key"));
            $imageName2 = $this->generateImageName($request->file("image_2.$key"));

            $imagePath1 = $this->storeImage($request->file("image_1.$key"), 'images', $imageName1);
            $imagePath2 = $this->storeImage($request->file("image_2.$key"), 'images', $imageName2);

            $product->images()->create([
                'product_id' => $product->id,
                'item_id' => $item->id,
                'image_1' => asset($imagePath1),
                'image_2' => asset($imagePath2),
                'created_at' => now(),
            ]);
        }



        return response()->json(['status' => 'success', 'product_id' => $product->id], 201);
    }

    
    public function updateEntity(Request $request, $productId)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'items' => 'sometimes|required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.price' => 'sometimes|required|numeric',
            'items.*.size' => 'sometimes|required|string',
            'items.*.color' => 'sometimes|required|string',
            'images' => 'sometimes|required|array',
            'images.*.id' => 'required|exists:images,id',
            'images.*.image_1' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.image_2' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                    $item = Item::findOrFail($itemData['id']);
                    $updateFields = [
                        'price' => $itemData['price'] ?? $item->price,
                        'size' => $itemData['size'] ?? $item->size,
                        'color' => $itemData['color'] ?? $item->color,
                    ];
                    $item->update($updateFields);
                }
            }
            if ($request->has('images')) {
                foreach ($request->input('images') as $imageData) {
    
                    $imageId = $imageData;
                    $image = Image::findOrFail($imageId);
                    $updateFields = [
                        'image_1' => $this->updateImage($imageData['image_1'], $image->image_1 ?? null),
                        'image_2' => $this->updateImage($imageData['image_2'], $image->image_2 ?? null),
                    ];
                    $image->update($updateFields);
                }
            }
    
    
            return response()->json(['status' => 'success', 'message' => 'Product, items, and images updated successfully']);
        } catch (\Exception $exception) {
            
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }
    
    public function updateImage($newImage, $oldImagePath = null)
{

    if ($newImage) {
    
        $imageName = $this->generateImageName($newImage);
        $imagePath = $this->storeImage($newImage, 'images', $imageName);

    
        return asset($imagePath);
    }

   
    return $oldImagePath;
}

    private function generateImageName($image)
    {
        return now()->timestamp . '_' . $image->getClientOriginalName();
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
  


    public function getAllProducts()
    {
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->get();

        return response()->json(['products' => $products]);
    }
    public function hardDeleteProduct($productId)
    {
        try {
            $product = Product::with(['items', 'images'])->findOrFail($productId);
            $product->forceDelete();

            return response()->json(['status' => 'success', 'message' => 'Product and associated items/images deleted successfully']);
        } catch (\Exception $exception) {
            \Log::error('Exception: ' . $exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }
    
}
