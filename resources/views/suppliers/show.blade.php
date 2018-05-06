@extends('adminlte::page')
@section('title', "Supplier")
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
                <h4>Supplier Details</h4>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#details">Details</a></li>
                        <li><a id="trendingLink" data-toggle="tab" href="#purchases">Sales</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="details">

                            <div class="col-lg-5 col-xs-offset-1">
                                <br>
                                <br>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Name: </label>
                                    <span class="text-black">{{$customer->name}}</span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Email: </label>
                                    <span class="text-black">{{$customer->email}}</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Contact No: </label>
                                    <span class="text-black">{{$customer->contact_no}}</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Address: </label>
                                    <span class="text-black">{{$customer->address}}</span>
                                </div>

                            </div>

                            <div class="col-lg-5">
                                <label for="yearSelector">Year: </label>
                                <select name="yearSelector" id="yearSelector">

                                </select>

                                <canvas id="sales" height="400px" width="500px"></canvas>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="purchases">
                            <table id="saleOrderList" class="table table-responsive">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Invoice</th>
                                    <th>Ref</th>
                                    <th>User</th>
                                    {{--<th>Supplier</th>--}}
                                    <th>No. of Items</th>
                                    <th>Amount</th>
                                    {{--<th>Due</th>--}}
                                    <th>Date</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td></td>
                                        <td>{{$order->id}}</td>
                                        <td>{{$order->ref}}</td>
                                        <td>{{$order->user->name}}</td>
                                        {{--<td>{{$order->customer->name}}</td>--}}
                                        <td>{{$order->purchaseOrderItems->count()}}</td>
                                        <td>{{$order->amount()}}</td>
                                        {{--<td>{{$order->due}}</td>--}}
                                        <td>{{$order->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
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

            let table = $('#saleOrderList').DataTable({
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [[1, 'asc']]
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            let yearValueString = "";
            const currentYear = new Date().getFullYear();
            for (let i = 1995; i < currentYear; i++) {
                yearValueString += `<option value="${i}" >${i}</option>`
            }
            yearValueString += `<option value="${currentYear}" selected>${currentYear}</option>`;

            $yearSelector.html(yearValueString);

            function displayTrend(year) {
                console.log("trend");
                $.ajax({
                    method: "GET",
                    url: "{{url("/admin/suppliers") . "/". $customer->id . "/sale"}}",
                    data: {
                        year: year
                    },
                    success: function (response) {
                        console.log(response);
                        const months = ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'];
                        const sales = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                        response.forEach(function (item) {
                            sales[item['month'] - 1] = item['_count'];
                        });

                        const myChart = new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: "Purchase for year " + year,
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

            $yearSelector.on('change', function () {
                const year = $yearSelector.val();
                displayTrend(year);
            });
        });
    </script>
@endpush
