@extends('adminlte::page')
@section('title', "Purchase Orders")
@section('content_header')
    <h1>Purchase Order List</h1>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <a href="{{url('admin/purchase-orders/create')}}">
                <button class="btn btn-primary margin-bottom">Add Purchase Order</button>
            </a>
            <table id="purchaseOrderList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Invoice</th>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Supplier</th>
                    <th>No. of Items</th>
                    <th>Amount</th>
                    <th>Due</th>
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
                        <td>{{$order->supplier->name}}</td>
                        <td>{{$order->purchaseOrderItems->count()}}</td>
                        <td>{{$order->amount()}}</td>
                        <td>{{$order->due}}</td>
                        <td>{{$order->created_at}}</td>
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
            let table = $('#purchaseOrderList').DataTable({
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
        });
    </script>
@endpush
