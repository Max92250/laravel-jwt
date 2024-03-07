<x-app>

    <div class="p-4 w-3/4  h-40">
        @if ($shipments->isNotEmpty())
            <div class="  h-20    w-3/4 grid grid-cols-1 gap-2"
                style="overflow: auto; -ms-overflow-style: none;  /* IE and Edge */; scrollbar-width: none">
                @foreach ($shipments as $shipment)
                    <div class=" bg-gray-200  p-1 h-8  shadow-md rounded-lg relative">
                        <label class="flex items-center mb-2">
                            <input type="radio" name="shipment_id" value="{{ $shipment->id }}" class="mr-2">
                            <div>
                                <p>Shipment Address:{{ $shipment->address_line1 }}, {{ $shipment->city }}</p>

                            </div>
                        </label>
                        <a href="{{ route('delete.shipment', ['id' => $shipment->id]) }}"
                            class="absolute top-0 right-0 mt-2 mr-2 text-red-500">X</a>

                    </div>
                @endforeach

            </div>
        @endif


        <button id="open-create-customer-modal"
            class="bg-white hover:bg-gray-100 text-gray-800 mt-2 font-semibold py-1 px-2 border border-gray-400 rounded shadow">
            Add Shipment Address
        </button>
    </div>
    <div id="create-customer-modal"
        class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex rounded-lg shadow-md justify-center items-center hidden ">
        <div id="shipmentForm" class="bg-white w-4/5  p-6 relative">
            <button id="close-create-customer-modal"
                class="absolute top-0 right-0   text-gray-600 hover:text-gray-800 focus:outline-none"
                aria-label="Close">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="text-2xl font-semibold mb-6">Shipment Address</h2>
            @if ($errors->has('name') || $errors->has('address_line1') || $errors->has('city') || $errors->has('state') || $errors->has('postal_code'))
       
            <!-- Display payment error message or handle it as needed -->
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">

                    <strong class="font-bold">Validation Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('shipment.store') }}" method="POST">

                @csrf
                <div class="grid grid-cols-2 "
                    style="overflow: auto; -ms-overflow-style: none;  /* IE and Edge */; scrollbar-width: none">

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium mb-2 text-gray-700">Recipient
                            Name</label>
                        <input type="text" name="name" id="name"
                            class="mt-4 border h-10 block w-4/5 border-black rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="address_line1" class="block text-sm font-medium text-gray-700">Address Line
                            1</label>
                        <input type="text" name="address_line1" id="address_line1"
                            class="mt-4 border block border-gray-300 h-10 rounded-md w-4/5 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="address_line2" class="block text-sm font-medium text-gray-700">Address Line
                            2</label>
                        <input type="text" name="address_line2" id="address_line2"
                            class="mt-4 block border  w-4/5 border-gray-300 rounded-md h-10 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city"
                            class="mt-4 block w-4/5 border  border-gray-300 rounded-md shadow-sm h-10 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" name="state" id="state"
                            class="mt-4 block w-4/5 border border-gray-300 rounded-md shadow-sm  h-10 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal
                            Code</label>
                        <input type="text" name="postal_code" id="postal_code"
                            class="mt-4 border h-10 block w-4/5 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class=" px-4 py-2 w-40 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Add</button>
                </div>
            </form>
        </div>
    </div>


</x-app>