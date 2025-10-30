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

        .table-bordered td, .table-bordered th{
            border-color: black !important;
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
                            <a class="btn btn-warning btn-sm" href="{{ route('product-category-index') }}">Back to list</a>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="myForm" role="form" method="POST" enctype="multipart/form-data" action="{{ route('product-category-store') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                              
                                                <div class="form-group">
                                                    <label>Product Category Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <input class="form-control" type="text" name="product_category_name" placeholder="ex: Main Course" required>
                                                    </div>
                                                </div>
                                    
                                                <div class="form-group">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file-excel-o"></i>
                                                        </div>
                                                        <input class="form-control" type="text" name="product_category_description" placeholder="ex: Menu utama makanan berat ...." required>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                   
                                    <div class="form-group float-right">
                                        <button class="btn btn-sm btn-success" id="submitBtn" type="submit">Create Product</button>
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
        function previewImage(event) 
        {
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        function previewImageProduct(event) 
        {
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-product-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        $('.box-image').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
            $(this).parent().find('input').click();
            var id = $(this).attr("data-id"); 
        });

        function previewImageMultiple(id) 
        {
             if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-multiple-preview-"+id);
                preview.src = src;
                preview.style.display = "block";
            }
        }

        function previewImageClone(i) 
        {
            // $('#image_preview'+i).click();

            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-preview-clone"+i);
                preview.src = src;
                preview.style.display = "block";
            }
            // const image = document.querySelector('#image' + i);
            // const imgPreview = document.querySelector('#img-preview-clone' + i);


            // imgPreview.style.display = 'block';
            // console.log(image.files)
            // const oFReader = new FileReader();
            // oFReader.readAsDataURL(image.files[0]);

            // oFReader.onload = function(oFREvent) {
            //     imgPreview.src = oFREvent.target.result;
            // }
        }
        
        $("#product_type").select2(); 

        $(".size_type").select2(); 

        $(".variant_type").select2({
            tags: true,
            tokenSeparators: [',']
        });

        $(document).on('keyup', '#priceArticle1', function () 
        {
            $('.priceArticle').val($(this).val());

        })
       
        $(document).on('keyup', '.articleVariant1', function () 
        { 
           $('.'+$(this)[0].id).val($(this)[0].value);
        })

        $(document).on('click', '.btn-generate-article', function () {
            var variantLength = $('.variant_type').select2('data').length;
            var sizeLength = 0;
            var rowArticle = '';
            var counter = 0 ;

            if(variantLength == 0 && sizeLength > 0)
            {
                $(".size_type option:selected").each(function() {
                    counter++
                    var valueSize = $(this).val();
                    var textSize = $(this).text();

                    rowArticle = rowArticle + '<tr>' +
                                                '<td>' +
                                                    '<input class="form-control article1" type="text" name="variant[]" disabled id="">' +
                                                '</td>' +
                                                '<td>' +
                                                    '<input class="form-control article1 priceArticle" type="number" name="price_article[]" id="priceArticle'+counter+'">' +
                                                '</td>' +
                                                '<td>' +
                                                    
                                                '</td>' +
                                            '</tr>';
                });

                $('.article-body').html(rowArticle);
            }
            else if(variantLength > 0 && sizeLength > 0)
            {
                var rowCounter = 0;
                $(".variant_type option:selected").each(function() {
                    var valueVariant = $(this).val();
                    var textVariant = $(this).text();
                    var counterSize = 0;
                    $(".size_type option:selected").each(function() {
                        var valueSize = $(this).val();
                        var textSize = $(this).text();
                        if(counterSize == 0)
                        {
                            rowArticle = rowArticle + '<tr>' +
                                                        '<td>' +
                                                            '<input class="form-control articleVariant" type="text" name="variant['+rowCounter+']" id="" value="'+textVariant+'" readonly>' +
                                                        '</td>' +
                                                        '<td>' +
                                                            '<input class="form-control articleVariant1 price_article-'+textVariant+'" type="number" name="price_article['+rowCounter+']" id="price_article-'+textVariant+'">' +
                                                        '</td>' +
                                                        '<td>' +
                                                            '<input class="form-control articleVariant" type="file" name="image_article['+rowCounter+']" id="" required>' +
                                                        '</td>' +
                                                    '</tr>';
                        }
                        else
                        {
                            rowArticle = rowArticle + '<tr>' +
                                                        '<td>' +
                                                            '<input class="form-control articleVariant" type="text" name="variant[]" id="" value="'+textVariant+'" readonly>' +
                                                        '</td>' +
                                                        '<td>' +
                                                            '<input class="form-control articleVariant price_article-'+textVariant+'" type="number" name="price_article[]" id="price_article-'+textVariant+'">' +
                                                        '</td>' +
                                                        '<td>' +
                                                        '</td>' +
                                                    '</tr>';
                        }

                        counterSize++;
                        rowCounter++;
                    });
                });

                $('.article-body').html(rowArticle);
            }
            else if(variantLength > 0 && sizeLength == 0)
            {
                $(".variant_type option:selected").each(function() {
                    var valueVariant = $(this).val();
                    var textVariant = $(this).text();
                    var counterSize = 0;
                    rowArticle = rowArticle + '<tr>' +
                                                '<td>' +
                                                    '<input class="form-control articleVariant" type="text" name="variant[]" id="" value="'+textVariant+'" readonly>' +
                                                '</td>' +
                                                '<td>' +
                                                    '<input class="form-control articleVariant" type="number" name="price_article[]" id="">' +
                                                '</td>' +
                                                '<td>' +
                                                    '<input class="form-control articleVariant" type="file" name="image_article[]" id="" required>' +
                                                '</td>' +
                                            '</tr>';
                });

                $('.article-body').html(rowArticle);
            }
            else
            {
                var rowArticle = rowArticle + '<tr>' +
                                                    '<td>' +
                                                        '<input class="form-control articleVariant" type="text" name="variant[]" id="" disabled>' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<input class="form-control articleVariant" type="number" name="price_article[]" id="">' +
                                                    '</td>' +
                                                    '<td></td>' +
                                                '</tr>';

                $('.article-body').html(rowArticle);
            }
            $('#articleForm').show(1000);
        });
    </script>

    <script>
    
        let i = 6;
        
        $(document).on('click', '.addImagePreview', function () 
        {
            ++i;

            $(".image-preview-content").append(
               '<div class="col-md-2 copy-image-preview'+i+'">'+
                    '<label>Image '+ i+'</label>'+
                    '<div class="photo-preview'+i+'" style="border-style: dotted!important;border-color:green;">'+
                        '<a class="box-image" data-id="'+i+'" id="box-image'+i+'" style="cursor: pointer;">'+
                        '<img class="img-preview" src="{{ asset("img/add-photo-icon-19.jpg") }}" id="img-multiple-preview-'+i+'" width="100%" height=160px style="object-fit: cover;display:block;">'+
                        '</a>'+
                        '<input class="form-control " accept="image/*" onchange="previewImageMultiple('+i+') " type="file" name="image_preview[]" style="display: none" id="image_preview'+i+'">'+
                    '</div>'+
                '</div>'
            );

            $('#box-image'+i).click(function(){
            // Simulate a click on the file input button
            // to show the file browser dialog
                $(this).parent().find('input').click();
                var id = $(this).attr("data-id"); 
            });

        });


        $('.deleteImagePreview').on('click', function () 
        {

            $('.copy-image-preview'+i).remove();
           
            // $('#my-select-'+i).multiSelect('refresh');
            if(i > 6)
            {
                 if(i > 1)
                {
                    i = i - 1;
                }
                else
                {
                    i = 0;
                }
            }
           
        });

    </script>
@endsection