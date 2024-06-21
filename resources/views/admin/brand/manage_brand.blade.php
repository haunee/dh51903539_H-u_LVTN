@extends('admin_layout')
@section('content_dash')

<?php use Illuminate\Support\Facades\Session; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="content-page">
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                
               
                <a href="{{URL::to('/add-brand')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Thêm Thương Hiệu</a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
            <table class=" table mb-0 tbl-server-info">
                <thead class="bg-white text-uppercase">
                    <tr class="ligth ligth-data">
                        <th>Mã thương hiệu</th>
                        <th>Tên thương hiệu</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody class="ligth-body" id="load-brand">
                    @foreach($list_brand as $key => $brand)
                    <tr>
                        <td>{{$brand->idBrand}}</td>
                        <td>{{$brand->BrandName}}</td>
                        <td>
                            <div class="d-flex align-items-center list-action">
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sửa"
                                    href="{{ URL::to('/edit-brand/' . $brand->idBrand) }}"><i class="fas fa-edit mr-0"></i></a>
                                <a class="badge bg-warning mr-2" data-toggle="modal" data-target="#model-delete-{{ $brand->idBrand }}" data-placement="top" title="" data-original-title="Xóa"
                                    style="cursor:pointer;"><i class="fas fa-trash-alt mr-0"></i></a>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="model-delete-{{$brand->idBrand}}"  aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thông báo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có muốn xóa thương hiệu {{$brand->BrandName}} không?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-dismiss="modal">Trở về</button>
                                <a href="{{URL::to('/delete-brand/'.$brand->idBrand)}}" type="button" class="btn btn-primary">Xác nhận</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
   
    </div>

</div>

@endsection