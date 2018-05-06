@extends('adminlte::page')
@section('title', "Suppliers")
@section('content_header')
    <h1>Supplier List</h1>
@endsection

@section('content')
    {{--Modal Dialog for adding new supplier--}}
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
                        <input type="text" name="name" id="supplierNameInput" placeholder="Enter Supplier Name"
                               class="form-control"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{--Modal dialog for editing existing supplier--}}

    <div id="editSupplierModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Supplier</h4>
                </div>

                <!--The appropriate url is filled by javascript when edit button is pressed on a row. The format of url is '/admin/suppliers/edit/{id}'-->
                <form action="" method="post" id="supplierEditForm">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the supplier name on the row the button was pressed. Filled by JS.--}}
                        <label for="supplierEditInput">Supplier Name</label>
                        <input type="text" name="name" id="supplierEditInput" placeholder="Enter Supplier Name"
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
            <a class="btn btn-primary margin-bottom" href="{{url("/admin/suppliers/create")}}" >Add
                Supplier
            </a>
            <table id="supplierList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                    <th>Address</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($suppliers as $supplier)
                    <tr id="{{$supplier->id}}">
                        <td></td>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>{{$supplier->contact_no}}</td>
                        <td>{{$supplier->address}}</td>
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
    <script src="{{asset("js/notify.js")}}"></script>
@endpush
@push('js')
    <script>
        $(document).ready(() => {
            const supplierTable = $('#supplierList');
            let table = supplierTable.DataTable({
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

            supplierTable.on('click', '.delete', function () {
                const id = $(this).parents('tr').attr('id');
                $.ajax({
                    method: "delete",
                    url: "{{url('admin/suppliers/')}}" + `/${id}`,
                    data: {
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

            table.on('click', '.edit', function(){
                console.log("edit clicked");
                const id = $(this).parents('tr').attr('id');
                window.location.href = "{{url('/admin/suppliers/')}}" + '/' + id + '/edit';
                return false;
            });

            table.on('click', 'tr', function () {
                const id = $(this).attr('id');
                window.location.href = "{{url("/admin/suppliers/")}}" + `/${id}/view`;
            });

            $('#editSupplierModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#supplierEditForm').attr('action', "{{url('/admin/suppliers/edit')}}" + "/" + id);
                $('#supplierEditInput').val(name);
            })
        });
    </script>
@endpush
