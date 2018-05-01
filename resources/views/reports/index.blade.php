@extends('adminlte::page')
@section('title', "Reports")
@section('content_header')
    <h1>Reports</h1>
@endsection

@section('content')
    <div class="box box-primary " style="height: 480px; overflow-y: auto">
        <div class="box-body">
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


            <label for="from">From</label>
            <input type="text" id="from" name="from">
            <label for="to">to</label>
            <input type="text" id="to" name="to">
            <input type="button" class="btn btn-primary" value="Generate" id="generateButton"/>

            <div class="nav-tabs-custom">
                <br>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#purchase">Purchase</a></li>
                    <li><a data-toggle="tab" href="#sale">Sale</a></li>
                </ul>

                <div class="tab-content">
                    <div id="purchase" class="tab-pane fade in active">
                        <div class="col-lg-6">
                            <table id="purchaseTable" class="table table-responsive">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Invoice</th>
                                    <th>Supplier</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>

                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                        <div class="col-lg-4">
                            <span id="purchaseCount"></span><br>
                            <canvas id="myChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                    <div id="sale" class="tab-pane fade">
                        <div class="col-lg-6">
                            <table id="saleTable" class="table table-responsive">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>

                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                        <div class="col-lg-4">
                            <span id="saleCount"></span><br>
                            <canvas id="saleChart" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection



@push('js')
    <script src="{{asset("/js/jquery_ui.js")}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script>
        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };

        function getDates(startDate, stopDate) {
            var dateArray = [];
            var currentDate = startDate;
            while (currentDate <= stopDate) {
                dateArray.push(new Date (currentDate));
                currentDate = currentDate.addDays(1);
            }
            return dateArray;
        }
    </script>
    <script>
        $(document).ready(() => {
            let fromDate, toDate, purchases, sales;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            let table = $('#purchaseTable').DataTable({

                columns: [
                    {data: "SN"},
                    {data: "invoice"},
                    {data: "supplier"},
                    {data: "amount"},
                    {data: "date"},
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },],
                "order": [[1, 'asc']]
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            let saleTable = $('#saleTable').DataTable({

                columns: [
                    {data: "SN"},
                    {data: "invoice"},
                    {data: "customer"},
                    {data: "amount"},
                    {data: "date"},
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },],
                "order": [[1, 'asc']]
            });

            saleTable.on('order.dt search.dt', function () {
                saleTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();


            var dateFormat = "mm/dd/yy",
                from = $("#from")
                    .datepicker({
                        changeMonth: true,
                        numberOfMonths: 1
                    })
                    .on("change", function () {
                        fromDate = getDate(this);
                        to.datepicker("option", "minDate", fromDate);
                    }),
                to = $("#to").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1
                })
                    .on("change", function () {
                        toDate = getDate(this);
                        from.datepicker("option", "maxDate", toDate);
                    });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }

            $("#generateButton").on('click', function () {
                fromDate = $.datepicker.formatDate('yy-mm-dd', new Date(fromDate));
                toDate = $.datepicker.formatDate('yy-mm-dd', new Date(toDate));

                $.ajax({
                    method: "POST",
                    url: "{{url("/admin/reports/purchase")}}",
                    data: {
                        date1: fromDate,
                        date2: toDate
                    },
                    success: function (response) {
                        purchases = response;
                        purchasedate = [];
                        purchasecount = [];

                        let range = getDates(new Date(fromDate), new Date(toDate));
                        // console.log(range);

                        for (date of range){
                            purchasedate.push($.datepicker.formatDate("yy-mm-dd", new Date(date)));
                            purchasecount.push(0);
                        }

                        let cost = 0;
                        response.forEach(function (order) {
                            let neworder = {
                                invoice: order['id'],
                                supplier: order['supplier']['name'],
                                amount: order['amount'],
                                date: order['created_at'],
                                SN: 0,
                                DT_RowId: order['id']
                            };
                            const formattedDate = $.datepicker.formatDate("yy-mm-dd", new Date(order['created_at']));
                            const index = $.inArray(formattedDate, purchasedate);
                            purchasecount[index]++;
                            cost += parseFloat(order['amount']);

                            table.row.add(neworder).draw();

                            $('#purchaseCount').text("Total Purchase: " + purchases.length + "\nTotal Cost: " + cost);


                        });

                        for (i in purchasedate){
                            console.log(purchasedate[i], purchasecount[i]);
                        }


                        var ctx = document.getElementById("myChart");
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: purchasedate,
                                datasets: [{
                                    label: '# of Purchases',
                                    data: purchasecount,
                                    backgroundColor: 'rgba(255,99,132,1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{url("/admin/reports/sale")}}",
                    data: {
                        date1: fromDate,
                        date2: toDate
                    },
                    success: function (response) {
                        sales = response;
                        saledate = [];
                        salecount = [];

                        let range = getDates(new Date(fromDate), new Date(toDate));
                        // console.log(range);

                        for (date of range){
                            saledate.push($.datepicker.formatDate("yy-mm-dd", new Date(date)));
                            salecount.push(0);
                        }

                        let cost = 0;
                        response.forEach(function (order) {
                            let neworder = {
                                invoice: order['id'],
                                customer: order['customer']['name'],
                                amount: order['amount'],
                                date: order['created_at'],
                                SN: 0,
                                DT_RowId: order['id']
                            };
                            const formattedDate = $.datepicker.formatDate("yy-mm-dd", new Date(order['created_at']));
                            const index = $.inArray(formattedDate, saledate);
                            salecount[index]++;
                            cost += parseFloat(order['amount']);
                            saleTable.row.add(neworder).draw();

                            $('#saleCount').text("Total Sale: " + sales.length + "\nTotal Income: " + cost);
                        });

                        for (i in saledate){
                            console.log(saledate[i], salecount[i]);
                        }


                        var ctx = document.getElementById("saleChart");
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: saledate,
                                datasets: [{
                                    label: '# of Sales',
                                    data: salecount,
                                    backgroundColor: 'rgba(255,99,132,1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    }
                })
            })

        });
    </script>
@endpush
