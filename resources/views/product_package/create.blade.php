    @extends('layouts.app')

    @section('htmlheader_title')
        {{ $title ?? '' }}
    @endsection

    @section('contentheader_title')
        {{ $title ?? '' }}
    @endsection

    @section('contentheader_description')
        {{ $title_description ?? '' }}
    @endsection

    @push('content-header')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />

    <style type="text/css">
        td {
            vertical-align : middle!important;
            /* text-align:center!important; */
        }

        .items{
            width: 70px!important;
        }

        /* .table-bordered td, .table-bordered th{
            border-color: black !important;
        } */

        .product-detail-card {
        background: #ffffff;
        border-radius: 10px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .product-detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    #productName {
        font-size: 1.25rem;
        color: #222;
    }

    #productPrice {
        font-size: 1.1rem;
        color: #28a745;
    }

    #addProductBtn {
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    #addProductBtn:hover {
        background-color: #218838;
        transform: scale(1.03);
    }

    /* Responsive fix */
    @media (max-width: 768px) {
        .product-detail-card .row {
            flex-direction: column;
            text-align: center;
        }

        #addProductBtn {
            width: 100%;
            margin-top: 10px;
        }
    }

     /* ====== TABLE STYLING ====== */
    .package-product-list {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .package-product-list:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .package-product-list h4 i {
        vertical-align: middle;
    }

    .table thead th {
        background-color: #e8f5e9 !important;
        color: #2e7d32;
        border-bottom: 2px solid #c8e6c9 !important;
    }

    .table tbody td {
        vertical-align: middle !important;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    /* Gambar produk di tabel */
    .product-thumb {
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .product-thumb:hover {
        transform: scale(1.05);
    }

    /* Tombol aksi */
    .btn-remove {
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-remove:hover {
        background-color: #dc3545;
        color: white;
        transform: scale(1.05);
    }
    </style>
    @endpush

    @section('main-content')
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <a class="btn btn-warning btn-sm" href="{{ route('package-index') }}">Back to list</a>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="myForm" role="form" method="POST" enctype="multipart/form-data" action="{{ route('package-store') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <div class="form-group">
                                                    <label>Product Package Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <input class="form-control" type="text" name="product_package_name" placeholder="ex: Paket Suhe" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Price <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            Rp.
                                                        </div>
                                                        <input class="form-control" type="number" name="product_package_price" placeholder="ex: 10000" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file-excel-o"></i>
                                                        </div>
                                                        <input class="form-control" type="text" name="product_package_description" placeholder="ex: Paket Suhe(Super Hemat) ayam+nasi+esteh" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-6">
                                                    <div class="form-group   ">
                                                        <label>Product Package Image</label>
                                                        <img class="img-preview" id="img-product-package-preview" width=270px height=190px
                                                            style="object-fit: contain;display:block;">
                                                        <input class="form-control" onchange="previewImageProductPackage(event);"
                                                            type="file" accept="image/*" name="image_product_package" id="image_product_package"
                                                            placeholder="Black" style="border:none" required>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box use_varians">
                                            <div class="box-header">
                                                <label>Pilih Produk</label>
                                                <select id="productSelect" class="form-control">
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                
                                                    <div id="productDetail" class="card product-detail-card d-none mb-4 shadow-sm border-0">
                                                        <div class="row g-0 align-items-center p-3">
                                                            <div class="col-md-3 text-center">
                                                                <img id="productImage" src="" alt="Gambar Produk" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 id="productName" class="fw-bold mb-1 text-dark"></h5>
                                                                <p class="text-muted mb-1">Harga:</p>
                                                                <p id="productPrice" class="text-success fw-semibold fs-5 mb-2"></p>
                                                            </div>
                                                            <div class="col-md-3 text-center">
                                                                <button type="button" id="addProductBtn" class="btn btn-success w-100 py-2">
                                                                    <i class="fa fa-plus me-1"></i> Tambahkan ke Daftar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="mt-5 package-product-list" id="articleForm" style="display: none;">
                                                    <h4 class="fw-bold mb-3 text-dark">
                                                        <i class="fa fa-box me-2 text-success"></i> Daftar Produk dalam Package
                                                    </h4>

                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-middle shadow-sm" id="productTable">
                                                            <thead class="table-success text-center">
                                                                <tr>
                                                                    <th style="width: 15%;" class="text-center">Gambar</th>
                                                                    <th style="width: 35%;" class="text-center">Nama Produk</th>
                                                                    <th style="width: 20%;" class="text-center">Harga</th>
                                                                    <th style="width: 15%;" class="text-center">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-center text-muted">
                                                                <!-- Produk akan ditambahkan via JavaScript -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group float-right">
                                        <button class="btn btn-sm btn-success" id="submitBtn" type="submit">Create Package</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                                {{-- <a class="btn btn-success btn-xs" href="{{ secure_asset('/uploads/template/template-create-hse-element.xlsx') }}">Download Template</a> --}}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>	
        </div>
    @endsection

    @section('content-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#myForm').submit(function(){
            $('#submitBtn').prop('disabled', true);

            Swal.fire({
                title: 'Wait a second, Processing File..',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            })
        })
    </script>


    <script>
    const select = document.getElementById('productSelect');
    const detail = document.getElementById('productDetail');
    const addBtn = document.getElementById('addProductBtn');
    const tbody = document.querySelector('#productTable tbody');
    const articleForm = document.getElementById('articleForm');
    const addedProducts = new Set();
    let selectedProduct = null;

    // Ketika produk dipilih
    select.addEventListener('change', async () => {
        const id = select.value;
        if (!id) {
            detail.classList.add('d-none');
            return;
        }

        try {
            const res = await fetch(`/package/product/${id}`);
            const product = await res.json();
            selectedProduct = product;

            // isi detail produk
            document.getElementById('productImage').src = product.image_url ?? '/no-image.png';
            document.getElementById('productName').innerText = product.name ?? '-';
            document.getElementById('productPrice').innerText = `Rp ${parseInt(product.price).toLocaleString('id-ID')}`;

            detail.classList.remove('d-none');
        } catch (error) {
            console.error('Gagal mengambil data produk:', error);
        }
    });

    // Ketika tombol tambah ditekan
    addBtn.addEventListener('click', () => {
        if (!selectedProduct) return;

        // Cegah duplikasi produk
        if (addedProducts.has(selectedProduct.id)) {
            Swal.fire({
                icon: 'warning',
                title: 'Produk sudah ditambahkan!',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        addedProducts.add(selectedProduct.id);

        // Tampilkan tabel jika sebelumnya disembunyikan
        articleForm.style.display = 'block';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="${selectedProduct.image_url ?? '/no-image.png'}" width="80"></td>
            <td>${selectedProduct.name}</td>
            <td>Rp ${parseInt(selectedProduct.price).toLocaleString('id-ID')}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-btn">Hapus</button></td>
            <input type="hidden" name="products_selected_package[]" value="${selectedProduct.id}">
        `;

        // Hapus produk dari tabel
        row.querySelector('.remove-btn').addEventListener('click', () => {
            row.remove();
            addedProducts.delete(selectedProduct.id);

            // Sembunyikan tabel jika tidak ada produk lagi
            if (tbody.children.length === 0) {
                articleForm.style.display = 'none';
            }
        });

        tbody.appendChild(row);

        // Reset pilihan agar bisa tambah produk lain
        select.value = '';
        detail.classList.add('d-none');
    });


    function previewImage(event) 
        {
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        function previewImageProductPackage(event) 
        {
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-product-package-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
</script>

    
@endsection