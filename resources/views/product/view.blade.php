@extends('product.nav')

@section('section')






<div class="bg-white shadow-md rounded-lg overflow-hidden mt-20 mx-auto">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-center">ID</th>
                    <th class="px-4 py-2 text-center">Image</th>
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Description</th>
                    <th class="px-4 py-2 text-center">Category</th>
                    <th class="px-4 py-2 text-center">Created At</th>
                    <th class="px-4 py-2 text-center">Updated At</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>

            <?php
                $sortedProducts = $products->sortByDesc('created_at')->values()->all();
            ?>
            @foreach ($products as $product)
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="fixed-column border px-4 py-2">{{ $product->id }}</td>
                <td class="fixed-column border px-4 py-2 w-48 text-center">
                    @if ($product->images->count() > 0)
                    <div class="img-container hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/' . $product->images->first()->image_path) }}"
                            alt="Product Image" class="w-20 h-20 object-cover mx-auto">
                    </div>
                    @else
                        No Image
                    @endif
                </td>
                <td class="border px-4 py-2">{{ $product->name }}</td>
                <td class="border px-4 py-2 w-600">
                    @if (strlen($product->description) > 100)
                        <span id="shortDesc_{{ $product->id }}">
                            {{ substr($product->description, 0, 100) }}...
                            <button onclick="toggleDescription('{{ $product->id }}')"
                                id="readMoreBtn_shortDesc_{{ $product->id }}" class="text-blue-500 hover:underline focus:outline-none">
                                Read More
                            </button>
                        </span>
                        <span id="fullDesc_{{ $product->id }}" style="display: none">
                            {{ $product->description }}
                            <button onclick="toggleDescription('{{ $product->id }}')"
                                id="readMoreBtn_fullDesc_{{ $product->id }}" class="text-blue-500 hover:underline focus:outline-none">
                                Read Less
                            </button>
                        </span>
                    @else
                        {{ $product->description }}
                    @endif
                </td>
                <td class="border px-4 py-2">
                    <div class="text-center">
                        @foreach ($product->categories as $category)
                            <button class="bg-blue-200 hover:bg-blue-300 text-blue-800 font-semibold py-1 px-2 rounded-full mr-1 mb-1">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </td>
                <td class="border px-4 py-4 text-center">{{ \Carbon\Carbon::parse($product->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }} {{ $product->createdBy->username ?? 'Unknown' }}</td>
                <td class="border px-4 text-center py-4">{{ \Carbon\Carbon::parse($product->updated_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}  {{ $product->updatedBy->username ?? 'Unknown' }}</td>
                <td class="border px-4 py-2 text-center">
                    <a href="{{ route('showimages', ['product_id' => $product->id]) }}">
                        <i class="fas fa-images text-blue-500 cursor-pointer mr-2"></i>
                    </a>
                    <a href="{{ route('products.edit', ['product_id' => $product->id]) }}">
                        <i class="fas fa-edit text-blue-500 cursor-pointer"></i> <!-- Edit Icon -->
                    </a>
                </td>
             
            </tr>
            @endforeach
        </table>
       

    </div>
</div>

<script>
    function toggleDescription(descriptionId) {
        const shortDesc = document.getElementById(`shortDesc_${descriptionId}`);
        const fullDesc = document.getElementById(`fullDesc_${descriptionId}`);
        const readMoreBtnShort = document.getElementById(`readMoreBtn_shortDesc_${descriptionId}`);
        const readMoreBtnFull = document.getElementById(`readMoreBtn_fullDesc_${descriptionId}`);

        if (shortDesc.style.display === 'none') {
            shortDesc.style.display = 'block';
            fullDesc.style.display = 'none';
            readMoreBtnShort.innerText = 'Read More';
            readMoreBtnFull.innerText = 'Read More';
        } else {
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'block';
            readMoreBtnShort.innerText = 'Read Less';
            readMoreBtnFull.innerText = 'Read Less';
        }
    }

</script>

@endsection
