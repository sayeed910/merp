@extends('adminlte::page')
@section('title', "Products")
@section('content_header')
    <h1>Product List</h1>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body">
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
                            <button title="Edit" class="btn btn-primary edit"><i class="fa fa-edit"></i></button>
                            <button title="Delete" class="btn btn-google delete"><i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(() => {
            let table = $('#productList').DataTable({
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

            table.on('click', '.edit', function(){
                const id = $(this).parents('tr').attr('id');
                window.location.href = "{{url('/admin/products/')}}" + '/' + id + '/edit';
            });

            table.on('click', '.delete', function() {
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
        });
    </script>
@endpush
