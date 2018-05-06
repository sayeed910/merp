@extends('adminlte::page')
@section('title', "Customers")
@section('content_header')
    <h1>Customer List</h1>
@endsection

@section('content')
    {{--Modal Dialog for adding new customer--}}
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
                        <input type="text" name="name" id="customerNameInput" placeholder="Enter Customer Name"
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

    {{--Modal dialog for editing existing customer--}}

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
                    <input type="hidden" name="_method" value="PATCH">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the customer name on the row the button was pressed. Filled by JS.--}}
                        <label for="customerEditInput">Name</label>
                        <input type="text" name="name" id="customerEditInput" placeholder="Enter Customer Name"
                               class="form-control" value=""/>

                        <label for="customerEmailInput">Email </label>
                        <input type="email" name="name" id="customerEmailInput" placeholder="Enter Customer Email"
                               class="form-control" value=""/>

                        <label for="customerPhoneInput">Contact No</label>
                        <input type="text" name="name" id="customerPhoneInput" placeholder="Enter Customer Contact No."
                               class="form-control" value=""/>

                        <label for="customerAreaInput">Address</label>
                        <textarea name="address" id="customerAreaInput" cols="30" rows="3"
                                  class="form-control"></textarea>

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
            <a class="btn btn-primary margin-bottom" href="{{url("/admin/customers/create")}}">Add
                Customer
            </a>
            <table id="customerList" class="table table-responsive">
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
                @foreach($customers as $customer)
                    <tr id="{{$customer->id}}">
                        <td></td>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->contact_no}}</td>
                        <td>{{$customer->address}}</td>
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
    <script src="{{asset("js/notify.js")}}"></script>
@endpush
@push('js')
    <script>
        $(document).ready(() => {
            const customerTable = $('#customerList');
            let table = customerTable.DataTable({
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

            table.on('click', 'tr', function () {
                const id = $(this).attr('id');
                window.location.href = "{{url("/admin/customers/")}}" + `/${id}/view`;
            });

            table.on('click', '.edit', function(){
                console.log("edit clicked");
                const id = $(this).parents('tr').attr('id');
                window.location.href = "{{url('/admin/customers/')}}" + '/' + id + '/edit';
                return false;
            });


            table.on('click', '.delete', function () {
                const id = $(this).parents('tr').attr('id');
                $.ajax({
                    method: "delete",
                    url: "{{url('admin/customers/')}}" + `/${id}`,
                    data: {
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

                });

                return false;
            });

            $('#editCustomerModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#customerEditForm').attr('action', "{{url('/admin/customers/edit')}}" + "/" + id);
                $('#customerEditInput').val(name);
                return false;
            })
        });
    </script>
@endpush
