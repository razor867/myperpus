$(document).ready(function () {
  const $thead = $("thead tr th").length;
  const template = document.getElementById("template");

  var t = $("#tabledata").DataTable({
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
      // {
      //   orderable: false,
      //   targets: $thead == 6 ? [2, 3] : [2, 3, 6],
      // },
      {
        className: "wrap-max-10",
        targets: [0],
      },
      {
        className: "wrap-max-30",
        targets: [1, 2],
      },
      {
        className: "wrap-max-10 dt-nowrap",
        targets: [3, 4, 5],
      },
    ],
    ordering: false,
    // info: true,
    serverSide: true,
    responsive: true,
    // stateSave: true,
    scrollX: true,
    ajax: {
      url: "/peminjaman/listdata",
      type: "get",
      error: function (e) {
        console.log("data tidak ditemukan di server");
      },
      // success: function (data) {
      //   console.log(data);
      // },
    },
    fnInitComplete: function (oSettings, json) {
      // tippy(".btn-info", {
      //   trigger: "click",
      //   content: template.innerHTML,
      //   allowHTML: true,
      //   content: "Testing",
      //   appendTo: () => document.body,
      // });
    },
    columns: [
      { data: "no", name: "no" },
      { data: "peminjam", name: "peminjam" },
      { data: "buku", name: "buku" },
      { data: "tgl_pinjam", name: "tgl_pinjam" },
      { data: "tgl_pengembalian", name: "tgl_pengembalian" },
      { data: "action", name: "action" },
    ],
  });
});

function detail(id_data) {
  $.ajax({
    url: "/peminjaman/detail",
    method: "post",
    dataType: "json",
    data: {
      id: id_data,
    },
    success: function (data) {
      let msg = "";
      if (data != "error") {
        msg +=
          detail_content("Judul Buku", data.judul_buku) +
          detail_content("Peminjam", data.peminjam) +
          detail_content("Total Pinjam", data.total_pinjam) +
          detail_content("Tanggal Pinjam", data.tgl_pinjam) +
          detail_content("Tanggal Pengembalian", data.tgl_pengembalian);

        $(".modal-title").text("Detail");
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
    '<div class="col"><span style="font-weight: 600;">' +
    label +
    " " +
    '<div class="float-end">:</div></span></div>' +
    '<div class="col">' +
    data +
    "</div>" +
    "</div>";
  return content;
}
