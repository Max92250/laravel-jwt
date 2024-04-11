<?php
// app/View/Components/TestComponent.php

namespace App\View\Components;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class TestComponent extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View | string
    {
        $member = auth()->guard('members')->user();

        $customer = $member->customer;
        $categories = $customer->category;

   

        return view('components.test-component', [
            'categories' => $categories,
          
        ]);
    }
}
