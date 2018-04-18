@extends('adminlte::page')
@section('title', "Product")
@section('content_header')
    <h1>Edit Product</h1>
@endsection

@section('content')
    <div class="col-lg-5 col-lg-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Product Details</h4>
            </div>
            <div class="box-body">
                <form action="{{url('/admin/products')."/".$product->item_code}}" method="post">
                    <input type="hidden" name="_method" value="PATCH">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="nameInput">Name</label>
                        <input type="text" id="nameInput" name="name" class="form-control" value="{{$product->name}}"/>
                    </div>
                    <div class="form-group">
                        <label for="sizeInput">Size</label>
                        <input type="text" id="sizeInput" name="size" class="form-control" value="{{$product->size}}"/>
                    </div>
                    <div class="form-group">
                        <label for="brandSelect">Brand</label>
                        <select name="brand_id" id="brandSelect" class="form-control">
                            <option></option>
                            @foreach($brands as $brand)
                                @if($brand->id == $product->brand->id)
                                    <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                                @else
                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categorySelect">Category</label>
                        <select name="category_id" id="categorySelect" class="form-control">
                            <option></option>
                            @foreach($categories as $category)
                                @if($category->id == $product->category->id)
                                    <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                @else
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unitInput">Unit</label>
                        <input type="text"  id="unitInput" name="unit" class="form-control" value="{{$product->unit}}"/>
                    </div>
                    <div class="form-group">
                        <label for="costInput">Cost</label>
                        <input type="number" step=".010" id="costInput" name="cost" class="form-control" value="{{$product->cost}}"/>
                    </div>
                    <div class="form-group">
                        <label for="priceInput">Price</label>
                        <input type="number" step=".010" id="priceInput" name="price" class="form-control" value="{{$product->price}}"/>
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
