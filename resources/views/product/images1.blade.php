@extends('product.nav')


@section('section2')


    <div class="bg-white shadow-md rounded-lg overflow-hidden w-full mt-20 mx-auto">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 table-auto">
                @if(isset($product) && $product->images->isNotEmpty())
                    <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Image</th>
                            <th class="px-4 py-2 text-center">Created At</th>
                            <th class="px-4 py-2 text-center">Updated At</th>
                            <th class="px-4 py-2 text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->images as $image)
                        <tr>
                            <td class="border px-4 py-2 text-center align-middle">
                                <div class="flex justify-center items-center">
                                    <img src="{{ asset('images/' . $image->image_path) }}" class="w-20 h-auto" alt="Product Image">
                                </div>
                            </td>
                            <td class="border px-4 py-2 text-center align-middle">{{ date('d/m/Y h:i A', strtotime($image->created_at)) }}</td>
                            <td class="border px-4 py-2 text-center align-middle">{{ date('d/m/Y h:i A', strtotime($image->updated_at)) }}</td>
                            <td class="border px-4 py-2 text-center align-middle">
                                <button type="button" onclick="confirmDelete('{{ route('images.delete', $image->id) }}')"
                                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-400 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 ">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
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
