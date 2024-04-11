<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class MemberProductController extends Controller
{

    public function show($id)
    {
        $member = auth()->guard('members')->user();
        $amounts = $member->credits()->pluck('amount');

        $product = Product::findOrFail($id);

        if ($member->customer_id !== $product->customer_id) {
            abort(403, 'Unauthorized');
        }

        $products = Product::where('id', $id)
            ->with(['items' => function ($query) {
                $query->where('status', 'active');
            }, 'images'])
            ->get();

        return view('components.member.details', compact('products', 'amounts'));

    }
}
