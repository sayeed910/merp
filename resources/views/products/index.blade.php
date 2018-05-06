@extends('adminlte::page')
@section('title', "Products")
@section('content_header')
    <h1>Product List</h1>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#listing">Listing</a></li>
                    <li><a id="trendingLink" data-toggle="tab" href="#trending">Trending</a></li>
                </ul>

                <div class="tab-content">
                    <div id="listing" class="tab-pane fade in active">
                        <a href="{{url('admin/products/create')}}">
                            <button class="btn btn-primary margin-bottom">Add Product</button>
                        </a>
                        <table id="productList" class="table table-responsive">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>ItemCode</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Size</th>
                                <th>Unit</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Damaged</th>
                                <th></th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr id="{{$product->item_code}}">
                                    <td></td>
                                    <td>{{$product->item_code}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->brand->name}}</td>
                                    <td>{{$product->category->name}}</td>
                                    <td>{{$product->size}}</td>
                                    <td>{{$product->unit}}</td>
                                    <td>{{$product->cost}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>{{$product->stock}}</td>
                                    <td>{{$product->damaged}}</td>
                                    <td>
                                        <button title="Edit" class="btn btn-primary edit"><i class="fa fa-edit"></i>
                                        </button>
                                        <button title="Delete" class="btn btn-google delete"><i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div id="trending" class="tab-pane fade">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="0">Monthly</option>
                            <option value="1">Yearly</option>
                        </select>
                        <select name="years" class="year"></select>
                        <select name="months" class="month"></select>
                        <button id="generateButton" class="btn btn-bitbucket">Generate</button>

                        <div>
                            <canvas id="top10graph"></canvas>
                        </div>
                    </div>
                    <div id="Contribution" class="tab-pane fade">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="0">Monthly</option>
                            <option value="1">Yearly</option>
                        </select>
                        <select name="years" id="cyears"></select>
                        <select name="months" id="cmonths"></select>
                        <button id="cgenerateButton" class="btn btn-bitbucket">Generate</button>

                        <div>
                            <canvas id="contributionGraph"></canvas>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset("/js/chart.js")}}"></script>
    <script src="{{asset("js/products/index.js")}}"></script>
    <script>
        $(document).ready(() => {
            const rangeSelector = $('#type');
            const yearSelector = $('#years');
            const monthSelector = $('#months');
            const trendingLink = $('#trendingLink');
            const generateButton = $('#generateButton');

            const crangeSelector = $('#ctype');
            const cyearSelector = $('#cyears');
            const cmonthSelector = $('#cmonths');
            const cgenerateButton = $('#cgenerateButton');
            const contributionGraph = $('#contributionGraph');

            const table = $('#productList').DataTable({
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

            table.on('click', '.edit', function () {
                console.log("edit clicked");
                const id = $(this).parents('tr').attr('id');
                window.location.href = "{{url('/admin/products/')}}" + '/' + id + '/edit';
                return false;
            });

            table.on('click', 'tr', function(){
                const id = $(this).attr('id');
               window.location.href = "{{url("/admin/products/")}}" + `/${id}/view`;
            });

            table.on('click', '.delete', function () {

                const id = $(this).parents('tr').attr('id');
                const onDone = (status) => {
                    console.log(status);
                    if (status.success === 'true') {
                        $.notify('Product Deleted', {position: 'top center', className: 'success'});
                        table.rows(document.getElementById(id)).remove().draw();
                    }
                    else {
                        $.notify('Product Not deleted', {
                            position: 'top center',
                            className: 'error'
                        });

                    }
                    return false;
                };

                const deleteUrl = "{{url('admin/products/')}}" + "/" + id;
                console.log(deleteUrl);
                const requestData = {
                    method: "delete",
                    url: deleteUrl,
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}"
                    }
                };
                $.ajax(requestData).done(onDone);
                console.log(requestData.url);
            });

            //Charting
            const chartOptions = {
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            };
            function getLabelsAndDatas(response){
                let labelsAndDatas = {
                    labels : [],
                    datas : [],
                };

                const products = response['products'];
                if (! products) return null;
                products.forEach(function(product){
                    labelsAndDatas.labels.push(product['name']);
                    labelsAndDatas.datas.push(product['sale_count']);
                });

                return labelsAndDatas;

            }
            const top10graph = new GraphMaker("trending", "{{url("/admin/products/top10")}}", "horizontalBar", chartOptions);
            top10graph.makeGraph(getLabelsAndDatas, "Top 10 Products");
            generateButton.on('click', function(){
               top10graph.makeGraph(getLabelsAndDatas, "Top 10 products");
            });

        });
    </script>
@endpush
