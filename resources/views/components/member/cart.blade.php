<!-- Your HTML code -->
<x-app>
    <div class="bg-white rounded-lg overflow-hidden pb-20 pt-10  w-11/12 mt-20 mx-auto"
        style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px; ">
        <!-- Your Blade content goes here -->
        <h1 class="mb-10 text-center text-2xl font-bold">Cart Items</h1>
        <div class="mx-auto w-6/5 justify-center px-6 md:flex xl:px-0">
            @if ($cart->items->isEmpty())
                <p class="text-center text-gray-500">Your cart is empty.</p>
            @else
                <div class="grid grid-cols-1 h-80 md:grid-cols-1"
                    style="overflow: auto; -ms-overflow-style: none;  /* IE and Edge */; scrollbar-width: none">
                    <!-- Your Blade content for cart items goes here -->
                    @foreach ($cart->items as $item)
                        <div class="mb-6  w-6/5 rounded-lg bg-white p-6 shadow-md flex justify-between"
                            id="item_{{ $item->id }}">
                            <div>
                                <img src="{{ asset('images/' . $item->product->images->random()->image_path) }}"
                                    alt="product-image" class="w-20 h-20 rounded-lg " />
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
                                        <input id="quantity_{{ $item->id }}" data-max-quantity="{{ $item->item->quantity }}"
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
            @endif
            @if (!$cart->items->isEmpty())
                <div class="  ml-20  h-full w-80 mb-20 rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
                    <div class="mb-2 flex justify-between">
                        <p class="text-gray-700">Subtotal</p>
                        <p class="text-gray-700" id="subtotal_amount">$0.00</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-700">Shipping</p>
                        <p class="text-gray-700 mb-2">$4.99</p>
                    </div>
                    <hr class="my-4" />
                    <div class="flex mt-2 justify-between">
                        <p class="text-lg font-bold">Total</p>
                        <div>
                            <p class="mb-1 text-lg font-bold" id="total_amount">$4.99</p>

                        </div>
                    </div>
                    <button
                        class="mt-6 w-full h-10 rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600"><a
                            href="{{ route('checkout.details', ['cart_id' => $cart->id]) }}"
                            class="mt-6 w-full h-10 rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600">Checkout</a>
                    </button>
                </div>
            @endif


        </div>


    </div>

</x-app>

<script>
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
