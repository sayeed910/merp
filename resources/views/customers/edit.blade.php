@extends('adminlte::page')
@section('title', "Customer")
@section('content_header')
    <h1>Edit Customer</h1>
@endsection

@section('content')
    <div class="col-lg-5 col-lg-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Customer Details</h4>
            </div>
            <div class="box-body">
                <form action="{{url('/admin/customers')."/".$customer->id}}" method="post">
                    <input type="hidden" name="_method" value="PATCH">
                    {{csrf_field()}}
                    <label for="customerEditInput">Name</label>
                    <input type="text" name="name" id="customerEditInput" placeholder="Enter Customer Name"
                           class="form-control" value="{{$customer->name}}"/>

                    <label for="customerEmailInput">Email </label>
                    <input type="email" name="email" id="customerEmailInput" placeholder="Enter Customer Email"
                           class="form-control" value="{{$customer->email}}"/>

                    <label for="customerPhoneInput">Contact No</label>
                    <input type="text" name="contact_no" id="customerPhoneInput" placeholder="Enter Customer Contact No."
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
