@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Certificate Of Analysis (COA)</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Marketing</a></li>
                                <li class="breadcrumb-item active">Certificate Of Analysis (COA)</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-block-helper label-icon"></i><strong>Failed</strong> - {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-alert-outline label-icon"></i><strong>Warning</strong> - {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-alert-circle-outline label-icon"></i><strong>Info</strong> - {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row pb-3">
                <div class="col-12">
                    <a href=""
                        class="btn btn-primary waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> Back To List Data Order Confirmation
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                      <form action="{{ route('coa.store') }}" method="POST">
                        @csrf
                            <div class="card-header">
                                <i class="mdi mdi-file-multiple-outline label-icon"></i> Certificate Of Analysis (COA)
                            </div>

                            <div class="card-body">
                                <div class="mt-4 mt-lg-0">
                                    <dl class="row">    
                                        <dt class="col-sm-3"><label>No COA</label></dt>
                                        <dd class="col-sm-9">: </dd>
                                        
                                        <dt class="col-sm-3"><label>Date</label></dt>
                                        <dd class="col-sm-9">: {{ \Carbon\Carbon::parse($orderConfirmation->date)->isoFormat('dddd, D MMMM YYYY', 'ID') }}</dd>
                                        
                                        <dt class="col-sm-3"><label>Customer</label></dt>
                                        <dd class="col-sm-9">: {{ $orderConfirmation->customer }}</dd>
                                        
                                        <dt class="col-sm-3"><label>No Po</label></dt>
                                        <input type="hidden" name="no_ko" value="{{ $orderConfirmation->id_order_confirmations }}">
                                        <dd class="col-sm-9">: {{ $orderConfirmation->id_order_confirmations }}</dd>
                                        
                                        <dt class="col-sm-3"><label>Brand Name</label></dt>
                                        <dd class="col-sm-9">: {{ $orderConfirmation->description }}</dd>
                                        
                                        <dt class="col-sm-3"><label>Thickness</label></dt>
                                        <dd class="col-sm-9">: </dd>
                                        
                                        <dt class="col-sm-3"><label>Color</label></dt>
                                        <dd class="col-sm-9">: 
                                            <input type="radio" name="colour" value="Transparan" id="colour_transparan">
                                            <label for="colour_transparan">Transparan</label>
                                            <input type="radio" name="colour" value="Printing" id="colour_printing">
                                            <label for="colour_printing">Printing</label>
                                        </dd>
                                        
                                        <dt class="col-sm-3"><label>Material</label></dt>
                                        <dd class="col-sm-9">: 
                                            <input type="radio" name="material" value="Polyolefin" id="material_polyolefin">
                                            <label for="material_polyolefin">Polyolefin</label>
                                            <input type="radio" name="material" value="Polypropylene" id="material_polypropylene">
                                            <label for="material_polypropylene">Polypropylene</label>
                                        </dd>
                                        
                                        <dt class="col-sm-3"><label>Machine No</label></dt>
                                        <dd class="col-sm-9">: 
                                            <select class="form-select request_number data-select2" name="id_work_centers" style="max-width: 300px;">
                                                <option>Work Center</option>
                                                @foreach ($wc as $data)
                                                <option value="{{ $data->id }}" data-id="{{ $data->id }}">{{ $data->work_center_code }} - {{ $data->work_center }}</option>
                                                @endforeach
                                            </select>
                                        </dd>
                                        
                                        <dt class="col-sm-3"><label>Perforasi</label></dt>
                                        <dd class="col-sm-9">: {{ $orderConfirmation->perforasi ?? '-' }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table>
                                            <tr>
                                                <th>Characteristic</th>
                                                <th>Test Method</th>
                                                <th>Unit</th>
                                                <th>Standard</th>
                                                <th>Sample</th>
                                            </tr>
                                            <tr>
                                                <td>Colour</td>
                                                <td>Unprinted</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Thickness</td>
                                                <td>-</td>
                                                <td>µ</td>
                                                <td>15 (±3)</td>
                                                <td><input type="text" name="thickness" placeholder="15.2" required></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">Tensile Strength
                                                    <br> -MD
                                                    <br> -TD
                                                </td>
                                                <td>ASTM D-882</td>
                                                <td>N/mm²</td>
                                                <td>min 100</td>
                                                <td><input type="text" name="tensile_strength_md" placeholder="143.47" required></td>
                                            </tr>
                                            <tr>
                                                <td>ASTM D-882</td>
                                                <td>N/mm²</td>
                                                <td>min 100</td>
                                                <td><input type="text" name="tensile_strength_td" placeholder="155.53" required></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">Shrinkage
                                                    <br> -MD
                                                    <br> -TD
                                                </td>
                                                <td>ASTM D-1204</td>
                                                <td>%</td>
                                                <td>min 60</td>
                                                <td><input type="text" name="shrinkage_md" placeholder="68" required></td>
                                            </tr>
                                            <tr>
                                                <td>ASTM D-1204</td>
                                                <td>%</td>
                                                <td>min 60</td>
                                                <td><input type="text" name="shrinkage_td" placeholder="70" required></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">Elongation
                                                    <br> -MD
                                                    <br> -TD
                                                </td>
                                                <td>ASTM D-882</td>
                                                <td>%</td>
                                                <td>min 90</td>
                                                <td><input type="text" name="elongation_md" placeholder="116.37" required></td>
                                            </tr>
                                            <tr>
                                                <td>ASTM D-882</td>
                                                <td>%</td>
                                                <td>min 90</td>
                                                <td><input type="text" name="elongation_td" placeholder="120.02" required></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">COF
                                                    <br> -Static
                                                    <br> -Kinetic
                                                </td>
                                                <td>ASTM D-1894-01</td>
                                                <td>-</td>
                                                <td>0.1 - 0.4</td>
                                                <td><input type="text" name="cof_static" placeholder="0.23" required></td>
                                            </tr>
                                            <tr>
                                                <td>ASTM D-1894-01</td>
                                                <td>-</td>
                                                <td>0.1 - 0.4</td>
                                                <td><input type="text" name="cof_kinetic" placeholder="0.14" required></td>
                                            </tr>
                                        </table>
                                        <br><center>
                                        <button class="btn btn-primary" type="submit">
                                          <i class="bx bxs-paper-plane"></i> Submit</button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
<style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    input[type="text"] {
      width: 100%;
      box-sizing: border-box;
    }
    .left-align {
      text-align: left;
    }
    .center-align {
      text-align: center;
    }
</style>
@endsection

