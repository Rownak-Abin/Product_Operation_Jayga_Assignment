<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Loader styles */
        #loader {
            display: none;
            position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .search-container {
            position: relative;
        }
        body{
            background-color: #e1ffff;
            font-size: 18px
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Product Dashboard</h1>
        <div id="loader"></div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="mb-4">
            <button class="btn btn-success" onclick="showCreateCategoryForm()">Create Category</button>
            <button class="btn btn-success" onclick="showCreateAttributeForm()">Create Attribute</button>
            <button class="btn btn-success" onclick="showCreateProductForm()">Create Product</button>
        </div>

        <!-- Search Form with Loader -->
        <div class="mb-4 search-container">
            <input type="text" id="search-query" class="form-control" placeholder="Search products...">
            <div id="loader"></div>
        </div>
        

        <!-- Products Table -->
        <table class="table table-bordered table-hover" id="products-table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Attributes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <ul class="list-group">
                                @foreach($product->attributes as $attribute)
                                    <li class="list-group-item">
                                        <strong>{{ ucfirst($attribute->name) }}:</strong> {{ $attribute->value }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="showEditForm({{ $product->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteProduct({{ $product->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Category Modal -->
    <div class="modal" id="createCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-category-form">
                        <div class="form-group">
                            <label for="create-category-name">Name</label>
                            <input type="text" id="create-category-name" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Attribute Modal -->
    <div class="modal" id="createAttributeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-attribute-form">
                        <div class="form-group">
                            <label for="create-attribute-name">Name</label>
                            <input type="text" id="create-attribute-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="create-attribute-value">Value</label>
                            <input type="text" id="create-attribute-value" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Product Modal -->
    <div class="modal" id="createProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-product-form">
                        <div class="form-group">
                            <label for="create-product-name">Name</label>
                            <input type="text" id="create-product-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="create-product-category">Category</label>
                            <select id="create-product-category" class="form-control"></select>
                        </div>
                        <div id="create-attributes-section"></div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal" id="editProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-product-form">
                        <input type="hidden" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" id="edit-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit-category">Category</label>
                            <select id="edit-category" class="form-control"></select>
                        </div>
                        <div id="edit-attributes-section"></div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- AJAX Search -->
    <script>
               // Show loader
               function showLoader() {
            $('#loader').show();
        }

        // Hide loader
        function hideLoader() {
            $('#loader').hide();
        }

        // AJAX Search with Loader
        $('#search-query').on('keyup', function() {
            const query = $(this).val();

            showLoader(); // Show the loader before starting the AJAX request

            if (query === '') {
                // If the search field is empty, fetch all products
                $.ajax({
                    url: '{{ route('products.index') }}', // Assuming you have a route to get all products
                    type: 'GET',
                    success: function(response) {
                        const tbody = $('#products-tbody');
                        tbody.empty(); // Clear existing table rows

                        if (response.length === 0) {
                            tbody.append(`
                                <tr>
                                    <td colspan="4" class="text-center">No products found</td>
                                </tr>
                            `);
                        } else {
                            response.forEach(product => {
                                let attributesList = '<ul class="list-group">';
                                product.attributes.forEach(attribute => {
                                    attributesList += `<li class="list-group-item"><strong>${attribute.name.charAt(0).toUpperCase() + attribute.name.slice(1)}:</strong> ${attribute.value}</li>`;
                                });
                                attributesList += '</ul>';

                                tbody.append(`
                                    <tr>
                                        <td>${product.name}</td>
                                        <td>${product.category.name}</td>
                                        <td>${attributesList}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="showEditForm(${product.id})">Edit</button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteProduct(${product.id})">Delete</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        }

                        hideLoader(); // Hide the loader after the request is complete
                    },
                    error: function() {
                        const tbody = $('#products-tbody');
                        tbody.empty();
                        tbody.append(`
                            <tr>
                                <td colspan="4" class="text-center">Error fetching products</td>
                            </tr>
                        `);

                        hideLoader(); // Hide the loader after the request is complete
                    }
                });
            } else {
                // Perform the search as before
                $.ajax({
                    url: '{{ route('products.search') }}',
                    type: 'GET',
                    data: { query: query },
                    success: function(response) {
                        const tbody = $('#products-tbody');
                        tbody.empty(); // Clear existing table rows

                        if (response.length === 0) {
                            tbody.append(`
                                <tr>
                                    <td colspan="4" class="text-center">No products found</td>
                                </tr>
                            `);
                        } else {
                            response.forEach(product => {
                                let attributesList = '<ul class="list-group">';
                                product.attributes.forEach(attribute => {
                                    attributesList += `<li class="list-group-item"><strong>${attribute.name.charAt(0).toUpperCase() + attribute.name.slice(1)}:</strong> ${attribute.value}</li>`;
                                });
                                attributesList += '</ul>';

                                tbody.append(`
                                    <tr>
                                        <td>${product.name}</td>
                                        <td>${product.category.name}</td>
                                        <td>${attributesList}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="showEditForm(${product.id})">Edit</button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteProduct(${product.id})">Delete</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        }

                        hideLoader(); // Hide the loader after the request is complete
                    },
                    error: function() {
                        const tbody = $('#products-tbody');
                        tbody.empty();
                        tbody.append(`
                            <tr>
                                <td colspan="4" class="text-center">Error fetching products</td>
                            </tr>
                        `);

                        hideLoader(); // Hide the loader after the request is complete
                    }
                });
            }
        });

        function showCreateCategoryForm() {
            $('#createCategoryModal').modal('show');
        }

        function showCreateAttributeForm() {
            $('#createAttributeModal').modal('show');
        }

        function showCreateProductForm() {
            $('#create-product-category').empty();
            $('#create-attributes-section').empty();

            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    const createCategorySelect = document.getElementById('create-product-category');
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.text = category.name;
                        createCategorySelect.appendChild(option);
                    });
                });

            fetch('/api/attributes')
                .then(response => response.json())
                .then(attributes => {
                    const attributeNames = [...new Set(attributes.map(attr => attr.name))];
                    attributeNames.forEach(attributeName => {
                        const attributeSelect = $('<select>').addClass('form-control').attr('data-name', attributeName).attr('disabled', true);
                        attributes.filter(attr => attr.name === attributeName).forEach(attr => {
                            const option = $('<option>').val(attr.value).text(attr.value);
                            attributeSelect.append(option);
                        });

                        const checkbox = $('<input>').attr('type', 'checkbox').addClass('mr-2').attr('data-name', attributeName);
                        checkbox.on('change', function() {
                            attributeSelect.prop('disabled', !this.checked);
                        });

                        const attributeLabel = $('<label>').text(attributeName).attr('for', attributeName);
                        const attributeDiv = $('<div>').addClass('form-group')
                            .append(checkbox)
                            .append(attributeLabel)
                            .append(attributeSelect);
                        $('#create-attributes-section').append(attributeDiv);
                    });
                });

            $('#createProductModal').modal('show');
        }

        function showEditForm(id) {
            fetch(`/api/products/${id}`)
                .then(response => response.json())
                .then(data => {
                    $('#edit-id').val(data.id);
                    $('#edit-name').val(data.name);
                    $('#edit-category').val(data.category.id);
                    $('#edit-attributes-section').empty();

                    fetch('/api/attributes')
                        .then(response => response.json())
                        .then(attributes => {
                            const existingAttributes = data.attributes.map(attr => attr.name);

                            const attributeNames = [...new Set(attributes.map(attr => attr.name))];
                            attributeNames.forEach(attributeName => {
                                const attributeSelect = $('<select>').addClass('form-control').attr('data-name', attributeName).attr('disabled', true);
                                attributes.filter(a => a.name === attributeName).forEach(a => {
                                    const option = $('<option>').val(a.value).text(a.value);
                                    attributeSelect.append(option);
                                });

                                const checkbox = $('<input>').attr('type', 'checkbox').addClass('mr-2').attr('data-name', attributeName);
                                if (existingAttributes.includes(attributeName)) {
                                    checkbox.prop('checked', true);
                                    attributeSelect.prop('disabled', false);
                                    const existingAttribute = data.attributes.find(attr => attr.name === attributeName);
                                    attributeSelect.val(existingAttribute.value);
                                }
                                checkbox.on('change', function() {
                                    attributeSelect.prop('disabled', !this.checked);
                                });

                                const attributeLabel = $('<label>').text(attributeName).attr('for', attributeName);
                                const attributeDiv = $('<div>').addClass('form-group')
                                    .append(checkbox)
                                    .append(attributeLabel)
                                    .append(attributeSelect);
                                $('#edit-attributes-section').append(attributeDiv);
                            });
                        });

                    $('#editProductModal').modal('show');
                });

            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    const editCategorySelect = document.getElementById('edit-category');
                    editCategorySelect.innerHTML = '';
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.text = category.name;
                        editCategorySelect.appendChild(option);
                    });
                });
        }

        document.getElementById('create-category-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = $('#create-category-name').val();

            fetch('/api/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                }),
            }).then(response => {
                if (response.ok) {
                    $('#createCategoryModal').modal('hide');
                    location.reload();
                }
            });
        });

        document.getElementById('create-attribute-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = $('#create-attribute-name').val();
            const value = $('#create-attribute-value').val();

            fetch('/api/attributes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    value: value,
                }),
            }).then(response => {
                if (response.ok) {
                    $('#createAttributeModal').modal('hide');
                    location.reload();
                }
            });
        });

        document.getElementById('create-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = $('#create-product-name').val();
            const category = $('#create-product-category').val();
            const attributes = {};

            $('#create-attributes-section input[type=checkbox]:checked').each(function() {
                const attributeName = $(this).data('name');
                const attributeValue = $(this).siblings('select').val();
                attributes[attributeName] = attributeValue;
            });

            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    category_id: category,
                    attributes: [attributes], // Wrap attributes object in an array
                }),
            }).then(response => {
                if (response.ok) {
                    alert("Product Created Successfully");
                    $('#createProductModal').modal('hide');
                    location.reload();
                }
            });
        });

        document.getElementById('edit-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const id = $('#edit-id').val();
            const name = $('#edit-name').val();
            const category = $('#edit-category').val();
            const attributes = {};

            $('#edit-attributes-section input[type=checkbox]:checked').each(function() {
                const attributeName = $(this).data('name');
                const attributeValue = $(this).siblings('select').val();
                attributes[attributeName] = attributeValue;
            });

            fetch(`/api/products/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    category_id: category,
                    attributes: [attributes], // Wrap attributes object in an array
                }),
            }).then(response => {
                if (response.ok) {
                    alert("Product updated Successfully");
                    $('#editProductModal').modal('hide');
                    location.reload();
                }
            });
        });

        function confirmDeleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                deleteProduct(id);
            }
        }

        function deleteProduct(id) {
            $.ajax({
                url: `/api/products/${id}`,
                type: 'DELETE',
                success: function() {
                    alert("Product Deleted Successfully");
                    location.reload();
                },
                error: function() {
                    alert('Error deleting product');
                }
            });
        }
    </script>
</body>
</html>
