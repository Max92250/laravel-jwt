<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Mail\TestMail;
use App\Jobs\SendTestMail;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Services\ProductService;
use DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('product.profile', ['user' => $user]);
    }




}
