<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function stores(Request $request)
    
    {
        if (auth()->user()->hasPermission('create-product')) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'items.*.price' => 'required|numeric',
                'items.*.quantity' => 'required|numeric',
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
    }else{
        abort(403);
    }
    }

    public function update(Request $request, $productId)
    {

        if (auth()->user()->can('update-product')) {

            $request->validate([
                'name' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'categories' => 'sometimes|required|array',
                'categories.*' => 'integer|exists:categories,id', // Updated validation for category IDs
                'items' => 'sometimes|required|array',
                'items.*.id' => 'sometimes|required|exists:items,id',
                'items.*.price' => 'sometimes|required|numeric',
                'items.*.quantity' => 'sometimes|required|numeric',
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
        } else {
            abort(403);
        }
    }

    public function edit($productId)
    {
        if (auth()->user()->hasPermission('edit-product')) {

            $product = Product::find($productId);
            if (!$product) {
                abort(404);
            }
            $skus = $product->items()->pluck('sku')->toArray();

            $payload = [
                'itemSkuNumbers' => $skus,
            ];

            $response = Http::withHeaders(['apiToken' => config('app.config.warehouse_api_token')])
                ->post(config('app.config.warehouse_api_url'), $payload);

            if ($response->successful()) {
                $itemsData = $response->json()['result']['customerItems'] ?? [];
                $product->items->each(function ($item) use ($itemsData) {
                    $itemData = collect($itemsData)->firstWhere('itemSkuNumber', $item->sku);
                    if ($itemData) {
                        $item->quantity = $itemData['A2S'];
                        $item->status = $itemData['status'] == 1 ? 'active' : 'inactive';
                    } else {
                        $item->quantity = 0;
                        $item->status = 'inactive';
                    }
                    $item->save();
                });

            }

            $customerId = auth()->user()->customer_id;
            $categories = Category::where('customer_id', $customerId)->get();
            $sizes = Size::where('customer_id', $customerId)->get();

            return view('product.edit', compact('product', 'categories', 'sizes'));
        } else {
            abort(403);
        }

    }

    public function show(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        $customer = Customer::where('id', $user->customer_id)->first();

        if ($customer) {

            $products = Product::where('customer_id', $customer->id)->with('createdBy', 'updatedBy')
                ->latest()

                ->get();

            return view('product.view', [
                'products' => $products,

            ]);
        }
    }

    public function search(Request $request)
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

    public function create()
    {
        if (auth()->user()->hasPermission('create-product')) {

            $user = auth()->user();
            $categories = Category::where('customer_id', $user->customer_id)->get();
            $sizes = Size::where('customer_id', $user->customer_id)->get();

            return view('product.create_product', compact('categories', 'sizes')); // Pass both categories and sizes to the view

        } else {
            abort(403);
        }
    }

}
