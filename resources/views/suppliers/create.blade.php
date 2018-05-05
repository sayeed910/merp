@extends('adminlte::page')
@section('title', "Supplier|Add")
@section('content_header')
    <h1>New Supplier</h1>
@endsection

@section('content')
    <div class="col-lg-5 col-lg-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Supplier Details</h4>
            </div>
            <div class="box-body">
                <form action="{{url('/admin/suppliers')}}" method="post">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="nameInput">Name</label>
                        <input type="text" required id="nameInput" name="name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="emailInput">Email</label>
                        <input type="email" required id="emailInput" name="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="contactNoInput">Contact No.</label>
                        <input type="tel" required id="contactNoInput" name="contact_no" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control" id="address" cols="30" rows="3" ></textarea>
                    </div>



                    <div class="right-side pull-right">
                        <button type="submit" class="btn btn-primary right"><i class="fa fa-check"></i> SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(()=> {
            $('#brandSelect').select2({
                placeholder: "Select Brand",
                width: '100%'
            });
            $('#categorySelect').select2({
                placeholder: "Select Category",
                width: '100%'
            })
        });
    </script>
    @endpush
