@extends('admin_layout')
@section('content_dash')
    <?php use Illuminate\Support\Facades\Session; ?>

    <form action="{{ URL::to('/submit-edit-product/' . $product->idProduct) }}" method="POST" id="form-edit-product"
        data-toggle="validator" enctype="multipart/form-data">
        @csrf
        <div class="content-page">
            <div class="container-fluid add-form-list">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Sửa sản phẩm</h4>
                                </div>
                            </div>
                            <?php
                            $message = Session::get('message');
                            $error = Session::get('error');
                            if ($message) {
                                echo '<span class="text-success ml-3 mt-3">' . $message . '</span>';
                                Session::put('message', null);
                            } elseif ($error) {
                                echo '<span class="text-danger ml-3 mt-3">' . $error . '</span>';
                                Session::put('error', null);
                            }
                            ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="ProductName" class="required">Tên sản phẩm</label>
                                            <input id="ProductName" name="ProductName" type="text"
                                                class="form-control slug" value="{{ $product->ProductName }}"
                                                placeholder="Vui lòng nhập tên" data-errors="Please Enter Name." required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="idCategory" class="required">Danh mục</label>
                                            <select id="idCategory" name="idCategory" class="selectpicker form-control"
                                                data-style="py-0" required>
                                                <option value="{{ $product->idCategory }}">{{ $product->CategoryName }}
                                                </option>
                                                @foreach ($list_category as $key => $category)
                                                    <option value="{{ $category->idCategory }}">
                                                        {{ $category->CategoryName }}</option>
                                                @endforeach
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="idBrand" class="required">Thương hiệu</label>
                                            <select id="idBrand" name="idBrand" class="selectpicker form-control"
                                                data-style="py-0" required>
                                                <option value="{{ $product->idBrand }}">{{ $product->BrandName }}</option>
                                                @foreach ($list_brand as $key => $brand)
                                                    <option value="{{ $brand->idBrand }}">{{ $brand->BrandName }}</option>
                                                @endforeach
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>


                                

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product-attributes">Chọn thuộc tính sản phẩm:</label>
                                            <select id="product-attributes" name="attributes[]" class="form-control">
                                                <option value="" disabled selected>Chọn thuộc tính</option>
                                                @foreach ($list_attribute as $attribute)
                                                    <option value="{{ $attribute->idProperty }}"
                                                        @if ($name_attribute && $name_attribute->idProperty == $attribute->idProperty) selected @endif>
                                                        {{ $attribute->PropertyName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="attribute_value">Kích thước:</label>
                                            <select id="attribute_value" name="size[]" class="form-control" multiple>
                                                @foreach ($list_pd_attr as $pd_attr)
                                                    <option value="{{ $pd_attr->idProVal }}" selected>
                                                        {{ $pd_attr->ProValName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div id="sizes-container" class="form-group">
                                            @foreach ($list_pd_attr as $pd_attr)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control text-center"
                                                        value="{{ $pd_attr->ProValName }}" disabled>
                                                    <input id="qty-attr-{{ $pd_attr->idProVal }}"
                                                        value="{{ $pd_attr->Quantity }}"
                                                        class="form-control text-center qty-attr" name="qty_attr[]"
                                                        type="number" placeholder="Nhập số lượng phân loại">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Price" class="required">Giá</label>
                                            <input id="Price" name="Price" type="number" min="0"
                                                class="form-control" value="{{ $product->Price }}"
                                                placeholder="Vui lòng nhập giá" data-errors="Please Enter Price." required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Quantity">Tổng số lượng</label>
                                            <input id="Quantity" name="QuantityTotal" type="number" min="0"
                                                class="form-control" value="{{ $product->QuantityTotal }}"
                                                placeholder="Vui lòng nhập số lượng" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">Hình ảnh</label>
                                            <input name="ImageName[]" id="images" type="file" onchange="loadPreview(this)" class="form-control  image-file" multiple />
                                            <div class="help-block with-errors"></div>
                                            <div class="text-danger alert-img"></div>
                                            <div class="d-flex flex-wrap" id="image-list">
                                                @foreach (json_decode($product->ImageName) as $key => $image)
                                                    <div id="image-item-{{ $key }}" class="image-item">
                                                        <img src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                            class="img-fluid rounded avatar-100 mr-3 mt-2">

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">Mô tả ngắn</label>
                                            <textarea id="ShortDes" name="ShortDes" class="form-control" placeholder="Nhập mô tả ngắn" rows="3"
                                                required>{{ $product->ShortDes }}</textarea>
                                            <div class="help-block with-errors"></div>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">Mô tả / Chi tiết sản phẩm</label>
                                            <textarea id="DesProduct" name="DesProduct" class="form-control tinymce" placeholder="Nhập mô tả chi tiết"
                                                rows="4">{{ $product->DesProduct }}</textarea>
                                            <div class="help-block with-errors"></div>

                                        </div>
                                    </div>

                                    <input type="submit" class="btn btn-primary mr-2" id="btn-submit"
                                        value="Sửa sản phẩm">
                                    <a href="{{ URL::to('/manage-product') }}" class="btn btn-light">Trở về</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     

           
    </form>

    <!-- Validate hình ảnh -->
    <script>
        function loadPreview(input) {
            $('.image-item').remove();
            var data = $(input)[0].files; //this file data
            $.each(data, function(index, file) {
                if (/(\.|\/)(gif|jpeg|png|jpg|svg)$/i.test(file.type) && file.size < 2000000) {
                    var fRead = new FileReader();
                    fRead.onload = (function(file) {
                        return function(e) {
                            var img = $('<img/>').addClass('img-fluid rounded avatar-100 mr-3 mt-2')
                                .attr('src', e.target.result); //create image thumb element
                            $("#image-list").append('<div id="image-item-' + index +
                                '" class="image-item"></div>');
                            $('#image-item-' + index).append(img);
                            //    $('#image-item-'+index).append('<span id="dlt-item-'+index+'" class="dlt-item"><span class="dlt-icon">x</span></span>');
                        };
                    })(file);
                    fRead.readAsDataURL(file);
                    $('.alert-img').html("");
                    $('#btn-submit').removeClass('disabled-button');
                } else {
                    document.querySelector('#images').value = '';
                    $('.alert-img').html("Tệp hình ảnh phải có định dạng .gif, .jpeg, .png, .jpg, .svg dưới 2MB");
                    //    $('#btn-submit').addClass('disabled-button');
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            // Khi thay đổi thuộc tính sản phẩm
            $('#product-attributes').on('change', function() {
                var attributeId = $(this).val(); // Lấy ID thuộc tính được chọn

                // Xóa các dữ liệu cũ
                $('#attribute_value').empty();
                $('#sizes-container').empty();

                if (attributeId) {
                    // Gửi yêu cầu AJAX để lấy dữ liệu kích thước
                    $.ajax({
                        url: '/get-attribute-values/' + attributeId,
                        type: 'GET',
                        success: function(response) {
                            if (response.length > 0) {
                                // Thêm các tùy chọn kích thước vào dropdown
                                $.each(response, function(index, value) {
                                    $('#attribute_value').append(
                                        '<option value="' + value.idProVal +
                                        '">' + value.ProValName + '</option>'
                                    );
                                });
                            } else {
                                $('#attribute_value').append(
                                    '<option disabled>Không có kích thước</option>');
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi tải dữ liệu kích thước.');
                        }
                    });
                }
            });

            // Khi thay đổi kích thước sản phẩm
            $('#attribute_value').on('change', function() {
                $('#sizes-container').empty(); // Xóa các trường số lượng cũ

                // Lặp qua các kích thước đã chọn
                $('#attribute_value option:selected').each(function() {
                    var sizeId = $(this).val(); // Lấy ID kích thước
                    var sizeName = $(this).text(); // Lấy tên kích thước

                    // Tạo trường nhập số lượng cho mỗi kích thước
                    $('#sizes-container').append(
                        '<div class="input-group mb-2">' +
                        '<input type="text" class="form-control text-center" value="' +
                        sizeName + '" disabled>' +
                        '<input id="qty-size-' + sizeId +
                        '" class="form-control text-center qty-size" name="qty_attr[]" type="number" placeholder="Nhập số lượng" min="0">' +
                        '</div>'
                    );
                });
            });

            // Tính tổng số lượng khi nhập số lượng
            $(document).on('input', '.qty-size', function() {
                var totalQuantity = 0;

                // Lặp qua tất cả các trường nhập số lượng và cộng tổng
                $('.qty-size').each(function() {
                    var qty = parseFloat($(this).val()) ||
                    0; // Lấy giá trị nhập và chuyển đổi thành số, nếu không có giá trị thì là 0
                    totalQuantity += qty;
                });

                // Cập nhật giá trị tổng số lượng
                $('#Quantity').val(totalQuantity);
            });

            $(document).ready(function() {
                // Khi thay đổi giá trị trong bất kỳ trường số lượng nào
                $('.qty-attr').on('input', function() {
                    var qty = $(this).val(); // Lấy giá trị nhập vào
                    var attrId = $(this).data('attr-id'); // Lấy ID thuộc tính

                    // Nếu giá trị âm thì đặt lại giá trị thành 0 và hiển thị thông báo
                    if (qty < 0) {
                        alert('Số lượng không thể là số âm!');
                        $(this).val(0); // Đặt lại giá trị về 0
                    }
                });
            });
        });
    </script>



    
@endsection
