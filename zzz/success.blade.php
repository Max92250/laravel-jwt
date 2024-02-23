@extends('product.index')


@section('section1')
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
        bordewhr: none;
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
   

    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">

            @foreach ($products as $product)
           
                <div class="col-lg-4 col-md-6 pb-1">
                   
                    <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                        
                        <a href="{{ route('productdetails', ['product_id' => $product->id]) }} " class="cat-img position-relative overflow-hidden mb-3">
                            <img  src="{{ asset('images/' . $product->images[0]->image_path) }}" alt="">
                        </a>
                        <div class="info">
                           <h2>{{ $product->name }}</h2>
                         <p>{{ Illuminate\Support\Str::limit($product->description, 200) }}</p>
                                @foreach ($product->items as $item)
                                    Price:${{ $item->price }}
                                   
                                @endforeach
                            </p>
                   
                        </div>
                  
                    </div>
            
                </div>
        
            @endforeach
        </div>
    </div>


    @endsection