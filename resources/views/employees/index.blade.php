@extends('adminlte::page')
@section('title', "Employees")
@section('content_header')
    <h1>Employee List</h1>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <a href="{{url('admin/employees/create')}}" class="btn btn-primary margin-bottom">
                Add Employee
            </a>
            <table id="employeeList" class="table table-responsive">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Contact No.</th>
                    <th>Salary</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                @foreach($employees as $employees)
                    <tr id="{{$employees->item_code}}">
                        <td></td>
                        <td>{{$employees->name}}</td>
                        <td>{{$employees->designation}}</td>
                        <td>{{$employees->contact_no}}</td>
                        <td>{{$employees->salary}}</td>
                        <td>
                            <button title="Pay" class="btn btn-github pay" id="pay"><i class="fa fa-money"></i></button>
                            <button title="Edit" class="btn btn-primary edit"><i class="fa fa-edit"></i></button>
                            <button title="Delete" class="btn btn-google delete"><i class="fa fa-trash"></i></button>
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
            let table = $('#employeeList').DataTable({
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

            $('#employeeList').on('click', '.edit', function(){
                const id = $(this).parents('tr').attr('id');
                window.location.href = "{{url('/admin/employee/edit')}}" + '/' + id;
            });
        });
    </script>
@endpush
