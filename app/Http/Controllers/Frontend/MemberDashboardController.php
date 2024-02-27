<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class MemberDashboardController extends Controller
{

    public function index()
    {
        $member = auth()->guard('members')->user();

        // Assuming the relationship between Member and Customer is defined correctly
        $customer = $member->customer;

        // Assuming the relationship between Customer and Category is defined correctly
        $categories = $customer->category;
        view()->share('categories', $categories);
        return view('member.dashboard', compact('categories'));
    }
    public function productsbycategory(Category $category)
    {
        // Assuming you have a relationship between Category and Product
        $products = $category->products;

        view()->share('categories', $category->customer->category);
        return view('member.product', compact('products'));
    }
}
