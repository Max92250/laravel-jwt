<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;
use App\Models\Item;
use App\Models\Product;
use App\Models\category;
use App\Models\size;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use App\Traits\LogTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use JWTAuth;

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
    use LogTrait;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function createCategory(Request $request)
    {
        
    // Validate the incoming data from the client-side
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);

        // Create a new Category object and save it to the database
        $category = Category::create([
            'name' => $request->input('name'),
        ]);
    // Return the id of the newly created Category object as a JSON response
        return response()->json(['status' => 'success', 'category_id' => $category->id]);
    }
   
    public function updateItemsFromWarehouse()
    {
        // Fetch data from the warehouse API
        $response = Http::withHeaders([
            'apiToken' => '08d3abae99badba40441ca74519c0e11',
        ])->post('https://voxshipsapi.shikhartech.com/inventoryItems/A2S');
        
        if ($response->successful()) {
            // Retrieve the items from the response
            $itemsData = $response->json()['result']['customerItems'];

    
            // Iterate through the retrieved items
            foreach ($itemsData as $itemData) {
                $sku = $itemData['itemSkuNumber'];
                $quantity = $itemData['A2S'];
                $status = $itemData['status'];
                // Update the corresponding item in your database
                $item = Item::where('sku', $sku)->first();
                $item->quantity = $quantity;
                $item->status = $status;
                $item->save();
                return $item;
                    
                
                
            }
    
            return response()->json(['message' => 'Items updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to fetch data from the warehouse API'], $response->status());
        }
    }
    //Create a new product with its associated items in the database.
    public function createProductWithItems(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'numeric|exists:categories,id', // Ensure categories are numeric and exist
            'items.*.price' => 'required|numeric',
            'items.*.size_id' => 'required|numeric',
            'items.*.color' => 'required|string',
            'items.*.sku' => 'required|string',
        ]);

         // Extract the product data from the request
        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => $userName,
            'updated_by' => $userName,
            'user_id'=>$user->id,

        ];

        // Extract the category id from the request
        $categoryId = $request->input('categories');

        // Extract the items data from the request
        $itemsData = $request->input('items');

        // Call the product service to create the product with its items
        $result = $this->productService->createProductWithItems($productData, $itemsData, $categoryId);
    
        return response()->json($result);
    }

    // Update an existing product and its associated items and category in the database.
    public function updateEntity(Request $request, $productId)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'categories' => 'sometimes|required|array',
            'categories.*' => 'integer|exists:categories,id', // Updated validation for category IDs
            'items' => 'sometimes|required|array',
            'items.*.id' => 'sometimes|required|exists:items,id',
            'items.*.price' => 'sometimes|required|numeric',
            'items.*.size_id' => 'sometimes|required|numeric',
            'items.*.color' => 'sometimes|required|string',
            'items.*.sku' => 'sometimes|required|string',
        ]);

        // Get the category IDs from the request, or an empty array if not present
        $categoryId = $request->input('categories', []);

        // Get the product data from the request
        $productData = $request->only(['name', 'description']);

         // Get the items data from the request, or an empty array if not present
        $itemsData = $request->input('items', []);

        // Call the product service to update the product and its items
        $result = $this->productService->updateProduct($productId, $productData, $itemsData,$categoryId);

        return response()->json($result);
       
    }


    public function createProductWithImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);
        // Extract the product ID and image files from the request
        $productId = $request->input('product_id');
        $imageFiles = $request->file('images');
        // Call the productService method to create the product with the specified images
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
        
        // Extract the image files from the request
        $imagesData = $request->file('images');
         // Call the productService method to update the product images
        $result = $this->productService->updateImages($productId, $imagesData);
        
        return response()->json($result);
    }

    public function getAllProducts(Request $request)
    {
        $token = JWTAuth::fromUser($request->user());
    
        // Get product data
        $products = Product::all();
    
        // Return product data along with token in response header
        return response()->json(['products' => $products])->withheader('Authorization', 'Bearer ' . $token);
         // Get all products that have active items
      
    }

    public function getProductById($productId)
    {
        // Try to find the product with the specified ID
        try {
            $product = Product::with(['items', 'images'])->findOrFail($productId);
         // Try to find the product with the specified ID
            return response()->json(['product' => $product]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found for the specified ID'], 404);
        }
    }

    public function deactivateItem($productId, $itemId)
    {
        try {
            $product = Product::findOrFail($productId);

        // Update the status of the specified item to 'inactive'
            $product->items()->where('id', $itemId)->update(['status' => 'inactive']);

        // Return a JSON response indicating that the item was deactivated successfully
            return response()->json(['status' => 'success', 'message' => 'Item deactivated successfully']);

    
    // If the product or item is not found, catch the ModelNotFoundException and return a JSON response with an error message
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product or item not found for the specified ID'], 404);
        }
    }

    public function hardDeleteProduct($productId)
    {

        // Try to find the product with the specified ID

        try {
            $product = Product::findOrFail($productId);

            // Delete the product and its associated items and images
            $product->delete();

            return response()->json(['status' => 'success', 'message' => 'Product and associated items/images deleted successfully']);

        } catch (\Exception $exception) {
            \Log::error('Exception: ' . $exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 404);
        }
    }
    public function getProductsByCategory($categoryId)
    {
           

        try {

            // Try to find the category with the specified ID
            $category = Category::findOrFail($categoryId);

            // Get the products associated with the category and eager load their items and images relationships
            $products = $category->products()->with(['items','images'])->get();

            // Return the products as a collection of ProductResource objects

            return ProductResource::collection($products);

        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch products by category']);
        }
    }
    public function getCategoriesByProductName($productName)
    {
        // Try to find the categories that have a product with the specified name
        try {
            
            $categories = Category::whereHas('products', function ($query) use ($productName) {
                $query->where('name', 'like', '%' . $productName . '%');
            })->get();

            // Return a JSON response indicating that the categories were found and include them in the response

            return response()->json(['status' => 'success', 'categories' => $categories]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch categories by product name']);
        }
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:sizes,id',
            'status' => 'nullable|in:active,inactive',
        ]);
    
        // Create a new Size object and save it to the database
        $size = Size::create($request->all());
    
        // Return a JSON response indicating success
        return $this->storeSuccessResponse($size);
      
    }
    
    public function getBySizeId($sizeId)
    {
        // Retrieve items with the related size information
        $items = Item::where('size_id', $sizeId)->with('size')->get();

        // You can return the items with their size names
        return response()->json(['items' => $items]);
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
