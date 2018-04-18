@extends('adminlte::page')
@section('title', "customers")
@section('content_header')
    <h1>Customer List</h1>
@endsection

@section('content')
    <div id="addCustomerModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Customer</h4>
                </div>
                <form action="{{url('/admin/customers')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-body">
                    <input type="text" name="name" id="customerNameInput" placeholder="Enter Customer Name" class="form-control"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    {{--Modal Dialog for editing Customer--}}
    <div id="editCustomerModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Customer</h4>
                </div>

                <!--The appropriate url is filled by javascript when edit button is pressed on a row. The format of url is '/admin/customers/edit/{id}'-->
                <form action="" method="post" id="customerEditForm">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the customer name on the row the button was pressed. Filled by JS.--}}
                        <label for="customerEditInput">Customer Name</label>
                        <input type="text" name="name" id="customerEditInput" placeholder="Enter Customer Name"
                               class="form-control" value=""/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <button class="btn btn-primary margin-bottom" data-toggle="modal" data-target="#addCustomerModal">Add Customer</button>
            <table id="customerList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>No. of Items</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr id="{{$customer->id}}">
                        <td></td>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->products->count()}}</td>
                        <td>
                            <button title="Edit" data-toggle="modal" data-target="#editCustomerModal"
                                    class="btn btn-primary edit"><i class="fa fa-edit"></i></button>
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
            let table = $('#CustomerList').DataTable({
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

            table.on('click', '.delete', function () {
                const id = $(this).parents('tr').attr('id');
                $.ajax({
                    method: "post",
                    url: "{{url('admin/customers/delete')}}",
                    data: {
                        "id": id,
                        "_token": "{{csrf_token()}}"
                    }
                }).done((status) => {
                    if (status === 'success') {
                        $.notify('Customer Deleted', {position: 'top center', className: 'success'});
                        table.rows(document.getElementById(id)).remove().draw();
                    }
                    else {
                        $.notify('Customer Not Deleted: ' + status, {
                            position: 'top center',
                            className: 'error'
                        });

                    }

                })
            });

            $('#editCustomerModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#CustomerEditForm').attr('action', "{{url('/admin/customers/edit')}}" + "/" + id);
                $('#CustomerEditInput').val(name);
            })



        });
    </script>
@endpush
