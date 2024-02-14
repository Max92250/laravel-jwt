<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testCreateProductWithItemsAndImages()
    {


        $productName = $this->faker->word;
        $productDescription = $this->faker->sentence;

        $itemData = [
            'price' => 10.99,
            'size' => 'M',
            'color' => 'Blue',
            'sku' => 'ffvdf4556',
        ];
        $imageData = [

            'image_1' => UploadedFile::fake()->image('image1.jpg'),
            'image_2' => UploadedFile::fake()->image('image2.jpg'),

        ];
        $this->validateProductData($productName, $productDescription, [$itemData], [$imageData]);
     
        
        $response = $this->postJson('/api/products/create', [
            'name' => $productName,
            'description' => $productDescription,
            'items' => [$itemData],
            'images' => [$imageData],
        ]);

       

        $product = Product::where('name', $productName)->first();

        $this->assertProductItemsAndImages($product, $productName, $productDescription, $itemData, $imageData);
    }

    protected function assertProductItemsAndImages($product, $name, $description, $itemData, $imageData)
    {
        $this->assertNotNull($product);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);

        $this->assertCount(1, $product->items);

        $item = $product->items->first();
        $this->assertEquals($itemData['price'], $item->price);
        $this->assertEquals($itemData['size'], $item->size);
        $this->assertEquals($itemData['color'], $item->color);

        $this->assertCount(1, $product->images);

        $updatedImages = $product->images->first();
        $image1Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH)));
        $image2Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH)));

        $this->assertFileExists($image1Path);
        $this->assertFileExists($image2Path);

    }
    public function testUpdateProductWithItemsAndImages()
    {

        $product = Product::create([
            'name' => 'Fake Product',
            'description' => 'Fake Description',
        ]);

        $item = Item::create([
            'product_id' => $product->id,
            'price' => 9.99,
            'size' => 'M',
            'color' => 'Blue',
            'sku' => 'fake_sku',
        ]);

        $image = Image::create([
            'product_id' => $product->id,
            'image_1' => 'images/fake_image1.jpg',
            'image_2' => 'images/fake_image2.jpg',
        ]);

        $newName = $this->faker->word;
        $newDescription = $this->faker->sentence;

        $newItemData = [
            'id' => $item->id,
            'price' => 15.99,
            'size' => 'L',
            'color' => 'Red',
            'sku' => 'fiubvhfib344',
        ];

        $newImage1 = UploadedFile::fake()->image('new_image1.jpg');
        $newImage2 = UploadedFile::fake()->image('new_image2.jpg');

        $response = $this->postJson("/api/update-product/{$product->id}", [
            'name' => $newName,
            'description' => $newDescription,
            'items' => [$newItemData],
            'image_ids' => $image->id,
            'image_1' => $newImage1,
            'image_2' => $newImage2,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ]);

        $product->refresh();

        $this->assertEquals($newName, $product->name);
        $this->assertEquals($newDescription, $product->description);

        $this->assertCount(1, $product->items);
        $updatedItem = $product->items->first();
        $this->assertEquals($newItemData['price'], $updatedItem->price);
        $this->assertEquals($newItemData['size'], $updatedItem->size);
        $this->assertEquals($newItemData['color'], $updatedItem->color);

        $this->assertCount(1, $product->images);
        $updatedImages = $product->images->first();

        $image1Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH)));
        $image2Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH)));

        $this->assertFileExists($image1Path);
        $this->assertFileExists($image2Path);
    }
    
    public function testGetAllProducts()
{
    $response = $this->get('/api/products/all');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'products' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'items' => [
                        '*' => [
                            'id',
                            'price',
                            'size',
                            'color',
                            'sku',  
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'images' => [
                        '*' => [
                            'id',
                            'product_id',
                            'image_1',
                            'image_2',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);


}
protected function validateProductData($name, $description, $items, $images, $responseCode)
{
    $response = $this->postJson('/api/products/create', [
        'name' => $name,
        'description' => $description,
        'items' => $items,
        'images' => $images,
    ]);


    $response->assertStatus($responseCode)
        ->assertJson([
            'status' => 'error',
            'message' => 'The given data was invalid.',
           
        ]);
     

}

    
   
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Select Category</h1>
    @if($categories->isEmpty())
        <p>No categories found.</p>
    @else
     <form id="categoryForm" action="{{ route('products.by.category', ['categoryid' => ':categoryid']) }}" method="GET">
 
        
            <select id="categorySelect" name="category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
     
        <button type="submit" id="submitButton">Submit</button>
    </form>
    @endif

    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            var selectedOption = document.getElementById('categorySelect').value;
            var actionUrl = "{{ route('products.by.category', ':categoryid') }}".replace(':categoryid', selectedOption);
            this.setAttribute('action', actionUrl);
        });
    </script>
</body>
</html><?php

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
            $validatedData =  $request->validate([
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
    
            $duplicatedSkus = $this->checkDuplicatedSkus($validatedData['items']);
            if (!empty($duplicatedSkus)) {
                // If duplicated SKUs are found, return an error response
                return back()->withInput()->withErrors(['items' => 'Duplicate SKUs found: ' . implode(', ', $duplicatedSkus)]);
            }

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
  
    
        $customer = Customer::where('id', $user->customer_id)->first();

        
        if ($customer) {
            // Fetch all products associated with the customer's ID
            $products = Product::where('customer_id', $customer->id)->get();
      
        return view('product.view', ['products' => $products]);
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
