<!-- resources/views/products/show.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <!-- Add your stylesheets here if needed -->
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .product-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 600px; /* Adjust as needed */
        }
        .review-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .reviews {
            margin-top: 20px;
        }
        .review {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="product-details">
        <h2>{{ $product->name }}</h2>
        <p>Description: {{ $product->description }}</p>
        <!-- Add more details as needed -->

        <!-- Review Form -->
        <div class="review-form">
            <h3>Post a Review</h3>
            <form action="{{ route('review', ['productId' => $product->id]) }}" method="post">
                @csrf
                <label for="content">Review Content:</label>
                <textarea name="content" id="content" rows="4" required></textarea>

                <label for="rating">Rating (1-5):</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>

                <button type="submit">Post Review</button>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews">
        <h2>Reviews</h2>
        @forelse ($product->reviews as $review)
            <div class="review">
                <p>Content: {{ $review->content }}</p>
                <p>Rating: {{ $review->rating }}</p>
                <!-- Add more review details as needed -->
            </div>
        @empty
            <p>No reviews yet.</p>
        @endforelse
    </div>

</body>
</html>
