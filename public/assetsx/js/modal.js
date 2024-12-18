function supplier() {
    // alert('test')
    $('.form-reset').trigger('reset')
    // Kirim data melalui Ajax
    $.ajax({
      url: '/get-supplier/',
      method: 'GET',
      data: {},
      success: function (response) {
        // console.log(response);
        // Loop melalui data dan tambahkan opsi ke dalam select
        $('.name_supplier').empty()
        $('.name_supplier').append(` <option>Pilih Supplier</option>`)
        $.each(response.data, function (i, value) {
          $('.name_supplier').append(
            `<option value="` + value.id + `">` + value.name + `</option>`
          )
        });
      },
      error: function (xhr, status, error) {
        // Tangkap pesan error jika ada
        alert('Terjadi kesalahan saat mengirim data.');
      }
    });
}

function po_number() {
 
// // $(document).ready(function () {
//     $('#generateCode').click(function () {
        $.ajax({
            type: 'GET',
            url: '/generate-code', // Ganti dengan URL rute Laravel yang sesuai
            success: function (response) {
              // console.log(response.data.find);
  
                $('#generatedCode').val(response.data.find)
                
            },
            error: function (error) {
                console.log(error);
            }
        });
    // });
// });
}

function request_number() {
  // alert('test')
  $('.form-reset').trigger('reset')
  // Kirim data melalui Ajax
  $.ajax({
    url: '/get-supplier/',
    method: 'GET',
    data: {},
    success: function (response) {
      // console.log(response);
      // Loop melalui data dan tambahkan opsi ke dalam select
      $('.request_number').empty()
      $('.request_number').append(` <option>Pilih Reference Number</option>`)
      $.each(response.data.rn, function (i, value) {
        $('.request_number').append(
          `<option value="` + value.id + `">` + value.request_number + `</option>`
        )
      });
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
}

function hapusData(form) {
  Swal.fire({
    title: "Hapus Data",
    text: "Apakah yakin ingin menghapus data?",
    icon: "warning",
    showCancelButton: !0,
    confirmButtonColor: "#2ab57d",
    cancelButtonColor: "#fd625e",
    confirmButtonText: "Ya, hapus!"
  }).then(function (result) {
    if (result.value) {
      form.submit();
    }
  });
}

function edit_po(id) {
  // $('.editPenjualan').click(function () {
  //   let id = $(this).attr('data-id')
  // Kirim data melalui Ajax
  $.ajax({
    url: '/get-edit-po/' + id,
    method: 'GET',
    data: {
      id: id
    },
    success: function (response) {
      // Tangkap pesan dari server dan tampilkan ke user
      console.log(response);

      $('#form_po').attr('action', '/update_po/' + response.data.find.id)
      $('#id_po').val(response.data.find.id)
      $('#po_number_po').val(response.data.find.po_number)
      $('#date_po').val(response.data.find.date)
      $('#request_number_po').val(response.data.find.request_number)
      $('#qc_check_po').val(response.data.find.qc_check)
      $('#down_payment_po').val(response.data.find.down_payment)
      $('#own_remarks_po').val(response.data.find.own_remarks)
      $('#supplier_remarks_po').val(response.data.find.supplier_remarks)
      $('#status_po').val(response.data.find.status)
      $('#type_po').val(response.data.find.type)

      let produkSelect = response.data.find.produk
      let satuanSelect = response.data.find.satuan
      let provinsiSelect = response.data.find.provinsi
      let kotaSelect = response.data.find.kabupaten_kota
      let sektorSelect = response.data.find.sektor

      $('#produk_penjualan').empty()
      $('#produk_penjualan').append(` <option>Pilih Produk</option>`)
      $.each(response.data.produk, function (i, value) {
        let isSelected = produkSelect == value.name ? 'selected' : ''

        $('#produk_penjualan').append(
          `<option value="` + value.name + `"` + isSelected + `>` + value.name + `</option>`
        )
      });

      $('#sektor_penjualan').empty()
      $('#sektor_penjualan').append(` <option>Pilih Sektor</option>`)
      $.each(response.data.sektor, function (i, value) {
        let isSelected = sektorSelect == value.nama_sektor ? 'selected' : ''

        $('#sektor_penjualan').append(
          `<option value="` + value.nama_sektor + `"` + isSelected + `>` + value.nama_sektor + `</option>`
        )
      });

      $.ajax({
        url: '/get-satuan/' + produk,
        method: 'GET',
        data: {},
        success: function (response) {
          // console.log(response);
          // Loop melalui data dan tambahkan opsi ke dalam select
          $('#satuan_penjualan').empty()
          $('#satuan_penjualan').append(` <option>Pilih Satuan</option>`)
          $.each(response.data, function (i, value) {
            let isSelected = satuanSelect == value.satuan ? 'selected' : ''
            $('#satuan_penjualan').append(
              `<option value="` + value.satuan + `" ` + isSelected + `>` + value.satuan + `</option>`
            )
          });
        },
        error: function (xhr, status, error) {
          // Tangkap pesan error jika ada
          alert('Terjadi kesalahan saat mengirim data.');
        }
      });

      // alert(kabupaten_kota)
      // console.log(response.data.provinsi);

      $('#provinsi_penjualan').empty()
      $('#provinsi_penjualan').append(` <option>Pilih Provinsi</option>`)
      $.each(response.data.provinsi, function (i, value) {
        let isSelected = provinsiSelect.toLowerCase() == value.name.toLowerCase() ? 'selected' : ''

        $('#provinsi_penjualan').append(
          `<option data-id="` + value.id + `" value="` + value.name + `"` + isSelected + `>` + value.name + `</option>`
        )
      });


      $.ajax({
        url: '/get_kota_lng/' + kabupaten_kota,
        method: 'GET',
        data: {},
        success: function (response) {
          // console.log(response);
          // console.log(kabupaten_kota);
          // Loop melalui data dan tambahkan opsi ke dalam select
          $('#kab_penjualan').empty()
          $('#kab_penjualan').append(` <option>Pilih Kab / Kota</option>`)
          $.each(response.data, function (i, value) {
            let isSelected = kotaSelect == value.nama_kota ? 'selected' : ''
            $('#kab_penjualan').append(
              `<option value="` + value.nama_kota + `" ` + isSelected + `>` + value.nama_kota + `</option>`
            )
          });
        },
        error: function (xhr, status, error) {
          // Tangkap pesan error jika ada
          alert('Terjadi kesalahan saat mengirim data.');
        }
      });

      // Contoh: Lakukan tindakan selanjutnya setelah data berhasil dikirim
      // window.location.href = '/success-page';
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
  // })
}

function edit_po_detail(id) {
  // alert('test')
  // $('.editPenjualan').click(function () {
  //   let id = $(this).attr('data-id')
  // Kirim data melalui Ajax
  $.ajax({
    url: '/get-edit-po/' + id,
    method: 'GET',
    data: {
      id: id
    },
    success: function (response) {
      // Tangkap pesan dari server dan tampilkan ke user
      console.log(response.data.finddetail.qty);

      $('#form_po_detail').attr('action', '/update_po_detail/' + response.data.finddetail.id)
      $('#id_po_detail').val(response.data.finddetail.id)
      $('#id_purchase_orders_po_detail').val(response.data.finddetail.id_purchase_orders)
      $('#type_product_po_detail').val(response.data.finddetail.type_product)
      $('#master_products_id_po_detail').val(response.data.finddetail.master_products_id)
      $('#qty_po_detail').val(response.data.finddetail.qty)
      $('#master_units_id_po_detail').val(response.data.finddetail.master_units_id)
      $('#price_po_detail').val(response.data.finddetail.price)
      $('#discount_po_detail').val(response.data.finddetail.discount)
      $('#tax_po_detail').val(response.data.finddetail.tax)
      $('#amount_po_detail').val(response.data.finddetail.amount)
      $('#note_po_detail').val(response.data.finddetail.note)

      let produkSelect = response.data.finddetail.master_products_id
      let unitSelect = response.data.finddetail.master_units_id
      

      $('#master_products_id_po_detail').empty()
      $('#master_products_id_po_detail').append(` <option>Pilih Produk</option>`)
      $.each(response.data.produk, function (i, value) {
        let isSelected = produkSelect == value.id ? 'selected' : ''

        $('#master_products_id_po_detail').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.description + `</option>`
        )
      });

      $('#master_units_id_po_detail').empty()
      $('#master_units_id_po_detail').append(` <option>Pilih Unit</option>`)
      $.each(response.data.unit, function (i, value) {
        let isSelected = unitSelect == value.id ? 'selected' : ''

        $('#master_units_id_po_detail').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.unit_code + `</option>`
        )
      });

      // Contoh: Lakukan tindakan selanjutnya setelah data berhasil dikirim
      // window.location.href = '/success-page';
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
  // })
}

function edit_pr(id) {
  // $('.editPenjualan').click(function () {
  //   let id = $(this).attr('data-id')
  // alert('test');
  // Kirim data melalui Ajax
  $.ajax({
    url: '/get-edit-pr/' + id,
    method: 'GET',
    data: {
      id: id
    },
    success: function (response) {
      // Tangkap pesan dari server dan tampilkan ke user
      // console.log(response.data.find.cc_co);

      $('#form_pr_detail').attr('action', '/update_pr_detailx/' + response.data.find.id)
      $('#id_pr').val(response.data.find.id)
      $('#type_product_pr').val(response.data.find.type_product)
      $('#master_products_id_pr').val(response.data.find.master_products_id)
      $('#qty_pr').val(response.data.find.qty)
      $('#master_units_id_pr').val(response.data.find.master_units_id)
      $('#required_date_pr').val(response.data.find.required_date)
      $('#cc_co_pr').val(response.data.find.cc_co)
      $('#remarks_pr').val(response.data.find.remarks)
      $('#request_number_pr').val(response.data.find.request_number)

      let produkSelect = response.data.find.master_products_id
      let unitSelect = response.data.find.master_units_id
      let unitrequester = response.data.find.cc_co

      $('#master_products_id_pr').empty()
      $('#master_products_id_pr').append(` <option>Pilih Produk</option>`)
      $.each(response.data.produk, function (i, value) {
        let isSelected = produkSelect == value.id ? 'selected' : ''

        $('#master_products_id_pr').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.description + `</option>`
        )
      });

      $('#master_units_id_pr').empty()
      $('#master_units_id_pr').append(` <option>Pilih Unit</option>`)
      $.each(response.data.unit, function (i, value) {
        let isSelected = unitSelect == value.id ? 'selected' : ''

        $('#master_units_id_pr').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.unit_code + `</option>`
        )
      });

      
      $('#cc_co_pr').empty()
      $('#cc_co_pr').append(` <option>Pilih CC / CO</option>`)
      $.each(response.data.requester, function (i, value) {
        let isSelected = unitrequester == value.id ? 'selected' : ''

        $('#cc_co_pr').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.nm_requester + `</option>`
        )
      });


      // Contoh: Lakukan tindakan selanjutnya setelah data berhasil dikirim
      // window.location.href = '/success-page';
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
  // })
}