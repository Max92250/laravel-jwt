<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
</head>
<body>
    <h1>Order List</h1>

    <table>
        <thead>
          
        </thead>
        <tbody>
            @foreach($orders as $order)
                
                    <h1 class="font-bold h-80 ">{{ $order->id }}</h1>
                   
                    <!-- Add more table cells with order data as needed -->
                

                @foreach ($order->products as $product )

                {{$product->name}}

                <td>{{ $product->item ? $product->item->price : 'No Item Associated' }}</td>
            
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>
