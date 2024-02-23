@extends('product.nav')

@section('section')

@if (Auth::user()->type === 'admin')
    <div id="create-customer-modal"
        class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">

        <div class="bg-white  shadow-md p-6 rounded-md relative">
            <button id="close-create-customer-modal"
                class="absolute top-0 right-0   text-gray-600 hover:text-gray-800 focus:outline-none" aria-label="Close">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="text-2xl font-semibold mb-4">Create Customer</h2>



            <!-- Form for creating a new customer -->
            <form action="{{ route('customers.store') }}" method="POST" class="space-y-4" id="create-customer-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                            required>
                        
                    </div>
                    <div class="mb-4">
                        <label for="identifier" class="block text-sm font-medium text-gray-700">Customer Identifier</label>
                        <input type="text" name="identifier" id="identifier"
                            class="bg-gray-50 border border-gray-300 mt-4 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                            required>
                        @error('identifier')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="text-center">
                    <!-- Button to submit the form -->
                    <button type="submit"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create</button>
                </div>
            </form>
        </div>
    </div>

    <div id="overlay" class="fixed top-0 left-0 w-full h-full bg-black opacity-30 z-20" style="display: none;"></div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden pt-20 mx-auto">
        <div class="overflow-x-auto">
            <div class="flex justify-start py-4 px-4">
                <a id="open-create-customer-modal"
                    class="bg-white hover:bg-gray-100 text-gray-500 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Create
                    Customer</a>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Identifier</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3">Updated</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="border px-4 py-4">
                                <a href="{{ route('user.dashboard', ['customerId' => $customer->id]) }}">{{ $customer->id }}
                                </a></td>
                            <td class="border px-4 py-4">
                                {{ $customer->name }}

                            </td>
                            <td class="border px-4 py-4">{{ $customer->identifier }}</td>
                            <td class="border px-4 py-4">{{ $customer->status }}</td>
                            <td class="border px-4 py-4">
                                {{ $customer->created_at->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                {{ $customer->createdBy->username ?? '' }}
                            </td>
                            <td class="border px-4 py-4">
                                {{ $customer->updated_at->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}
                                {{ $customer->updatedBy->username ?? '' }}
                            </td>
                            <td class="border px-4 py-2 text-center align-middle text-center">
                                <button class="text-blue-500 hover:underline"
                                    onclick="openEditModal('{{ $customer->id }}', 
                                   '{{ $customer->name }}', '{{$customer->identifier}}',)">
                                    <i class="fas fa-edit cursor-pointer"></i> <!-- Edit Icon -->
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="editModal" class="modal fixed top-20 left-1/2 transform -translate-x-1/2 z-50" style="display: none;">
            <div class="modal-content p-4 mt-20 bg-white shadow-md rounded-lg" style="width: 400px;">
                <span class="close font-bold mt-2 mr-2 cursor-pointer" onclick="closeEditModal()">&times;</span>
                <h2 class="text-center font-bold mb-4">Edit Customer</h2>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <input type="text" id="customerName" name="name"
                            class="block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <input type="text" id="identifierName" name="identifier"
                            class="block w-full p-2 border border-gray-300 rounded-md">
                            @error('identifier')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                        
                    </div>
                    <button type="submit"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-2 border border-gray-400 rounded shadow">
                        Update 
                    </button>
                </form>
            </div>
        </div>
    
        <script>
           <!-- In your Blade view -->

           function openEditModal(id, name,identifier) {
            var modal = document.getElementById("editModal");
            var form = document.getElementById("editForm");
            form.action = "{{ url('customers') }}" + "/" + id + "/update";
            var sizeNameInput = document.getElementById("customerName");

            var identifierInput = document.getElementById("identifierName");

            sizeNameInput.value = name;
            identifierInput.value = identifier;
            modal.style.display = "block";

            // Show the overlay
            var overlay = document.getElementById("overlay");
            overlay.style.display = "block";
        }

        function closeEditModal() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none";

            // Hide the overlay
            var overlay = document.getElementById("overlay");
            overlay.style.display = "none";
        }

    document.addEventListener("DOMContentLoaded", function() {
        const openModalButton = document.getElementById("open-create-customer-modal");
        const modal = document.getElementById("create-customer-modal");
        const closeModalButton = document.getElementById("close-create-customer-modal");

        // Function to open the modal
        function openModal() {
            modal.classList.remove("hidden");
        }

        // Function to close the modal
        function closeModal() {
            modal.classList.add("hidden");
        }

        // Event listener to open the modal when the button is clicked
        openModalButton.addEventListener("click", openModal);

        // Event listener to close the modal when the close button is clicked
        closeModalButton.addEventListener("click", closeModal);

        // Event listener to close the modal when clicked outside of it
        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Check if there are errors and show the modal accordingly
        const errors = {!! $errors->toJson() !!}; // Convert PHP errors to JavaScript object
        if (Object.keys(errors).length > 0) {
            openModal();
        }
    });
</script>

@elseif (Auth::user()->type === 'user')

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

@endif
 @endsection
