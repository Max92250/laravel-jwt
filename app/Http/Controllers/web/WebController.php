<?php

namespace App\Http\Controllers\web;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\Image;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Customer;


class WebController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function Category(Request $request)
    {

        $categories = Category::all();

    
        return view('product.category',compact('categories'));
    }
    public function Sizes(Request $request)
    {

        $sizes = Size::all();
        return view('product.size', compact('sizes'));
    }

    public function createProductWithItem(Request $request)
    {
        try {
            $request->validate([
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
            $userName = preg_replace('/[^a-zA-Z]/', '', explode('@', $user->email)[0]);
            $productData = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'created_by' => $userName,
                'updated_by' => $userName,
                'user_id'=>$user->id,
                'customer_id' => $user->customer_id, // Add customer_id to product data
        
            ];
    
            $itemsData = $request->input('items');
    
            $categoryId = $request->input('categories');
         
            $result = $this->productService->createProductWithItems($productData, $itemsData,$categoryId);
            if ($result['status'] === 'success') {
                return redirect()->route('imagecreate', ['product_id' => $result['product_id'] ,]);
            } else {
                return view('product.error', ['message' => $result['message']]);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create product. Please try again.']);
        }
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


    public function createimages($product_id){
        $product = Product::findOrFail($product_id);
        return view('product.images', compact('product','product_id'));
    }


    public function showimages($product_id){
        $product = Product::findOrFail($product_id);
        return view('product.images1', compact('product'));
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
        $image = Image::findOrFail($id);
    
        // Delete the image from storage
     
        $image->delete();
    
        return redirect()->back()->with('success', 'Image deleted successfully');
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
            'items.*.sku' => 'sometimes|required|string',
        ]);
        $categoryId = $request->input('categories', []);

        $user = Auth::user();
        $userName = preg_replace('/[^a-zA-Z]/', '', explode('@', $user->email)[0]);
        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => $userName,
            'updated_by' => $userName,
            
            'user_id'=>$user->id,
            'customer_id' => $user->customer_id, // Add customer_id 
    
        ];
        $itemsData = $request->input('items', []);

        $result = $this->productService->updateProduct($productId, $productData, $itemsData,$categoryId);

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
        return view('product.edit', compact('product','categories','sizes'));
    }
public function getAllProducts(Request $request)
{
    // Get the authenticated user
    $user = Auth::user();
  
    if ($user->isAdmin()) {
        return view('admin.customercreate');

    

    } else {
        $customer = Customer::where('id', $user->customer_id)->first();

        
        if ($customer) {
            // Fetch all products associated with the customer's ID
            $products = Product::where('customer_id', $customer->id)->get();
      
        return view('product.view', ['products' => $products]);
        }

    }
    
 /*   if ($user->isAdmin()) {
        // Fetch all products without any restrictions
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->latest()
            ->get();
    } else {
        
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }*/

    // Return the view with the fetched products
  
}

    public function hardDeleteProduct(Request $request, $productId)
    {

        $product = Product::findOrFail($productId);
        $product->delete();

        $responseMessage = 'Product and associated items/images deleted successfully';

        return redirect()->route('products.index')->with('success', $responseMessage);

    }

    public function showCategories()
    {
        // Fetch all categories
        $categories = Category::all();
        
        // Pass categories to the view
        return view('product.product', ['categories' => $categories]);
    }
    public function getProductsByCategory($categoryId)
    {
           
        try {

            // Try to find the category with the specified ID
            $category = Category::findOrFail($categoryId);
            $categories = Category::all();

            // Get the products associated with the category and eager load their items and images relationships
            $products = $category->products()->with(['items','images'])->get();

            // Return the products as a collection of ProductResource objects

            return view('product.success', ['products' => $products],['categories' => $categories]);

        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch products by category']);
        }
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
    

    public function productdetails($productId)
    {
        // Fetch the product details using the product ID
        $product = Product::with(['items', 'images'])->findOrFail($productId);
        if (!$product) {
            // Handle the case when the product is not found
            return redirect()->back()->with('error', 'Product not found.');
        }
    

        // You can perform other actions here, like adding the product to the cart session

        // Redirect to the product detail page
      
        return view('product.details', compact('product'));

    }

    public function showCreateForm()
    {
        $categories = Category::all(); // Assuming you have a Category model
        $sizes = Size::all();
        return view('product.create_product', compact('categories', 'sizes')); // Pass both categories and sizes to the view
    }
    

    
    public function profile()
    {
        $user = Auth::user();
        return view('product.profile', ['user' => $user]);
    }

}
