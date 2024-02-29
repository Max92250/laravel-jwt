<!-- resources/views/components/test-component.blade.php -->

<nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class=" sm:block sm:ml-6 mt-4">
                    <div class="flex space-x-4">
                        <!-- Iterate over categories -->
                        @foreach($categories as $category)
                        <a href="{{ route('products.by.category', ['category' => $category->id]) }}" class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
