@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner-shop.png)">
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="shop-image">
                        <div class="shop-single-preview-image">
                            <img class="product-zoom" src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                alt="">

                            @if ($product->QuantityTotal == '0')
                                <span class="sticker-new label-sale">HẾT HÀNG</span>
                            @endif
                        </div>
                        <div id="gallery_01" class="shop-single-thumb-image shop-thumb-active swiper-container">
                            <div class="swiper-wrapper">
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

                            <!-- Add Arrows -->
                            <div class="swiper-thumb-next"><i class="fa fa-angle-right"></i></div>
                            <div class="swiper-thumb-prev"><i class="fa fa-angle-left"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shop-single-content">
                        <h3 class="title">{{ $product->ProductName }}</h3>
                        <span class="product-sku">Mã sản phẩm: <span>{{ $product->idProduct }}</span></span>
                        <div class="text-primary">Đã Bán: {{ $product->Sold }} sản phẩm</div>
                        <div class="text-primary">Còn Lại: {{ $product->QuantityTotal }} sản phẩm</div>
                        {{-- <div class="text-primary">Lượt Yêu Thích: {{$count_wish}} </div> --}}
                        {{-- <div class="product-rating">
                            <ul class="rating-star">
                                <li class="rating-on"><i class="fa fa-star-o"></i></li>
                                <li class="rating-on"><i class="fa fa-star-o"></i></li>
                                <li class="rating-on"><i class="fa fa-star-o"></i></li>
                                <li class="rating-on"><i class="fa fa-star-o"></i></li>
                                <li class="rating-on"><i class="fa fa-star-o"></i></li>
                            </ul>
                            <span>No reviews</span>
                        </div> --}}
                        <div class="thumb-price">
                            <span class="current-price">{{ number_format(round($product->Price, -3), 0, ',', '.') }}đ</span>
                        </div>
                        <div>{!! $product->ShortDes !!}</div>

                        <div class="shop-single-material pt-3">
                            <div class="material-title col-lg-2">{{ $name_attribute->AttributeName }}:</div>
                            <ul class="material-list">
                                @foreach ($list_pd_attr as $key => $pd_attr)
                                    <li>
                                        <div class="material-radio">
                                            <input type="radio" value="{{ $pd_attr->idProAttr }}" class="AttriValName"
                                                name="material" id="{{ $pd_attr->idProAttr }}"
                                                data-name="{{ $pd_attr->AttriValName }}"
                                                data-qty="{{ $pd_attr->Quantity }}">
                                            <label for="{{ $pd_attr->idProAttr }}">{{ $pd_attr->AttriValName }}</label>
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


                            <input type="hidden" id="AttributeName" name="AttributeName"
                                value="{{ $name_attribute->AttributeName }}">
                            <input type="hidden" id="AttributeProduct" name="AttributeProduct">
                            <input type="hidden" id="idProAttr" name="idProAttr">

                            <div class="text-primary alert-qty"></div>


                            <div class="product-action d-flex flex-wrap">
                                <div class="action">
                                    <button type="button" class="btn btn-primary add-to-cart">Thêm vào giỏ hàng</button>
                                </div>

                                <div class="action">
                                    
                                    <a class="add-to-wishlist" data-id="{{$product->idProduct}}" data-tooltip="tooltip" data-placement="right" title="Thêm vào yêu thích"><i class="fa fa-heart"></i></a>
                                
                                </div>

                               
                              
                            </div>
                            <div class="text-primary alert-add-to-cart"></div>

                            <div class="dynamic-checkout-button">

                                <div class="checkout-btn">
                                    <input type="submit" formaction="{{ URL::to('/buy-now') }}"
                                        class="btn btn-primary buy-now" value="Mua ngay" />
                                </div>
                            </div>
                            <div class="text-primary alert-buy-now"></div>
                            <?php
                            $error = Session::get('error');
                            if ($error) {
                                echo '<div class="text-danger">' . $error . '</div>';
                                Session::put('error', null);
                            }
                            ?>
                        </form>

                        <div class="custom-payment-options">
                            <p>Phương thức thanh toán</p>

                            <ul class="payment-options">
                                <li><img src="{{ asset('/page/images/payment-icon/payment-1.svg') }}" alt="">
                                </li>
                                <li><img src="{{ asset('/page/images/payment-icon/payment-2.svg') }}" alt="">
                                </li>
                                <li><img src="{{ asset('/page/images/payment-icon/payment-3.svg') }}" alt="">
                                </li>
                                <li><img src="{{ asset('/page/images/payment-icon/payment-4.svg') }}" alt="">
                                </li>
                                <li><img src="{{ asset('/page/images/payment-icon/payment-5.svg') }}" alt="">
                                </li>
                                <li><img src="{{ asset('/page/images/payment-icon/payment-7.svg') }}" alt="">
                                </li>
                            </ul>
                        </div>
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
                                    role="tab">Mô tả/Chi tiết</a></li>
                            {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Nhận
                                xét</a></li> --}}
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                            <div class="description">
                                <p>{!! $product->DesProduct !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                            <div class="reviews">
                                <h3 class="review-title">Customer Reviews</h3>

                                <ul class="reviews-items">
                                    <li>
                                        <div class="single-review">
                                            <h6 class="name">Rosie Silva</h6>
                                            <div class="rating-date">
                                                <ul class="rating">
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                </ul>
                                                <span class="date">13 ngày trước</span>
                                            </div>
                                            <p>Áo tốt, giá cả hợp lý. Shop gói hàng rất cẩn thận, mình rất hài lòng.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="single-review">
                                            <h6 class="name">James Turner</h6>
                                            <div class="rating-date">
                                                <ul class="rating">
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                </ul>
                                                <span class="date">7 ngày trước</span>
                                            </div>
                                            <p>Áo đẹp, mình mua cho con trai, bé rất thích.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="single-review">
                                            <h6 class="name">Maria Garcia</h6>
                                            <div class="rating-date">
                                                <ul class="rating">
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li class="rating-on"><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                </ul>
                                                <span class="date">4 ngày trước</span>
                                            </div>
                                            <p>Sản phẩm rất tốt, màu sắc như hình, mình rất hài lòng.</p>
                                        </div>
                                    </li>
                                </ul>

                                <div class="review-form">
                                    <h3 class="review-title">Thêm nhận xét</h3>
                                    <form action="#">
                                        <div class="rating-form d-flex align-items-center">
                                            <span class="rating-title mr-3">Đánh giá của bạn:</span>
                                            <ul class="rating">
                                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Họ và tên</label>
                                                    <input type="text" id="name" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="review">Nhận xét của bạn</label>
                                                    <textarea id="review" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Gửi nhận xét</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--Shop Single info End-->
            </div>
        </div>
        <!--Shop Single End-->



        <!-- Validate QuantityBuy & Add To Cart & Buy Now -->
        <script>
            $(document).ready(function() {
                var idCustomer = '<?php echo Session::get('idCustomer'); ?>';
                var $Quantity = parseInt($('.qty-of-attr').val());
                $("input:radio[name=material]:first").attr('checked', true);
                $('#idProAttr').val($("input:radio[name=material]:first").val());

                var AttributeProduct = $('#AttributeName').val() + ': ' + $('.AttriValName').data("name");
                $('#AttributeProduct').val(AttributeProduct);

                $("input:radio[name=material]").on('click', function() {
                    $(".qty-buy").val("1");
                    $('.alert-qty').html("");
                    $('.alert-add-to-cart').html("");
                    $('.alert-buy-now').html("");
                    $idAttribute = $(this).attr("id");
                    $AttriValName = $(this).data("name");
                    $Quantity = $(this).data("qty");
                    $('.qty-of-attr-label').html("Còn Lại: " + $Quantity);
                    $('.qty-of-attr').val($Quantity);

                    AttributeProduct = $('#AttributeName').val() + ': ' + $AttrValName;
                    $('#AttributeProduct').val(AttributeProduct);

                    $('#idProAttr').val($("#" + $idAttribute).val());
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

                $('.buy-now').on('click', function(e) {
                    if ($(".qty-buy").val() > $Quantity) {
                        $('.alert-buy-now').html("Vượt quá số lượng sản phẩm hiện có!");
                        e.preventDefault();
                    }
                });

                $('.add-to-cart').on('click', function() {
                    if (idCustomer == "") {
                        window.location.href = '../login';
                    } else if ($(".qty-buy").val() > $Quantity) {
                        $('.alert-add-to-cart').html("Vượt quá số lượng sản phẩm hiện có!");
                    } else {
                        var idProduct = $('#idProduct').val();
                        var AttributeProduct = $('#AttributeProduct').val();
                        var QuantityBuy = $('.qty-buy').val();
                        var Price = $('input[name="Price"]').val(); // Lấy giá trị từ trường ẩn
                        var _token = $('input[name="_token"]').val();
                        var qty_of_attr = $('.qty-of-attr').val();
                        var idProAttr = $('#idProAttr').val();

                        $.ajax({
                            url: '{{ url('/add-to-cart') }}',
                            method: 'POST',
                            data: {
                                idProduct: idProduct,
                                idProAttr: idProAttr,
                                AttributeProduct: AttributeProduct,
                                QuantityBuy: QuantityBuy,
                                Price: Price,
                                qty_of_attr: qty_of_attr,
                                _token: _token
                            },
                            success: function(data) {
                                // Hiển thị modal nếu có dữ liệu trả về
                                if (data) {
                                    $('#modal-AddToCart').html(data);
                                    $('.modal-AddToCart').modal('show');
                                } else {
                                    $('.alert-add-to-cart').html(
                                        "Có lỗi xảy ra. Vui lòng thử lại.");
                                }
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
