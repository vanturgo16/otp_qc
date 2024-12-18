
function detail_sparepart_auxiliaries_edit(id) {
  
  // Kirim data melalui Ajax
  $.ajax({
    url: '/production-req-sparepart-auxiliaries-detail-edit-get/' + id,
    method: 'GET',
    data: {
      id: id
    },
    success: function (response) {
      // Tangkap pesan dari server dan tampilkan ke user
      // console.log(response.data.find.cc_co);

      $('#form_detail_sparepart_auxiliaries_edit').attr('action', '/production-req-sparepart-auxiliaries-detail-edit-save/' + response.data.find.id)
      $('#qty_pr').val(response.data.find.qty)
	  $('#remarks_pr').val(response.data.find.remarks)
      $('#request_number_pr').val(document.getElementById('request_number_original').value)
	  
      let produkSelect = response.data.find.id_master_tool_auxiliaries

      $('#id_master_tool_auxiliaries_pr').empty()
      $('#id_master_tool_auxiliaries_pr').append(` <option>Pilih Produk</option>`)
      $.each(response.data.ms_tool_auxiliaries, function (i, value) {
        let isSelected = produkSelect == value.id ? 'selected' : ''

        $('#id_master_tool_auxiliaries_pr').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.description + `</option>`
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

function detail_entry_material_use_edit(id) {
  
  // Kirim data melalui Ajax
  $.ajax({
    url: '/production-entry-material-use-detail-edit-get/' + id,
    method: 'GET',
    data: {
      id: id
    },
    success: function (response) {
      // Tangkap pesan dari server dan tampilkan ke user
      // console.log(response.data.find.cc_co);

      $('#form_detail_sparepart_auxiliaries_edit').attr('action', '/production-req-sparepart-auxiliaries-detail-edit-save/' + response.data.find.id)
      $('#qty_pr').val(response.data.find.qty)
	  $('#remarks_pr').val(response.data.find.remarks)
      $('#request_number_pr').val(document.getElementById('request_number_original').value)
	  
      let produkSelect = response.data.find.id_master_tool_auxiliaries

      $('#id_master_tool_auxiliaries_pr').empty()
      $('#id_master_tool_auxiliaries_pr').append(` <option>Pilih Produk</option>`)
      $.each(response.data.ms_tool_auxiliaries, function (i, value) {
        let isSelected = produkSelect == value.id ? 'selected' : ''

        $('#id_master_tool_auxiliaries_pr').append(
          `<option value="` + value.id + `"` + isSelected + `>` + value.description + `</option>`
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


function get_data() {
  
  request_number = $('.request_number option:selected').attr('data-id');
  // alert(request_number);
  $.ajax({
    url: '/get-data',
    method: 'GET',
    data: {id : request_number},
    success: function (response) {
      console.log(response);
      // unit = response.data_lengkap.master_unit.unit_code
      rn = response.data_lengkap[0].request_number
      sp = response.data_lengkap[0].name
      // console.log(unit);
      // Loop melalui data dan tambahkan opsi ke dalam select
      // $('#type_pr').val(response.pr_detail.type)
      $('#reference_number').empty()
        $('#reference_number').append(` <option>Pilih Reference Number</option>`)
        $.each(response.data_pr, function (i, value) {
          isSelected = value.request_number == rn ? 'selected' : '';
          $('#request_number').append(
            `<option value="` + value.id + `" ` + isSelected + `>` + value.request_number + `</option>`
          )
        });

      $('#id_master_suppliers').empty()
        $('#id_master_suppliers').append(` <option>Pilih Supplier</option>`)
        $.each(response.data_sp, function (i, value) {
          isSelected = value.name == sp ? 'selected' : '';
          $('#id_master_suppliers').append(
            `<option value="` + value.id + `" ` + isSelected + `>` + value.name + `</option>`
          )
        });
        $('#type').val(response.data_lengkap[0].type)
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
}

function get_data_pr() {
  
  request_number = $('.request_number option:selected').attr('data-id');
  // alert(request_number);
  $.ajax({
    url: '/get-data',
    method: 'GET',
    data: {id : request_number},
    success: function (response) {
      console.log(response);
      // unit = response.data_lengkap.master_unit.unit_code
      sp = response.data_lengkap_pr[0].name
      // console.log(unit);
      // Loop melalui data dan tambahkan opsi ke dalam select
      // $('#type_pr').val(response.pr_detail.type)

      $('#id_master_suppliers').empty()
        $('#id_master_suppliers').append(` <option>Pilih Supplier</option>`)
        $.each(response.data_sp, function (i, value) {
          isSelected = value.name == sp ? 'selected' : '';
          $('#id_master_suppliers').append(
            `<option value="` + value.id + `" ` + isSelected + `>` + value.name + `</option>`
          )
        });
        $('#type').val(response.data_lengkap_pr[0].type)
    },
    error: function (xhr, status, error) {
      // Tangkap pesan error jika ada
      alert('Terjadi kesalahan saat mengirim data.');
    }
  });
}

function lot_number(id,id_good_receipt_notes) {
  $.ajax({
      type: 'GET',
      url: '/generate-code', // Ganti dengan URL rute Laravel yang sesuai
      data: { id: id }, // Mengirim id sebagai data dalam permintaan Ajax
      success: function (response) {
          $('#generatedCode').val(response.data.find);
          $('#idOke').val(id); // Mengisi nilai dari elemen dengan ID 'idOke' dengan nilai 'id'
          $('#id_good_receipt_notes').val(id_good_receipt_notes); // Mengisi nilai dari elemen dengan ID 'idOke' dengan nilai 'id'
      },
      error: function (error) {
          console.log(error);
      }
  });
}

function lot_number_edit(id,lot_number,id_good_receipt_notes) {
  $.ajax({
    type: 'GET',
    url: '/generate-code', // Ganti dengan URL rute Laravel yang sesuai
    data: { id: id }, // Mengirim id sebagai data dalam permintaan Ajax
    success: function (response) {
        $('#generatedCode').val(response.data.find);
        $('#idOke2').val(id); // Mengisi nilai dari elemen dengan ID 'idOke' dengan nilai 'id'
        $('#lot_numberok').val(lot_number); // Mengisi nilai dari elemen dengan ID 'idOke' dengan nilai 'id'
        $('#id_good_receipt_notes_edit').val(id_good_receipt_notes); 
    },
    error: function (error) {
        console.log(error);
    }
});
}



  function edit_grn_detail(id) {
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

  function ext_lot_number(id) {
    // console.log(id);
    $('#id_ext_lot').val(id);
    // $.ajax({
    //     type: 'GET',
    //     data: { id: id }, // Mengirim id sebagai data dalam permintaan Ajax
    //     success: function (response) {
    //         $('#id_ext_lot').val(5); // Mengisi nilai dari elemen dengan ID 'idOke' dengan nilai 'id'
    //     },
    //     error: function (error) {
    //         console.log(error);
    //     }
    // });
  }








