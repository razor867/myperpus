$(document).ready(function () {
  const $thead = $("thead tr th").length;

  $("#tabledata").DataTable({
    processing: true,
    oLanguage: {
      sLengthMenu: "Tampilkan _MENU_ data per halaman",
      sSearch: "Pencarian: ",
      sZeroRecords: "Maaf, tidak ada data yang ditemukan",
      sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
      sInfoEmpty: "Menampilkan 0 s/d 0 dari 0 data",
      sInfoFiltered: "(di filter dari _MAX_ total data)",
      // oPaginate: {
      //   sFirst: "<<",
      //   slast: ">>",
      //   sPrevious: "<",
      //   sNext: ">",
      // },
    },
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search...",
    },
    columnDefs: [
      {
        orderable: false,
        targets: $thead == 4 ? [] : [4],
      },
      {
        className: "wrap-max-10",
        targets: [0],
      },
      {
        className: "wrap-max-40",
        targets: [1, 2],
      },
      {
        className: "wrap-max-10 dt-nowrap",
        targets: [3],
      },
    ],
    // ordering: true,
    // info: true,
    serverSide: true,
    responsive: true,
    // stateSave: true,
    scrollX: true,
    ajax: {
      url: "/users/listdata",
      type: "get",
      error: function (e) {
        console.log("data tidak ditemukan di server");
      },
      // success: function (data) {
      //   console.log(data);
      // },
    },
    columns: [
      { data: "no", name: "no" },
      { data: "pengguna", name: "pengguna" },
      { data: "role", name: "role" },
      { data: "action", name: "action" },
    ],
  });
});

function detail(id_data) {
  $.ajax({
    url: "/users/detail",
    method: "post",
    dataType: "json",
    data: {
      id: id_data,
    },
    success: function (data) {
      let msg = "";
      if (data != "error") {
        msg +=
          detail_content("Nama Lengkap", data.nama) +
          detail_content("NIS", data.nis);

        $(".modal-title").text("Detail User");
        $(".modal-body").empty();
        $(".modal-body").append(msg);
        // console.log(data);
      } else {
        msg = "Data tidak ditemukan";
        $(".modal-title").text(msg);
        $(".modal-body").text(msg);
      }
    },
  });
}

function detail_content(label, data) {
  let content =
    '<div class="mb-3 row">' +
    '<div class="col-md-4"><span style="font-weight: 600;">' +
    label +
    " " +
    '<div class="float-end">:</div></span></div>' +
    '<div class="col-md-8">' +
    data +
    "</div>" +
    "</div>";
  return content;
}
