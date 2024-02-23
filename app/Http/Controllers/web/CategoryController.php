<?php

namespace App\Http\Controllers\web;
use App\Jobs\SendTestMail;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Mail\TestMail;
use Illuminate\Support\Collection;

use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use DB;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class CategoryController extends Controller
{
    
     public function update(Request $request, $id)
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

     public function show(Request $request)
     {
        if (auth()->user()->hasPermission('access-category-page')) {

         $user = Auth::user();
         $categories = Category::where('customer_id', $user->customer_id)
         ->with('createdBy','updatedBy')
         ->get();
     
         // Fetch the usernames for the creators of the categories
        
         return view('product.category', [
             'categories' => $categories,
         
         ]);
        }else{
            abort(403);
        }
     }
     
     public function create(Request $request)
     {
         $this->validateCategory($request);
 
         try {
             $category = $this->createCategoryModel($request);
             $this->sendEmailNotification($category);
          
             return redirect()->back()->with('success', 'Category created successfully.');
         } catch (\Exception $e) {
            dd($e);
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
         $recipientEmails = [
             "maxrai788@gmail.com",
             "maxrai788@gmail.com",
             "maxrai788@gmail.com",
             "najus777@gmail.com",
             "maxrai788@gmail.com"
         ];
 
         $jobs = [];
         
         // Create job instances and pass the batch ID
         foreach ($recipientEmails as $recipientEmail) {
             $jobs[] = new SendTestMail($recipientEmail, uniqid());
         }

 
        Bus::batch($jobs)->dispatch();


     }
 
}