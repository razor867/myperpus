$(document).ready(function () {
  const $thead = $("thead tr th").length;

  $("#tabledata").DataTable({
    // dom: "lfrtip",
    // buttons: [
    //   {
    //     extend: "copy",
    //     className: "btn btn-secondary btn-sm",
    //   },
    //   {
    //     extend: "csv",
    //     className: "btn btn-secondary btn-sm",
    //   },
    //   {
    //     extend: "excel",
    //     className: "btn btn-secondary btn-sm",
    //   },
    //   {
    //     extend: "pdf",
    //     className: "btn btn-secondary btn-sm",
    //   },
    //   {
    //     extend: "print",
    //     className: "btn btn-secondary btn-sm",
    //   },
    // ],
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
        targets: $thead == 3 ? [] : [3],
      },
      {
        className: "wrap-max-50",
        targets: [0],
      },
      {
        className: "wrap-max-25",
        targets: [1],
      },
      {
        className: "wrap-max-15",
        targets: [2],
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
      url: "/buku/listdata",
      type: "post",
      error: function (e) {
        console.log("data tidak ditemukan di server");
      },
      // success: function (data) {
      //   console.log(data);
      // },
    },
  });
});

function detail(id_data) {
  $.ajax({
    url: "/buku/detail",
    method: "post",
    dataType: "json",
    data: {
      id: id_data,
    },
    success: function (data) {
      let msg = "";
      if (data != "error") {
        msg +=
          "<h5>Penulis</h5>" +
          "<p>" +
          data.penulis +
          "</p>" +
          "<h5>Penerbit</h5>" +
          "<p>" +
          data.penerbit +
          "</p>" +
          "<h5>Detail</h5>" +
          "<p>" +
          data.deskripsi +
          "</p>" +
          "<h5>Stok Buku</h5>" +
          "<p>" +
          data.stok +
          "</p>";
        $(".modal-title").text(data.judul);
        $(".modal-body").empty();
        $(".modal-body").append(msg);
        if (data.stok < 1) {
          $(".modal-footer").find("btn-primary").remove();
        } else {
          $(".modal-footer")
            .find(".btn-primary")
            .attr("href", "/buku/pinjam/" + data.id);
        }
        // console.log(data);
      } else {
        msg = "Data tidak ditemukan";
        $(".modal-title").text(msg);
        $(".modal-body").text(msg);
      }
    },
  });
}
