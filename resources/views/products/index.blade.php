<x-layout>
    <h1 class="mb-4">Product Management</h1>
    <div class="card mb-4">
        <div class="card-header">
            Add New Product
        </div>
        <div class="card-body">
            <form id="productForm">
                <div class="row d-flex">
                    <div class="col ">
                        <input type="text" class="form-control" id="product_name" name="product_name"
                               placeholder="Product Name" required>
                        <div class="invalid-feedback" id="product_name_error"></div>
                    </div>
                    <div class="col ">
                        <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock"
                               min="0" placeholder="Quantity in Stock" required>
                        <div class="invalid-feedback" id="quantity_in_stock_error"></div>
                    </div>
                    <div class="col ">
                        <input type="number" class="form-control" id="price_per_item" name="price_per_item" step="0.01"
                               min="0" placeholder="Price per Item" required>
                        <div class="invalid-feedback" id="price_per_item_error"></div>
                    </div>
                    <div class="col align-self-center ">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Add Product</button>
                    </div>
                </div>
                <button type="button" class="btn btn-warning d-none" id="updateBtn">Update Product</button>
                <button type="button" class="btn btn-secondary d-none" id="cancelEditBtn">Cancel Edit</button>
                <input type="hidden" id="edit_product_id">
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            All Products
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity in Stock</th>
                        <th>Price per Item</th>
                        <th>Datetime Submitted</th>
                        <th>Total Value</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="productTable">
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total :</th>
                        <th id="totalSum">0</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            $(document).ready(function () {
                //fetch Data
                const fetchData = () => {
                    $.get("{{route('products.fetch')}}", function (data) {
                        const products = Object.values(data)
                        let tableRows = ``;
                        let totalSum = 0;

                        products.forEach(product => {
                            totalSum += product.total_value_number;
                            tableRows += `
                     <tr id="product-row-${product.id}">
                            <td class=".name">${product.product_name}</td>
                            <td class=".quantity">${product.quantity_in_stock}</td>
                            <td class=".price">${parseFloat(product.price_per_item).toFixed(2)}</td>
                            <td class="">${new Date(product.created_at).toLocaleString()}</td>
                            <td class="">${product.total_value_number.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-btn" data-id="${product.id}">Edit</button>
                                <button class="btn btn-sm btn-success d-none save-btn" id="save-${product.id}" data-id="${product.id}">Save</button>
                                <button class="btn btn-sm btn-info d-none cancel-btn" id="cancel-${product.id}" data-id="${product.id}">Cancel</button>
                            </td>
                        </tr>
                   `;

                        })
                        $("#productTable").html(tableRows)
                        $("#totalSum").text(totalSum.toFixed(2))
                    })
                }
                fetchData();

                //update Date
                function updateTotal() {
                    let total = 0;
                    $('#productTable tr').each(function () {
                        const totalValueText = $(this).find('td:eq(4)').text();
                        total += parseFloat(totalValueText);
                    });
                    $('#totalSum').text(`${total.toFixed(2)}`);
                }

                //Edit button click
                $(document).on('click', '.edit-btn', function (e) {
                    e.preventDefault();
                    const productId = $(this).data('id');
                    const row = $(`#product-row-${productId}`);
                    const productName = row.find('td:eq(0)').text();
                    const quantity = row.find('td:eq(1)').text();
                    const price = row.find('td:eq(2)').text();
                    row.find('td:eq(0)').html(`<input type="text" class="form-control" id="edit-name-${productId}" value="${productName}">`);
                    row.find('td:eq(1)').html(`<input type="number" class="form-control" id="edit-quantity-${productId}" value="${quantity}">`);
                    row.find('td:eq(2)').html(`<input type="number" class="form-control" id="edit-price-${productId}" value="${parseFloat(price).toFixed(2)}">`);
                    row.find('.edit-btn').addClass('d-none');
                    row.find(`#save-${productId}, #cancel-${productId}`).removeClass('d-none');

                });
                // Add Product
                $(document).on('submit', '#productForm', function (e) {
                    e.preventDefault();
                    $('.invalid-feedback').text('').removeClass('d-block');
                    $('.form-control').removeClass('is-invalid');
                    const url = "{{ route('products.store')}}";
                    createOrUpdate($(this).serialize(), url, 'POST')
                });
                //Update Product
                $(document).on('click', '.save-btn', function (e) {
                    e.preventDefault();
                    const productId = $(this).data('id');
                    const row = $(`#product-row-${productId}`);
                    updateForm(row, productId, true)

                })
                //Cancel
                $(document).on('click', '.cancel-btn', function (e) {
                    e.preventDefault();
                    const productId = $(this).data('id');
                    const row = $(`#product-row-${productId}`);
                    updateForm(row, productId)
                })

                //updateForm
                function updateForm(row, id, update = false) {
                    const productName = row.find(`#edit-name-${id}`).val();
                    const quantity = row.find(`#edit-quantity-${id}`).val();
                    const price = row.find(`#edit-price-${id}`).val();
                    if (update) {
                        let url = "{{ route('products.update', ":id") }}";
                        url = url.replace(':id', id);
                        let formData = {
                            product_name: productName,
                            quantity_in_stock: quantity,
                            price_per_item: price
                        }
                        createOrUpdate(formData, url, 'PUT')

                    }
                    row.find('td:eq(0)').text(productName);
                    row.find('td:eq(1)').text(quantity);
                    row.find('td:eq(2)').text(parseFloat(price).toFixed(2));
                    row.find('td:eq(4)').text(parseFloat(quantity * price).toFixed(2));
                    row.find('.edit-btn').removeClass('d-none');
                    row.find(`#save-${id}, #cancel-${id}`).addClass('d-none');
                }

                //create or update with ajax
                function createOrUpdate(data, url, method) {
                    $.ajax({
                        url: url,
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: function (response) {
                            if (response.message) {
                                notification(response.message, 'success')
                            }
                            if (response.product) {
                                if (method === 'POST') {
                                    addProduct(response.product);
                                    clearForm();
                                }
                                updateTotal()
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $(`#${field}`).addClass('is-invalid');
                                    $(`#${field}_error`).text(errors[field][0]).addClass('d-block');
                                }
                            } else {
                                alert('An error occurred: ' + (xhr.responseJSON.message || ''));
                            }
                        }
                    });
                }

                function addProduct(product) {
                    const newRow = `
                  <tr id="product-row-${product.id}">
                            <td class=".name">${product.product_name}</td>
                            <td class=".quantity">${product.quantity_in_stock}</td>
                            <td class=".price">${parseFloat(product.price_per_item).toFixed(2)}</td>
                            <td class="">${new Date(product.created_at).toLocaleString()}</td>
                            <td class="">${product.total_value_number.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-btn" data-id="${product.id}">Edit</button>
                                <button class="btn btn-sm btn-success d-none save-btn" id="save-${product.id}" data-id="${product.id}">Save</button>
                                <button class="btn btn-sm btn-info d-none cancel-btn" id="cancel-${product.id}" data-id="${product.id}">Cancel</button>
                            </td>
                        </tr>
                `;
                    $('#productTable').prepend(newRow);
                }

                //clear Form
                function clearForm() {
                    $('#productForm')[0].reset();
                    $('.invalid-feedback').text('').removeClass('d-block');
                    $('.form-control').removeClass('is-invalid');
                    $('#submitBtn').removeClass('d-none').prop('disabled', false);
                    $('#updateBtn').addClass('d-none');
                    $('#cancelEditBtn').addClass('d-none');
                    $('#edit_product_id').val('');
                }

                //Notification
                const notification = (message, type = 'success') => {
                    $.toast({
                        text: message,
                        heading: type,
                        icon: type,
                        showHideTransition: 'fade',
                        allowToastClose: true, //
                        hideAfter: 3000, //
                        stack: 5,
                        position: 'top-right',
                        textAlign: 'left',
                        loader: true,
                        loaderBg: '#9EC600',
                    });

                }
            });

        </script>
    @endpush
</x-layout>
