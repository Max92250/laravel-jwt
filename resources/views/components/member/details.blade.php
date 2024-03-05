<x-app>
    <style>
        #selected-color-circle {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
    </style>
    <div class="container w-11/12 mt-6 mx-auto pt-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-5">
            <!-- Product Image Carousel -->
            <div class="relative w-full">
                <div id="product-carousel" class="carousel">
                    @foreach ($products as $product)
                        @foreach ($product->images as $image)
                            <div class="carousel-item  ml-40">
                                <img src="{{ asset('images/' . $image->image_path) }}" class="w-80 h-80 mb-4"
                                    alt="Product Image">
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="ml-20 pl-20 mt-4  w-3/4 ">
                    <div class="grid grid-cols-4 ">
                        @foreach ($products as $product)
                            @foreach ($product->images as $key => $image)
                                <div class="thumbnail ">
                                    <img src="{{ asset('images/' . $image->image_path) }}"
                                        class="w-20 h-20 rounded-lg shadow-lg cursor-pointer" alt="Product Thumbnail"
                                        onclick="currentSlide({{ $key + 1 }})">
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Product Details -->
            <div class="ml-4 pl-4">
                @foreach ($products as $product)
                    <h4 class="text-2xl font-bold mb-2">{{ $product->name }}</h4>
                    <!-- Star Ratings -->
                    <div class="flex items-center mb-2 text-yellow-500">
                        <!-- Add your star SVG here -->
                    </div>
                    <p class="text-gray-700 mb-4">{{ $product->description }}</p>

                    <div class="mb-4">
                        <label for="product-size-select" class="text-lg font-medium text-gray-800">Select Size:</label>
                        <select id="product-size-select"
                            class="block w-40 mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($product->items as $item)
                                <option value="{{ $item->id }}" data-price="{{ $item->price }}"
                                    data-color="{{ $item->color }}">{{ $item->size->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="flex flex-col mb-4">
                        <span class="text-lg font-medium text-gray-800">Selected Color:</span>
                        <div id="selected-color-circle"></div>
                    </div>

                    <!-- Product Price -->
                    <div class="flex flex-col mb-4">
                        <span class="text-lg font-medium text-gray-800">Price:</span>
                        <span id="product-price" class="text-gray-600"></span>
                    </div>
                    <div class="flex  mt-4">
                        <button
                            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
                            id="buy-button">Buy now</button>
                        <button
                            class="bg-white ml-4 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow ml-2" id="add-to-cart">Add
                            to cart</button>
                    </div>


            </div>
            @endforeach
        </div>
    </div>
    </div>

    <script>
        var slideIndex = 1;

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("carousel-item");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        // Initialize the slideIndex with the first image
        currentSlide(slideIndex);



        function setInitialColorAndPrice() {
            var selectedItem = document.querySelector('#product-size-select option:first-child');
            var color = selectedItem.getAttribute('data-color');
            var price = selectedItem.getAttribute('data-price');
            var amountValue = "{{ $amounts }}";
            var amount = parseInt(amountValue.replace(/[^\d.-]/g, ''));

            document.getElementById('product-price').textContent = '$' + price;

            // Set the background color of the color circle element
            var colorCircle = document.getElementById('selected-color-circle');
            colorCircle.style.backgroundColor = color;

            // Check if any item price is greater than or equal to the amount
            if (parseFloat(price) > amount) {
                console.log(false);
                document.getElementById('buy-button').style.display = 'none';
            } else {
                console.log(true);
                document.getElementById('buy-button').style.display = 'block';
            }
        }

        // Event listener for when the size select changes
        document.getElementById('product-size-select').addEventListener('change', function() {
            var selectedItem = this.options[this.selectedIndex];
            var price = selectedItem.getAttribute('data-price');
            var color = selectedItem.getAttribute('data-color');
            var amountValue = "{{ $amounts }}";
            var amount = parseInt(amountValue.replace(/[^\d.-]/g, ''));

            document.getElementById('product-price').textContent = '$' + price;
            var colorCircle = document.getElementById('selected-color-circle');
            colorCircle.style.backgroundColor = color;

            // Check if any item price is greater than or equal to the amount
            if (parseFloat(price) >= amount) {
                console.log('true');
                document.getElementById('buy-button').style.display = 'none';
            } else {

                document.getElementById('buy-button').style.display = 'block';
            }
        });

        // Call the function to set the initial color and price when the page loads
        window.addEventListener('load', function() {
            setInitialColorAndPrice();
        });

        document.getElementById('add-to-cart').addEventListener('click', function() {
        // Get the selected item ID from the select element
        var itemId = document.getElementById('product-size-select').value;

        // Send an AJAX request to the backend controller
        axios.post('/add-to-cart', {
            item_id: itemId
        })
        .then(function(response) {
            // Handle success response
            console.log(response);
            updateCartCounter();

        })
        .catch(function(error) {
            // Handle error response
            console.error(error);
        });
    });

    
    function updateCartCounter() {
        axios.post('/update-cart-counter')
            .then(function(response) {
                var itemCount = response.data.itemCount;
                document.getElementById('cart-counter').innerText = itemCount;
            })
            .catch(function(error) {
                console.error('Error updating cart counter:',error);
            });
    }
  
    </script>


</x-app>
