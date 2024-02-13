<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Products</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 h-screen">
    <div class="bg-white shadow-md rounded-lg overflow-hidden w-full lg:w-5/6 mt-4 mx-auto">
  
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2 w-48">Image</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Updated At</th>
                        <th class="px-4 py-2 text-center">View</th>
                    </tr>
                </thead>
                
                    @foreach ($products as $product)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="border px-4 py-2">{{ $product->id }}</td>
                            <td class="border px-4 py-2 w-48 text-center">
                                @if ($product->images->count() > 0)
                                    <img src="{{ asset('images/' . $product->images->first()->image_path) }}"
                                        alt="Product Image" class="w-20 h-auto mx-auto">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $product->name }}</td>
                            <td class="border px-4 py-2">
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
                            <td class="border px-4 py-4">{{ \Carbon\Carbon::parse($product->created_at)->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}</td>
                    <td class="border px-4 py-4">{{ date('Y-m-d H:i:s', strtotime($product->created_at)}}</td>
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
</body>

</html>
