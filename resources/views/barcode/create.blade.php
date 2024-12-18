@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
        @if (session('pesan'))
        <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
            <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('pesan') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <form method="post" action="/store-barcode" class="form-material m-t-40" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Generate Barcode</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">Generate Barcode</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Generate Barcode</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="col-sm-12">
                                <div class="mt-4 mt-lg-0">
                                    <div class="row mb-4 field-wrapper">
                                        <label for="horizontal-password-input" class="col-sm-3 col-form-label">Work Orders</label>
                                        <div class="col-sm-9">
                                            <select class="form-select request_number2 data-select2" name="id_work_orders" id="id_work_orders">
                                                <option>Pilih WO Number</option>
                                                @foreach ($wo as $data)
                                                <option value="{{ $data->id }}" 
                                                    data-id-sales-orders="{{ $data->id_sales_orders }}" 
                                                    data-id-master-products-material="{{ $data->id_master_process_productions }}"
                                                    data-id-master-customers="{{ $data->id_master_customers }}"
                                                    data-id-master-products="{{ $data->id_master_products }}"
                                                    data-type-product-code="{{ $data->type_product_code }}"
                                                    data-group-sub-code="{{ $data->group_sub_code }}"
                                                    data-type-product="{{ $data->type_product }}"> <!-- Tambahkan ini -->
                                                    {{ $data->wo_number }} {{ $data->status }} | {{ $data->note }}
                                                </option>
                                                
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Hidden input fields -->
                                    
                                            <input type="hidden" class="form-control" id="id_sales_orders" name="id_sales_orders" value="" >
                                            <input type="hidden" class="form-control" id="id_master_process_productions" name="id_master_process_productions" value="">
                                            <input type="hidden" class="form-control" id="id_master_customers" name="id_master_customers" value="">
                                            <input type="hidden" class="form-control" id="id_master_products" name="id_master_products" value="">
                                            <input type="hidden" class="form-control" id="type_product_code" name="type_product_code" value="">
                                            <input type="hidden" class="form-control" id="group_sub_code" name="group_sub_code" value="">
                                            <input type="hidden" class="form-control" id="type_product" name="type_product" value="">

                                    <div class="row mb-4 field-wrapper">
                                        <label for="horizontal-password-input" class="col-sm-3 col-form-label">Work Center</label>
                                        <div class="col-sm-9">
                                            <select class="form-select request_number data-select2" name="id_work_centers">
                                                <option>Work Center</option>
                                                @foreach ($wc as $data)
                                                <option value="{{ $data->id }}" data-id="{{ $data->id }}">{{ $data->work_center_code }} - {{ $data->work_center }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4 field-wrapper">
                                        <label for="horizontal-password-input" class="col-sm-3 col-form-label">Pilih Shift</label>
                                        <div class="col-sm-9">
                                            <select class="form-select request_number1 data-select2" name="shift">
                                                <option>Pilih Shift</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4 field-wrapper">
                                        <label for="horizontal-firstname-input" class="col-sm-3 col-form-label">QTY</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="qty" value="" placeholder="Masukan Qty">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row left-content-end">
                                        <div class="col-sm-9">
                                            <div>
                                                <a href="/barcode" class="btn btn-info waves-effect waves-light">Back</a>
                                                <button type="submit" class="btn btn-primary w-md" name="save">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end row -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_work_orders').change(function() {
            var selectedOption = $(this).find('option:selected');
            var idSalesOrders = selectedOption.data('id-sales-orders');
            var idMasterProductsMaterial = selectedOption.data('id-master-products-material');
            var idMasterCustomers = selectedOption.data('id-master-customers');
            var idMasterProducts = selectedOption.data('id-master-products');
            var typeProductCode = selectedOption.data('type-product-code');
            var groupSubCode = selectedOption.data('group-sub-code');
            var typeProduct = selectedOption.data('type-product'); //

            // Update hidden fields
            $('#id_sales_orders').val(idSalesOrders);
            $('#id_master_process_productions').val(idMasterProductsMaterial);
            $('#id_master_customers').val(idMasterCustomers);
            $('#id_master_products').val(idMasterProducts);
            $('#type_product_code').val(typeProductCode);
            $('#group_sub_code').val(groupSubCode);
            $('#type_product').val(typeProduct); // Tambahkan
        });
    });
</script>
@endsection
