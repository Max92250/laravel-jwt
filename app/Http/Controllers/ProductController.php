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
            'price' => 'sometimes|required|numeric',
            'size' => 'sometimes|required|string',
            'color' => 'sometimes|required|string',
            'image_1' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_2' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        try {
            $product = Product::findOrFail($productId);
    
            $productData = [
                'name' => $request->input('name', $product->name),
                'description' => $request->input('description', $product->description),
            ];
    
            $product->update($productData);
    
            $itemData = [
                'price' => $request->input('price', $product->items->first()->price ?? null),
                'size' => $request->input('size', $product->items->first()->size ?? null),
                'color' => $request->input('color', $product->items->first()->color ?? null),
            ];
    
            if ($product->items->first()) {
                $product->items->first()->update($itemData);
            } else {
                $product->items()->create($itemData);
            }
    
            $imageData = [];
            if ($request->hasFile('image_1')) {
                $imageData['image_1'] = $this->updateImage($request->file('image_1'), $product->images->first()->image_1 ?? null);
            }
            if ($request->hasFile('image_2')) {
                $imageData['image_2'] = $this->updateImage($request->file('image_2'), $product->images->first()->image_2 ?? null);
            }
    
            if ($product->images->first()) {
                $product->images->first()->update($imageData);
            } else {
                $product->images()->create($imageData);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Product, item, and image updated successfully']);
        } catch (\Exception $exception) {
            \Log::error('Exception: ' . $exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }
    private function updateImage($newImage, $oldImagePath)
    {
        $oldImagePath = public_path(str_replace(asset(''), '', $oldImagePath));

        $imageName = $this->generateImageName($newImage);
        $imagePath = $this->storeImage($newImage, 'images', $imageName);

        $this->deleteImage($oldImagePath);

        return asset($imagePath);
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
            $product = Product::findOrFail($productId);
            $product->forceDelete();

            return response()->json(['status' => 'success', 'message' => 'Product and associated items/images deleted successfully']);
        } catch (\Exception $exception) {
            \Log::error('Exception: ' . $exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }
    
}
