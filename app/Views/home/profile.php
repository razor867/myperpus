<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
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
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#info-fill" />
            </svg>
            <div>
                Welcome <?= user()->username ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Note: You can change your profile on this page, by clicking the "edit profile" button.</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <?php if (in_groups('super admin')) : ?>
                            <img src="<?= base_url('img/superadmin.png') ?>" class="img-fluid rounded me-1" alt="<?= user()->username ?>" />
                        <?php elseif (in_groups('admin')) : ?>
                            <img src="<?= (user()->jk == 0) ? base_url('img/admin.png') : base_url('img/admincewe.png') ?>" class="img-fluid rounded me-1" alt="<?= user()->username ?>" />
                        <?php else : ?>
                            <img src="<?= (user()->jk == 0) ? base_url('img/usercowo.png') : base_url('img/usercewe.png') ?>" class="img-fluid rounded me-1" alt="<?= user()->username ?>" />
                        <?php endif ?>

                    </div>
                    <div class="col-md-10 mt-3">
                        <h2><?= user()->username ?></h2>
                    </div>
                </div>
                <a href="#" class="btn btn-primary float-end"><i class="fas fa-user-edit"></i> Edit Profil</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>