@extends('admin_layout')
@section('content_dash')

<?php use Illuminate\Support\Facades\Session; ?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-info-light">
                                        <img src="/kidadmin/images/product/icon2.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Tổng Doanh Thu</p>
                                        <h4>{{number_format($total_revenue,0,',','.')}}đ</h4>
                                    </div>
                                </div>                                
                            </div>
            
                            
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="/kidadmin/images/product/icon3.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Tổng Sản Phầm Bán Ra</p>
                                        <h4>{{number_format($total_sell,0,',','.')}} sản phẩm</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                
                                    <div class="d-flex align-items-center mb-4 card-total-sale">
                                        <div class="icon iq-icon-box-2 bg-success-light">
                                            <img src="/kidadmin/images/product/icon9.png" class="img-fluid" alt="image">
                                        </div>
                                        <div>
                                            <p class="mb-2">Tổng Đơn hàng </p>
                                            <h4>{{number_format($total_orders,0,',','.')}} đơn hàng</h4>
                                        </div>
                                    </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Doanh Thu Cửa Hàng</h4>
                        </div>
                     
                        <form class="col-lg-3 p-0"> @csrf
                        <div class="input-group">
                         
                            <select class="custom-select" id="chart-by-days">
                                
                                <option value="lastweek">7 ngày qua</option>
                                <option value="lastmonth">30 ngày qua</option>
                                <option value="lastyear">365 ngày qua</option>
                            </select>
                        </div>
                        </form>
                    </div>

                    
                    <div class="card-body">
                        <div id="chart-sale" style="height: 280px;"></div>
                    </div>
                </div>
            </div>  
                <div class="col-lg-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Sản Phẩm Bán Chạy</h4>
                            </div>
                        
                        </div>
                        <div class="card-body list-topPro" style="padding-bottom:4px;">
                            <ul class="list-unstyled row mb-0">
                                @foreach($list_topProduct as $key => $topProduct)
                                    <li class="col-lg-3 topPro-item">
                                        <div class="card-block card-stretch mb-0">
                                            <div class="card-body">
                                                <div class="bg-warning-light rounded">
                                                    <?php $image = json_decode($topProduct->ImageName)[0];?>
                                                    <img src="{{asset('/storage/kidadmin/images/product/'.$image)}}" class="style-img img-fluid m-auto p-3" alt="image">
                                                </div>
                                                <div class="style-text text-left mt-3">
                                                    <h5 class="mb-1 limit-2-lines">{{$topProduct->ProductName}}</h5>
                                                    <p class="mb-0">Đã bán: {{number_format($topProduct->Sold,0,',','.')}}</p>
                                                    <p class="mb-0">Giá: {{number_format($topProduct->Price,0,',','.')}}đ</p>
                                                    <p class="mb-0">Tổng doanh thu: {{number_format($topProduct->Sold * $topProduct->Price,0,',','.')}}đ</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
      </div>
    </div>
</div>
<!-- Page end  -->

<!-- Tạo datetimepicker form -->
<script>
    $(document).ready(function(){  
        APP_URL = '{{url('/')}}' ;
        jQuery.datetimepicker.setLocale('vi');
       

        chart_7days();
        var chart = new Morris.Line({
            element: 'chart-sale',
            barColors: ['orange','#32BDEA','#FF9DBE'],
            gridTextColor: ['orange','#32BDEA','#FF9DBE'],
            pointFillColors: ['#fff'],
            pointStrokeColors: ['black'],
            fillOpacity: 1,
            hideHover: 'auto',
            parseTime: false, 
            xkey: 'Date',
            ykeys: ['TotalSold','Sale','QtyBill'],
            behaveLikeLine: true,
            labels: ['Số lượng bán','Doanh thu','Đơn hàng'],
        });

        function chart_7days(){
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: APP_URL + '/chart-7days',
                method: 'POST',
                dataType: 'JSON',//dl trả về là json
                data: {_token:_token},
                success:function(data){
                    chart.setData(data);//cập nhật dữ liệu cho biểu đồ
                }
            });
        }

     
        $('#chart-by-days').on("change", function(){
            var Days = $(this).val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: APP_URL + '/statistic-by-date-order',
                method: 'POST',
                dataType: 'JSON',
                data: {Days:Days,_token:_token},
                success:function(data){
                     
                    chart.setData(data);
                }
            });
        });

      
    });
</script>

@endsection