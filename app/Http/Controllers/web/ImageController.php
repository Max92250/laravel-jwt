<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Mail\TestMail;
use App\Jobs\SendTestMail;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function create(Request $request)
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

    public function store($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('product.images', compact('product', 'product_id'));
    }

    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);

        // Filter images based on product ID
        $images = $product->images->where('product_id', $product->id)->where('status', 1);

        return view('product.images1', compact('images'));
    }

    public function update(Request $request, $productId)
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
   
}
