<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Product with Images</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS to center the form */
        body, html {
            height: 100%;
        }

        .container-fluid {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Create Product with Images</h2>
                <form action="{{ route('products.createWithImages') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="product_id">Product ID:</label>
                        <input type="text" id="product_id" name="product_id" class="form-control" required>
                    </div>

                    <div class="form-group" id="imageFields">
                        <label for="images">Images:</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple required onchange="displaySelectedImages(event)">
                    </div>

                    <button type="button" class="btn btn-primary mb-3" onclick="addImageField()">Add Image</button>
                    <div id="imagePreview"></div>

                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function displaySelectedImages(event) {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = ''; // Clear previous images

            const files = event.target.files;
            for (const file of files) {
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.style.maxWidth = '200px';
                image.style.maxHeight = '200px';
                previewContainer.appendChild(image);
            }
        }

        function addImageField() {
            const imageFields = document.getElementById('imageFields');
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'images[]';
            newInput.accept = 'image/*';
            newInput.multiple = true;
            newInput.required = true;
            newInput.className = 'form-control mt-3'; // Add margin top for spacing
            newInput.onchange = displaySelectedImages;
            imageFields.appendChild(newInput);
        }
    </script>
</body>
</html>
