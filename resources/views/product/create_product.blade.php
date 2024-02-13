<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Product with One Item</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <style>
        /* Custom CSS to center the form */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center pt-2">
            <div class="col-md-6 ">
                <h1 class="text-center mb-4 ">Create Product with Items</h1>
                <form action="{{ route('products.create.items') }}" method="POST" id="product-form">
                    @csrf
                    <div class="form-group">
                        <label for="name">Product Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                    <div  style="display: grid; grid-template-columns: repeat(5, 1fr);">
                       
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    id="category_{{ $category->id }}" value="{{ $category->id }}">
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    </div>

                    <hr>
                    <h3>Items:</h3>
                    <div id="items">
                        <div class="item">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="price">Price:</label>
                                        <input type="number" class="form-control" name="items[0][price]" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="size_id">Size:</label>
                                        <select class="form-control" name="items[0][size_id]" required>
                                            @foreach ($sizes as $size)
                                                @if ($size->parent_id === null)
                                                    {{-- Check if it's a parent size --}}
                                                    <optgroup label="{{ $size->name }}">
                                                        @foreach ($size->subsizes as $subsize)
                                                            {{-- Loop through subsizes --}}
                                                            <option value="{{ $subsize->id }}">{{ $subsize->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="color">Color:</label>
                                        <input type="text" class="form-control" name="items[0][color]" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="sku">SKU:</label>
                                        <input type="text" class="form-control" name="items[0][sku]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mb-3" id="add-item">Add Item</button>
                    <hr>
                    <button type="submit" class="btn btn-success">Create Product</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.getElementById('add-item');
            const itemsContainer = document.getElementById('items');

            let itemIndex = 0;

            addItemButton.addEventListener('click', function() {
                itemIndex++;

                const newItem = document.createElement('div');
                newItem.classList.add('item');
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="number" class="form-control" name="items[${itemIndex}][price]" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="size_id">Size:</label>
                                <select class="form-control" name="items[${itemIndex}][size_id]" required>
                                    @foreach ($sizes as $size)
                                        @if ($size->parent_id === null)
                                            {{-- Check if it's a parent size --}}
                                            <optgroup label="{{ $size->name }}">
                                                @foreach ($size->subsizes as $subsize)
                                                    {{-- Loop through subsizes --}}
                                                    <option value="{{ $subsize->id }}">{{ $subsize->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="color">Color:</label>
                                <input type="text" class="form-control" name="items[${itemIndex}][color]" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="sku">SKU:</label>
                                <input type="text" class="form-control" name="items[${itemIndex}][sku]" required>
                            </div>
                        </div>
                    </div>
                `;

                itemsContainer.appendChild(newItem);
            });
        });
    </script>

</body>

</html>
