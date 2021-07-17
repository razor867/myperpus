<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/sweetalert/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
<link rel="stylesheet" href="<?= base_url('css/datatable_style.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/fixedHeader.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<style>
    .close-btn {
        background-color: #00ffff00;
        color: #fff;
        border: 0;
        font-size: 14px;
    }

    thead {
        background-color: #ceddf3;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<script src="<?= base_url('plugins/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatable/js/fixedHeader.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatable/js/responsive.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('plugins/sweetalert/sweetalert2.min.js') ?>"></script>
<!-- <script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script> -->
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<script src="<?= base_url('js/pages/listdata_approval.js') ?>"></script>
<script src="<?= base_url('js/extensions/sweetalert.js') ?>"></script>
<script src="<?= base_url('js/action_table.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
<!-- Modal -->
<div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="modalDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="modalDataLabel">Modal title</h4>
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                <div class="change-status"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>
<div class="row">
    <div class="col-12">
        <?php if (in_groups('anggota')) : ?>
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#info-fill" />
                </svg>
                <div>
                    Pastikan setelah membuat pengajuan peminjaman buku, anda harus <b>datang</b> ke perpustakaan
                    untuk mengambil buku dan <b>verifikasi data</b> oleh admin. Jika dalam 1 hari setelah pengajuan
                    peminjaman buku anda tidak datang ke perpustakaan, maka pengajuan peminjaman buku akan
                    <b>ditolak</b> oleh admin.
                </div>
            </div>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Sebelum admin menyetujui pengajuan peminjaman buku, anda masih dapat <b>merubah</b> tanggal pengembalian ataupun <b>membatalkan</b> pengajuan peminjaman buku.
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#info-fill" />
                </svg>
                <div>
                    Sistem secara otomatis akan melakukan <b>Reject</b> pada tiket pengajuan peminjaman buku
                    yang berstatus pending, apabila tidak ada proses verikasi data dari peminjam setelah
                    1 hari dari proses pengajuan peminjaman.
                </div>
            </div>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Admin dapat melakukan perbaikan data pengajuan peminjaman buku, apabila status pengajuan
                    peminjaman buku masih berstasus <b>pending</b>.
                </div>
            </div>
        <?php endif ?>
        <div class="info" info_data="<?= session()->getFlashdata('info') ?>"></div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">Daftar persetujuan peminjaman buku</h5>
                    </div>
                    <div class="col-md-8 mt-3">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabledata" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="wrap-max-10">No</th>
                                <th class="wrap-max-40">Judul Buku</th>
                                <th class="wrap-max-30">Peminjam</th>
                                <th class="wrap-max-10">Status</th>
                                <th class="wrap-max-10 dt-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>