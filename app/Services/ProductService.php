<?php
// app/Services/ProductService.php

namespace App\Services;
use App\Models\Category;
use App\Events\ItemCreated;
use App\Models\Item;
use App\Models\Size;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class ProductService
{

    
public function createProductWithItems(array $productData, array $itemsData, array $categoryId)
{
    try {
        DB::beginTransaction();
    
        // Create a new product
        $product = Product::create($productData);
        
        // Associate the product with categories
        $product->categories()->attach($categoryId);

        // Format the items data
        $formattedItems = collect($itemsData)->map(function ($item) {
            return [
                'sku' => $item['sku'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'size_id' => $item['size_id'],
                'color' => $item['color'],
            ];
        });
      
        $product->items()->createMany($formattedItems->toArray());

        // Execute database operations
        DB::commit();
       
        // Return success response
        return ['status' => 'success', 'product_id' => $product->id];

    } catch (\Exception $exception) {
        DB::rollBack();
      dd($exception);
        // Return error response
        return ['status' => 'error', 'message' => 'Failed to create product with items and categories'];
    }
}



    public function createProductWithImages(int $productId, array $imageFiles)
    {
        try {
            DB::beginTransaction(); // Begin a new database transaction

            $product = $this->findProductOrFail($productId);// Find the product or throw an exception

            $imageData = collect($imageFiles)->map(function ($image) use ($product) {// Create a collection from the image files and transform each image
                $imageName = $this->generateImageName($image); // Generate a unique image name
                $this->storeImage($image, 'images', $imageName);// Store the image in the images directory


                return [// Prepare the image data to be saved in the database
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ];
            });

            $product->images()->createMany($imageData->toArray()); // Save the image data in the database

            DB::commit();// Commit the database transaction

            return ['status' => 'success','product_id' => $product->id];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to create product with images'];
        }
    }
    public function updateProduct($productId, array $productData, array $itemsData,array $categoryId)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);
          
            if ($product) {

                $product->update($productData);
                
         

                if (!empty($categoryId)) {
                    
                    $product->categories()->sync($categoryId);
                }
    
                $itemsToCreate = [];// Initialize an array to store the items to create
                
                if (!empty($itemsData)) {
                    foreach ($itemsData as $itemData) { 
                        
                        // Loop through each item data
                        $itemId = $itemData['id'] ?? null;

                        

                        if ($itemId) {
                            $item = Item::where('product_id', $product->id)->find($itemId);// Find the item by ID
                            if ($item->sku !== $itemData['sku']) {
                                Validator::make($itemData, [
                                    'sku' => 'required|string|unique:items,sku',
                                ])->validate();
                            }
                            $updateFields = [ // Prepare the fields to update
                                'price' => $itemData['price'] ?? $item->price,
                                'quantity' => $itemData['quantity'] ?? $item->quantity,
                                'size_id' => $itemData['size_id'] ?? $item->size,
                                'color' => $itemData['color'] ?? $item->color,
                                'sku' => $itemData['sku'] ?? $item->sku
                                
                            ];

                            $item->update($updateFields); // Update the item fields
                        } else {
                            $existingItem = Item::where('sku', $itemData['sku'])->first();
                            if ($existingItem) {
                                throw new \Exception('SKU must be unique.');
                            }
             
                            $itemsToCreate[] = [ // Prepare the fields to create a new item
                                'product_id' => $product->id,
                                'price' => $itemData['price'],
                                'quantity' => $itemData['price'],
                                'size_id' => $itemData['size_id'],
                                'color' => $itemData['color'],
                                'sku' => $itemData['sku'],
                                'created_at' => now(),  
                                'updated_at' => now(),  
                            ];
                        }
                    }
                    if (!empty($itemsToCreate)) { // If there are items to create
                        Item::insert($itemsToCreate);// Create the items
                    
                    }
                }

                DB::commit();

                return ['status' => 'success', 'message' => 'Product updated successfully'];
            }
            dd($itemsData);

            return ['status' => 'error', 'message' => 'Product not found', 'code' => 404];
        } catch (\Exception $exception) {
            
            DB::rollBack(); // Commit the database transaction

           // dd($exception);
           return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => 500];
        }
    }

    public function updateImages($productId, $imagesData)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);// Find the product by ID

            $product->images->each(function ($oldImage) {// Loop through each old image
                $oldImagePath = public_path(str_replace(asset(''), '', $oldImage->image_path)); // Get the old image path
                $this->deleteImage($oldImagePath);// Delete the old image
            });

            foreach ($imagesData as $index => $image) {// Loop through each new image
                $imageName = $this->generateImageName($image);// Generate a unique image name
                $imagePath = $this->storeImage($image, 'images', $imageName);// Store the image in the images directory

                $product->images[$index]->update([// Update the image data
                    'product_id' => $productId,
                    'image_path' => $imageName,
                ]);
            }

            DB::commit();

            return ['status' => 'success', 'product_id' => $productId];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to update images. Please try again.'];
        }
    }

    private function findProductOrFail($productId)
    {
        return Product::findOrFail($productId);
    }
    
    private function generateImageName($image)
    {
        if ($image && $image->isValid() && $image->getClientOriginalName()) { // Check if the image is valid and has a name
            return now()->timestamp . '_' . $image->getClientOriginalName();// Generate a unique image name
        }

        return null;// Return null if the image is not valid or has no name
    }

    private function storeImage($image, $directory, $imageName)
    {
        if ($image) {// Check if an image is provided
            // Attempt to move the image to the target directory
            if ($image->move(public_path($directory), $imageName)) {
                  // If the image is moved successfully, return the image path
                $imagePath = $directory . '/' . $imageName;
                return $imagePath;
            } else {
                  // If the image cannot be moved, return null
                return null;
            }
        }

        return null;
    }


    
    private function deleteImage($imagePath)
    {
        File::delete($imagePath);
    }

}

