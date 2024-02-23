<!-- resources/views/products/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- Add your stylesheets and scripts here -->
    <style>
        /* Add your styles here */
        .modal {
            /* Add modal styles */
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            /* Add modal content styles */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
        }
        .close {
            /* Add close button styles */
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>All Products</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
       
<tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>
                {{ $product->name }}
                <!-- Add edit name button -->
                <button onclick="openEditModal({{ $product->id }})" style="cursor: pointer;">✏️ Edit Name</button>
            </td>
            <td>
                <!-- Add your actions, such as edit or delete buttons -->
                <a href="{{ route('edit', $product->id) }}">Edit</a>  
            </td>
        </tr>
        <!-- Modal for editing name -->
        <div id="editModal{{ $product->id }}" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal({{ $product->id }})">&times;</span>
                <form method="post" action="{{ route('update.product', ['productId' => $product->id]) }}">
                    @csrf
                    <!-- Include a hidden input for the updated name -->
                    <input type="hidden" name="product_id" id="formProductId{{ $product->id }}" value="{{ $product->id }}">
                    <label ">Edited Name:</label>
                    <input type="text" name="name" required>
                    <button type="submit" onclick="saveEditedName({{ $product->id }})">Save</button>
                    <a href="#" onclick="closeEditModal({{ $product->id }})">Cancel</a>
                </form>
            </div>
        </div>
    @endforeach
</tbody>
    </table>

  

    <script>
        function openEditModal(productId) {
            // Display the edit modal for the corresponding product
            document.getElementById('editModal' + productId).style.display = 'block';
        }
    
        function closeEditModal(productId) {
            // Close the edit modal for the corresponding product
            document.getElementById('editModal' + productId).style.display = 'none';
        }
    
        function saveEditedName(productId) {
            // Implement your logic to save the edited name for the corresponding product
            // ...
            // After saving, close the modal
            closeEditModal(productId);
        }
    </script>

</body>
</html>
