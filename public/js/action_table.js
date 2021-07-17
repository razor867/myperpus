function deleteData(page, id) {
  let link = "";
  if (page == "_datbk") {
    link = "/buku/delete/" + id;
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
