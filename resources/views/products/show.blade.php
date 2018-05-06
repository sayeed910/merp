@extends('adminlte::page')
@section('title', "Product")
{{--@section('content_header')--}}
{{--@endsection--}}
@push('css')
    <style>
        .content {
            font-size: 18px;
        }
    </style>
@endpush
@section('content')
    <div class="">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Product Details</h4>
            </div>
            <div class="box-body">
                <div class="col-lg-5 col-xs-offset-1 ">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Item Code: </label>
                        <span class="text-black">{{$product->item_code}}</span>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Name</label>
                        <span class="text-black">{{$product->name}}</span>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4">Brand</label>
                        <span class="text-black">{{$product->brand->name}}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Category: </label>
                        <span class="text-black">{{$product->category->name}}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Purchase Price: </label>
                        <span class="text-black">{{$product->cost}}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Sale Price: </label>
                        <span class="text-black">{{$product->price}}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Stock: </label>
                        <span class="text-black">{{$product->stock}}</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Damaged: </label>
                        <span class="text-black">{{$product->damaged}}</span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <label for="yearSelector">Year: </label>
                    <select name="yearSelector" id="yearSelector">

                    </select>

                    <canvas id="sales" height="400px" width="500px"></canvas>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset("/js/chart.js")}}"></script>
    <script>
        $(document).ready(() => {
            const $yearSelector = $('#yearSelector');
            const canvas = $('#sales');

            let yearValueString = "";
            const currentYear = new Date().getFullYear();
            for (let i = 1995; i < currentYear; i++) {
                yearValueString += `<option value="${i}" >${i}</option>`
            }
            yearValueString += `<option value="${currentYear}" selected>${currentYear}</option>`;

            $yearSelector.html(yearValueString);

            function displayTrend(year){
                $.ajax({
                    method: "GET",
                    url: "{{url("/admin/products") . "/". $product->item_code . "/sale"}}",
                    data: {
                        year: year
                    },
                    success: function(response){
                        const months = ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'];
                        const sales = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                        response.forEach(function (item){
                            sales[item['month'] - 1] = item['qty_sum'];
                        });

                        const myChart = new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: "Sale for year " + year,
                                    data: sales,
                                    borderColor: 'rgba(74,192,132,1)',
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            min: 0,
                                            stepSize: 1
                                        }
                                    }]
                                }
                            }
                        });



                    }
                })
            }

            displayTrend(currentYear);

            $yearSelector.on('change', function(){
               const year = $yearSelector.val();
               displayTrend(year);
            });
        });
    </script>
@endpush
