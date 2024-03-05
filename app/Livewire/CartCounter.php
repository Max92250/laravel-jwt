<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartCounter extends Component
{
    public $itemcount;

    public function mount()
    {
        $user = Auth::guard('members')->user();
        $this->itemcount = $user->cart->items->count();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
