@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@push('content-header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <style>
        .highlight {
            background-color: rgba(255, 0, 0, 0.5);
        }

        .swal2-popup {
            font-size: 1.5rem !important;
            font-family: Georgia, serif;
        }

        .select2-selection__rendered {
            line-height: 30px !important;
        }
        .select2-container .select2-selection--single {
            height: 34px !important;
        }
        .select2-selection__arrow {
            height: 33px !important;
        }
    </style>
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <form data-toggle="validator" role="form" method="POST" action="{{ route('catering-store-order') }}" id="formOrder">
            <div class="col-sm-12">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                {{-- if subscription active then get end date(renewal), else get start date --}}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="invoice_date" id="invoice_date" value="" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h3>Customer Details</h3>
                        </div>

                        <div class="col-md-12 non-student-wrapper">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Name <small class="text-danger">(Required)</small></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" class="form-control" name="customer_name" id="customer_name"
                                                    value="{{ old('customer_name') }}" placeholder="Customer Name" autocomplete="off" required/>
                                            {{-- <select name="customer_email" id="customer_email" class="form-control" style="width: 100%">
                                                <option value="">Select Customer</option>
                                                @foreach($users as $key => $user)
                                                    <option value="{{ $user->email }}" data-phone="{{ $user->mobile_phone }}">{{ $user->member->name }}</option>
                                                @endforeach
                                            </select> --}}
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Mobile Phone</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" class="form-control" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" placeholder="Customer Mobile Phone" />
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Address</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" class="form-control" name="customer_address" id="customer_address" value="{{ old('customer_address') }}" placeholder="Customer Address"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3>Order List</h3>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary btn-sm" id="add_product" style="margin-top: 20px; margin-bottom: 10px">
                                                <span class="fa fa-plus"></span> Add product
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Price/pcs</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead> 
                                            <tbody class="product-list">
                                                
                                            </tbody> 
                                            <tr class="grand-total">
                                                <td class="text-right" colspan=3><b>Grand Total</b></td>
                                                <td class="text-center"><b id="final_amount"></b></td>
                                            </tr> 
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bookmark"></i>
                                    </div>
                                    <textarea type="text" class="form-control" name="description">{{ old('description') }}</textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Method</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bookmark"></i>
                                    </div>
                                    <select class="form-control" name="payment_method" id="payment_method">
                                        @foreach($paymentMethods as $paymentMethod)
                                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Final Amount</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="text" class="form-control" name="final_amount" id="final_amount" readonly>
                                </div>

                                <!-- /.input group -->
                            </div>
                        </div> --}}
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success" id="createOrderBtn" style="width: 150px">
                                Create Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Choose Product</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=clearProductInput()>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="addProductForm">
                {{-- @csrf --}}
                <div class="modal-body">
                    <div class="product-wrapper">
                        <label for="">Produk</label>
                        <select class="form-control" name="product_id" id="product_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-type="{{ $product->type }}">{{ $product->name }} (Type: {{ $product->type }})</option>
                            @endforeach
                        </select>
                        <br>
                        <input type="hidden" name="product_type" id="product_type" value="">
                    </div>

                    <div class="variant-wrapper hide">
                        <label for="">Jenis Poduk</label>
                        <select class="form-control " name="variant_id" id="variant_id" required>
                        </select>
                        <br>
                    </div>

                    <div class="size-wrapper hide">
                        <label for="">Ukuran</label>
                        <select class="form-control" name="size_id" id="size_id">
                        </select>
                        <br>
                    </div>

                    <div class="qty-wrapper">
                        <label for="">Jumlah</label>
                        <input class="form-control" type="number" min="1" max="10" name="qty" id="qty" required>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick=clearProductInput()>Cancel</button>
                    <button type="submit" id="addProduct" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    let user_id = $('#user_id').val();

    getShoppingCart(user_id)

    $(document).ready(function() {

        $('#add_product').click(function(){
            $('#exampleModal').modal({backdrop: 'static', keyboard: false}) 
        })

        $('#customer_email').select2({
            placeholder: "Select user",
            allowClear: true,
            tags: true
        });

        $('#invoice_date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '1970:{{\Carbon\Carbon::now()->year}}'
        });

        // Get today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        // Set the date input's value to today
        document.getElementById('invoice_date').value = today;
        
    })

    $('#product_id').on('change', function(){
        var product_id = $('#product_id').val();

        clearInput();

        if(product_id != "")
        {
            $('#product_type').val($(this).find(':selected').data('type'));

            var url = '{{ route('get-store-product-variant-ajax') }}';
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    product_id: product_id,
                },
                success: function(data)
                {
                    if(Object.values(data).flat().length > 0)
                    {
                        $('#variant_id').empty();

                        $('#variant_id').prop('disabled', false);

                        $('#variant_id').append("<option value=''>Pilih jenis produk</option>");
                        $.each(data, function(key, value) {
                            $('#variant_id').append($("<option></option>")
                                                .attr({ value:value.id })
                                                .text(value.name));
                        });

                        $('.variant-wrapper').removeClass('hide');
                        $('#variant_id').prop('disabled', false);                         
                    }
                }, 
                error: function (e)
                {
                    console.log(e)
                }
            });
        }
    })

    function clearInput()
    {
        // $('#product_id').val('');
        $('.variant-wrapper').addClass('hide');
        $('.size-wrapper').addClass('hide');
        // $('.qty-wrapper').addClass('hide');

        $('#variant_id').prop('disabled', true);
        $('#size_id').prop('disabled', true);
        // $('#qty').prop('disabled', true);

        $('#variant_id').val("");
        $('#size_id').val("");
        $('#qty').val("");
    }

    function clearProductInput()
    {
        // $('#product_id').val('');
        $('.variant-wrapper').addClass('hide');
        $('.size-wrapper').addClass('hide');
        // $('.qty-wrapper').addClass('hide');

        $('#variant_id').prop('disabled', true);
        $('#size_id').prop('disabled', true);
        // $('#qty').prop('disabled', true);

        $('#product_id').val("");
        $('#variant_id').val("");
        $('#size_id').val("");
        $('#qty').val("");
    }

    let orderArray = [];

    $('#addProductForm').submit(function(e) {

        e.preventDefault();

        var user_id = $('#user_id').val();
        var product_id = $('#product_id').val();
        var type = $('#product_type').val();
        var variant_id = $('#variant_id').val();

        console.log(user_id, product_id, variant_id, type);

        var qty = $('#qty').val();

        var url = "{{route('add-product-to-cart-ajax')}}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                user_id: user_id,
                product_id: product_id,
                variant_id:variant_id,
                type:type,
                qty: qty
            },
            success: function(data)
            {
                if(data)
                {
                    $('.product-list').html('');

                    $("#exampleModal .close").click();

                    Swal.fire({
                        icon: "success",
                        title: "Product Added",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                else
                {
                    Swal.fire({
                        icon: "error",
                        title: "Failed to Add Product",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                getShoppingCart(user_id)
            }, 
            error: function (e)
            {
                console.log(e)
            }
        });
    });

    // Show shopping cart based on user_id seller
    function getShoppingCart(user_id)
    {
        var url = "{{route('get-shopping-cart-ajax')}}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                user_id: user_id,
            },
            success: function(data)
            {
                $('.product-list').html('');

                if(data.length > 0)
                {
                    $.each(data, function(index, item) {
                        const totalPrice = item.qty * item.product_price;
                        // Create a new option element
                        const $listOrder = 
                            "<tr>"+
                                "<td>"+ item.product_name +
                                    "<input hidden name='product_package_id' value='"+item.package_id+"'/>" +
                                    "<input hidden name='product_id' value='"+item.product_id+"'/>" +
                                    "<input hidden name='product_variant_id' value='"+item.variant_id+"'/>" + 
                                "</td>"+
                                "<td class='text-center'>"+ item.qty +"</td>"+
                                "<td class='text-center'> Rp. "+ item.product_price+"</td>"+
                                "<td class='text-center'> Rp. "+ totalPrice +"</td>"+
                                "<td class='text-center'><a class='btn btn-danger btn-sm removeItem' data-id='"+item.id+"'>Remove<a/></td>"+
                            "</tr>";

                        // Append the option to the select element
                        $('.product-list').append($listOrder);
                    });

                    // Extract only the 'id' key from each object
                    const productArticles = $.map(data, function(item) {
                        return {
                            product_price: item.product_price,
                            qty: item.qty
                        };
                    });

                    calculateFinalAmount(productArticles);

                    $('.removeItem').on('click', function(){
                        var id = $(this).data('id');

                        removeItemCart(id)
                    });

                    $('.grand-total').show();
                }
                else
                {
                    $('.grand-total').hide();
                }
            }, 
            error: function (e)
            {
                console.log(e)
            }
        });
    }

    function calculateFinalAmount(productArticles)
    {
        // Calculate the final amount
        const finalAmount = productArticles.reduce((total, item) => {
            return total + (item.product_price * item.qty);
        }, 0);

        $('#final_amount').text('Rp. ' + finalAmount);
    }

    // Remove item product on cart
    function removeItemCart(id)
    {
        var url = "{{ route('remove-item-shopping-cart-ajax') }}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: id,
            },
            success: function(data)
            {
                Swal.fire({
                    icon: "success",
                    title: "Product Removed",
                    showConfirmButton: false,
                    timer: 1500
                });

                getShoppingCart(user_id)

            }, 
            error: function (e)
            {
                Swal.fire({
                    icon: "error",
                    title: "Failed to Remove Product",
                    showConfirmButton: false,
                    width: 600,
                    timer: 1500
                });
            }
        });
    }

    $('form#formOrder').submit(function(event) {

        event.preventDefault();

        var url = "{{route('get-shopping-cart-ajax')}}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                user_id: user_id,
            },
            success: function(data)
            {
                // $('.product-list').html('');

                if(data.length > 0)
                {
                    Swal.fire({
                        icon:'question',
                        title: "Do you want to place the order?",
                        showCancelButton: true,
                        confirmButtonText: "Place Order",
                        cancelButtonText: "Edit Order",
                        width: 600,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            /* Read more about isConfirmed, isDenied below */
                            const form = $('#formOrder')[0];

                            // Cek apakah form valid
                            if (form.checkValidity()) {
                                // Form valid, submit form
                                form.submit()
                            } else {
                                // Form tidak valid, menampilkan pesan validasi
                                form.reportValidity(); // Menunjukkan elemen yang tidak valid
                            }
                        }
                    });
                }
                else
                {
                    Swal.fire({
                        icon: "error",
                        title: "Please Add the products first",
                        showConfirmButton: false,
                        width: 600,
                        timer: 1500
                    });
                }
            }, 
            error: function (e)
            {
                console.log(e)
                Swal.fire({
                    icon: "error",
                    title: "Something went wrong, try again later",
                    showConfirmButton: false,
                    width: 600,
                    timer: 1500
                });
            }
        });
    })
</script>
@endsection
