<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            max-width: 800px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
        }

        .file-input-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }

        .file-input-container label {
            margin-bottom: 8px;
        }

        .file-input-container input {
            margin-bottom: 8px;
        }

        .hidden {
            display: none;
        }

        .uploaded-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 16px;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
        }

        h2, h3 {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    <h2>Update Product</h2>

    <!-- Product Information -->
    <form action="{{ route('update.product', ['productId' => $product->id]) }}" method="POST" enctype="multipart/form-data">

        @csrf

        <label for="name">Product Name:</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

        <label for="description">Product Description:</label>
        <textarea name="description" required>{{ old('description', $product->description) }}</textarea>

        <!-- Items -->
        <h3>Items</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Price</th>
                <th>Size</th>
                <th>Color</th>
            </tr>
            @foreach ($product->items as $item)
                <tr>
                    <td><input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}" class="hidden">{{ $item->id }}</td>
                    <td><input type="number" name="items[{{ $item->id }}][price]" value="{{ old('items.' . $item->id . '.price', $item->price) }}" required></td>
                    <td><input type="text" name="items[{{ $item->id }}][size]" value="{{ old('items.' . $item->id . '.size', $item->size) }}" required></td>
                    <td><input type="text" name="items[{{ $item->id }}][color]" value="{{ old('items.' . $item->id . '.color', $item->color) }}" required></td>
                </tr>
            @endforeach
        </table>

        <!-- Images -->
        <h3>Images</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Image 1</th>
                <th>Image 2</th>
            </tr>
        </tr>
       @foreach ($product['images'] as $image)
    <div>
        <img src="{{ $image['image_1'] }}" alt="Image 1">
        <img src="{{ $image['image_2'] }}" alt="Image 2">
    </div>
@endforeach

        </table>

        <button type="submit">Update Product</button>
    </form>

</body>
</html>
