@extends('product.nav')

@section('section6')
    <div class="container pt-20 mx-auto my-5 p-5">

        <!-- Left Side -->
        <div class="w-full mb-10 md:w-3/12 md:mx-2">

            <form action="{{ route('products.createWithImages') }}" method="POST" enctype="multipart/form-data"
                class="px-6 py-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product_id }}">
                <div class="mb-4">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-4">Select Images:</label>
                    <input type="file" id="images" name="images[]" multiple required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Upload Images
                </button>
            </form>
        </div>

        @if (isset($product) && $product->images->isNotEmpty())
            <div class="w-full bg-white  mx-2 ">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                            <tr>
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Created At</th>
                                <th class="px-4 py-2">Updated At</th>
                                <th class="px-4 py-2">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach ($product->images as $image)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="fixed-column border px-4 py-2 w-48 text-center">
                                        <img src="{{ asset('images/' . $image->image_path) }}" class="w-12 h-auto mx-auto"
                                            alt="Product Image">
                                    </td>
                                    <td class="border px-4 py-2">{{ $image->created_at }}</td>
                                    <td class="border px-4 py-2">{{ $image->updated_at }}</td>
                                    <td class=" border px-4 py-2">
                                        
                                        <button type="button" onclick="confirmDelete('{{ route('images.delete', $image->id) }}')"
                                            class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-400 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 ">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        @endif
    </div>
    <script>
        function confirmDelete(deleteUrl) {
            if (confirm('Are you sure you want to delete this image?')) {
                // If the user confirms, submit the form
                window.location.href = deleteUrl;
            } else {
                // If the user cancels, do nothing
                return false;
            }
        }
    </script>
    
@endsection
