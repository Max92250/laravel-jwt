<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class MemberDashboardController extends Controller
{

    public function index()
    {
        
        


        return view('components.member.dashboard');
    }

    public function productsbycategory($category)
    {
        
        $category = Category::findOrFail($category);
        // Get the products associated with the category that are active and have at least one item
        $products = $category->products()
            ->where('status', '1') // Condition: Product should be active
            ->whereHas('items')  // Condition: Product should have at least one item
            ->with(['items', 'images'])
            ->get();
     
        return view('components.member.product', compact('products'));
    }
}
