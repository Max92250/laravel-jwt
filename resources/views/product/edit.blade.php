@extends('product.nav')

@section('section7')

    <div class="container mx-auto  mt-10 bg-gray-100 p-8  ">
        <div class="w-4/5 mt-20 bg-white ml-60 rounded-lg shadow-md p-6">

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
            <h1 class="text-3xl text-gray-600 font-bold mb-6">Edit Product</h1>
          
            <form method="POST" action="{{ route('products.update', $product->id) }}">
                @csrf
                @method('PUT')

                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name"
                        class="block w-2/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                        value="{{ $product->name }}" required>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Product Description</label>
                    <textarea name="description" id="description"
                        class="form-textarea block w-2/4 bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                        rows="4" required>{{ $product->description }}</textarea>
                </div>


                <!-- Categories -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                    <div class="grid grid-cols-8 w-full ">
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    id="category_{{ $category->id }}" value="{{ $category->id }}"
                                    {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-4" id="items">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Items</label>
                    @foreach ($product->items as $index => $item)
                        <div class="flex flex-wrap gap-2  mb-4">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <div class="w-50">
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" name="items[{{ $index }}][price]" value="{{ $item->price }}"
                                    class="block w-full bg-gray-50 border border-gray-300 mt-2 mr-30  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                                    required>
                            </div>
                            <div class="w-50">
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="items[{{ $index }}][quantity]"
                                    value="{{ $item->quantity }}"
                                    class="block w-full bg-gray-50 border border-gray-300 mt-2 mr-30  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                                    required>
                            </div>

                            <div class="w-60">
                                <label class="block text-sm font-medium text-gray-700">Size</label>
                                <select name="items[{{ $index }}][size_id]"
                                    class="form-select block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                                    required>
                                    @foreach ($sizes as $size)
                                        @if ($size->parent_id === null)
                                            {{-- Parent size --}}
                                            <optgroup label="{{ $size->name }}">
                                                @foreach ($size->subsizes as $subsize)
                                                    {{-- Child size --}}
                                                    <option value="{{ $subsize->id }}"
                                                        {{ $item->size_id == $subsize->id ? 'selected' : '' }}>
                                                        {{ $subsize->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-50">
                                <label class="block text-sm font-medium text-gray-700">Color</label>
                                <input type="text" name="items[{{ $index }}][color]" value="{{ $item->color }}"
                                    class="form-input block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                                    required>
                            </div>

                            <div class="w-50">
                                <label class="block text-sm font-medium text-gray-700">SKU</label>
                                <input type="text" name="items[{{ $index }}][sku]" value="{{ $item->sku }}"
                                    class="form-input block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5"
                                    required>
                            </div>
                            <div class="w-20">
                                <label class="block text-sm mb-2 font-medium text-gray-700">Status</label>
                                <input type="text" class="border border-gray-300 rounded-md px-3 py-2 text-gray w-full"
                                    value="{{ $item->status }}" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Item Button -->
                <button type="button" id="addItem"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Add
                    Item</button>

                <button type="submit"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Update
                    Product</button>
            </form>
        </div>
    </div>
  

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.getElementById('addItem');
            const itemsContainer = document.getElementById('items');
            let newIndex = {{ $product->items->count() }}; // Start indexing from the count of existing items

            addItemButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.classList.add('flex', 'flex-wrap', 'mb-4');
                newItem.innerHTML = `
                    <div class="w-50 ">
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="items[${newIndex}][price]" class="block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5" required>
                    </div>
                    <div class="w-50 ml-2">
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="items[${newIndex}][quantity]" class="block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5" required>
                    </div>
                    
                    <div class="w-1/4 ml-2">
                        <label class="block text-sm font-medium text-gray-700">Size</label>
                        <select name="items[${newIndex}][size_id]" class="form-select block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5" required>
                            @foreach ($sizes as $size)
                                @if ($size->parent_id === null)
                                    {{-- Parent size --}}
                                    <optgroup label="{{ $size->name }}">
                                        @foreach ($size->subsizes as $subsize)
                                            {{-- Child size --}}
                                            <option value="{{ $subsize->id }}">{{ $subsize->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-50 ml-2">
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" name="items[${newIndex}][color]" class="form-input block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5" required>
                    </div>

                    <div class="w-50 ml-2">
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="items[${newIndex}][sku]" class="form-input block w-full bg-gray-50 border border-gray-300 mt-2 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5" required>
                    </div>
                `;
                itemsContainer.appendChild(newItem);
                newIndex++; // Increment the index for the next new item
            });
        });
    </script>
@endsection
