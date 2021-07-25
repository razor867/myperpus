<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/sweetalert/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
<link rel="stylesheet" href="<?= base_url('css/datatable_style.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/fixedHeader.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<style>
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
<script src="<?= base_url('js/pages/listdata_category.js') ?>"></script>
<script src="<?= base_url('js/extensions/sweetalert.js') ?>"></script>
<script src="<?= base_url('js/action_table.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<div class="row">
    <div class="col-12">
        <div class="info" info_data="<?= session()->getFlashdata('info') ?>"></div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">Daftar kategori buku saat ini</h5>
                    </div>
                    <div class="col-md-8 mt-3">
                        <a href="<?= base_url('category/form') ?>" class="btn btn-primary float-end"><i class="fas fa-plus"></i> Add</a>
                        <div class="dropdown float-end" style="margin-right:10px">
                            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Export
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="<?= base_url('category/convert_document/' . 'excel') ?>"><i class="fas fa-file-excel"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('category/convert_document/' . 'csv') ?>"><i class="fas fa-file-csv"></i> CSV</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('category/convert_document/' . 'pdf') ?>"><i class="fas fa-file-pdf"></i> PDF</a></li>
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
                                <th class="wrap-max-40">Nama</th>
                                <th class="wrap-max-50">Deskripsi</th>
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