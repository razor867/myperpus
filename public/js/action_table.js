function deleteData(page, id) {
  let link = "";
  if (page == "_datbk") {
    link = "/home/delete/" + id;
  } else if (page == "_datcat") {
    link = "/category/delete/" + id;
  }

  Swal.fire({
    title: "Yakin hapus data ini?",
    text: "Kamu tidak akan melihatnya lagi!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = link;
    }
  });
}

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
          "</p>";
        $(".modal-title").text(data.judul);
        $(".modal-body").empty();
        $(".modal-body").append(msg);
      } else {
        msg = "Data tidak ditemukan";
        $(".modal-title").text(msg);
        $(".modal-body").text(msg);
      }
    },
  });
}
