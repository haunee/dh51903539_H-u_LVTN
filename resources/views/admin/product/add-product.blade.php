@extends('admin_layout')
@section('content_dash')
    <?php use Illuminate\Support\Facades\Session; ?>

    <form action="{{ URL::to('/submit-add-product') }}" method="POST" id="form-add-product" data-toggle="validator"
        enctype="multipart/form-data">
        @csrf
        <div class="content-page">
            <div class="container-fluid add-form-list">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Thêm sản phẩm</h4>
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
                                                class="form-control slug" placeholder="Vui lòng nhập tên"
                                                data-errors="Please Enter Name." required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ProductSlug" class="form-control" id="convert_slug">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="idCategory" class="required">Danh mục</label>
                                            <select id="idCategory" name="idCategory" class="selectpicker form-control"
                                                data-style="py-0" required>
                                                <option value="">Chọn danh mục sản phẩm</option>
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
                                                <option value="">Chọn thương hiệu sản phẩm</option>
                                                @foreach ($list_brand as $key => $brand)
                                                    <option value="{{ $brand->idBrand }}">{{ $brand->BrandName }}</option>
                                                @endforeach
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>




                                    <div class="col-md-4">

                                        <div class="form-group ">
                                            <label for="product-attributes">Chọn thuộc tính sản phẩm:</label>
                                            <select id="product-attributes" name="attributes[]" class="form-control">
                                                <option value="" disabled selected>Chọn thuộc tính</option>
                                                @foreach ($list_attribute as $attribute)
                                                    <option value="{{ $attribute->idProperty }}">
                                                        {{ $attribute->PropertyName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-group ">
                                            <label for="attribute_value">Kích thước:</label>
                                            <select id="attribute_value" name="size[]" class="form-control" multiple>
                                                <!-- Các tùy chọn sẽ được thêm vào bằng AJAX -->
                                            </select>
                                        </div>

                                    </div>


                                    <div class="col-md-4">
                                        <div id="sizes-container" class="form-group">
                                            <!-- Các trường nhập số lượng cho từng kích thước sẽ được thêm vào đây bằng jQuery -->
                                        </div>
                                    </div>




                                    <div class="col-md-12 d-flex flex-wrap input-attrs">
                                        <div class="col-md-12 d-flex flex-wrap attr-title">
                                            <div class="attr-title-1 col-md-6 text-center d-none"></div>
                                            <div class="attr-title-2 col-md-6 text-center d-none">Số lượng</div>
                                        </div>
                                    </div>




                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Price" class="required">Giá</label>
                                            <input id="Price" name="Price" type="number" min="0"
                                                class="form-control" placeholder="Vui lòng nhập giá"
                                                data-errors="Please Enter Price." required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Quantity" class="required">Tổng số lượng</label>
                                            <input id="Quantity" name="QuantityTotal" type="number" min="0"
                                                class="form-control" placeholder="Vui lòng nhập số lượng" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">Hình ảnh</label>
                                            <input name="ImageName[]" id="images" type="file"
                                                onchange="loadPreview(this)" class="form-control image-file" multiple
                                                required />

                                            <div class="help-block with-errors"></div>
                                            <div class="text-danger alert-img"></div>
                                            <div class="d-flex flex-wrap" id="image-list"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="ShortDes" class="required">Mô tả</label>
                                            <textarea id="ShortDes" name="ShortDes" class="form-control" rows="4" placeholder="Nhập mô tả sản phẩm"
                                                required></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="DesProduct" class="required">Mô tả chi tiết</label>
                                            <textarea id="DesProduct" name="DesProduct" class="form-control" rows="6"
                                                placeholder="Nhập mô tả chi tiết sản phẩm" required></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary mr-2" id="btn-submit"
                                    value="Thêm sản phẩm">
                                <a href="{{ URL::to('/manage-product') }}" class="btn btn-light">Trở về</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end  -->

        <!-- Model phân loại hàng -->
        {{-- <div class="modal fade" id="modal-attributes" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="popup text-left">
                            <h4 class="mb-3">Thêm phân loại hàng</h4>
                            <div class="content create-workform bg-body">
                                <label class="mb-0">Nhóm phân loại</label>
                                <select name="idAttribute" id="attribute" class="selectpicker form-control choose-attr"
                                    data-style="py-0">
                                    <option value="">Chọn nhóm phân loại</option>
                                    @foreach ($list_attribute as $key => $attribute)
                                        <option id="attr-group-{{ $attribute->idAttribute }}"
                                            data-attr-group-name="{{ $attribute->AttributeName }}"
                                            value="{{ $attribute->idAttribute }}">{{ $attribute->AttributeName }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="pb-3 d-flex flex-wrap" id="PropertyValue">

                                </div>
                                <div class="col-lg-12 mt-4">
                                    <div class="d-flex flex-wrap align-items-ceter justify-content-center">
                                        <div class="btn btn-light mr-4" data-dismiss="modal">Trở về</div>
                                        <div class="btn btn-primary" id="confirm-attrs" data-dismiss="modal">Xác nhận
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </form>


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
                        };
                    })(file);
                    fRead.readAsDataURL(file);
                    $('.alert-img').html("");
                    $('#btn-submit').removeClass('disabled-button');
                } else {
                    document.querySelector('#images').value = '';
                    $('.alert-img').html("Tệp hình ảnh phải có định dạng .gif, .jpeg, .png, .jpg, .svg dưới 2MB");
                }
            });
        }
    </script>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-attributes').change(function() {
                var attributeId = $(this).val();

                $.ajax({
                    url: '/get-attribute-values/' + attributeId,
                    method: 'GET',
                    data: {
                        idProperty: attributeId
                    },
                    success: function(response) {
                        $('#attribute_value').empty();

                        $.each(response, function(index, attrival) {
                            $('#attribute_value').append(
                                $('<option>').val(attrival.idProVal).text(attrival
                                    .ProValName)
                            );
                        });
                    },
                    error: function(xhr) {
                        console.log('Có lỗi xảy ra khi gửi yêu cầu AJAX');
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#attribute_value').change(function() {
                var selectedSizes = $(this).val();

                if (selectedSizes) {
                    $('#sizes-container').empty();

                    var sizeNames = {};
                    $('#attribute_value option').each(function() {
                        var value = $(this).val();
                        var text = $(this).text();
                        sizeNames[value] = text;
                    });

                    selectedSizes.forEach(function(sizeId) {
                        var sizeName = sizeNames[sizeId] || sizeId;
                        var sizeInput = `
                    <div class="form-group">
                        <label for="size_quantity_${sizeId}">Số lượng cho kích thước ${sizeName}:</label>
                        <input type="number" id="size_quantity_${sizeId}" name="size_quantities[${sizeId}]" class="form-control size-quantity" min="0" />
                        <div id="error_${sizeId}" class="text-danger" style="display:none;">Số lượng không thể âm!</div>
                    </div>
                `;
                        $('#sizes-container').append(sizeInput);
                    });

                    updateTotalQuantity();
                }
            });

            $(document).on('input', '.size-quantity', function() {
                var input = $(this);
                var quantity = parseInt(input.val(), 10);
                var errorDiv = $('#error_' + input.attr('id'));

                if (quantity < 0 || isNaN(quantity)) {
                    errorDiv.show();
                    input.addClass('is-invalid');
                } else {
                    errorDiv.hide();
                    input.removeClass('is-invalid');
                }

                updateTotalQuantity();
            });

            function updateTotalQuantity() {
                var totalQuantity = 0;
                $('.size-quantity').each(function() {
                    var quantity = parseInt($(this).val(), 10);
                    if (!isNaN(quantity) && quantity >= 0) {
                        totalQuantity += quantity;
                    }
                });

                $('#Quantity').val(totalQuantity);
            }
        });
    </script>
@endsection
