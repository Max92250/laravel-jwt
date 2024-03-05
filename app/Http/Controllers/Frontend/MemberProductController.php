<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MemberProductController extends Controller
{

    public function show($id)
    {
        $member = auth()->guard('members')->user();
        $amounts = $member->credits()->pluck('amount');
       
        
        $products = Product::where('id', $id)
            ->with(['items' => function ($query) {
                $query->where('status', 'active');
            }, 'images'])
            ->get();
 
        return view('components.member.details', compact('products','amounts'));
    
    }
}
