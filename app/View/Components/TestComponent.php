<?php
// app/View/Components/TestComponent.php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class TestComponent extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|string
    {
        $member = auth()->guard('members')->user();
        
            $customer = $member->customer;
            $categories = $customer->category;
      
        return view('components.test-component')->with('categories', $categories);
    }
}
