<?= $this->extend('form/form') ?>

<?= $this->section('form') ?>
<form action="<?= $action_url ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="mb-3 row">
        <label for="nama" class="col-sm-4 col-form-label">Nama</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('nama') ? 'is-invalid' : '') ?>" id="nama" name="nama" autofocus value="<?= ($is_edit) ? $nama : old('nama') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('nama') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="deskripsi" class="col-sm-4 col-form-label">Deskripsi</label>
        <div class="col-sm-8">
            <textarea name="deskripsi" id="deskripsi" class="form-control <?= ($validation->hasError('deskripsi') ? 'is-invalid' : '') ?>" autofocus cols="30" rows="5"><?= ($is_edit) ? $deskripsi : old('deskripsi') ?></textarea>
            <div class="invalid-feedback">
                <?= $validation->getError('deskripsi') ?>
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary d-inline"><i class="fas fa-save"></i> Save</button>
        <button type="reset" class="btn btn-light d-inline"><i class="fas fa-undo-alt"></i> Reset</button>
    </div>
</form>
<?= $this->endSection() ?>