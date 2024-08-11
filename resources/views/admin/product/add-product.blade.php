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
                                                    <option value="{{ $attribute->idAttribute }}">
                                                        {{ $attribute->AttributeName }}</option>
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

                                <div class="pb-3 d-flex flex-wrap" id="attributevalue">

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
            data: { idAttribute: attributeId },
            success: function(response) {
                $('#attribute_value').empty();

                $.each(response, function(index, attrival) {
                    $('#attribute_value').append(
                        $('<option>').val(attrival.idAttriValue).text(attrival.AttriValName)
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





















    <script>
        // $(document).ready(function() {
        //     function validateQuantityInput(input) {
        //         var inputVal = parseInt($(input).val());
        //         if (inputVal < 0) {
        //             alert("Số lượng không được âm");
        //             $(input).val(0); // Reset giá trị về 0 nếu người dùng nhập số âm
        //         }
        //         var total_qty = 0;
        //         $(".qty-attr").each(function() {
        //             if (!isNaN(parseInt($(this).val()))) {
        //                 total_qty += parseInt($(this).val());
        //             }
        //         });
        //         $("#Quantity").val(total_qty);
        //     }

        //     // Add event listener for input and change events on quantity inputs
        //     $(document).on("input change", ".qty-attr", function() {
        //         validateQuantityInput(this);
        //     });

        //     // AJAX call and other logic remain the same
        //     $('.choose-attr').on('change', function() {
        //         var action = $(this).attr('id');
        //         var idAttribute = $(this).val();
        //         var attr_group_name = $("#attr-group-" + idAttribute).data("attr-group-name");
        //         var _token = $('input[name="_token"]').val();
        //         var result = '';

        //         if (action == 'attribute') result = 'attributevalue';

        //         $.ajax({
        //             url: '{{ url('/select-attribute') }}',
        //             method: 'POST',
        //             data: {
        //                 action: action,
        //                 idAttribute: idAttribute,
        //                 _token: _token
        //             },
        //             success: function(data) {
        //                 $('#' + result).html(data);

        //                 $("input[type=checkbox]").on("click", function() {
        //                     var attr_id = $(this).data("id");
        //                     var attr_name = $(this).data("name");

        //                     if ($(this).is(":checked")) {
        //                         $("#attr-name-" + attr_id).addClass("text-primary");

        //                         $("#confirm-attrs").click(function() {
        //                             var input_attrs_item =
        //                                 '<div id="input-attrs-item-' + attr_id +
        //                                 '" class="col-md-12 d-flex flex-wrap input_attrs_items"><div class="col-md-6"><input class="form-control text-center" type="text" value="' +
        //                                 attr_name +
        //                                 '" disabled></div><div class="form-group col-md-6"><input id="qty-attr-' +
        //                                 attr_id +
        //                                 '" class="form-control text-center qty-attr" name="qty_attr[]" placeholder="Nhập số lượng phân loại" type="number" min="0" required></div></div>';
        //                             if ($('#input-attrs-item-' + attr_id)
        //                                 .length < 1) $('.input-attrs').append(
        //                                 input_attrs_item);

        //                             $(document).on("input change", ".qty-attr",
        //                                 function() {
        //                                     validateQuantityInput(this);
        //                                 });
        //                         });
        //                     } else if ($(this).is(":not(:checked)")) {
        //                         $("#attr-name-" + attr_id).removeClass("text-primary");

        //                         $("#confirm-attrs").click(function() {
        //                             $('#input-attrs-item-' + attr_id).remove();

        //                             var total_qty = 0;
        //                             $(".qty-attr").each(function() {
        //                                 if (!isNaN(parseInt($(this)
        //                                     .val()))) {
        //                                     total_qty += parseInt($(
        //                                         this).val());
        //                                 }
        //                             });
        //                             $("#Quantity").val(total_qty);
        //                         });
        //                     }

        //                     $('.choose-attr').on('change', function() {
        //                         $('.chk_attr').prop('checked', false);

        //                         $("#confirm-attrs").click(function() {
        //                             $('.input_attrs_items').remove();
        //                         });
        //                     });
        //                 });

        //                 $("#confirm-attrs").click(function() {
        //                     if ($('[name="chk_attr[]"]:checked').length >= 1) {
        //                         $('.attr-title-1').html(attr_group_name);
        //                         $('.attr-title-1').removeClass('d-none');
        //                         $('.attr-title-2').removeClass('d-none');
        //                         $('#Quantity').addClass('disabled-input');
        //                     } else {
        //                         $('.attr-title-1').addClass('d-none');
        //                         $('.attr-title-2').addClass('d-none');
        //                         $('#Quantity').removeClass('disabled-input');
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
