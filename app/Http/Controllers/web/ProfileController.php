<?php
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    public function show()
    {
        if (Gate::allows('view-profile')) {
            $user = Auth::user();
            return view('product.profile', ['user' => $user]);
        } else {
            abort(403, 'Unauthorized action.');
        }

    }

}
