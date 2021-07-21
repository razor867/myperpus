<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<link rel="stylesheet" href="<?= base_url('plugins/sweetalert/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
<link rel="stylesheet" href="<?= base_url('plugins/select2/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/select2/select2-bootstrap-5-theme.min.css') ?>">
<style>
    span#select2-category_id-container,
    span#select2-jk-container {
        font-size: 14px;
    }

    ul#select2-category_id-results li,
    ul#select2-jk-results li {
        font-size: 14px;
    }

    span#select2-group_id-container,
    span#select2-jk-container {
        font-size: 14px;
    }

    ul#select2-group_id-results li,
    ul#select2-jk-results li {
        font-size: 14px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<script src="<?= base_url('plugins/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('plugins/sweetalert/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('plugins/select2/select2.min.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<script src="<?= base_url('js/extensions/sweetalert.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("select").select2({
            theme: "bootstrap-5",
        });
        $('.swal2-container').find('.select2').css('display', 'none');

        var inp_pass = document.getElementById("password");
        var inp_pass_conf = document.getElementById("pass_confirm");
        $('#show-password').click(function() {
            if (inp_pass.type === "password") {
                inp_pass.type = "text";
                $('.eye-pass').addClass('fa-eye');
                $('.eye-pass').removeClass('fa-eye-slash');
            } else {
                inp_pass.type = "password";
                $('.eye-pass').addClass('fa-eye-slash');
                $('.eye-pass').removeClass('fa-eye');
            }
        })

        $('#show-pass_confirm').click(function() {
            if (inp_pass_conf.type === "password") {
                inp_pass_conf.type = "text";
                $('.eye-pass-conf').addClass('fa-eye');
                $('.eye-pass-conf').removeClass('fa-eye-slash');
            } else {
                inp_pass_conf.type = "password";
                $('.eye-pass-conf').addClass('fa-eye-slash');
                $('.eye-pass-conf').removeClass('fa-eye');
            }
        })
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="info" info_data="<?= session()->getFlashdata('info') ?>"></div>
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title">Form <?= $title_page ?></h5>
                    </div>
                    <div class="col-md-8">
                        <a href="<?= $back ?>" class="btn btn-secondary float-end"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->renderSection('form') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>