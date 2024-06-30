@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner-shop.png);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Cửa Hàng</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cửa Hàng</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->
    <?php use App\Http\Controllers\ProductController; ?>
    <!-- Shop Start -->
    <div class="shop-page section-padding-6">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <!--Shop Top Bar Start-->
                    <div class="shop-top-bar d-sm-flex align-items-center justify-content-between mt-3">

                        <div class="top-bar-sorter">
                            <div class="sorter-wrapper d-flex align-items-center">
                                <label>Sắp xếp theo:</label>

                                <div class="select-input">
                                    <span class="select-input__sort" <?php
                                    if (isset($_GET['sort_by'])) {
                                        echo 'data-sort=' . '&sort_by=' . $_GET['sort_by'];
                                    } else {
                                        echo "data-sort='&sort_by=new'";
                                    }
                                    ?>>
                                        <?php
                                        if (isset($_GET['sort_by'])) {
                                            if ($_GET['sort_by'] == 'new') {
                                                echo 'Mới Nhất';
                                            } elseif ($_GET['sort_by'] == 'old') {
                                                echo 'Cũ Nhất';
                                            } elseif ($_GET['sort_by'] == 'bestsellers') {
                                                echo 'Bán Chạy';
                                            } elseif ($_GET['sort_by'] == 'featured') {
                                                echo 'Nổi Bật';
                                            } elseif ($_GET['sort_by'] == 'sale') {
                                                echo 'Đang SALE';
                                            } elseif ($_GET['sort_by'] == 'price_desc') {
                                                echo 'Giá, Cao đến Thấp';
                                            } elseif ($_GET['sort_by'] == 'price_asc') {
                                                echo 'Giá, Thấp đến Cao';
                                            }
                                        } else {
                                            echo 'Mới Nhất';
                                        }
                                        ?>
                                    </span><i class="select-input__icon fa fa-angle-down"></i>
                                    <ul class="select-input__list">
                                        <li class="select-input__item" data-sort="&sort_by=new">Mới Nhất</li>
                                        <li class="select-input__item" data-sort="&sort_by=old">Cũ Nhất</li>
                                        <li class="select-input__item" data-sort="&sort_by=bestsellers">Bán Chạy</li>
                                        <li class="select-input__item" data-sort="&sort_by=featured">Nổi Bật</li>
                                        <li class="select-input__item" data-sort="&sort_by=sale">Đang SALE</li>
                                        <li class="select-input__item" data-sort="&sort_by=price_desc">Giá, Cao đến Thấp
                                        </li>
                                        <li class="select-input__item" data-sort="&sort_by=price_asc">Giá, Thấp đến Cao</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="top-bar-page-amount">
                            <p>Có: {{ $count_pd }} sản phẩm</p>
                        </div>
                    </div>
                    <!--Shop Top Bar End-->




                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel">
                            <div class="row">
                                @foreach ($list_pd as $key => $product)
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="single-product">
                                            <div class="product-image">
                                                <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                                    <img src="{{ asset('/storage/kidadmin/images/product/' . json_decode($product->ImageName)[0]) }}"
                                                        alt="{{ $product->ProductName }}">
                                                </a>



                                                <div class="action-links">
                                                    <ul>

                                                        <li><a class="add-to-compare"
                                                                data-idcat="{{ $product->idCategory }}"
                                                                id="{{ $product->idProduct }}" data-tooltip="tooltip"
                                                                data-placement="left" title="So sánh"><i
                                                                    class="icon-sliders"></i></a></li>
                                                        <li><a class="add-to-wishlist" data-id="{{ $product->idProduct }}"
                                                                data-tooltip="tooltip" data-placement="left"
                                                                title="Thêm vào danh sách yêu thích"><i
                                                                    class="icon-heart"></i></a></li>
                                                        <li><a class="quick-view-pd" data-id="{{ $product->idProduct }}"
                                                                data-tooltip="tooltip" data-placement="left"
                                                                title="Xem nhanh"><i class="icon-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content text-center">

                                                <h4 class="product-name"><a
                                                        href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a>
                                                </h4>
                                                <div class="price-box">
                                                    <span
                                                        class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="list" role="tabpanel">
                            @foreach ($list_pd as $key => $product)
                                <div class="single-product product-list">
                                    <div class="product-image">
                                        <?php $image = json_decode($product->ImageName)[0]; ?>
                                        <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                            <img src="{{ asset('/storage/kidadmin/images/product/' . json_decode($product->ImageName)[0]) }}"
                                                alt="{{ $product->ProductName }}">
                                        </a>
                                        <div class="action-links">
                                            <ul>
                                                <li><a class="quick-view-pd" data-id="{{ $product->idProduct }}"
                                                        data-tooltip="tooltip" data-placement="left" title="Xem nhanh"><i
                                                            class="icon-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">

                                        <h4 class="product-name"><a
                                                href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a>
                                        </h4>
                                        <div class="price-box">
                                            <span
                                                class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <p>{!! $product->ShortDes !!}</p>

                                        <ul class="action-links">

                                            <li><a class="add-to-wishlist" data-id="{{ $product->idProduct }}"
                                                    data-tooltip="tooltip" data-placement="left"
                                                    title="Thêm vào danh sách yêu thích"><i class="icon-heart"></i></a>
                                            </li>
                                            <li><a class="add-to-compare" data-idcat="{{ $product->idCategory }}"
                                                    id="{{ $product->idProduct }}" data-tooltip="tooltip"
                                                    data-placement="left" title="So sánh"><i
                                                        class="icon-sliders"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="shop-sidebar">

                        <h4><i class="fa fa-filter"></i> BỘ LỌC TÌM KIẾM</h4>

                        <!--Sidebar Categories Start-->
                        <div class="sidebar-categories">
                            <h3 class="widget-title">Theo danh mục</h3>

                            <ul class="categories-list">
                                @foreach ($list_category as $key => $category)
                                    <li class="d-flex align-items-center">
                                        <input <?php
                                        if (isset($_GET['category'])) {
                                            $idCategory = $_GET['category'];
                                            $category_arr = explode(',', $idCategory);
                                        
                                            if (in_array($category->idCategory, $category_arr)) {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        }
                                        ?> class="filter-product" type="checkbox"
                                            id="cat-{{ $category->idCategory }}" data-filter="category"
                                            value="{{ $category->idCategory }}" name="category-filter"
                                            style="width:15px;height:15px;">
                                        <label class="mb-0 ml-2" for="cat-{{ $category->idCategory }}"
                                            style="font-size:15px;cursor:pointer;"><span
                                                style="position:relative; top:2px;">{{ $category->CategoryName }}</span></label>
                                        <span
                                            style="margin-left:auto">({{ App\Models\Product::where('idCategory', $category->idCategory)->count() }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!--Sidebar Categories End-->

                        <!--Sidebar Categories Start-->
                        <div class="sidebar-categories">
                            <h3 class="widget-title">Theo thương hiệu</h3>

                            <ul class="categories-list">
                                @foreach ($list_brand as $key => $brand)
                                    <li class="d-flex align-items-center">
                                        <input <?php
                                        if (isset($_GET['brand'])) {
                                            $idBrand = $_GET['brand'];
                                            $brand_arr = explode(',', $idBrand);
                                        
                                            if (in_array($brand->idBrand, $brand_arr)) {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        }
                                        ?> class="filter-product" type="checkbox"
                                            id="brand-{{ $brand->idBrand }}" data-filter="brand"
                                            value="{{ $brand->idBrand }}" name="brand-filter"
                                            style="width:15px;height:15px;">
                                        <label class="mb-0 ml-2" for="brand-{{ $brand->idBrand }}"
                                            style="font-size:15px;cursor:pointer;"><span
                                                style="position:relative; top:2px;">{{ $brand->BrandName }}</span></label>
                                        <span
                                            style="margin-left:auto">({{ App\Models\Product::where('idBrand', $brand->idBrand)->count() }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="sidebar-categories">
                            <h3 class="widget-title">Theo giá</h3>
                            <div class="d-flex justify-content-between">
                                <input class="input-filter-price min" type="number" min="0" maxlength="13"
                                    placeholder="đ TỪ" onkeypress="return /[0-9]/i.test(event.key)" <?php
                                    if (isset($_GET['priceMin'])) {
                                        echo 'value=' . $_GET['priceMin'];
                                    }
                                    ?>>
                                <span style="line-height: 240%;"> - </span>
                                <input class="input-filter-price max" type="number" min="0" maxlength="13"
                                    placeholder="đ ĐẾN" onkeypress="return /[0-9]/i.test(event.key)" <?php
                                    if (isset($_GET['priceMax'])) {
                                        echo 'value=' . $_GET['priceMax'];
                                    }
                                    ?>>
                            </div>
                            <div class="alert-filter-price text-primary mt-2 d-none">Vui lòng điền khoảng giá phù hợp</div>
                            <button type="button" class="btn-filter-price btn btn-primary">Áp dụng</button>
                        </div>
                        <!--Sidebar Categories End-->


                        <!--Sidebar Size Start-->
                        <div class="sidebar-banner">
                            <a href="#"><img src="/page/images/banner-top-pd.png" alt=""></a>
                        </div>
                        <!--Sidebar Size End-->




                    </div>
                </div>
            </div>
        </div>
        <!-- Shop End -->
    </div>





    
@endsection
