<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('css/check.css')}}" rel="stylesheet">
    
</head>
<style>
    img {
        width: 100%;
        height: 250px;
        object-fit: contain;

       
    }

    img:hover {
            transform: scale(1.05);
        }

    



    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <div class="container">
        @include('product.index')
    </div>
    

    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">

            @foreach ($products as $product)
                <div class="col-lg-4 col-md-6 pb-1">
                    <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                      
                        <a href="" class="cat-img position-relative overflow-hidden mb-3">
                            <img  src="{{ asset('images/' . $product->images[0]->image_path) }}" alt="">
                        </a>
                        <div class="info">
                            <h2>{{ $product->name }}</h2>
                            <p>{{ Illuminate\Support\Str::limit($product->description, 50) }}</p>
                                @foreach ($product->items as $item)
                                    Price: {{ $item->price }}
                                   
                                @endforeach
                            </p>
                            <button>Add to Cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
