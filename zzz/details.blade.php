<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<style>
    .container-fluid {
        width: 90%;
        margin: 0 auto;
    }

    .opo {
        display: flex;
    }

    .color-indicator {
        width: 60px; /* Adjust the size as needed */
        height: 60px; /* Adjust the size as needed */
        border-radius: 50%;
        margin-right: 10px; /* Adjust spacing between indicators */
        cursor: pointer; /* Change cursor to indicate clickable element */
    }
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 2fr)); /* Adjust as needed */
        gap: 20px; /* Adjust the gap between images */
    }

    .image-container {
        position: relative;
    }

    .image-container img {
        width: 10%;
        height: 60%;
        object-fit: cover;
  /* Adjust border radius as needed */
       
    }
    .image-overlay-content {
        color: #fff;
        text-align: center;
    }

    /* Additional styling for image background section */
    .image-background-section {
        margin-top: 40px; /* Adjust margin as needed */
    }

    .image-background-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjust as needed */
        gap: 20px; /* Adjust the gap between images */
        justify-content: center; /* Center align images */
    }

    .image-background-grid img {
        width: 70%;
        height: auto;
        object-fit: cover;
      
        border-radius: 8px; /* Adjust border radius as needed */
    }

</style>

<body>
    <div class="container-fluid pt-5" id="app">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-6 col-md-6 mb-4 pb-1">
                <div id="product-carousel" class="carousel slide" data-ride="carousel" data-interval="false"
                    style="width: 400px; height: 400px;">
                    <div class="carousel-inner">
                        @foreach ($product->images as $key => $image)
                        <div class="carousel-item{{ $key === 0 ? ' active' : '' }}">
                            <img src="{{ asset('images/' . $image->image_path) }}" class="d-block w-100"
                                alt="Product Image">
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

                <div class="row mt-3">
                    @foreach ($product->images as $key => $image)
                    <div class="col-md-3">
                        <img src="{{ asset('images/' . $image->image_path) }}" class="img-thumbnail"
                            style="width: 120px; height: 100px;" alt="Product Thumbnail"
                            onclick="changeImages({{ $key }})">
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 mb-4 col-md-6 ml-4 pl-4 pb-1">
                <h4 class="title text-dark">
                    {{ $product->name }}
                </h4>
                <div class="d-flex flex-row my-3">
                    <div class="text-warning mb-1 me-2">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <p>{{ Illuminate\Support\Str::limit($product->description, 200) }}</p>
                <!-- Display items -->
                <div class="mb-3">
                    <span class="h5" id="product-price">{{ $product->items[0]->price }}</span>
                    <span class="text-muted">/per box</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted">Size/</span>
                    <span class="h5" id="product-size">{{ $product->items[0]->size['name'] }}</span>
                </div>
                <h5 class="text-muted">Color:</h5>
                <div class="opo">
                    @foreach ($product->items->groupBy('color') as $color => $items)
                    @foreach ($items as $item)
                    <div class="color-indicator" style="background-color: {{ $item->color }}"
                        onclick="changeProductDetails('{{ $item->color }}', '{{ $item->size->name }}')"></div>
                    @endforeach
                    @endforeach
                </div>
                <!-- Add to Cart button or form -->
                <div class="row mb-4 mt-4">
                    <a href="#" class="btn btn-warning shadow-0"> Buy now </a>
                    <button class="btn btn-primary ml-4" onclick="">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
  <!--  <div class="container-fluid image-background-section">
      
        <div class="image-background-grid">
            @foreach ($product->images as $key => $image)
            <div class="image-container">
                <img src="{{ asset('images/' . $image->image_path) }}" alt="Product Image">
            </div>
            @endforeach
        </div>
    </div>
    -->
    <script>
        function changeProductDetails(color, size) {
            // Find the item corresponding to the selected color
            var items = {!! json_encode($product->items) !!};
            var selectedItem = items.find(item => item.color === color);
            if (selectedItem) {
            // Fetch the image path for the selected item from your image table
            var imagePath = ''; // Set the default image path here
            // Get the index of the selected item
            var selectedIndex = items.indexOf(selectedItem);
            // Get the corresponding image path based on the selected index
            var images = {!! json_encode($product->images) !!};
            if (images.length > selectedIndex) {
                imagePath = '{{ asset("images/") }}' + '/' + images[selectedIndex].image_path;
            }
            // Update the carousel image directly
            $('#product-carousel img').attr('src', imagePath);
                var price = selectedItem.price;
                document.getElementById('product-price').innerHTML = price;
                document.getElementById('product-size').innerHTML = size;
            }
        }
        function changeImages(key) {
        // Update the carousel to show the clicked image
        $('#product-carousel').carousel(key);
    }
    </script>
    
</body>

</html>
