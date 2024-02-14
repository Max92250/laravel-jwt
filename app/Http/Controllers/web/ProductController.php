<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function Category(Request $request)
    {

        $user = Auth::user();

    // Retrieve products based on the user_id
    $products = Product::where('user_id', $user->id)->with('categories')->get();


    return $products;

    }
    public function Sizes(Request $request)
    {

        $sizes = Size::all();

        foreach ($sizes as $size) {
            $user = User::find($size->user_id);
            $size->user_name = $user ? $user->username : '';
        }
        return view('product.size', compact('sizes'));
    }

    public function createProductWithItem(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'items.*.price' => 'required|numeric',
                'items.*.size_id' => 'required|numeric',
                'items.*.color' => 'required|string',
                'items.*.sku' => 'required|string|unique:items,sku',
                'categories' => 'required|array',
                'categories.*' => 'numeric|exists:categories,id', // Ensure categories are numeric and exist
                // Ensure customer_id is numeric and exists

            ]);

            $user = Auth::user();
        
            $productData = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'created_by' => $user->username,
                'updated_by' => $user->username,
                'user_id' => $user->id,
                'customer_id' => $user->customer_id, // Add customer_id to product data

            ];

            $duplicatedSkus = $this->checkDuplicatedSkus($validatedData['items']);
            if (!empty($duplicatedSkus)) {
                // If duplicated SKUs are found, return an error response
                return back()->withInput()->withErrors(['items' => 'Duplicate SKUs found: ' . implode(', ', $duplicatedSkus)]);
            }

            $itemsData = $request->input('items');

            $categoryId = $request->input('categories');

            $result = $this->productService->createProductWithItems($productData, $itemsData, $categoryId);
            if ($result['status'] === 'success') {
                return redirect()->route('imagecreate', ['product_id' => $result['product_id']]);
            } else {
                return view('product.error', ['message' => $result['message']]);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create product. Please try again.']);
        }
    }
    private function checkDuplicatedSkus($items)
    {
        $skus = collect($items)->pluck('sku')->toArray();
        $uniqueSkus = array_unique($skus);
        return array_diff_assoc($skus, $uniqueSkus);
    }

    public function createProductWithImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $imageFiles = $request->file('images');

        $result = $this->productService->createProductWithImages($productId, $imageFiles);

        if ($result['status'] === 'success') {
            return redirect()->route('imagecreate', ['product_id' => $productId]);
        } else {
            return view('product.error', ['message' => $result['message']]);
        }
    }

    public function createimages($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('product.images', compact('product', 'product_id'));
    }

    public function showimages($product_id)
    {
        $product = Product::findOrFail($product_id);

        // Filter images based on product ID
        $images = $product->images->where('product_id', $product->id)->where('status', 1);

        return view('product.images1', compact('images'));
    }

    public function showSubSizes($id)
    {

        $size = Size::findOrFail($id);

        if (!$size) {
            // Handle the case where the size is not found
            return response()->json(['error' => 'Size not found'], 404);
        }

        // Get all sizes from the database
        $sizes = Size::all();

        // Return the view with the parent size and all sizes
        return view('product.subsizes', compact('size', 'sizes'));

    }
    public function delete($id)
    {
        $image = Image::find($id);

        // Delete the image from storage

        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully');
    }
    public function softDelete($id)
    {
        try {
            // Soft delete the image by updating the status to 0
            DB::table('images')
                ->where('id', $id)
                ->update(['status' => '0']);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Image has been soft deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            dd($e);

            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to soft delete the image.');
        }
    }

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
            'items.*.size' => 'sometimes|required|string',
            'items.*.color' => 'sometimes|required|string',

        ]);
        $categoryId = $request->input('categories', []);

        $user = Auth::user();
    
        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => $user->username,
            'updated_by' => $user->username,
            'user_id' => $user->id,
            'customer_id' => $user->customer_id, // Add customer_id

        ];
        $itemsData = $request->input('items', []);

        $result = $this->productService->updateProduct($productId, $productData, $itemsData, $categoryId);

        if ($result['status'] === 'success') {
            return back()->with('success', "updated sucessfully");
        } else {
            return back()->with('error', $result['message']);
        }
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

        if ($result['status'] === 'success') {
            return view('product.success', ['product_id' => $result['product_id']]);
        } else {
            return view('product.error', ['message' => $result['message']]);
        }
    }

    public function productedit($productId)
    {
        // Retrieve the product from the database based on the product ID
        $product = Product::findOrFail($productId);
        $sizes = Size::all();
        $categories = Category::all();
        // Pass the product data to the view
        return view('product.edit', compact('product', 'categories', 'sizes'));
    }
    public function getAllProducts(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        $customer = Customer::where('id', $user->customer_id)->first();
    
        if ($customer) {
            $products = $this->fetchProductsForCustomer($customer);
            $this->attachUserNameToProducts($products);
    
            return view('product.view', ['products' => $products]);
        }
    }
    
    protected function fetchProductsForCustomer($customer)
    {
        return Product::where('customer_id', $customer->id)->latest()->get();
    }
    
    protected function attachUserNameToProducts($products)
    {
        $userIds = $products->pluck('user_id')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
    
        foreach ($products as $product) {
            $product->user_name = $users->has($product->user_id) ? $users[$product->user_id]->username : 'N/A';
        }
    }
    public function showCategories()
    {
        // Fetch all categories
        $categories = Category::all();

        // Pass categories to the view
        return view('product.product', ['categories' => $categories]);
    }

    public function getProductsBySearch(Request $request)
    {
        $categories = Category::all();

        // Get the authenticated user
        $user = Auth::user();

        // Get the search query from the request
        $searchQuery = $request->input('q');

        try {
            // Query to fetch products
            $productsQuery = Product::query();

            // If the user is not an admin, filter products based on the user ID
            if (!$user->isAdmin()) {
                $productsQuery->where('user_id', $user->id);
            }

            // If a search query is provided, filter products based on the search query
            if ($searchQuery) {
                $productsQuery->where('name', 'like', '%' . $searchQuery . '%');
            }

            // Fetch the products
            $products = $productsQuery->get();

            // Return the view with the search results
            return view('product.view', ['products' => $products, 'categories' => $categories]);
        } catch (\Exception $exception) {
            // Handle any exceptions, if needed
            return response()->json(['status' => 'error', 'message' => 'Failed to search products']);
        }
    }

    public function ProductCreate()
    {
        $categories = Category::all(); // Assuming you have a Category model
        $sizes = Size::all();
        return view('product.create_product', compact('categories', 'sizes')); // Pass both categories and sizes to the view
    }
    public function sizeupdate(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'sizeName' => 'required|string|max:255', // Assuming 'sizeName' is the name of the input field
        ]);
    
        $user = Auth::user();
        
        try {
            // Find the size by ID
            $size = Size::findOrFail($id);
    
            // Update the size name
            $size->update([
                'name' => $request->input('sizeName'),
                'created_by'=>$user->username,
                'updated_by'=>$user->username,
                'user_id'=>$user->id,
            ]);
    
            return redirect()->back()->with('success', 'Size name updated successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to update size name.');
        }
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('product.profile', ['user' => $user]);
    }

}
