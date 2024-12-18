<div class="btn-group" role="group">
    <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
        <li>
            <button class="dropdown-item drpdwn-dgr" data-id-work-orders="{{ $data->id_work_orders }}" data-id-raw-materials="{{ $data->id_raw_materials }}"
                data-status="Delete WO Detail" onclick="showModal(this, 'Delete');"><span
                    class="mdi mdi-trash-can"></span>
                | Delete</button>
        </li>
        <li>
            <a class="dropdown-item drpdwn-pri"
                href="{{ route('ppic.workOrder.editWODetail', [encrypt($data->id_work_orders), encrypt($data->id_raw_materials)]) }}">
                <span class="mdi mdi-circle-edit-outline"></span> | Edit Data
            </a>

        </li>
        <li>
            <a class="dropdown-item drpdwn"
                href="{{ route('ppic.workOrder.viewWODetail', [encrypt($data->id_work_orders), encrypt($data->id_raw_materials)]) }}"><span
                    class="mdi mdi-eye"></span> | View Data</a>
        </li>
    </ul>
</div>
