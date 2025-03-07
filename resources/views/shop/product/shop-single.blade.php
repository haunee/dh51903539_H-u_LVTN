@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner5.jpg)">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Chi tiết sản phẩm</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item">Chi tiết sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->

    <?php
    use App\Http\Controllers\ProductController;
    use Illuminate\Support\Facades\Session;
    
    $image = json_decode($product->ImageName)[0];
    ?>

    <!--Shop Single Start-->
    <div class="shop-single-page section-padding-4">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="shop-image d-flex">
                        <!-- Hình ảnh phụ nằm bên trái -->
                        <div id="gallery_01" class="shop-single-thumb-image shop-thumb-active swiper-container">
                            <div>
                                @foreach (json_decode($product->ImageName) as $img)
                                    <div class="swiper-slide single-product-thumb">
                                        <a class="active" href="#"
                                            data-image="{{ asset('/storage/kidadmin/images/product/' . $img) }}">
                                            <img src="{{ asset('/storage/kidadmin/images/product/' . $img) }}"
                                                alt="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Hình ảnh chính nằm bên phải -->
                        <div class="shop-single-preview-image">
                            <img class="product-zoom" src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                alt="">

                            @if ($product->QuantityTotal == '0')
                                <span class="sticker-new label-sale">HẾT HÀNG</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8">
                    <div class="shop-single-content">
                        <h3 class="title">{{ $product->ProductName }}</h3>
                        <div class="text-primary">Đã Bán: {{ $product->Sold }} sản phẩm</div>
                        <div class="text-primary">Còn Lại: {{ $product->QuantityTotal }} sản phẩm</div>


                        <div class="thumb-price">
                            <span class="current-price">{{ number_format(round($product->Price, -3), 0, ',', '.') }}đ</span>
                        </div>
                        <div>{!! $product->ShortDes !!}</div>

                        <div class="shop-single-material pt-3">
                            <div class="material-title col-lg-2">{{ $name_attribute->PropertyName }}:</div>
                            <ul class="material-list">
                                @foreach ($list_pd_attr as $key => $pd_attr)
                                    <li>
                                        <div class="material-radio">
                                            <input type="radio" value="{{ $pd_attr->idProperPro }}" class="ProValName"
                                                name="material" id="{{ $pd_attr->idProperPro }}"
                                                data-name="{{ $pd_attr->ProValName }}"
                                                data-qty="{{ $pd_attr->Quantity }}">
                                            <label for="{{ $pd_attr->idProperPro }}">{{ $pd_attr->ProValName }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-20 qty-of-attr-label">Còn Lại: {{ $name_attribute->Quantity }}</div>

                        <form method="POST" action="{{ URL::to('/add-to-cart') }}">
                            @csrf
                            <div class="product-quantity d-flex flex-wrap align-items-center pt-30">
                                <span class="quantity-title">Số Lượng: </span>
                                <div class="quantity d-flex align-items-center">
                                    <button type="button" class="sub-qty"><i class="ti-minus"></i></button>
                                    <input type="number" class="qty-buy" name="qty_buy" value="1" />
                                    <button type="button" class="add-qty"><i class="ti-plus"></i></button>
                                </div>
                            </div>


                            <input type="hidden" name="Price" value="{{ $product->Price }}">
                            <input type="hidden" name="idProduct" id="idProduct" value="{{ $product->idProduct }}">
                            <input class="qty-of-attr" name="qty_of_attr" type="hidden"
                                value="{{ $name_attribute->Quantity }}">


                            <input type="hidden" id="PropertyName" name="PropertyName"
                                value="{{ $name_attribute->PropertyName }}">
                            <input type="hidden" id="PropertyPro" name="PropertyPro">
                            <input type="hidden" id="idProperPro" name="idProperPro">

                            <div class="text-primary alert-qty"></div>


                            <div class="product-action d-flex flex-wrap">
                                <div class="action">
                                    <button type="button" class="btn btn-primary add-to-cart">Thêm vào giỏ hàng</button>
                                </div>

                                <div class="action">
                                    <?php
                                    $isInWishlist = in_array($product->idProduct, $wishlistProducts);
                                    ?>
                                    <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : 'not-in-wishlist' }}"
                                        data-id="{{ $product->idProduct }}" data-tooltip="tooltip" data-placement="left"
                                        title="Thêm vào danh sách yêu thích">
                                        <i class="fa fa-heart"></i>
                                    </a>
                                </div>



                            </div>
                            <div class="text-primary alert-add-to-cart"></div>



                            <div class="text-primary alert-buy-now"></div>
                            <?php
                            $error = Session::get('error');
                            if ($error) {
                                echo '<div class="text-danger">' . $error . '</div>';
                                Session::put('error', null);
                            }
                            ?>
                        </form>


                    </div>
                </div>
            </div>
            <!--Shop Single End-->








            <div id="modal-AddToCart">
                <!--Shop Single info Start-->
                <div class="shop-single-info">
                    <div class="shop-info-tab">
                        <ul class="nav justify-content-center" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab1"
                                    role="tab">Mô tả/Chi tiết</a>
                            </li>
                            {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Nhận xét</a></li> --}}

                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                            <div class="description">
                                <p>{!! $product->DesProduct !!}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <!--Shop Single info End-->
            </div>
        </div>
        <!--Shop Single End-->

        @if ($list_related_products->count() > 0)
            <div class="new-product-area section-padding-2">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-9 col-sm-11">
                            <div class="section-title text-center">
                                <h2 class="title">Sản Phẩm Liên Quan</h2>

                            </div>
                        </div>
                    </div>
                    <div class="product-wrapper">
                        <div class="swiper-container product-active">
                            <div class="swiper-wrapper">
                                @foreach ($list_related_products as $key => $related_product)
                                    <div class="swiper-slide">
                                        <div class="single-product">
                                            <div class="product-image">
                                                <?php $image = json_decode($related_product->ImageName)[0]; ?>
                                                <a href="{{ URL::to('/shop-single/' . $related_product->idProduct) }}">
                                                    <img src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                        alt="">
                                                </a>
                                                <?php
                                                $isInWishlist = in_array($related_product->idProduct, $wishlistProducts);
                                                ?>

                                                <div class="action-links">
                                                    <ul>
                                                        <li>
                                                            <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : 'not-in-wishlist' }}"
                                                                data-id="{{ $related_product->idProduct }}"
                                                                data-tooltip="tooltip" data-placement="left"
                                                                title="Thêm vào danh sách yêu thích">
                                                                <i class="fa fa-heart"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content text-center">
                                                <h4 class="product-name"><a
                                                        href="{{ URL::to('/shop-single/' . $related_product->idProduct) }}">{{ $related_product->ProductName }}</a>
                                                </h4>
                                                <div class="price-box">
                                                    <span
                                                        class="current-price">{{ number_format($related_product->Price, 0, ',', '.') }}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add Arrows -->
                            <div class="swiper-next"><i class="fa fa-angle-right"></i></div>
                            <div class="swiper-prev"><i class="fa fa-angle-left"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif





        <!-- Validate QuantityBuy & Add To Cart & Buy Now -->
        <script>
            $(document).ready(function() {
                var idCustomer = '<?php echo Session::get('idCustomer'); ?>';
                var $Quantity = parseInt($('.qty-of-attr').val());
                $("input:radio[name=material]:first").attr('checked', true);
                $('#idProperPro').val($("input:radio[name=material]:first").val());

                var PropertyPro = $('#PropertyName').val() + ': ' + $('.ProValName').data("name");
                $('#PropertyPro').val(PropertyPro);

                $("input:radio[name=material]").on('click', function() {
                    $(".qty-buy").val("1");
                    $('.alert-qty').html("");
                    $('.alert-add-to-cart').html("");
                    $('.alert-buy-now').html("");
                    $idProperty = $(this).attr("id");
                    $ProValName = $(this).data("name");
                    $Quantity = $(this).data("qty");
                    $('.qty-of-attr-label').html("Còn Lại: " + $Quantity);
                    $('.qty-of-attr').val($Quantity);

                    PropertyPro = $('#PropertyName').val() + ': ' + $ProValName;
                    $('#PropertyPro').val(PropertyPro);

                    $('#idProperPro').val($("#" + $idProperty).val());
                });

                $('.add-qty').on('click', function() {
                    var $input = $(this).prev();
                    var currentValue = parseInt($input.val());
                    if (currentValue >= $Quantity) {
                        $('.alert-qty').html("Vượt quá số lượng sản phẩm hiện có!");
                    } else {
                        $input.val(currentValue + 1);
                    }
                });

                $('.sub-qty').on('click', function() {
                    var $input = $(this).next();
                    var currentValue = parseInt($input.val());
                    (currentValue == 1) ? currentValue: $input.val(currentValue - 1);
                });



                $('.add-to-cart').on('click', function() {
                    if (idCustomer == "") {
                        window.location.href = '../login';
                    } else {
                        // Thực hiện thêm vào giỏ hàng
                        var idProduct = $('#idProduct').val();
                        var PropertyPro = $('#PropertyPro').val();
                        var QuantityBuy = $('.qty-buy').val();
                        var Price = $('input[name="Price"]').val();
                        var _token = $('input[name="_token"]').val();
                        var qty_of_attr = $('.qty-of-attr').val();
                        var idProperPro = $('#idProperPro').val();

                        $.ajax({
                            url: '{{ url('/add-to-cart') }}',
                            method: 'POST',
                            data: {
                                idProduct: idProduct,
                                idProperPro: idProperPro,
                                PropertyPro: PropertyPro,
                                QuantityBuy: QuantityBuy,
                                Price: Price,
                                qty_of_attr: qty_of_attr,
                                _token: _token
                            },
                            success: function(data) {
                                // Hiển thị modal thông báo
                                if (data) {
                                    $('body').append(data); // Thêm modal vào body
                                    $('.modal-AddToCart').modal('show');
                                    $('.text-primary').on('click', function() {
                                        location
                                    .reload(); // Tải lại trang khi nhấp "Tiếp tục mua sắm"
                                    });
                                } else {
                                    $('.alert-add-to-cart').html(
                                    "Có lỗi xảy ra. Vui lòng thử lại.");
                                }

                                // Cập nhật giỏ hàng sau khi thêm sản phẩm
                                updateCartHeader();
                            },
                            error: function() {
                                $('.alert-add-to-cart').html("Có lỗi xảy ra. Vui lòng thử lại.");
                            }
                        });
                    }
                });


            });
        </script>

    @endsection
