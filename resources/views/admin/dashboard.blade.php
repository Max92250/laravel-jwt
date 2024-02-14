@extends('product.nav')

@section('section1')
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
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
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
                                {{ $customer->created_at->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}</td>
                            <td class="border px-4 py-4">
                                {{ $customer->updated_at->setTimezone('Asia/Kathmandu')->format('d/m/Y h:i A') }}</td>



                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
           <!-- In your Blade view -->

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

      
    @endsection
