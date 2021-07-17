$(document).ready(function () {
    const infoFlash = $(".info").attr("info_data");
    let msg = '';
    if (infoFlash != "") {
      if (
        infoFlash == "error_add" ||
        infoFlash == "error_delete" ||
        infoFlash == "error_edit"
      ) {
        msg = (infoFlash == 'error_add') ? 'menambahkan' : ((infoFlash == 'error_delete') ? 'menghapus' : 'merubah');
        Swal.fire({
          icon: "error",
          title: "Terjadi kesalahan...",
          text:
            "Gagal " + msg + " data!",
          showConfirmButton: false,
          timer: 2500,
        });
      } else if (
        infoFlash == "success_add" ||
        infoFlash == "success_delete" ||
        infoFlash == "success_edit"
      ) {
        msg = (infoFlash == 'success_add') ? 'menambahkan' : ((infoFlash == 'success_delete') ? 'menghapus' : 'merubah');
        Swal.fire({
          icon: "success",
          title: "Sukses",
          text:
            "Berhasil " + msg + " data!",
          showConfirmButton: false,
          timer: 1500,
        });
      } else if (infoFlash == "error_system") {
        Swal.fire({
          icon: "error",
          title: "Terjadi kesalahan...",
          text: "Harap hubungi administrator!",
          showConfirmButton: false,
          timer: 2500,
        });
      } else if (infoFlash == "error_pinjam") {
        Swal.fire({
          icon: "error",
          title: "Gagal Pinjam",
          text: "Anda tidak dapat meminjam buku ini, karena anda sudah mengajukan peminjaman sebelumnya dan belum disetujui oleh admin",
          // showConfirmButton: false,
          // timer: 10000,
        });
      } else if (infoFlash == "error_pinjam2") {
        Swal.fire({
          icon: "error",
          title: "Gagal Pinjam",
          text: "Anda tidak dapat meminjam buku ini, karena anda sedang meminjam buku ini dan belum dikembalikan",
          // showConfirmButton: false,
          // timer: 10000,
        });
      } else if (infoFlash == "success_change_status") {
	Swal.fire({
          icon: "success",
          title: "Sukses",
          text: "Perubahan status berhasil dirubah",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    }
  });
  