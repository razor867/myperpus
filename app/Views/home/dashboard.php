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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Empty card</h5>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>