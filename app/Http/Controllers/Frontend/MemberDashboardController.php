<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{

    public function index()
    {

        return view('components.member.dashboard');
    }

    public function productsbycategory($category)
    {
        // Find the category by its ID
        $category = Category::findOrFail($category);

        $member = auth()->guard('members')->user();

        if ($member->customer_id !== $category->customer_id) {
            abort(403, 'Unauthorized');
        }
        $products = $category->products()
            ->where('status', '1') // Condition: Product should be active
            ->whereHas('items', function ($query) {
                $query->where('status', 'active'); // Condition: Item should be active
            })
            ->with(['items', 'images'])
            ->get();

        return view('components.member.product', compact('products'));
    }
}
