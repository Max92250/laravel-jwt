<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WebController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);
    
        $category = Category::create([
            'name' => $request->input('name'),
        ]);
    
        return view('product.success', ['category_id' => $category->id]);
    }
    public function createProductWithItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.size' => 'required|string',
            'items.*.color' => 'required|string',
            'items.*.sku' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'numeric|exists:categories,id', // Ensure categories are numeric and exist
        ]);

        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $itemsData = $request->input('items');

        $categoryId = $request->input('categories');

        $result = $this->productService->createProductWithItems($productData, $itemsData,$categoryId);
        if ($result['status'] === 'success') {
            return view('product.success', ['product_id' => $result['product_id']]);
        } else {
            return view('product.error', ['message' => $result['message']]);
        }
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

        if ($result['status'] === 'success') {
            return view('product.success', ['product_id' => $result['product_id']]);
        } else {
            return view('product.error', ['message' => $result['message']]);
        }
    }

    public function updateEntity(Request $request, $productId)
    {
        $request->validate([
            'name' => 'sometimes|required|string|regex:/^[^0-9]*$/',
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

        $productData = $request->only(['name', 'description']);
        $itemsData = $request->input('items', []);

        $result = $this->productService->updateProduct($productId, $productData, $itemsData,$categoryId);

        if ($result['status'] === 'success') {
            return view('product.success', ['product_id' => $result['product_id']]);
        } else {
            return view('product.error', ['message' => $result['message']]);
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

    public function getAllProducts(Request $request)
    {
        $products = Product::with(['items', 'images'])
            ->whereHas('items', function (Builder $query) {
                $query->where('status', '=', 'active');
            })
            ->get();

        return view('product.view', ['products' => $products]);

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
        return view('product.index', ['categories' => $categories]);
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


}
