<x-app>


    <div class="w-11/12 grid grid-cols-2 pt-4  h-lvh   mx-auto ">
       
    
        <div class=" p-2 mt-4 ml-2 h-5/6   shadow-md rounded-lg">
           
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
                    @if (
                        $errors->has('name') ||
                            $errors->has('address_line1') ||
                            $errors->has('city') ||
                            $errors->has('state') ||
                            $errors->has('postal_code'))

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
            <div class="p-4">

                <h2 class="text-2xl font-semibold  mb-6">Select Payment Method</h2>
                
                @if(session('error'))
                <div  class="bg-red-100 border mb-10 border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif
                <form action="{{ route('place.order') }}" method="POST">
                    @if (
                        $errors->has('payment_method') ||
                            $errors->has('card_number') ||
                            $errors->has('expiration_date') ||
                            $errors->has('cvv'))
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

                    @csrf
                    <div class="grid grid-cols-2">
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                Method</label>
                            <select name="payment_method" id="payment_method"
                                class=" block w-4/5 border border-gray-300 rounded-md h-10 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="debit_card">Debit Card</option>

                            </select>

                        </div>

                        <div id="card_fields" class="">
                            <div class="mb-4">
                                <label for="card_number" class="block text-sm font-medium text-gray-700">Card
                                    Number</label>
                                <input type="text" name="card_number" id="card_number"
                                    class="mt-4 block w-4/5 border border-gray-300 rounded-md h-10 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <input type="hidden" name="selected_shipment_id" id="selected_shipment_id">
                            <input type="hidden" name="selected_total_price" value="" id="total">
                            <div class="mb-4">
                                <label for="expiration_date"
                                    class="block text-sm font-medium text-gray-700">Expiration
                                    Date</label>
                                <input type="date" name="expiration_date" id="expiration_date"
                                    class="mt-4 block w-4/5 border border-gray-300 rounded-md h-10 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" id="cvv"
                                    class="mt-4 block w-4/5 border border-gray-300 rounded-md h-10 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                    </div>
                    <button type="submit"
                        class=" px-4 py-2 w-40 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Order</button>

                </form>
            </div>
        </div>

        <div>
            <div class="grid grid-cols-1 mt-4 ml-2 p-4  h-5/6 shadow-md rounded-lg bg-white mr-2 md:grid-cols-1">
                <div class="h-5/5  mb-4 "
                    style="overflow: auto; -ms-overflow-style: none;  /* IE and Edge */; scrollbar-width: none">
                    <h2 class="text-center font-bold text-gray-500 ">Order Summary</h2>
                    <!-- Your Blade content for cart items goes here -->
                    @foreach ($cart->items as $item)
                        <div class=" w-3/4  mt-2 h-20  bg-white mb-4 p-2 ml-20 rounded-lg  shadow-md flex justify-between"
                            id="item_{{ $item->id }}">
                            <div>
                                <img src="{{ asset('images/' . $item->product->images->random()->image_path) }}"
                                    alt="product-image" class="w-12 h-12 rounded-lg " />
                            </div>

                            <div class=" flex justify-between">
                                <div class="pt-6 ml-2 w-40   sm:mt-0">
                                    <h2 class="text-sm  font-bold text-gray-900">{{ $item->product->name }}</h2>

                                </div>
                                <div class=" flex justify-between">
                                    <div class="flex items-center border-gray-100">
                                        <span
                                            class="cursor-pointer rounded-l bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50"
                                            onclick="updateQuantity('{{ $item->id }}', -1)"> - </span>
                                        <input id="quantity_{{ $item->id }}"
                                            data-max-quantity="{{ $item->item->quantity }}"
                                            class="h-8 w-8 border bg-white text-center text-xs outline-none"
                                            type="number" value="{{ $item->quantity }}" min="1" readonly />
                                        <span
                                            class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50"
                                            onclick="updateQuantity('{{ $item->id }}', 1)"> + </span>
                                    </div>
                                    <div class="flex items-center space-x-4 pl-10">
                                        <p id="price_{{ $item->id }}" class="text-sm">
                                            ${{ $item->price * $item->quantity }}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="h-5 w-5 cursor-pointer duration-150 hover:text-red-500"
                                            onclick="deleteItem('{{ $item->id }}')">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>

                                    </div>
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
                <div class="  ml-20 w-3/4  bg-white mt-10  ">
                    <hr>
                    <div class="mb-2 flex justify-between">
                        <p class="text-gray-400 font-bold">Subtotal</p>
                        <p class="text-gray-700" id="subtotal_amount">$0.00</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-400 font-bold">Shipping</p>
                        <p class="text-gray-700 mb-2">$4.99</p>
                    </div>
                    <hr class="my-4" />
                    <div class="flex mt-2 justify-between">
                        <p class="text-lg font-bold">Total</p>
                        <div>
                            <p class="mb-1 text-lg font-bold" id="total_amount">$4.99</p>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
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
            const errors = {!! $errors->toJson() !!}; // Convert PHP errors to JavaScript object
            if (Object.keys(errors).length > 0) {
                openModal();
            }
        });


        // Add event listener to radio buttons
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('click', function() {
                // Set the selected shipment ID to the hidden input field
                document.getElementById('selected_shipment_id').value = this.value;
            });
        });


        function updateQuantity(itemId, change) {
            // Get the current quantity from the input field
            var quantityInput = document.getElementById('quantity_' + itemId);
            var currentQuantity = parseInt(quantityInput.value);
            var maxQuantity = parseInt(quantityInput.getAttribute('data-max-quantity'));

            // Calculate the new quantity
            var newQuantity = currentQuantity + change;

            if (newQuantity <= 0) {
                newQuantity = 1;
            }

            newQuantity = Math.min(Math.max(newQuantity, 1), maxQuantity);
            quantityInput.value = newQuantity;
            console.log(quantityInput);


            // Send an AJAX request to update the quantity in the database
            axios.post('/update_quantity', {
                    itemId: itemId,
                    quantity: newQuantity
                })
                .then(function(response) {
                    // If the request is successful, update the price
                    var priceElement = document.getElementById('price_' + itemId);
                    var newPrice = response.data.price;
                    priceElement.textContent = '$' + newPrice;
                    console.log(response);
                    calculateTotal();
                })
                .catch(function(error) {
                    // If the request fails, log the error
                    console.error('Error updating quantity:', error);
                });
        }


        function deleteItem(itemId) {
            // Send an AJAX request to delete the item
            axios.post('/delete_item', {
                    itemId: itemId
                })
                .then(function(response) {
                    var item = document.getElementById('item_' + itemId);
                    if (item) {
                        console.log('Item found, removing...');
                        item.remove();
                        calculateTotal();
                        updateCartCounter();
                    } else {
                        console.log('Item not found.');
                    }


                })
                .catch(function(error) {
                    // If the request fails, log the error
                    console.error('Error deleting item:', error);
                });
        }

        function calculateTotal() {
            var subtotal = 0;
            var itemPrices = document.querySelectorAll('[id^="price_"]');
            itemPrices.forEach(function(itemPrice) {
                var priceText = itemPrice.textContent.trim(); // Ensure no leading/trailing spaces
                var priceValue = parseFloat(priceText.replace('$', '')); // Remove $ sign and parse as float

                subtotal += priceValue; // Remove the $ sign and parse as float
            });

            var shipping = 4.99; // Example shipping cost
            var total = subtotal + shipping;
            console.log(subtotal);

            // Update subtotal and total elements
            document.getElementById('subtotal_amount').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('total_amount').textContent = '$' + total.toFixed(2);
            document.getElementById('total').value = total;
            return subtotal;
        }

        window.onload = function() {
            var subtotalAmount = document.getElementById('subtotal_amount');
            var totalAmount = document.getElementById('total_amount');
            var shippingCost = 4.99;

            var subtotal = calculateTotal();
            updateCartCounter();
            subtotalAmount.textContent = '$' + subtotal.toFixed(2);
            totalAmount.textContent = '$' + (subtotal + shippingCost).toFixed(2);
        };


        function updateCartCounter() {
            axios.post('/update-cart-counter')
                .then(function(response) {
                    var itemCount = response.data.itemCount;
                    document.getElementById('cart-counter').innerText = itemCount;
                })
                .catch(function(error) {
                    console.error('Error updating cart counter:', error);
                });
        }
    </script>

</x-app>
