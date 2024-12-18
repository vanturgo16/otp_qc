$(document).ready(function () {
    updateRemoveButton();

    // In your Javascript (external .js resource or <script> tag)
    $('.data-select2').select2({
        width: 'resolve', // need to override the changed default
        theme: "classic"
    });

    // ketika option customer berubah
    $('#customerSelect').change(function () {
        let idCustomer = $(this).val();

        if (idCustomer != '') {
            // mengambil data salesman, term payment, currency dan ppn sesuai idCustomer yang dipilih
            $.ajax({
                url: baseRoute + '/marketing/inputPOCust/get-customer-detail',
                type: 'GET',
                dataType: 'json',
                data: {
                    idCustomer: idCustomer
                },
                success: function (response) {
                    // console.log(response.customer.id_master_salesmen);
                    let idMasterSalesman = response.customer.id_master_salesmen;
                    let idMasterTermPayment = response.customer.id_master_term_payments;
                    let idMasterCurrency = response.customer.id_master_currencies;
                    let ppn = response.customer.ppn;

                    let optionsSalesman = `<option value="">** Please select a Salesman</option>${response.salesmans.map(salesman => `<option value="${salesman.id}" ${idMasterSalesman == salesman.id ? 'selected' : ''}>${salesman.name}</option>`).join('')}`;
                    $('#salesmanSelect').html(optionsSalesman);

                    let isSalesmanDisabled = response.salesmans.some(salesman => idMasterSalesman == salesman.id);
                    $('#salesmanSelect').prop('disabled', isSalesmanDisabled);

                    let optionsTermPayment = `<option value="">** Please select a Term Payment</option>${response.termPayments.map(termPayment => `<option value="${termPayment.id}" ${idMasterTermPayment == termPayment.id ? 'selected' : ''}>${termPayment.term_payment}</option>`).join('')}`;
                    $('#termPaymentSelect').html(optionsTermPayment);

                    let isTermPaymentDisabled = response.termPayments.some(termPayment => idMasterTermPayment == termPayment.id);
                    $('#termPaymentSelect').prop('disabled', isTermPaymentDisabled);

                    let optionsCurrency = `<option value="">** Please select a Currency</option>${response.currencies.map(currency => `<option value="${currency.id}" ${idMasterCurrency == currency.id ? 'selected' : ''}>${currency.currency}</option>`).join('')}`;
                    $('#currencySelect').html(optionsCurrency);

                    let isCurrencyDisabled = response.currencies.some(currency => idMasterCurrency == currency.id);
                    $('#currencySelect').prop('disabled', isCurrencyDisabled);

                    let optionsPpn = `<option value="">** Please select a Ppn</option>` +
                        `<option value="Include" ${ppn == 'Include' ? 'selected' : ''}>Include</option>` +
                        `<option value="Exclude" ${ppn == 'Exclude' ? 'selected' : ''}>Exclude</option>`;
                    $('#ppnSelect').html(optionsPpn);

                    let $ppnSelect = $('#ppnSelect');
                    if ($ppnSelect != '') {
                        $ppnSelect.prop('disabled', true);
                    } else {
                        $ppnSelect.prop('disabled', false);
                    }

                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            let optionsSalesman = `<option value="">** Please select a Salesman</option>`;
            $('#salesmanSelect').html(optionsSalesman);

            let optionsTermPayment = `<option value="">** Please select a Term Payment</option>`;
            $('#termPaymentSelect').html(optionsTermPayment);

            let optionsCurrency = `<option value="">** Please select a Currency</option>`;
            $('#currencySelect').html(optionsCurrency);

            let ppn = `<option value="">** Please select a Ppn</option>`;
            $('#ppnSelect').val(ppn);
        }
    })

    // ketika option type product berubah
    $('.typeProductSelects').change(function () {
        let typeProduct = $(this).val();

        // Cara menggunakannya
        getAllUnit()
            .then(response => {
                // Lakukan sesuatu dengan response
                // console.log(response);
                let optionsUnit = `<option value="">** Please select a Unit</option>${response.map(unit => `<option value="${unit.id}">${unit.unit}</option>`).join('')}`;
                $('.unitSelect').html(optionsUnit);
            })
            .catch(error => {
                // Tangani kesalahan
                console.error(error);
            });

        $('.price').val('');

        if (typeProduct != '') {
            // mengambil data salesman, term payment, currency dan ppn sesuai typeProduct yang dipilih
            $.ajax({
                url: baseRoute + '/marketing/inputPOCust/get-data-product',
                type: 'GET',
                dataType: 'json',
                data: {
                    typeProduct: typeProduct
                },
                success: function (response) {
                    // console.log(response);
                    let optionsProduct = `<option value="">** Please select a Product</option>
                    ${response.products.map(product => `<option value="${product.id}">${product.description}</option>`
                    ).join('')}`;
                    $('.productSelect').html(optionsProduct);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            let optionsProduct = `<option value="">** Please select a Product</option>`;
            $('.productSelect').html(optionsProduct);
        }
    })

    // ketika option product berubah
    $('.productSelects').change(function () {
        let typeProduct = $('.typeProductSelect').val();
        let idProduct = $(this).val();

        if (idProduct != '') {
            // mengambil detail product sesuai dengan  product yang dipilih
            $.ajax({
                url: baseRoute + '/marketing/inputPOCust/get-product-detail',
                type: 'GET',
                dataType: 'json',
                data: {
                    typeProduct: typeProduct,
                    idProduct: idProduct
                },
                success: function (response) {
                    // console.log(response);
                    let idUnit = response.product.id_master_units;
                    // Cara menggunakannya
                    getAllUnit()
                        .then(response => {
                            // Lakukan sesuatu dengan response
                            // console.log(response);
                            let optionsUnit = `<option value="">** Please select a Unit</option>${response.map(unit => `<option value="${unit.id}"${idUnit == unit.id ? 'selected' : ''}>${unit.unit}</option>`).join('')}`;
                            $('.unitSelect').html(optionsUnit);
                        })
                        .catch(error => {
                            // Tangani kesalahan
                            console.error(error);
                        });

                    if (response.product.price != undefined) {
                        let price = response.product.price;
                        $('.price').val(price);
                    } else {
                        $('.price').val('');
                    }
                    // $('.price').val(response.product.price ?? '');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            let optionsProduct = `<option value="">** Please select a Product</option>`;
            $('.productSelect').html(optionsProduct);
        }
    })

    $("form").submit(function () {
        $("select").removeAttr("disabled");
    });

    const pathArray = window.location.pathname.split("/");
    const segment_3 = pathArray[3];
    if (segment_3 == 'show') {
        viewPOCustomer();
    } else if (segment_3 == 'edit') {
        editPOCustomer();
    }
});

// Event listener untuk perubahan tipe produk (yang memicu fetchProducts)
$('.product-row').on('change', '.typeProductSelect', function () {
    fetchProducts(this);
    resetSubtotal(this);
});

function resetSubtotal(selectElement) {
    let productRow = $(selectElement).closest('.product-row');
    productRow.find('.subtotal').val(0);
    calculateTotal();
}

function calculateSubtotal(selectElement) {
    let row = $(selectElement).closest('.product-row');
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let price = parseFloat(row.find('.price').val()) || 0;

    let subtotal = qty * price;
    row.find('.subtotal').val(subtotal.toFixed(0));

    // Panggil fungsi untuk menghitung total keseluruhan
    calculateTotal(row);
}

function calculateTotal() {
    let total = 0;

    // Iterasi setiap baris dan tambahkan subtotal ke total
    $('.product-row').each(function () {
        let subtotal = parseFloat($(this).find('.subtotal').val()) || 0;
        total += subtotal;
    });

    // Tampilkan total di elemen yang sesuai
    $('#totalAmount').text(total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
    $('.totalPrice').val(total.toFixed(0));
}

function getAllUnit() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: baseRoute + '/marketing/inputPOCust/get-all-unit',
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

function cloneRow() {
    $('.data-select2').select2("destroy");
    let $tr = $('#productTable tbody tr.product-row:last');
    let visibilityArray = [];

    // Menyimpan status tersembunyi atau tidaknya masing-masing elemen
    // Jika sedang tersembunyi, tampilkan; jika sedang ditampilkan, tetap tampilkan
    $('#productTable tbody tr.product-row').each(function () {
        let accordionBodies = $(this).find('.accordion-body-dynamic');
        let isHidden = accordionBodies.is(":hidden");
        visibilityArray.push(isHidden);
        accordionBodies.is(":hidden") ? accordionBodies.show() : null;
    });

    let $clone = $tr.clone();
    $tr.after($clone);
    $clone.find("input").val("");
    $clone.find(".unitSelect").val("");
    $clone.find(".productSelect").html('<option value="">** Please select a Product</option>');

    // Sembunyikan baris yang baru di-klon terlebih dahulu
    $clone.css('opacity', 0);

    // Gunakan animate untuk memberikan efek fadeIn
    $clone.animate({
        opacity: 1
    }, 300); // Sesuaikan waktu animasi (dalam milidetik) dengan durasi transisi CSS

    // $clone.find('.data-select2').select2();
    $('.data-select2').select2();
    // Tambahkan class 'remove-transition' pada elemen yang baru di-klon untuk animasi ketika menambahkan baris
    $clone.addClass('remove-transition');

    // Mengembalikan status tersembunyi atau tidaknya masing-masing elemen ke kondisi awal
    $('#productTable tbody tr.product-row').each(function (index) {
        let accordionBodies = $(this).find('.accordion-body-dynamic');
        visibilityArray[index] ? accordionBodies.hide() : accordionBodies.show();
    });

    // Update tampilan tombol Remove setelah mengkloning baris
    updateRemoveButton();
}

// Variabel global untuk menyimpan referensi tombol yang diklik
var currentButton;

// Fungsi untuk menampilkan modal konfirmasi
function confirmDelete(button) {
    // Simpan referensi tombol yang diklik
    currentButton = button;

    // Tampilkan modal konfirmasi
    $('#staticBackdrop').modal('show');
}

function removeRow() {
    // Tampilkan konfirmasi sebelum menghapus baris
    // let confirmDelete = confirm("Apakah Anda yakin ingin menghapus baris ini?");

    // Dapatkan elemen <tr> yang berisi tombol yang diklik
    // var row = $(currentButton).closest('tr');

    // if (confirmDelete) {
    let $row = $(currentButton).closest('tr');
    // Tutup modal konfirmasi
    $('#staticBackdrop').modal('hide');

    // Gunakan fadeOut untuk memberikan efek fadeOut sebelum menghapus baris
    $row.fadeOut(500, function () {
        $($row).remove();
        // Update tampilan tombol Remove setelah menghapus baris
        updateRemoveButton();
        calculateTotal();

    });
    // }
}

function updateRemoveButton() {
    let rowCount = $('#productTable tbody tr.product-row').length;

    // Jika ada lebih dari satu baris, tampilkan tombol Remove
    $('.removeButtonContainer').toggle(rowCount > 1);
}

function toggleRow(button) {
    let accordionBody = $(button).closest('.product-row').find('.accordion-body-dynamic');
    // Gunakan slideToggle() untuk efek transisi saat menunjukkan atau menyembunyikan baris
    accordionBody.slideToggle();
}

function fetchProducts(selectElement) {
    let selectedType = $(selectElement).val();
    let productSelect = $(selectElement).closest('.product-row').find('.productSelect');
    let unitSelect = $(selectElement).closest('.product-row').find('.unitSelect');
    let options = '<option value="">** Please select a Product</option>';
    let optionsUnit = '<option value="">** Please select a Unit</option>';
    let productRow = $(selectElement).closest('.product-row');
    // console.log(selectedType);

    // Hanya membuat permintaan AJAX jika tipe dipilih
    if (selectedType) {
        $.ajax({
            url: baseRoute + '/marketing/inputPOCust/get-data-product',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: selectedType
            },
            success: function (response) {
                // Tanggapi dengan mengisi opsi produk sesuai data dari server


                $.each(response.products, function (index, product) {
                    options += '<option value="' + product.id + '">' + product.description + '</option>';
                });
                productSelect.html(options);

                // Cara menggunakannya
                getAllUnit()
                    .then(response => {
                        // Lakukan sesuatu dengan response
                        let optionsUnit = `<option value="">** Please select a Unit</option>${response.map(unit => `<option value="${unit.id}">${unit.unit}</option>`).join('')}`;
                        // unitSelect.html(optionsUnit);
                        productRow.find('.unitSelect').html(optionsUnit);
                    })
                    .catch(error => {
                        // Tangani kesalahan
                        console.error(error);
                    });

                // price.val('');
                calculateSubtotal(selectElement)
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    } else {
        productSelect.html(options);
        unitSelect.html(optionsUnit);
    }
    productRow.find('.custProductCode').val('');
    productRow.find('.qty').val('');
    productRow.find('.price').val('');
    productRow.find('.subtotal').val('');
    calculateTotal();
}

function fethchProductDetail(selectElement) {
    let typeProduct = $('.typeProductSelect').val();
    let selectedProductId = $(selectElement).val();

    if (selectedProductId) {
        $.ajax({
            url: baseRoute + '/marketing/inputPOCust/get-product-detail',
            type: 'GET',
            dataType: 'json',
            data: {
                typeProduct: typeProduct,
                idProduct: selectedProductId
            },
            success: function (response) {
                let productRow = $(selectElement).closest('.product-row');
                let unitSelect = $(selectElement).closest('.product-row').find('.unitSelect');
                let idUnit = response.product.id_master_units;
                getAllUnit()
                    .then(response => {
                        // Lakukan sesuatu dengan response
                        let optionsUnit = `<option value="">** Please select a Unit</option>${response.map(unit => `<option value="${unit.id}"${idUnit == unit.id ? 'selected' : ''}>${unit.unit}</option>`).join('')}`;
                        unitSelect.html(optionsUnit);
                    })
                    .catch(error => {
                        // Tangani kesalahan
                        console.error(error);
                    });
                productRow.find('.price').val(response.product.price);
                productRow.find('.custProductCode').focus();
                // resetSubtotal(selectElement);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    calculateSubtotal(selectElement);
}

function editPOCustomer() {
    po_number = $('#po_number').val();
    customer_select = $('#customerSelect').val();

    if (po_number) {
        $.ajax({
            url: baseRoute + '/marketing/inputPOCust/get-data-po-customer',
            type: 'GET',
            dataType: 'json',
            data: {
                po_number: po_number,
                customer_select: customer_select,
            },
            success: function (response) {
                // console.log(response);
                if (response.inputPOCustomer != null) {
                    let idMasterSalesman = response.inputPOCustomer.id_master_salesmen;
                    let idMasterTermPayment = response.inputPOCustomer.id_master_term_payments;
                    let idMasterCurrency = response.inputPOCustomer.id_master_currencies;
                    let ppn = response.inputPOCustomer.ppn;
                    let products = response.products

                    let optionsSalesman = `<option value="">** Please select a Salesman</option>${response.salesmans.map(salesman => `<option value="${salesman.id}" ${idMasterSalesman == salesman.id ? 'selected' : ''}>${salesman.name}</option>`).join('')}`;
                    $('#salesmanSelect').html(optionsSalesman);

                    let isSalesmanDisabled = response.salesmans.some(salesman => response.customer.id_master_salesmen == salesman.id);
                    $('#salesmanSelect').prop('disabled', isSalesmanDisabled);

                    let optionsTermPayment = `<option value="">** Please select a Term Payment</option>${response.termPayments.map(termPayment => `<option value="${termPayment.id}" ${idMasterTermPayment == termPayment.id ? 'selected' : ''}>${termPayment.term_payment}</option>`).join('')}`;
                    $('#termPaymentSelect').html(optionsTermPayment);

                    let isTermPaymentDisabled = response.termPayments.some(termPayment => idMasterTermPayment == termPayment.id);
                    $('#termPaymentSelect').prop('disabled', isTermPaymentDisabled);

                    let optionsCurrency = `<option value="">** Please select a Currency</option>${response.currencies.map(currency => `<option value="${currency.id}" ${idMasterCurrency == currency.id ? 'selected' : ''}>${currency.currency}</option>`).join('')}`;
                    $('#currencySelect').html(optionsCurrency);

                    let isCurrencyDisabled = response.currencies.some(currency => idMasterCurrency == currency.id);
                    $('#currencySelect').prop('disabled', isCurrencyDisabled);

                    let optionsPpn = `<option value="">** Please select a Ppn</option>` +
                        `<option value="Include" ${ppn == 'Include' ? 'selected' : ''}>Include</option>` +
                        `<option value="Exclude" ${ppn == 'Exclude' ? 'selected' : ''}>Exclude</option>`;
                    $('#ppnSelect').html(optionsPpn);

                    let $ppnSelect = $('#ppnSelect');
                    if ($ppnSelect != '') {
                        $ppnSelect.prop('disabled', true);
                    } else {
                        $ppnSelect.prop('disabled', false);
                    }

                    // Mendapatkan detail dari respons AJAX
                    let details = response.inputPOCustomer.input_p_o_customer_details;

                    // Mengklon template baris
                    $('.data-select2').select2("destroy");
                    let $templateRow = $('.product-row').clone();

                    // Menghapus semua baris yang ada di dalam tabel
                    $('#productTable tbody tr.product-row').remove();

                    // Mengisi baris baru sesuai dengan detail
                    for (let i = 0; i < details.length; i++) {
                        let $clonedRow = $templateRow.clone();
                        // console.log(details[i].id_master_product);
                        // Mengisi nilai dari detail ke dalam baris yang di-klon
                        $clonedRow.find('.typeProductSelect').val(details[i].type_product);

                        // Function untuk menambahkan opsi produk ke elemen select
                        function appendProductOption(product) {
                            $clonedRow.find('.productSelect').append($('<option>', {
                                value: product.id,
                                text: product.description
                            }));
                        }

                        // Function untuk memfilter produk berdasarkan tipe
                        function filterProductsByType(productType) {
                            // Bersihkan opsi yang ada sebelum menambahkan yang baru
                            $clonedRow.find('.productSelect').empty();

                            // Filter dan tambahkan opsi produk sesuai dengan tipe yang dipilih
                            products.filter(function (product) {
                                return product.type_product === productType;
                            }).forEach(function (filteredProduct) {
                                appendProductOption(filteredProduct);
                            });
                        }

                        // Panggil fungsi pertama kali untuk menampilkan semua produk (jika ada)
                        filterProductsByType(details[i].type_product);
                        $clonedRow.find('.productSelect').val(details[i].id_master_product);
                        $clonedRow.find('.custProductCode').val(details[i].cust_product_code);
                        $clonedRow.find('.qty').val(details[i].qty);
                        $clonedRow.find('.unitSelect').val(details[i].id_master_units);
                        $clonedRow.find('.price').val(details[i].price);
                        $clonedRow.find('.subtotal').val(details[i].subtotal);

                        // Menambahkan baris yang di-klon ke dalam tabel
                        $('#productTable tbody').append($clonedRow);
                    }

                    // Menginisialisasi Select2 untuk baris baru
                    $('.data-select2').select2({
                        width: 'resolve', // need to override the changed default
                        theme: "classic"
                    });

                    // Memperbarui tampilan tombol Remove
                    updateRemoveButton();

                    $('#totalAmount').text(response.inputPOCustomer.total_price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function viewPOCustomer() {
    po_number = $('#po_number').text();

    if (po_number) {
        $.ajax({
            url: baseRoute + '/marketing/inputPOCust/get-data-po-customer',
            type: 'GET',
            dataType: 'json',
            data: {
                po_number: po_number,
            },
            success: function (response) {
                // console.log(response);
                if (response.inputPOCustomer != null) {
                    let products = response.products

                    // Mendapatkan detail dari respons AJAX
                    let details = response.inputPOCustomer.input_p_o_customer_details;

                    // Mengisi baris baru sesuai dengan detail
                    for (let i = 0; i < details.length; i++) {
                        // Fungsi untuk mendapatkan produk sesuai dengan tipe dan kode produk
                        function getFilteredProduct(type, code) {
                            return products.filter(product => product.type_product === type && product.id === code);
                        }

                        // Fungsi untuk menampilkan hasil pencarian
                        function displaySearchResult(type, code) {
                            // Mendapatkan hasil pencarian
                            let result = getFilteredProduct(type, code);
                            return result[0]['description'];
                            // Menampilkan hasil pencarian (misalnya, di konsol)
                            // console.log(result);
                            // Jika ingin menampilkan hasil pada elemen HTML, sesuaikan kode di sini
                        }

                        // Contoh pemanggilan fungsi dengan tipe dan kode produk tertentu
                        let description = displaySearchResult(details[i].type_product, details[i].id_master_product);
                        const custProductCode = details[i].cust_product_code !== null ? details[i].cust_product_code : '';

                        $('#productTable').append('<tr> <td class="text-center">' + (i + 1) + '</td>  <td class="text-center">' + details[i].type_product + '</td> <td>' + description + '</td> <td>' + custProductCode + '</td> <td>' + details[i].master_unit.unit + '</td> <td class="text-center">' + details[i].qty + '</td> <td class="text-end">' + details[i].price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + '</td> <td class="text-end">' + details[i].subtotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + '</td></tr>');
                    }

                    $('#totalAmount').text(response.inputPOCustomer.total_price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

var isChecked = false;

$('#checkAllRows').click(function () {
    isChecked = !isChecked;
    $(':checkbox').prop("checked", isChecked);
});

// Mendapatkan nilai data-po-number dari baris yang checkbox-nya dicentang
function getCheckedPoNumbers() {
    var checkedPoNumbers = [];

    $(':checkbox:checked').each(function () {
        var poNumber = $(this).data('po-number');
        if (poNumber !== undefined) {
            checkedPoNumbers.push(poNumber);
        }
    });

    return checkedPoNumbers;
}


function showModal(selectElement, actionButton = null) {
    let po_number = $(selectElement).attr("data-po-number");
    let status = $(selectElement).attr("data-status");

    let statusTitle = actionButton == 'Delete' ? 'Confirm to Delete' : (status == 'Request' ? 'Confirm to Posted' : 'Confirm to Un Posted');
    let statusLabel = actionButton == 'Delete' ? 'Are you sure you want to <b class="text-danger">delete</b> this data' : (status == 'Request' ? 'Are you sure you want to <b class="text-success">posted</b> this data?' : 'Are you sure you want to <b class="text-warning">unposted</b> this data?');
    let mdiIcon = actionButton == 'Delete' ? '<i class="mdi mdi-trash-can label-icon"></i>Delete' : (status == 'Request' ? '<i class="mdi mdi-check-bold label-icon"></i>Posted' : '<i class="mdi mdi-arrow-left-top-bold label-icon"></i>Un Posted');
    let buttonClass = actionButton == 'Delete' ? 'btn-danger' : (status == 'Request' ? 'btn-success' : 'btn-warning');
    let attrFunction = actionButton == 'Delete' ? `bulkDeleted('${po_number}');` : (status == 'Request' ? `bulkPosted('${po_number}');` : `bulkUnPosted('${po_number}');`);

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

function bulkPosted(po_number = null) {
    let arr_po_number = [po_number];
    var selectedPoNumbers = (po_number != null && po_number != 'undefined') ? arr_po_number : getCheckedPoNumbers();

    if ((selectedPoNumbers.length > 0) || (po_number != null && po_number != 'undefined')) {
        $.ajax({
            url: '/marketing/inputPOCust/bulk-posted',
            type: 'POST',
            data: {
                po_numbers: selectedPoNumbers,
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
                refreshDataTable();
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

function bulkUnPosted(po_number = null) {
    let arr_po_number = [po_number];
    var selectedPoNumbers = (po_number != null && po_number != 'undefined') ? arr_po_number : getCheckedPoNumbers();

    if ((selectedPoNumbers.length > 0) || (po_number != null && po_number != 'undefined')) {
        $.ajax({
            url: '/marketing/inputPOCust/bulk-unposted',
            type: 'POST',
            data: {
                po_numbers: selectedPoNumbers,
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
                refreshDataTable();
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

function bulkDeleted(po_number = null) {
    let arr_po_number = [po_number];
    var selectedPoNumbers = (po_number != null && po_number != 'undefined') ? arr_po_number : getCheckedPoNumbers();

    if ((selectedPoNumbers.length > 0) || (po_number != null && po_number != 'undefined')) {
        $.ajax({
            url: '/marketing/inputPOCust/bulk-deleted',
            type: 'POST',
            data: {
                po_numbers: selectedPoNumbers,
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
                refreshDataTable();
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
    $('#po_customer_table').DataTable().ajax.reload();
    $('#checkAllRows').prop('checked', false);
}

function modalPDF(po_number) {
    $('.preview').attr('href', baseRoute + '/marketing/inputPOCust/preview/' + po_number,)
    $('.print').attr('href', baseRoute + '/marketing/inputPOCust/print/' + po_number,)
    $('#modalPDF').modal('show');
}
