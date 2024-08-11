@extends('admin_layout')
@section('content_dash')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3"> Tổng: {{$count_product}} sản phẩm </h4>
                            
                        </div>
                        <a href="{{ URL::to('/add-product') }}" class="btn btn-primary add-list">Thêm sản phẩm</a> 

                    </div>

                </div>

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th> Mã SP</th>
                                    <th> Sản phẩm </th>
                                    <th> Danh mục </th>
                                    <th> Thương hiệu </th>
                                    <th> Số lượng </th>
                                    <th> Giá </th>
                                    <th> Thao tác </th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @if (isset($list_product) && count($list_product) > 0)
                                    @foreach ($list_product as $key => $product)
                                        <tr>
                                            <td>{{ $product->idProduct }}</td>
                                            <td>
                                                @if (isset($product->ImageName) && !is_null($product->ImageName))
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $images = json_decode($product->ImageName);
                                                            if (is_array($images) && count($images) > 0) {
                                                                $first_image = $images[0];
                                                        @endphp
                                                        <img src="{{ asset('/storage/kidadmin/images/product/' . $first_image) }}" class="img-fluid rounded avatar-50 mr-3" alt="image">
                                                        @php
                                                            }
                                                        @endphp
                                                        <div>{{ $product->ProductName }}</div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $product->CategoryName }}</td>
                                            <td>{{ $product->BrandName }}</td>
                                            <td>{{ $product->QuantityTotal }}</td>
                                            <td>{{ $product->Price }}</td>

                                            <td>
                                                <form> @csrf
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="badge bg-success mr-2" data-toggle="tooltip"
                                                            data-placement="top" title="" data-original-title="Sửa"
                                                            href="{{ URL::to('/edit-product/' . $product->idProduct) }}"><i
                                                                class="fas fa-edit mr-0"></i></a>
                                                        <a class="badge bg-warning mr-2" data-toggle="modal"
                                                            data-tooltip="tooltip"
                                                            data-target="#modal-delete-{{ $product->idProduct }}"
                                                            data-placement="top" title="Xóa" data-original-title="Xóa"
                                                            style="cursor:pointer;"><i
                                                                class="fas fa-trash-alt mr-0"></i></a>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>

                                        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
                                            id="modal-delete-{{ $product->idProduct }}" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Thông báo</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có muốn xóa sản phẩm {{ $product->ProductName }} không?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Trở về</button>
                                                        <a href="{{ URL::to('/delete-product/' . $product->idProduct) }}"
                                                            type="button" class="btn btn-primary">Xác nhận</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->



@endsection
