<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request; // Import the Request class


use App\Services\ProductService;

class ProductController extends Controller
{

    /*public function edit($productId)
    {
    $product = Product::findOrFail($productId);
    return view('edit', compact('product'));
    }*/
   
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createProductWithItems(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.size' => 'required|string',
            'items.*.color' => 'required|string',
            'items.*.sku' => 'required|string',
        ]);
    

        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $itemsData = $request->input('items');

        $result = $this->productService->createProductWithItems($productData, $itemsData);

        return response()->json($result);
        
    }
    public function updateEntity(Request $request, $productId)
    {
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

        $productData = $request->only(['name', 'description']);
        $itemsData = $request->input('items', []);

        $result = $this->productService->updateProduct($productId, $productData, $itemsData);

        return response()->json($result);
       
    }


    public function createProductWithImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $imageFiles = $request->file('images');

        $result = $this->productService->createProductWithImages($productId, $imageFiles);

        
        return response()->json($result);
      
    }


    public function updateImages(Request $request, $productId)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $imagesData = $request->file('images');

        $result = $this->productService->updateImages($productId, $imagesData);

        return response()->json($result);
    }

    public function getAllProducts()
    {
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->get();
        return ProductResource::collection($products);
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
