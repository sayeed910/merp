@extends('adminlte::page')
@section('title', "Brands")
@section('content_header')
    <h1>Brand List</h1>
@endsection

@section('content')
    <div id="addBrandModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Brand</h4>
                </div>
                <form action="{{url('/admin/brands')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-body">
                    <input type="text" name="name" id="brandNameInput" placeholder="Enter Brand Name" class="form-control"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    {{--Modal Dialog for editing brand--}}
    <div id="editBrandModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Brand</h4>
                </div>

                <!--The appropriate url is filled by javascript when edit button is pressed on a row. The format of url is '/admin/customers/edit/{id}'-->
                <form action="" method="post" id="brandEditForm">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the customer name on the row the button was pressed. Filled by JS.--}}
                        <label for="brandEditInput">Brand Name</label>
                        <input type="text" name="name" id="brandEditInput" placeholder="Enter Customer Name"
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
            <button class="btn btn-primary margin-bottom" data-toggle="modal" data-target="#addBrandModal">Add Brand</button>
            <table id="brandList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>No. of Items</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr id="{{$brand->id}}">
                        <td></td>
                        <td>{{$brand->name}}</td>
                        <td>{{$brand->products->count()}}</td>
                        <td>
                            <button title="Edit" data-toggle="modal" data-target="#editBrandModal"
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
            let table = $('#brandList').DataTable({
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
                    url: "{{url('admin/brands/delete')}}",
                    data: {
                        "id": id,
                        "_token": "{{csrf_token()}}"
                    }
                }).done((status) => {
                    if (status === 'success') {
                        $.notify('Brand Deleted', {position: 'top center', className: 'success'});
                        table.rows(document.getElementById(id)).remove().draw();
                    }
                    else {
                        $.notify('Brand Not Deleted: ' + status, {
                            position: 'top center',
                            className: 'error'
                        });

                    }

                })
            });

            $('#editBrandModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#brandEditForm').attr('action', "{{url('/admin/brands/edit')}}" + "/" + id);
                $('#brandEditInput').val(name);
            })



        });
    </script>
@endpush
