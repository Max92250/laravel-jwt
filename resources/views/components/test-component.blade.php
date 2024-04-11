<nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Left side: Categories -->
                <div class="sm:block sm:ml-6 mt-4 flex space-x-4">
                    @foreach($categories as $category)
                    <a href="{{ route('products.by.category', ['category' => $category->id]) }}" class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            
            <div class="flex items-center"> 
                
<div class=" mr-4 text-white">
    <form action="{{ route('member.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div><!-- Right side: Cart -->
                <div class="relative">
                    <a href="{{ route('cart.show') }}" class="flex items-center justify-center bg-gray-100 h-10 w-10 rounded-full hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <span id="cart-counter" class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white text-xs rounded-full">{{ Auth::guard('members')->user()->cart->items->count() }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

