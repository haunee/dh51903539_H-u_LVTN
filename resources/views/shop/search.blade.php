@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner5.jpg);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Tìm Kiếm Sản Phẩm</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tìm kiếm sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->

    <?php use App\Http\Controllers\ProductController; ?>

    <!--Shop Start-->
    <div class="shop-page section-padding-6">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">

                    <h4><i class="icon-search"></i> Kết quả tìm kiếm cho từ khóa <span
                            class="text-primary">'{{ $keyword }}'</span></h4>
                    <input type="hidden" id="keyword-link" value="{{ $keyword }}">

                    

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
                                            } elseif ($_GET['sort_by'] == 'bestsellers') {
                                                echo 'Bán Chạy';
                                            } elseif ($_GET['sort_by'] == 'price_desc') {
                                                echo 'Giá Cao - Thấp';
                                            } elseif ($_GET['sort_by'] == 'price_asc') {
                                                echo 'Giá Thấp - Cao';
                                            }
                                        } else {
                                            echo 'Mới Nhất';
                                        }
                                        ?>
                                    </span><i class="select-input__icon fa fa-angle-down"></i>
                                    <ul class="select-input__list">
                                        <li class="select-input__item" data-sort="&sort_by=new">Mới Nhất</li>
                                        <li class="select-input__item" data-sort="&sort_by=bestsellers">Bán Chạy</li>
                                        <li class="select-input__item" data-sort="&sort_by=price_desc">Giá Cao - Thấp</li>
                                        <li class="select-input__item" data-sort="&sort_by=price_asc">Giá Thấp - Cao</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="top-bar-page-amount">
                            <p>Có: {{ $count_pd }} sản phẩm</p>
                        </div>
                    </div>
                    <!--Shop Top Bar End-->
                    
                    @if (session('message'))
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                @endif

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel">
                            <div class="row">
                                @foreach ($list_pd as $key => $pd)
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="single-product">
                                            <div class="product-image">
                                                <?php $image = json_decode($pd->ImageName)[0]; ?>
                                                <a href="{{ URL::to('/shop-single/' . $pd->idProduct) }}">
                                                    <img src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                        alt="">
                                                </a>

                                             
                                                <?php
                                                $isInWishlist = in_array($pd->idProduct, $wishlistProducts);
                                                ?>

                                                <div class="action-links">
                                                    <ul>


                                                        <li><a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : 'not-in-wishlist' }}"
                                                                data-id="{{ $pd->idProduct }}" data-tooltip="tooltip"
                                                                data-placement="left"
                                                                title="Thêm vào danh sách yêu thích"><i
                                                                    class="fa fa-heart"></i></a></li>
                                                        <li><a class="quick-view-pd" data-id="{{ $pd->idProduct }}"
                                                                data-tooltip="tooltip" data-placement="left"
                                                                title="Xem nhanh"><i class="icon-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content text-center">

                                                <h4 class="product-name"><a
                                                        href="{{ URL::to('/shop-single/' . $pd->idProduct) }}">{{ $pd->ProductName }}</a>
                                                </h4>
                                                <div class="price-box">
                                                    <span
                                                        class="current-price">{{ number_format($pd->Price, 0, ',', '.') }}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="list" role="tabpanel">
                            @foreach ($list_pd as $key => $pd)
                                <div class="single-product product-list">
                                    <div class="product-image">
                                        <?php $image = json_decode($pd->ImageName)[0]; ?>
                                        <a href="{{ URL::to('/shop-single/' . $pd->idProduct) }}">
                                            <img src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                alt="">
                                        </a>



                                        <div class="action-links">
                                            <ul>
                                                <li><a class="quick-view-pd" data-id="{{ $pd->idProduct }}"
                                                        data-tooltip="tooltip" data-placement="left" title="Xem nhanh"><i
                                                            class="icon-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">

                                        <h4 class="product-name"><a
                                                href="{{ URL::to('/shop-single/' . $pd->idProduct) }}">{{ $pd->ProductName }}</a>
                                        </h4>
                                        <<div class="price-box">
                                            <span
                                                class="current-price">{{ number_format($pd->Price, 0, ',', '.') }}đ</span>
                                    </div>
                                    <p>{!! $pd->ShortDes !!}</p>

                                    <ul class="action-links">

                                        <li><a class="add-to-wishlist" data-id="{{ $pd->idProduct }}"
                                                data-tooltip="tooltip" data-placement="left"
                                                title="Thêm vào danh sách yêu thích"><i class="icon-heart"></i></a></li>

                                    </ul>
                                </div>
                        </div>
                        @endforeach
                    </div>
                </div>


                <!--Pagination Start-->
                <div class="page-pagination">
                    {{ $list_pd->appends(request()->input())->links() }}
                </div>
                <!--Pagination End-->


            </div>
            <div class="col-lg-3">
                <div class="shop-sidebar">


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
                                            <span style="margin-left:auto">({{ App\Http\Controllers\ProductController::count_cat_search($category->idCategory) }})</span>                                            {{-- <span style="margin-left:auto">({{ $count_pd[$category->idCategory] ?? 0 }})</span> --}}

                                            {{-- <span style="margin-left:auto">
                                                ({{ $category_counts[$category->idCategory] ?? 0 }})
                                            </span> --}}
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
                                            <span style="margin-left:auto">({{ App\Http\Controllers\ProductController::count_brand_search($brand->idBrand) }})</span>
                                        

                                        {{-- <span style="margin-left:auto"> ({{ $brand_counts[$brand->idBrand] ?? 0 }})</span> --}}
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












                </div>
            </div>
        </div>
    </div>
    </div>
    <!--Shop End-->
@endsection