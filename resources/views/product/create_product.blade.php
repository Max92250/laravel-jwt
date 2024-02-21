@extends('product.nav')

@section('section8')

    <div class="container mx-auto mt-20">
        <div class="w-4/5 mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-center text-2xl font-bold mb-4">Create Product with Items</h1>
         
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Validation Error!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

           
            <form action="{{ route('products.create.items') }}" method="POST" id="product-form">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name:</label>
                    <input type="text" id="name" name="name" required
                        class="mt-1 p-2 w-2/4 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="description" name="description" rows="3" required
                        class="mt-1 p-2 w-3/4 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Category:</label>
                    <div class="grid grid-cols-5 gap-4">
                        @foreach ($categories as $category)
                            <div class="flex items-center">
                                <input type="checkbox" name="categories[]" id="category_{{ $category->id }}"
                                    value="{{ $category->id }}"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="category_{{ $category->id }}"
                                    class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <hr class="mb-4">
                <h3 class="mb-2 text-lg font-medium">Items:</h3>
                <div id="items">
                    <div class="item mb-4">
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                                <input type="number" name="items[0][price]" required
                                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="size_id" class="block text-sm font-medium text-gray-700">Size:</label>
                                <select name="items[0][size_id]" required
                                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach ($sizes as $size)
                                        @if ($size->parent_id === null)
                                            <optgroup label="{{ $size->name }}">
                                                @foreach ($size->subsizes as $subsize)
                                                    <option value="{{ $subsize->id }}">{{ $subsize->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Color:</label>
                                <input type="text" name="items[0][color]" required
                                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="items[0][sku]" required
                                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="bg-white mb-2 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="add-item">Add Item</button>
                <hr class="mb-4">
                <button type="submit"
                    class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-green-500 rounded shadow ripple hover:shadow-lg hover:bg-green-600 focus:outline-none">
                    Create Product
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.getElementById('add-item');
            const itemsContainer = document.getElementById('items');

            let itemIndex = 0;

            addItemButton.addEventListener('click', function() {
                itemIndex++;

                const newItem = document.createElement('div');
                newItem.classList.add('item', 'mb-4');
                newItem.innerHTML = `
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                            <input type="number" name="items[${itemIndex}][price]" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="size_id" class="block text-sm font-medium text-gray-700">Size:</label>
                            <select name="items[${itemIndex}][size_id]" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ($sizes as $size)
                                    @if ($size->parent_id === null)
                                        <optgroup label="{{ $size->name }}">
                                            @foreach ($size->subsizes as $subsize)
                                                <option value="{{ $subsize->id }}">{{ $subsize->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">Color:</label>
                            <input type="text" name="items[${itemIndex}][color]" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU:</label>
                            <input type="text" name="items[${itemIndex}][sku]" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                `;

                itemsContainer.appendChild(newItem);
            });
        });
    </script>

@endsection