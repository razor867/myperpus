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
        targets: $thead == 2 ? [] : [2],
      },
      {
        className: "wrap-max-40",
        targets: [0],
      },
      {
        className: "wrap-max-50",
        targets: [1],
      },
      {
        className: "wrap-max-10 dt-nowrap",
        targets: [2],
      },
    ],
    // ordering: true,
    // info: true,
    serverSide: true,
    responsive: true,
    // stateSave: true,
    scrollX: true,
    ajax: {
      url: "/category/listdata",
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
