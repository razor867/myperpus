<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>