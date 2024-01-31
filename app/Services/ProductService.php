<?php
// app/Services/ProductService.php
/*
namespace App\Services;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
class ProductService
{
    public function createProductWithItems(array $productData, array $itemsData)
    {
        try {
            DB::beginTransaction();

            $product = Product::create($productData);

            $itemData = collect($itemsData)->map(function ($item) {
                return [
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                ];
            });

            $product->items()->createMany($itemData->toArray());

            DB::commit();

            return ['status' => 'success', 'product_id' => $product->id];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to create product with items'];
        }
    }
    public function createProductWithImages(int $productId, array $imageFiles)
    {
        try {
            DB::beginTransaction();

            $product = $this->findProductOrFail($productId);

            $imageData = collect($imageFiles)->map(function ($image) use ($product) {
                $imageName = $this->generateImageName($image);
                $this->storeImage($image, 'images', $imageName);

                return [
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ];
            });

            $product->images()->createMany($imageData->toArray());

            DB::commit();

            return ['status' => 'success', 'product_id' => $product->id];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to create product with images'];
        }
    }
    public function updateProduct($productId, array $productData, array $itemsData)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);

            if ($product) {
                $product->update($productData);

                if (!empty($itemsData)) {
                    foreach ($itemsData as $itemData) {
                        $itemId = $itemData['id'] ?? null;

                        if ($itemId) {
                            $item = Item::where('product_id', $product->id)->find($itemId);

                            $updateFields = [
                                'price' => $itemData['price'] ?? $item->price,
                                'size' => $itemData['size'] ?? $item->size,
                                'color' => $itemData['color'] ?? $item->color,
                            ];

                            $item->update($updateFields);
                        } else {
                            Item::create([
                                'product_id' => $product->id,
                                'price' => $itemData['price'],
                                'size' => $itemData['size'],
                                'color' => $itemData['color'],
                                'sku' => $itemData['sku'],
                            ]);
                        }
                    }
                }

                DB::commit();

                return ['status' => 'success', 'message' => 'Product updated successfully'];
            }

            return ['status' => 'error', 'message' => 'Product not found', 'code' => 404];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Failed to update product. Please try again.', 'code' => 500];
        }
    }

    public function updateImages($productId, $imagesData)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($productId);

            $product->images->each(function ($oldImage) {
                $oldImagePath = public_path(str_replace(asset(''), '', $oldImage->image_path));
                $this->deleteImage($oldImagePath);
            });

            foreach ($imagesData as $index => $image) {
                $imageName = $this->generateImageName($image);
                $imagePath = $this->storeImage($image, 'images', $imageName);

                $product->images[$index]->update([
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
        if ($image && $image->isValid() && $image->getClientOriginalName()) {
            return now()->timestamp . '_' . $image->getClientOriginalName();
        }

        return null;
    }

    private function storeImage($image, $directory, $imageName)
    {
        if ($image) {
            if ($image->move(public_path($directory), $imageName)) {
                $imagePath = $directory . '/' . $imageName;
                return $imagePath;
            } else {
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

