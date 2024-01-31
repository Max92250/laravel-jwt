<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class WebController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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
        ]);
    

        $productData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $itemsData = $request->input('items');

        $result = $this->productService->createProductWithItems($productData, $itemsData);
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

}
