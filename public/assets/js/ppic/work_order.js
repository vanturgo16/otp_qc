$(document).ready(function () {
    // In your Javascript (external .js resource or <script> tag)
    $('.data-select2').select2({
        width: 'resolve', // need to override the changed default
        theme: "classic"
    });

    $(".data-select2.read").prop("disabled", true);

    $('#startDate').change(function () {
        const date = new Date($(this).val());
        const newDate = addDays(date, 7);
        const finish_date = newDate.toISOString().split('T')[0];

        $('#finishDate').val(finish_date)
    });

    // ketika option sales order berubah
    $('#salesOrderSelect').change(function () {
        // let so_number = $(this).val();
        let so_number = $(this).select2().find(":selected").data("so-number");

        if (so_number != '') {
            // mengambil data type_product, product, qty dan unit sesuai so_number yang dipilih
            $.ajax({
                url: baseRoute + '/ppic/workOrder/get-order-detail',
                type: 'GET',
                dataType: 'json',
                data: {
                    so_number: so_number
                },
                success: function (response) {
                    // console.log(response);
                    let products = response.products

                    // Mendapatkan detail dari respons AJAX
                    let details = response.sales_order.sales_order_details;
                    $('.data-select2').select2("destroy");

                    // let idMasterTermPayment = response.order.id_master_term_payments;

                    // let isTermPaymentDisabled = response.termPayments.some(termPayment => idMasterTermPayment == termPayment.id);
                    // $('#termPaymentSelect').prop('disabled', isTermPaymentDisabled);

                    $('.typeProductSelect').val(response.sales_order.type_product);

                    // Function untuk menambahkan opsi produk ke elemen select
                    function appendProductOption(product) {
                        $perforasi = product.perforasi == null ? '-' : product.perforasi;
                        $('.productSelect').append($('<option>', {
                            value: product.id,
                            text: product.description + ' | Perforasi: ' + $perforasi
                        }));
                    }

                    // Function untuk memfilter produk berdasarkan tipe
                    function filterProductsByType(productType) {
                        // Bersihkan opsi yang ada sebelum menambahkan yang baru
                        $('.productSelect').empty();
                        $('.productSelect').append($('<option>', {
                            text: '** Please select a Product'
                        }));
                        // $('.productSelect').append('<option value="">** Please select a Product</option>');

                        // Filter dan tambahkan opsi produk sesuai dengan tipe yang dipilih
                        products.filter(function (product) {
                            return product.type_product === productType;
                        }).forEach(function (filteredProduct) {
                            appendProductOption(filteredProduct);
                        });
                    }

                    // Panggil fungsi pertama kali untuk menampilkan semua produk (jika ada)
                    filterProductsByType(response.sales_order.type_product);
                    $('.productSelect').val(response.sales_order.id_master_products)
                    $('.qty').val(response.sales_order.qty);
                    $('.unitSelect').val(response.sales_order.id_master_units);

                    // Menginisialisasi Select2 untuk baris baru
                    $('.data-select2').select2({
                        width: 'resolve', // need to override the changed default
                        theme: "classic"
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {

        }
    })

    $('#proccessProductionSelect').change(function () {
        let proccessProduction = $(this).find(':selected').data('code');

        if (proccessProduction != '') {
            $.ajax({
                url: baseRoute + '/ppic/workOrder/generate-wo-number',
                type: 'GET',
                dataType: 'json',
                data: {
                    proccessProduction: proccessProduction
                },
                success: function (response) {
                    // console.log(response);
                    $('#wo_number').val(response.code)
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#wo_number').val('')
        }
    });

    $('#editProccessProductionSelect').change(function () {
        let wo_number = $('#wo_number').val().slice(-5);
        let proccessProduction = $(this).find(':selected').data('code');
        let new_wo = 'WO' + proccessProduction + wo_number;
        $('#wo_number').val(new_wo)
    });

    $(document).on('submit', '#formWorkOrder', function (e) {
        // e.preventDefault(); // Mencegah formulir terkirim secara default

        $("select").removeAttr("disabled");
        this.submit();
    });

    var isChecked = false;
    $('#checkAllRows').click(function () {
        isChecked = !isChecked;
        $(':checkbox').prop("checked", isChecked);
    });

    $(document).on('change', '.rowCheckbox', function () {
        // $(this).closest('tr').toggleClass('table-success', this.checked);
        if (!this.checked) {
            $('#checkAllRows').prop('checked', false);
        } else {
            // Check if all checkboxes in tbody are checked
            if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
                $('#checkAllRows').prop('checked', true);
            }
        }
    });

    $(document).on('change', '#rawMaterialSelect', function () {
        let id_raw_material = $('#rawMaterialSelect').val();

        if (id_raw_material != '') {
            $.ajax({
                url: baseRoute + '/ppic/workOrder/get-raw-material',
                type: 'GET',
                dataType: 'json',
                data: {
                    id_raw_material: id_raw_material
                },
                success: function (response) {
                    // console.log(response.dataRawMaterial.id_master_units);
                    $('.data-select2').select2("destroy");
                    $('#masterUnitSelect').val(response.dataRawMaterial.id_master_units)
                    $('.data-select2').select2({
                        width: 'resolve', // need to override the changed default
                        theme: "classic"
                    });
                    $('#qty').focus();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('.data-select2').select2("destroy");
            $('#masterUnitSelect').val('')
            $('.data-select2').select2({
                width: 'resolve', // need to override the changed default
                theme: "classic"
            });
        }
    });

    // $('#table-list-wo').DataTable();

    // Event listener untuk perubahan nilai pada input status_search
    $('#status_search').on('input', function () {
        searchByStatus();
    });

    $('#modalPrintWO').on('click', function () {
        $.ajax({
            url: '/ppic/workOrder/get-data-sales-order',
            type: 'GET',
            data: {},
            success: function (response) {
                // console.log(response);
                // Bersihkan konten dropdown sebelum menambahkan opsi baru
                $('.data-select2').select2("destroy");
                $('#salesOrderSelectPrint').empty();

                // Iterasi melalui respons untuk membuat opsi dropdown
                response.forEach(function (item) {
                    // Buat elemen option
                    var option = $('<option></option>').attr('value', item.id).text(item.so_number + ' - ' + item.status);

                    // Tambahkan opsi ke dropdown
                    $('#salesOrderSelectPrint').append(option);
                });
                $('.data-select2').select2({
                    width: 'resolve', // need to override the changed default
                    theme: "classic",
                    dropdownParent: $("#printWorkOrder") 
                });
                $('#printWorkOrder').modal('show');
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#table-list-wo').css('min-height','250px');

});

const pathArray = window.location.pathname.split("/");
const segment_3 = pathArray[3];

function addDays(date, days) {
    const copy = new Date(Number(date))
    copy.setDate(date.getDate() + days)
    return copy
}

function getAllUnit() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: baseRoute + '/ppic/workOrder/get-all-unit',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                resolve(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                reject(error);
            }
        });
    });
}

function fetchProducts(selectElement) {
    let selectedType = $(selectElement).val();
    let productSelect = $('.productSelect');
    let unitSelect = $('.unitSelect');
    let options = '<option value="">** Please select a Product</option>';

    // Hanya membuat permintaan AJAX jika tipe dipilih
    if (selectedType) {
        $.ajax({
            url: baseRoute + '/ppic/workOrder/get-data-product',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: selectedType
            },
            success: function (response) {
                // Tanggapi dengan mengisi opsi produk sesuai data dari server
                if (selectedType == 'WIP') {
                    $.each(response.products, function (index, product) {
                        $perforasi = product.perforasi == null ? '-' : product.perforasi
                        options += '<option value="' + product.id + '">' + product.wip_code + ' | ' + product.description + ' | Perforasi: ' + $perforasi + '</option>';
                    });
                } else if (selectedType == 'FG') {
                    $.each(response.products, function (index, product) {
                        $perforasi = product.perforasi == null ? '-' : product.perforasi
                        options += '<option value="' + product.id + '">' + product.product_code + ' | ' + product.description + ' | Perforasi: ' + $perforasi + '</option>';
                    });
                }
                productSelect.html(options);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    } else {
        productSelect.html(options);
    }
    // Cara menggunakannya
    getAllUnit()
        .then(response => {
            // Lakukan sesuatu dengan response
            let optionsUnit = `<option value="">** Please select a Unit Proccess</option>${response.map(unit => `<option value="${unit.id}">${unit.unit_code}</option>`).join('')}`;
            // unitSelect.html(optionsUnit);
            unitSelect.html(optionsUnit);
        })
        .catch(error => {
            // Tangani kesalahan
            console.error(error);
        });
}

function fetchProductMaterials(selectElement) {
    let selectedType = $(selectElement).val();
    let productSelect = $('.productMaterialSelect');
    let unitSelect = $('.unitNeeded');
    let options = '<option value="">** Please select a Product Material</option>';

    // Hanya membuat permintaan AJAX jika tipe dipilih
    if (selectedType) {
        $.ajax({
            url: baseRoute + '/ppic/workOrder/get-data-product',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: selectedType
            },
            success: function (response) {
                // Tanggapi dengan mengisi opsi produk sesuai data dari server
                if (selectedType == 'WIP') {
                    $.each(response.products, function (index, product) {
                        $perforasi = product.perforasi == null ? '-' : product.perforasi
                        options += '<option value="' + product.id + '">' + product.wip_code + ' | ' + product.description + ' | Perforasi: ' + $perforasi + '</option>';
                    });
                } else if (selectedType == 'FG') {
                    $.each(response.products, function (index, product) {
                        $perforasi = product.perforasi == null ? '-' : product.perforasi
                        options += '<option value="' + product.id + '">' + product.product_code + ' | ' + product.description + ' | Perforasi: ' + $perforasi + '</option>';
                    });
                }
                productSelect.html(options);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    } else {
        productSelect.html(options);
    }
    // Cara menggunakannya
    getAllUnit()
        .then(response => {
            // Lakukan sesuatu dengan response
            let optionsUnit = `<option value="">** Please select a Unit Needed</option>${response.map(unit => `<option value="${unit.id}">${unit.unit_code}</option>`).join('')}`;
            // unitSelect.html(optionsUnit);
            unitSelect.html(optionsUnit);
        })
        .catch(error => {
            // Tangani kesalahan
            console.error(error);
        });
}

function fethchProductDetail(selectElement) {
    let typeProduct = $('.typeProductSelect').val();
    let selectedProductId = $(selectElement).val();

    if (selectedProductId) {
        $.ajax({
            url: baseRoute + '/ppic/workOrder/get-product-detail',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: typeProduct,
                idProduct: selectedProductId
            },
            success: function (response) {
                let qty = $('.qty');
                let unitSelect = $('.unitSelect');
                let idUnit = response.product.id_master_units;
                getAllUnit()
                    .then(response => {
                        // Lakukan sesuatu dengan response
                        let optionsUnit = `<option value="">** Please select a Unit</option>${response.map(unit => `<option value="${unit.id}"${idUnit == unit.id ? 'selected' : ''}>${unit.unit_code}</option>`).join('')}`;
                        unitSelect.html(optionsUnit);
                    })
                    .catch(error => {
                        // Tangani kesalahan
                        console.error(error);
                    });
                qty.focus();
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function fethchProductMaterialDetail(selectElement) {
    let typeProduct = $('.typeProductMaterialSelect').val();
    let selectedProductId = $(selectElement).val();

    if (selectedProductId) {
        $.ajax({
            url: baseRoute + '/ppic/workOrder/get-product-detail',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: typeProduct,
                idProduct: selectedProductId
            },
            success: function (response) {
                let qtyNeeded = $('.qtyNeeded');
                let unitSelect = $('.unitNeeded');
                let idUnit = response.product.id_master_units;
                getAllUnit()
                    .then(response => {
                        // Lakukan sesuatu dengan response
                        let optionsUnit = `<option value="">** Please select a Unit Needed</option>${response.map(unit => `<option value="${unit.id}"${idUnit == unit.id ? 'selected' : ''}>${unit.unit_code}</option>`).join('')}`;
                        unitSelect.html(optionsUnit);
                    })
                    .catch(error => {
                        // Tangani kesalahan
                        console.error(error);
                    });
                qtyNeeded.focus();
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function showModal(selectElement, actionButton = null) {
    let wo_number = $(selectElement).attr("data-wo-number");
    let id_work_orders = $(selectElement).attr("data-id-work-orders");
    let id_master_products = $(selectElement).attr("data-id-raw-materials");
    let status = $(selectElement).attr("data-status");

    let statusTitle = actionButton == 'Delete' ? 'Confirm to Delete' : ((status == 'Request' || status == 'Un Posted') ? 'Confirm to Posted' : 'Confirm to Un Posted');
    let statusLabel = actionButton == 'Delete' ? 'Are you sure you want to <b class="text-danger">delete</b> this data' : ((status == 'Request' || status == 'Un Posted') ? 'Are you sure you want to <b class="text-success">posted</b> this data?' : 'Are you sure you want to <b class="text-warning">unposted</b> this data?');
    let mdiIcon = actionButton == 'Delete' ? '<i class="mdi mdi-trash-can label-icon"></i>Delete' : ((status == 'Request' || status == 'Un Posted') ? '<i class="mdi mdi-check-bold label-icon"></i>Posted' : '<i class="mdi mdi-arrow-left-top-bold label-icon"></i>Un Posted');
    let buttonClass = actionButton == 'Delete' ? 'btn-danger' : ((status == 'Request' || status == 'Un Posted') ? 'btn-success' : 'btn-warning');
    let attrFunction = (actionButton == 'Delete' && (status != 'Delete WO Detail')) ? `bulkDeleted('${wo_number}');` :
        (status == 'Request' || status == 'Un Posted') ? `bulkPosted('${wo_number}');` :
        (status == 'Delete WO Detail') ? `deleteWODetail('${id_work_orders}', '${id_master_products}');` :
        `bulkUnPosted('${wo_number}');`;


    $('#staticBackdropLabel').text(statusTitle);
    $("#staticBackdrop .modal-body").html(statusLabel);
    $("#staticBackdrop button:last")
        .html(mdiIcon)
        .removeClass()
        .addClass(`btn waves-effect btn-label waves-light ${buttonClass}`)
        .attr('onClick', attrFunction);

    $('#staticBackdrop').modal('show');
}

// Fungsi untuk melakukan bulk update status
function showAlert(type, message) {
    const alertElement = (type === 'success') ? $('#alertSuccess') : $('#alertFail');
    alertElement.removeClass('d-none');
    $('.alertMessage').text(message);

    setTimeout(function () {
        alertElement.addClass('d-none');
    }, 3000); // Menyembunyikan setelah 3 detik (3000 milidetik)
}

// Mendapatkan nilai data-oc-number dari baris yang checkbox-nya dicentang
function getCheckedWONumbers() {
    var checkedWONumbers = [];

    $(':checkbox:checked').each(function () {
        var wo_number = $(this).data('wo-number');
        if (wo_number !== undefined) {
            checkedWONumbers.push(wo_number);
        }
    });

    return checkedWONumbers;
}

function bulkPosted(wo_number = null) {
    let arr_wo_number = [wo_number];
    var selectedWONumbers = (wo_number != null && wo_number != 'undefined') ? arr_wo_number : getCheckedWONumbers();

    if ((selectedWONumbers.length > 0) || (wo_number != null && wo_number != 'undefined')) {
        $.ajax({
            url: '/ppic/workOrder/bulk-posted',
            type: 'POST',
            data: {
                wo_numbers: selectedWONumbers,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // showAlert(response.type, response.message);
                // Tampilkan pesan alert sesuai dengan jenis pesan
                if (response.type === 'success') {
                    showAlert('success', response.message);
                } else if (response.type === 'error') {
                    showAlert('error', response.error);
                }
                // if (segment_3 == undefined || segment_3 != 'work-order-list') {
                refreshDataTable();
                // } else if (segment_3 == 'work-order-list') {
                // window.location.reload();
                // }
            },
            error: function (error) {
                showAlert('error', 'Error updating status: ' + error.responseJSON.error);
            }
        });
    } else {
        showAlert('error', 'No items selected for bulk update');
    }

    $('#staticBackdrop').modal('hide');
}

function bulkUnPosted(wo_number = null) {
    let arr_wo_number = [wo_number];
    var selectedWONumbers = (wo_number != null && wo_number != 'undefined') ? arr_wo_number : getCheckedWONumbers();

    if ((selectedWONumbers.length > 0) || (wo_number != null && wo_number != 'undefined')) {
        $.ajax({
            url: '/ppic/workOrder/bulk-unposted',
            type: 'POST',
            data: {
                wo_numbers: selectedWONumbers,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // showAlert(response.type, response.message);
                // Tampilkan pesan alert sesuai dengan jenis pesan
                if (response.type === 'success') {
                    showAlert('success', response.message);
                } else if (response.type === 'error') {
                    showAlert('error', response.error);
                }
                // if (segment_3 == undefined || segment_3 != 'work-order-list') {
                refreshDataTable();
                // } else if (segment_3 == 'work-order-list') {
                // window.location.reload();
                // }
            },
            error: function (error) {
                showAlert('error', 'Error updating status: ' + error.responseJSON.error);
            }
        });
    } else {
        showAlert('error', 'No items selected for bulk update');
    }

    $('#staticBackdrop').modal('hide');
}

function bulkDeleted(wo_number = null) {
    let arr_wo_number = [wo_number];
    var selectedWONumbers = (wo_number != null && wo_number != 'undefined') ? arr_wo_number : getCheckedWONumbers();

    if ((selectedWONumbers.length > 0) || (wo_number != null && wo_number != 'undefined')) {
        $.ajax({
            url: '/ppic/workOrder/bulk-deleted',
            type: 'POST',
            data: {
                wo_numbers: selectedWONumbers,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // showAlert(response.type, response.message);
                // Tampilkan pesan alert sesuai dengan jenis pesan
                if (response.type === 'success') {
                    showAlert('success', response.message);
                } else if (response.type === 'error') {
                    showAlert('error', response.error);
                }
                // if (segment_3 == undefined || segment_3 != 'work-order-list') {
                refreshDataTable();
                // } else if (segment_3 == 'work-order-list') {
                // window.location.reload();
                // }
            },
            error: function (error) {
                showAlert('error', 'Error delete data: ' + error.responseJSON.error);
            }
        });
    } else {
        showAlert('error', 'No items selected for bulk delete');
    }

    $('#staticBackdrop').modal('hide');
}

function refreshDataTable() {
    $('#wo_list').DataTable().ajax.reload();
    $('#table-list-wo').DataTable().ajax.reload();
    $('#wo_details_table').DataTable().ajax.reload();
    $('#checkAllRows').prop('checked', false);
}

function toggle(element) {
    $(element).slideToggle(500);
}

function deleteWODetail(id_work_orders = null, id_master_products = null) {
    if (id_work_orders && id_master_products) {
        $.ajax({
            url: '/ppic/workOrder/delete-wo-detail',
            type: 'POST',
            data: {
                id_work_orders: id_work_orders,
                id_master_products: id_master_products,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // console.log(response);
                // showAlert(response.type, response.message);
                // Tampilkan pesan alert sesuai dengan jenis pesan
                if (response.type === 'success') {
                    showAlert('success', response.message);
                } else if (response.type === 'error') {
                    showAlert('error', response.error);
                }
                // if (segment_3 == undefined || segment_3 != 'work-order-list') {
                refreshDataTable();
                // } else if (segment_3 == 'work-order-list') {
                // window.location.reload();
                // }
            },
            error: function (error) {
                showAlert('error', 'Error delete data: ' + error.responseJSON.error);
            }
        });
    } else {
        showAlert('error', 'No items selected for bulk delete');
    }

    $('#staticBackdrop').modal('hide');
}

function filterSearch(button) {
    let search = $(button).attr('data-search');
    // Ambil DataTable instance
    var dataTable = $('#wo_list').DataTable();

    // Jika bukan tombol "WO Finish" atau "WO Closed" yang diklik, atur nilai pencarian untuk kolom bebas
    dataTable.search(search).draw();

    // Perbarui tampilan DataTable
    dataTable.draw();
}

function searchByStatus(button = null) {
    // let searchButtonValue = $(button).attr('data-search');
    // var dataTable = $('#wo_list').DataTable();
    // $('#status_search').val(searchButtonValue)
    // let statusSearch = $('#status_search').val().trim();

    // if (statusSearch !== '') {
    //     dataTable.column('status:name').search(statusSearch).draw();
    //     $('#status_search').removeClass('d-none')
    // } else {
    //     dataTable.column('status:name').search(statusSearch).draw();
    //     $('#status_search').addClass('d-none')
    // }

    let search = $(button).attr('data-search');
    // Ambil DataTable instance
    var dataTable = $('#wo_list').DataTable();

    $('#status_search').val(search);

    // Perbarui tampilan DataTable
    dataTable.draw();
}
