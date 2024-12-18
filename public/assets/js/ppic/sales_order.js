$(document).ready(function () {
    $('.data-select2').select2({
        width: 'resolve', // need to override the changed default
        theme: "classic"
    });

    // Panggil fungsi saat halaman dimuat
    toggleElementsVisibility();

    // ketika option customer berubah
    $('#orderSelect').change(function () {
        let order_number = $(this).val();

        if (order_number != '') {
            // mengambil data customer, salesman, term payment dan ppn sesuai order_number yang dipilih
            $.ajax({
                url: baseRoute + '/marketing/salesOrder/get-order-detail',
                type: 'GET',
                dataType: 'json',
                data: {
                    order_number: order_number
                },
                success: function (response) {
                    // console.log(response);
                    let idMasterCustomer = response.order.id_master_customers;
                    let idMasterSalesman = response.order.id_master_salesmen;
                    let idMasterTermPayment = response.order.id_master_term_payments;
                    let ppn = response.order.ppn;

                    let optionsCustomer = `<option value="">** Please select a Customer</option>${response.customers.map(customer => `<option value="${customer.id}" ${idMasterCustomer == customer.id ? 'selected' : ''}>${customer.name}</option>`).join('')}`;
                    $('#customerSelect').html(optionsCustomer);

                    let isCustomerDisabled = response.customers.some(customer => idMasterCustomer == customer.id);
                    $('#customerSelect').prop('disabled', isCustomerDisabled);

                    let optionsCustomerAddress = `<option value="">** Please select a Customer Address</option>${response.order.master_customer_address.map(address => `<option value="${address.id}">${address.address}</option>`).join('')}`;
                    $('#customerAddressSelect').html(optionsCustomerAddress);

                    let optionsSalesman = `<option value="">** Please select a Salesman</option>${response.salesmans.map(salesman => `<option value="${salesman.id}" ${idMasterSalesman == salesman.id ? 'selected' : ''}>${salesman.name}</option>`).join('')}`;
                    $('#salesmanSelect').html(optionsSalesman);

                    let isSalesmanDisabled = response.salesmans.some(salesman => idMasterSalesman == salesman.id);
                    $('#salesmanSelect').prop('disabled', isSalesmanDisabled);

                    let optionsTermPayment = `<option value="">** Please select a Term Payment</option>${response.termPayments.map(termPayment => `<option value="${termPayment.id}" ${idMasterTermPayment == termPayment.id ? 'selected' : ''}>${termPayment.term_payment}</option>`).join('')}`;
                    $('#termPaymentSelect').html(optionsTermPayment);

                    let isTermPaymentDisabled = response.termPayments.some(termPayment => idMasterTermPayment == termPayment.id);
                    $('#termPaymentSelect').prop('disabled', isTermPaymentDisabled);

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

                    getDetailOrder(order_number, response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            let optionsCustomer = `<option value="">** Please select a Customers</option>`;
            $('#customerSelect').html(optionsCustomer);

            let optionsSalesman = `<option value="">** Please select a Salesman</option>`;
            $('#salesmanSelect').html(optionsSalesman);

            let optionsTermPayment = `<option value="">** Please select a Term Payment</option>`;
            $('#termPaymentSelect').html(optionsTermPayment);

            let ppn = `<option value="">** Please select a Ppn</option>`;
            $('#ppnSelect').val(ppn);
        }
    })

    $('#soTypeSelect').change(function () {
        let so_type = $(this).val();

        if (so_type != '') {
            $.ajax({
                url: baseRoute + '/marketing/salesOrder/generate-so-number',
                type: 'GET',
                dataType: 'json',
                data: {
                    so_type: so_type
                },
                success: function (response) {
                    // console.log(response);
                    $('#so_number').val(response.code)
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#so_number').val('')
        }

        // Panggil fungsi saat halaman dimuat
        toggleElementsVisibility();
    });

    function addDays(date, days) {
        const copy = new Date(Number(date))
        copy.setDate(date.getDate() + days)
        return copy
    }

    $('#date').change(function () {
        const date = new Date($(this).val());
        const newDate = addDays(date, 14);
        const due_date = newDate.toISOString().split('T')[0];

        $('#due_date').val(due_date)
    });

    // Check All functionality
    $('#checkAllRows').change(function () {
        $('.rowCheckbox').prop('checked', this.checked);
        toggleHighlight();
        calculateTotalPrice();
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
        toggleHighlight();
        calculateTotalPrice();
    });

    // Toggle checkbox when clicking on the row
    $(document).on('click', '#productTable tbody tr', function (event) {
        // Check if the click is on the checkbox, if yes, don't toggle the checkbox
        if (!$(event.target).is(':checkbox')) {
            const checkbox = $(this).find('.rowCheckbox');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            toggleHighlight();
            calculateTotalPrice();
        }
    });

    // $("form").submit(function () {
    //     $("select").removeAttr("disabled");
    // });

    $(document).on('submit', '#formSalesOrder', function (e) {
        e.preventDefault(); // Mencegah formulir terkirim secara default

        // Cek apakah ada setidaknya satu produk yang dicentang
        var selectedRows = $('.rowCheckbox:checked');

        if (selectedRows.length === 0) {
            // Jika tidak ada produk yang dicentang, tampilkan pesan kesalahan
            alert('Pilih setidaknya satu produk untuk melanjutkan.');
        } else {
            // Jika ada produk yang dicentang, lanjutkan dengan pengiriman form
            $("select").removeAttr("disabled");
            this.submit();
        }
    });

    const pathArray = window.location.pathname.split("/");
    const segment_3 = pathArray[3];
    if (segment_3 == 'show') {
        viewSalesOrder();
    } else if (segment_3 == 'edit') {
        editSalesOrder();
    }
});

// Fungsi untuk menampilkan/sembunyikan elemen berdasarkan nilai so_type
function toggleElementsVisibility() {
    var soType = $('#soTypeSelect').val(); // Ambil nilai so_type dari elemen dengan ID so_type

    // Cek nilai so_type dan sesuaikan tampilan elemen
    if (soType === 'Stock') {
        $('.customerSection').hide(); // Sembunyikan bagian customer
        $('.customerAddressSection').hide(); // Sembunyikan bagian customer address
        $('.salesmanSection').hide(); // Sembunyikan bagian salesman
    } else {
        $('.customerSection').show(); // Tampilkan bagian customer
        $('.customerAddressSection').show(); // Tampilkan bagian customer address
        $('.salesmanSection').show(); // Tampilkan bagian salesman
    }
}

function toggle(element) {
    $(element).slideToggle(500);
}

// Toggle row highlight based on checkbox state
function toggleHighlight() {
    $('.rowCheckbox').each(function () {
        $(this).closest('tr').toggleClass('table-success', this.checked);
    });
}

// Calculate total price based on checked rows
function calculateTotalPrice() {
    let totalPrice = 0;
    $('.rowCheckbox:checked').each(function () {
        // Menghilangkan titik sebelum mengonversi ke float
        let subtotalText = $(this).closest('tr').find('.subtotal').text().replace('.', '');
        totalPrice += parseFloat(subtotalText);
    });
    // $('#totalPrice').text(totalPrice.toFixed(2));
    $('#totalPrice').text(totalPrice.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
    $('#total-Price').val(totalPrice);
}

function getDetailOrder(order_number, response) {
    // console.log(response);
    if (response.order != null) {
        let products = response.products

        // Mendapatkan detail dari respons AJAX
        if (order_number.substring(0, 2) == 'PO') {
            var details = response.order.input_p_o_customer_details;
        } else if (order_number.substring(0, 2) == 'KO') {
            var details = response.order.order_confirmation_details;
        }

        $('#productTable tbody, #productTable tfoot').empty();

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

            // Check if the product is in response.compare
            let isInCompare = response.compare.some(compareProduct =>
                compareProduct.id_master_products === details[i].id_master_product && compareProduct.type_product === details[i].type_product
            );

            // Add a condition to show or hide the checkbox
            let checkboxHtml = isInCompare ? '' : '<input type="checkbox" class="rowCheckbox" name="selected_rows[]" value="' + i + '">';
            let tableColor = isInCompare ? 'table-danger' : '';

            $('#productTable').append('<tr class="row-check ' + tableColor + '"><td class="text-center">' + (i + 1) + '</td>  <td class="text-center"><input type="text" class="form-control d-none" name="type_product[]" value="' + details[i].type_product + '" readonly>' + details[i].type_product + '</td> <td><input type="text" class="form-control d-none" name="id_master_products[]" value="' + details[i].id_master_product + '" readonly>' + description + '</td> <td><input type="text" class="form-control d-none" name="cust_product_code[]" value="' + custProductCode + '" readonly>' + custProductCode + '</td> <td><input type="text" class="form-control d-none" name="id_master_units[]" value="' + details[i].master_unit.id + '" readonly>' + details[i].master_unit.unit + '</td> <td class="text-center"><input type="text" class="form-control d-none" name="qty[]" value="' + details[i].qty + '" readonly>' + details[i].qty + '</td> <td class="text-end"><input type="text" class="form-control d-none" name="price[]" value="' + details[i].price + '" readonly>' + details[i].price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + '</td> <td class="text-end"><input type="text" class="form-control d-none" name="subtotal[]" value="' + details[i].subtotal + '" readonly><span class="subtotal">' + details[i].subtotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + '</span></td><td class="text-center align-middle">' + checkboxHtml + '</td></tr>');

        }

        $('#productTable').append('<tfoot><tr><td colspan="7" class="text-end text-black">Total Price</td><td class="text-end" colspan="2"><span id="totalPrice">0</span></td></tr></tfott>');
    }
}

function editSalesOrder() {
    so_number = $('#so_number').val();

    if (so_number) {
        $.ajax({
            url: baseRoute + '/marketing/salesOrder/get-data-sales-order',
            type: 'GET',
            dataType: 'json',
            data: {
                so_number: so_number,
            },
            success: function (response) {
                console.log(response);
                getDetailOrder(response.order.id_order_confirmations, response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}
