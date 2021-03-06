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
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<script src="<?= base_url('js/pages/listdata_buku.js') ?>"></script>
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
                <?php if (in_groups('super admin') == false && in_groups('admin') == false) : ?>
                    <div class="pinjam"></div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<div class="row">
    <div class="col-12">
        <div class="info" info_data="<?= session()->getFlashdata('info') ?>"></div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">Daftar buku saat ini</h5>
                    </div>
                    <div class="col-md-8 mt-3">
                        <?php if (!in_groups('anggota')) : ?>
                            <a href="<?= base_url('buku/form') ?>" class="btn btn-primary float-end"><i class="fas fa-plus"></i> Add</a>
                        <?php endif ?>

                        <div class="dropdown float-end" style="margin-right:10px">
                            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Export
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="<?= base_url('buku/convert_document/' . 'excel') ?>"><i class="fas fa-file-excel"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('buku/convert_document/' . 'csv') ?>"><i class="fas fa-file-csv"></i> CSV</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('buku/convert_document/' . 'pdf') ?>"><i class="fas fa-file-pdf"></i> PDF</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabledata" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="wrap-max-50">Judul</th>
                                <th class="wrap-max-25">Penulis</th>
                                <th class="wrap-max-15">Kategori</th>
                                <th class="wrap-max-10 dt-nowrap"><?= in_groups('anggota') ? 'Stok' : 'Action' ?></th>
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