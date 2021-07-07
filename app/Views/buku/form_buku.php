<?= $this->extend('form/form') ?>

<?= $this->section('form') ?>
<form action="<?= $action_url ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="mb-3 row">
        <label for="judul" class="col-sm-4 col-form-label">Judul</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('judul') ? 'is-invalid' : '') ?>" id="judul" name="judul" autofocus value="<?= ($is_edit) ? $judul : old('judul') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('judul') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="penulis" class="col-sm-4 col-form-label">Penulis</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('penulis') ? 'is-invalid' : '') ?>" id="penulis" name="penulis" autofocus value="<?= ($is_edit) ? $penulis : old('penulis') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('penulis') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="penerbit" class="col-sm-4 col-form-label">Penerbit</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('penerbit') ? 'is-invalid' : '') ?>" id="penerbit" name="penerbit" autofocus value="<?= ($is_edit) ? $penerbit : old('penerbit') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('penerbit') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="category_id" class="col-sm-4 col-form-label">Kategori</label>
        <div class="col-sm-8">
            <select name="category_id" id="category_id" class="form-control form-select <?= ($validation->hasError('category_id') ? 'is-invalid' : '') ?>">
                <?php if ($is_edit) : ?>
                    <option value="<?= $category_id ?>"><?= $category_name->nama ?></option>
                <?php else : ?>
                    <option value="">Pilih Kategori</option>
                <?php endif ?>
                <?php foreach ($category as $cat) : ?>
                    <option value="<?= encode($cat->id) ?>"><?= $cat->nama ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">
                <?= $validation->getError('category_id') ?>
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