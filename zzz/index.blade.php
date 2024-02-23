<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!-- Libraries Stylesheet -->
    <link href="{{asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
 
    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>
<style>
      
    
        a{
            text-decoration: none;
            color:rgb(72, 33, 33);
        }
        .cat-item{
            height:500px;
        }
        .bt{
            background:#ccc;
        }

        .product-card {

            width: 320px;
            height:400px;
            border: 1px solid #ccc;
            color:black;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
.img-fluid{
    width: 100%;
            height: 300px;
            object-fit: contain;
           /* Choose the appropriate value: cover, contain, fill, etc. */
}

.img-flid{
    width: 270px;
            height: 200px;
           
           /* Choose the appropriate value: cover, contain, fill, etc. */
}
        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            max-width: 100%;
            height: auto;
        }

        .cat-item{
            text-align: center;
        }

        h3 {
            margin-top: 0;
        }
        h5{
            padding-top: 5px;
            text-align: center;
        }

        p {
            margin-bottom: 10px;
            font-size: 10px;
 
     
        h5{
            padding-top: 5px;
            text-align: center;
        }
a{
    text-decoration: none;
}
     
    </style>
<body>
   
    <div class="container-fluid"id="app"  >
    <nav class="navbar navbar-expand-lg bg-light  navbar-light py-3 py-lg-0 px-0" >
        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
        <div class="navbar-nav mr-auto py-0" >
            <a href=""  class="nav-item nav-link">Home</a>
            <a href="" class="nav-item nav-link">Shop</a>
            <a href="" class="nav-item nav-link">Shop Detail</a>
 
            <a href="" class="nav-item nav-link">Contact</a>
        </div>
        <div class="navbar-nav ml-auto py-0">
            <a href="" class="nav-item nav-link">{{session('user')}}</a>
            <a href="" class="nav-item nav-link">logout</a>
        </div>
    </div>
    </nav>
    </div>
    <div class="row align-items-center py-3 px-xl-5">
       
        <div class="col-lg-4 col-6 text-left" >
            <form action="{{ route('products.by.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" id="searchQuery" class="form-control" placeholder="Search for products" name="q" required>
                    <span class="input-group-text bg-transparent text-primary">
                        <i class="fa fa-search" type="submit"></i>
                    </span>
                </div>
            </form>
            
            </form>
        </div>
       
        <div class="col-lg-4 col-6 text-left">
        <form id="categoryForm" action="{{ route('products.by.category', ['categoryid' => ':categoryid']) }}" method="GET">
            <div class="input-group">

            <select name="category" id="category"  class="form-control"  required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
                <!-- Add more categories as needed -->
            </select>
            <span >
            <button class="input-group-text bg-transparent text-primary" type="submit" id="submitButton">Filter</button>
            </span>
            </div>
        </form>
        </div>
     
        <div class="col-lg-3 col-6 text-right">
           
            <a href="" class="btn border">
                <i class="fas fa-shopping-cart text-primary" ></i>
                <span class="badge" id="Counter">0</span>
            </a>
        </div>
    </div>   
    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            var selectedOption = document.getElementById('category').value;
            var actionUrl = "{{ route('products.by.category', ':categoryid') }}".replace(':categoryid', selectedOption);
            this.setAttribute('action', actionUrl);
        });
    </script>  
    @yield('section')
    @yield('section1')
   

</body>
</html>