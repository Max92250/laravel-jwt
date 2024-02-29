<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Jobs\SendTestMail;
use App\Models\Category;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class CategoryController extends Controller
{

    public function update(Request $request, $id)
    {
        
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Validate the incoming data from the client-side
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'status' => 'sometimes|required|in:0,1', // Assuming status can be either 0 or 1
        ]);
        
        // Update the customer details if changes are made
        if ($request->filled('name')) {
            $category->name = $request->input('name');
        }

        if ($request->filled('status')) {
            $category->status = $request->input('status');
        }

        // Update other fields as needed
        $category->updated_by = $request->user()->id;

        // Save the changes
        $category->save();

        return redirect()->back()->with('success', 'Category name updated successfully.');
    }

    public function show(Request $request)
    {
        if (auth()->user()->hasPermission('access-category-page')) {

            $user = Auth::user();
            $categories = Category::where('customer_id', $user->customer_id)
                ->with('createdBy', 'updatedBy')
                ->get();

            // Fetch the usernames for the creators of the categories

            return view('product.category', [
                'categories' => $categories,

            ]);
        } else {
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
            "maxrai788@gmail.com",
        ];

        $jobs = [];

        // Create job instances and pass the batch ID
        foreach ($recipientEmails as $recipientEmail) {
            $jobs[] = new SendTestMail($recipientEmail, uniqid());
        }

        Bus::batch($jobs)->dispatch();

    }

}
