@extends('adminlte::page')
@section('title', "suppliers")
@section('content_header')
    <h1>Supplier List</h1>
@endsection

@section('content')
    <div id="addSupplierModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Supplier</h4>
                </div>
                <form action="{{url('/admin/suppliers')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-body">
                    <input type="text" name="name" id="supplierNameInput" placeholder="Enter Supplier Name" class="form-control"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    {{--Modal Dialog for editing Supplier--}}
    <div id="editSupplierModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Supplier</h4>
                </div>

                <!--The appropriate url is filled by javascript when edit button is pressed on a row. The format of url is '/admin/customers/edit/{id}'-->
                <form action="" method="post" id="SupplierEditForm">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the customer name on the row the button was pressed. Filled by JS.--}}
                        <label for="SupplierEditInput">Supplier Name</label>
                        <input type="text" name="name" id="SupplierEditInput" placeholder="Enter Customer Name"
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
            <button class="btn btn-primary margin-bottom" data-toggle="modal" data-target="#addSupplierModal">Add Supplier</button>
            <table id="SupplierList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>No. of Items</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($suppliers as $Supplier)
                    <tr id="{{$Supplier->id}}">
                        <td></td>
                        <td>{{$Supplier->name}}</td>
                        <td>{{$Supplier->products->count()}}</td>
                        <td>
                            <button title="Edit" data-toggle="modal" data-target="#editSupplierModal"
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
            let table = $('#supplierList').DataTable({
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
                    url: "{{url('admin/suppliers/delete')}}",
                    data: {
                        "id": id,
                        "_token": "{{csrf_token()}}"
                    }
                }).done((status) => {
                    if (status === 'success') {
                        $.notify('Supplier Deleted', {position: 'top center', className: 'success'});
                        table.rows(document.getElementById(id)).remove().draw();
                    }
                    else {
                        $.notify('Supplier Not Deleted: ' + status, {
                            position: 'top center',
                            className: 'error'
                        });

                    }

                })
            });

            $('#editSupplierModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#SupplierEditForm').attr('action', "{{url('/admin/suppliers/edit')}}" + "/" + id);
                $('#SupplierEditInput').val(name);
            })



        });
    </script>
@endpush
