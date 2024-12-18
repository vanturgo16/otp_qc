function get_unit() {
  
    request_number = $('.request_number option:selected').attr('data-id');
    // alert(request_number);
    $.ajax({
      url: '/get-unit/',
      method: 'GET',
      data: {id : request_number},
      success: function (response) {
        console.log(response);
        unit = response.po_detail.master_unit.unit_code
        console.log(unit);
        // Loop melalui data dan tambahkan opsi ke dalam select
        // $('#type_pr').val(response.pr_detail.type)
        $('#unit_code').empty()
          $('#unit_code').append(` <option>Pilih Unit</option>`)
          $.each(response.data, function (i, value) {
            isSelected = value.unit_code == unit ? 'selected' : '';
            $('#unit_code').append(
              `<option value="` + value.id + `" ` + isSelected + `>` + value.unit_code + `</option>`
            )
          });
      },
      error: function (xhr, status, error) {
        // Tangkap pesan error jika ada
        alert('Terjadi kesalahan saat mengirim data.');
      }
    });
  }