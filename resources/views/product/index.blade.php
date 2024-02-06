<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Select Category</h1>
    @if($categories->isEmpty())
        <p>No categories found.</p>
    @else
     <form id="categoryForm" action="{{ route('products.by.category', ['categoryid' => ':categoryid']) }}" method="GET">
 
        
            <select id="categorySelect" name="category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
     
        <button type="submit" id="submitButton">Submit</button>
    </form>
    @endif

    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            var selectedOption = document.getElementById('categorySelect').value;
            var actionUrl = "{{ route('products.by.category', ':categoryid') }}".replace(':categoryid', selectedOption);
            this.setAttribute('action', actionUrl);
        });
    </script>
</body>
</html>