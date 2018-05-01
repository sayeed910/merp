@extends('adminlte::page')
@section('title', "Purchase Orders")
@section('content_header')
    <h1>New Purchase Order</h1>
@endsection

@section('content')

    <div class="col-lg-8">
        <div class="box box-primary">
            <div class="box-body">

                <div class="form-container" >
                    <div class="col-lg-6 form-group">
                        <select name="product" id="productSelect" class="">
                            <option></option>
                            @foreach($products as $product)
                                <option value="{{$product->item_code}}">{{$product->description()}}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-bitbucket" id="btnProductSelect"><i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>
                    <form class="form-horizontal col-lg-6" style="float: right; margin-right: 0;">
                        <label for="ref" class="control-label col-sm-2">Ref: </label>
                        <input type="text" class="col-sm-10" name="ref" id="ref"/>
                    </form>

                    <br>
                    <table id="purchaseOrderList" class="table table-responsive">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>

                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <select name="supplier form-control" id="supplierSelect" style="width: 100%">
                <option></option>
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="box box-primary">
            <form action="#" class="form-horizontal">
                <div class="box-body  ">
                    <div class="form-group">
                        <label for="amount" class="control-label col-sm-2">Amount: </label>
                        <div class="col-sm-10">
                            <input type="number" name="amount" id="amount" value="0" class="form-control bg-white"
                                   readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vat" class="control-label col-sm-2">VAT: </label>
                        <div class="col-sm-10">
                            <input type="number" name="vat" id="vat" value="0" class="form-control"/>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="total" class="control-label col-sm-2">Total: </label>
                        <div class="col-sm-10">
                            <input type="number" name="total" id="total" value="0" readonly
                                   class="bg-white form-control bg-white"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="box box-primary">
            <div class="box-body form-horizontal">
                <div class="form-group">
                    <label for="paid" class="control-label col-sm-2">Paid: </label>
                    <div class="col-sm-10">
                        <input type="number" name="paid" id="paid" value="0"
                               class="form-control "/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="due" class="control-label col-sm-2">Due: </label>
                    <div class="col-sm-10">
                        <input type="number" name="due" id="due" value="0" readonly
                               class="bg-white form-control bg-white"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="return" class="control-label col-sm-2">Return: </label>
                    <div class="col-sm-10">
                        <input type="number" name="return" id="return" value="0" readonly
                               class="bg-white form-control bg-white"/>
                    </div>
                </div>

            </div>
        </div>
        <div>
            <button class="btn btn-linkedin form-control" id="submit"><i class="fa fa-check"></i> SUBMIT</button>
        </div>
    </div>
@endsection
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('js/notify.js')}}"></script>
@endpush
@push('js')
    <script>
        $(document).ready(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            $('#productSelect').select2({placeholder: "Select Product"});
            $('#supplierSelect').select2({
                placeholder: "Select Supplier"
            });



            let table = $('#purchaseOrderList').DataTable({
                columns: [
                    {data: "SN"},
                    {data: "item_code"},
                    {data: "description"},
                    {data: "unit"},
                    {data: "qty"},
                    {data: "price"},
                    {data: "total"},
                    {
                        render: function(data, type, full, meta){
                           return '<button title="Delete" class="btn btn-google delete"><i class="fa fa-trash"></i>\n' +
                                '</button>'
                        }
                    }
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }, {
                    className: "qty",
                    "targets": 4


                }],
                "order": [[1, 'asc']]
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            table.on('click', '.delete', function(){
               table.rows($(this).parents('tr')).remove().draw();
               calculateAmount();
            });



            $('#submit').on('click', () => {
                //TODO: Validation

                let order = {products: []};
                const data = table.rows().data();
                for (let i = 0; i < data.length; i++) {
                    order.products.push({
                        item_code: data[i]['DT_RowId'],
                        qty: parseInt(data[i]['qty']),
                        price: parseFloat(data[i]['price'])
                    });
                }
                order.vat = parseFloat($('#vat').val());
                order.due = parseFloat($('#due').val());
                order.supplierId = $('#supplierSelect').val();
                order.ref = $('#ref').val();

                console.log(order);
                $.ajax({
                    'url': "{{url("/admin/purchase-orders")}}",
                    'method': 'POST',
                    data: order
                }).done(function (response) {
                    $.notify("Purchase Order Created Successfully", {position: 'top center', className: 'success'});
                    setTimeout(function () {
                        window.location.href = "{{url("/admin/purchase-orders")}}";
                    }, 1000);
                    console.log(response);
                }).fail(function () {
                    $.notify("Could not save purchase order", {position: 'top center', className: 'error'});
                });
            });

            function calculateTotal() {
                const vat = ($('#vat').val());
                if (isNaN(vat)) return;
                const amount = parseFloat($('#amount').val());

                $('#total').val((amount * vat / 100.0) + amount).change();
            }

            function calculateAmount() {
                const data = table.rows().data();

                let total = 0;
                for (let i = 0; i < data.length; i++)
                    total += data[i]['total'];
                $('#amount').val(total).change();
            }

            function calculateDueAndReturn() {
                console.log("called");
                let paid = $('#paid').val();
                if (isNaN(paid)) paid = 0;
                const total = $('#total').val();

                //has due
                if (total > paid) {
                    $('#due').val(total - paid);
                    $('#return').val(0);
                } else { //money has to be returned
                    $('#due').val(0);
                    $('#return').val(paid - total);

                }
            }

            $('#vat').on('input paste change', calculateTotal);
            $('#amount').on('input paste change', calculateTotal);

            $('#total').on('change', calculateDueAndReturn);
            $('#paid').on('input paste change', calculateDueAndReturn);

            let btnSelect = document.getElementById('btnProductSelect');


            function updateQty(row, newQty) {
                let data = (row.data());
                data['qty'] = newQty;
                const price = data['price'];
                data['total'] = data['qty'] * price;
                row.data(data).draw();
                calculateAmount();
            }

            function incrementQty(tr) {
                const row = table.row(tr);
                const newQty = parseInt(row.data()['qty']) + 1;
                updateQty(row, newQty);
            }

            btnSelect.addEventListener('click', () => {
                let productId = document.getElementById('productSelect').value;
                const tr = document.getElementById(productId);
                if (tr !== null) {
                    incrementQty(tr);
                    return;
                }
                // url: localhost/supertech/public/admin/products/{id}
                $.post("{{url('/admin/products/')}}" + "/" + productId, (data) => {
                    let product = {
                        description: `${data['name']} ${data['brand']} ${data['size']}`,
                        item_code: data['item_code'],
                        unit: data['unit'],
                        price: data['price'],
                        total: data['price'],
                        qty: 1,
                        SN: 0,
                        DT_RowId: data['item_code']
                    };
                    table.row.add(product).draw();


                    $('.qty').unbind().on('click', function () {
                        console.log("new qty");
                        let newQty = prompt("Enter Quantity:");
                        if (newQty == null || isNaN(newQty))
                            return;
                        const row = table.row($(this).parent('tr')[0]);
                        updateQty(row, newQty);


                    });

                    calculateAmount();

                });

                console.log(productId);
            });
        });
    </script>
@endpush
