// $(document).ready(function () {
//     $("#datatable").DataTable(),
//         $("#datatable-buttons")
//             .DataTable({
//                 lengthChange: !1,
//                 buttons: ["copy", "excel", "pdf", "colvis"],
//             })
//             .buttons()
//             .container()
//             .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
//         $(".dataTables_length select").addClass("form-select form-select-sm");
// });

function exportToExcel(route, filename, requestData) {
    $("#datatable-buttons")
        .DataTable({
            paging: false,
            info: false,
            lengthChange: !1,
            buttons: [
                {
                    extend: "excel",
                    text: '<i class="fas fa-file-excel"></i> | Export to Excel',
                    action: function (e, dt, button, config) {
                        $.ajax({
                            url: route,
                            method: "GET",
                            data: requestData,
                            success: function (response) {
                                generateExcel(response, filename);
                            },
                            error: function (error) {
                                console.error(
                                    "Error sending data to server:",
                                    error
                                );
                            },
                        });
                    },
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    $(".dataTables_length select").addClass("form-select form-select-sm");

    function generateExcel(data, filename) {
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.json_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        var blobData = XLSX.write(wb, {
            bookType: "xlsx",
            mimeType:
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            type: "binary",
        });
        var blob = new Blob([s2ab(blobData)], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    }
}
