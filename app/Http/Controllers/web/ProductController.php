<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Mail\TestMail;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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
                'created_by' => $user->id,
                'updated_by' => $user->id,

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
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Fetch sizes based on the customer ID associated with the authenticated user
        $sizes = Size::where('customer_id', $user->customer_id)->get();
    
        // Fetch the usernames for the creators of the sizes
        $createdByUsernames = User::whereIn('id', $sizes->pluck('created_by')->unique())
            ->pluck('username', 'id');
    
        // Fetch the usernames for the updaters of the sizes
        $updatedByUsernames = User::whereIn('id', $sizes->pluck('updated_by')->unique())
            ->pluck('username', 'id');
    
     
    
        // Return the view with the parent size, usernames, and filtered sizes
        return view('product.subsizes', compact('size', 'sizes', 'createdByUsernames', 'updatedByUsernames'));

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

    public function updateproduct(Request $request, $productId)
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
         
            'updated_by' => $user->id,

            

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

    public function edit($productId)
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
    
            // Fetch the usernames for the creators of the products
            $createdByUsername = User::whereIn('id', $products->pluck('created_by')->unique())
                ->pluck('username', 'id');
    
            // Fetch the usernames for the updaters of the products
            $updatedByUsername = User::whereIn('id', $products->pluck('updated_by')->unique())
                ->pluck('username', 'id');
    
            return view('product.view', [
                'products' => $products,
                'createdByUsername' => $createdByUsername,
                'updatedByUsername' => $updatedByUsername,
            ]);
        }
    }
    
    protected function fetchProductsForCustomer($customer)
    {
        return Product::where('customer_id', $customer->id)->latest()->get();
    }

    public function getProductsBySearch(Request $request)
    {
        $categories = Category::all();

        // Get the authenticated user
        $user = Auth::user();

        // Get the search query from the request
        $searchQuery = $request->input('q');

        try {
            // Fetch the products based on the customer_id of the authenticated user
            $productsQuery = Product::where('customer_id', $user->customer_id);

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
        $user = auth()->user();
        $categories = Category::where('customer_id', $user->customer_id)->get();
        $sizes = Size::where('customer_id', $user->customer_id)->get();
    
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
             
                'updated_by' =>$request->user()->id,
               
            ]);

            return redirect()->back()->with('success', 'Size name updated successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to update size name.');
        }
    }

    public function updateCategory(Request $request, $id)
    {
        // Validate the incoming data from the client-side
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
        ]);
    
        // Find the category by ID
        $category = Category::findOrFail($id);
    
        // Update the category with the validated data
        $category->update([
            'name' => $request->input('name'),
            'updated_by' => $request->user()->id,
        ]);
    
        return redirect()->back()->with('success', 'Category name updated successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('product.profile', ['user' => $user]);
    }

    public function Sizes(Request $request)
    {
        $user = Auth::user();

        $sizes = Size::where('customer_id', $user->customer_id)->get();
        // Fetch the username for the creator of the sizes
        $createdByUsername = User::whereIn('id', $sizes->pluck('created_by')->unique())
        ->pluck('username', 'id');

    // Fetch the usernames for the updaters of the sizes
    $updatedByUsername = User::whereIn('id', $sizes->pluck('updated_by')->unique())
        ->pluck('username', 'id');

        return view('product.size', [
            'sizes' => $sizes,
            'createdByUsername' => $createdByUsername,
            'updatedByUsername' => $updatedByUsername,
        ]);
    }
    public function createCategory(Request $request)
    {
        $this->validateCategory($request);

        try {
            $category = $this->createCategoryModel($request);
            $this->sendEmailNotification($category);

            return redirect()->back()->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create category. Please try again.']);
        }
    }

    private function validateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);
    }

    private function createCategoryModel(Request $request)
    {
        $user = Auth::user();

        return Category::create([
            'name' => $request->input('name'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'customer_id' => $user->customer_id,
        ]);
    }

    private function sendEmailNotification(Category $category)
    {
        $email = Auth::user()->email;
        Mail::to($email)->send(new TestMail($email));
    }

    
    public function Category(Request $request)
    {
        $user = Auth::user();
    
        $categories = Category::where('customer_id', $user->customer_id)->get();
    
        // Fetch the usernames for the creators of the categories
        $createdByUsername = User::whereIn('id', $categories->pluck('created_by')->unique())
            ->pluck('username', 'id');
    
        // Fetch the usernames for the updaters of the categories
        $updatedByUsername = User::whereIn('id', $categories->pluck('updated_by')->unique())
            ->pluck('username', 'id');
    
        return view('product.category', [
            'categories' => $categories,
            'createdByUsername' => $createdByUsername,
            'updatedByUsername' => $updatedByUsername,
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the incoming request data

        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:sizes,id',
            'status' => 'nullable|in:active,inactive',

        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create a new Size object with the authenticated user's ID as created_by and updated_by
        $size = new Size([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'status' => $request->input('status'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'customer_id' => $user->customer_id,
        ]);

        // Save the size to the database
        $size->save();

        return redirect()->back()->with('success', 'Size name successfully.');

    }

}
