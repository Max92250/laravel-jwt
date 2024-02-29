<?php
// app/Http/Controllers/BaseController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->shareCategories();
    }

    protected function shareCategories()
    {
        $member = auth()->guard('members')->user();
        if ($member) {
            $customer = $member->customer;
            $categories = $customer->category;
            view()->share('categories', $categories);
        }
    }
}
