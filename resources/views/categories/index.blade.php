@extends('adminlte::page')
@section('title', "Categories")
@section('content_header')
    <h1>Category List</h1>
@endsection

@section('content')
    <div id="addCategoryModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Category</h4>
                </div>
                <form action="{{url('/admin/categories')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-body">
                    <input type="text" name="name" id="categoryNameInput" placeholder="Enter Category Name" class="form-control"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    {{--Modal Dialog for editing category--}}
    <div id="editCategoryModal" class="modal fade">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Category</h4>
                </div>

                <!--The appropriate url is filled by javascript when edit button is pressed on a row. The format of url is '/admin/customers/edit/{id}'-->
                <form action="" method="post" id="categoryEditForm">
                    <input name="_method" type="hidden" value="PATCH">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{--The value of the text box is the customer name on the row the button was pressed. Filled by JS.--}}
                        <label for="categoryEditInput">Category Name</label>
                        <input type="text" name="name" id="categoryEditInput" placeholder="Enter Customer Name"
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
            <button class="btn btn-primary margin-bottom" data-toggle="modal" data-target="#addCategoryModal">Add Category</button>
            <table id="categoryList" class="table table-responsive table-hover">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>No. of Items</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($categories as $category)
                   <tr id="{{$category->id}}">
                        <td></td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->products->count()}}</td>
                        <td>
                            <button title="Edit" data-toggle="modal" data-target="#editCategoryModal"
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
            let table = $('#categoryList').DataTable({
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

            table.on('click', '.delete', function() {
                const id = $(this).parents('tr').attr('id');
                const onDone = (status) => {
                    console.log(status);
                    if (status.success === true) {
                        $.notify('Category Deleted', {position: 'top center', className: 'success'});
                        table.rows(document.getElementById(id)).remove().draw();
                    }
                    else {
                        $.notify('Category Not deleted', {
                            position: 'top center',
                            className: 'error'
                        });

                    }
                };

                const deleteUrl = "{{url('admin/categories/')}}" + "/" + id;
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

            table.on('click', 'tr', function(){
                console.log((this));
            });

            $('#editCategoryModal').on('show.bs.modal', function (e) {
                const row = $(e.relatedTarget).parents('tr');
                const id = row.attr('id');
                const name = row.children('td:eq(1)').text();
                $('#categoryEditForm').attr('action', "{{url('/admin/categories/')}}" + "/" + id);
                $('#categoryEditInput').val(name);
            })



        });
    </script>
@endpush
