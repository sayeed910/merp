@extends('adminlte::page')
@section('title', "Supplier")
@section('content_header')
    <h1>Edit Supplier</h1>
@endsection

@section('content')
    <div class="col-lg-5 col-lg-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Supplier Details</h4>
            </div>
            <div class="box-body">
                <form action="{{url('/admin/suppliers')."/".$customer->id}}" method="post">
                    <input type="hidden" name="_method" value="PATCH">
                    {{csrf_field()}}
                    <label for="customerEditInput">Name</label>
                    <input type="text" name="name" id="customerEditInput" placeholder="Enter Supplier Name"
                           class="form-control" value="{{$customer->name}}"/>

                    <label for="customerEmailInput">Email </label>
                    <input type="email" name="email" id="customerEmailInput" placeholder="Enter Supplier Email"
                           class="form-control" value="{{$customer->email}}"/>

                    <label for="customerPhoneInput">Contact No</label>
                    <input type="text" name="contact_no" id="customerPhoneInput" placeholder="Enter Supplier Contact No."
                           class="form-control" value="{{$customer->contact_no}}"/>

                    <label for="customerAreaInput">Address</label>
                    <textarea name="address" id="customerAreaInput" cols="30" rows="3"
                              class="form-control">{{$customer->address}}</textarea>



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
    </script>
@endpush
