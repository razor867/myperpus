<?= $this->extend('form/form') ?>

<?= $this->section('form') ?>
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
<form action="<?= $action_url ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
    <input type="hidden" name="id_approval" value="<?= $id_approval ?>">
    <input type="hidden" name="id_anggota" value="<?= $id_anggota ?>">
    <center>
        <h4 style="font-weight: 600;"><?= $judul_buku ?></h4>
    </center>
    <hr>
    <div class="mb-3 row">
        <label for="peminjam" class="col-sm-4 col-form-label">Peminjam</label>
        <div class="col-sm-8">
            <p><?= $peminjam ?></p>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="total_pinjam" class="col-sm-4 col-form-label">Total Pinjam</label>
        <div class="col-sm-8">
            <p><?= $total_pinjam ?> Buku</p>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="tgl_pinjam" class="col-sm-4 col-form-label">Tanggal Pinjam</label>
        <div class="col-sm-8">
            <p><?= $tgl_pinjam ?></p>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="tgl_pengembalian" class="col-sm-4 col-form-label">Tanggal Pengembalian</label>
        <div class="col-sm-8">
            <p><?= $tgl_pengembalian ?></p>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="tgl_dikembalikan" class="col-sm-4 col-form-label">Tanggal Dikembalian</label>
        <div class="col-sm-4">
            <input type="date" class="form-control <?= ($validation->hasError('tgl_dikembalikan') ? 'is-invalid' : '') ?>" id="tgl_dikembalikan" name="tgl_dikembalikan" autofocus value="<?= old('tgl_dikembalikan') ?>">
            <small>Tanggal buku dikembalikan!</small>
            <div class="invalid-feedback">
                <?= $validation->getError('tgl_dikembalikan') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="denda" class="col-sm-4 col-form-label">Denda</label>
        <div class="col-sm-8">
            <input type="number" class="form-control <?= ($validation->hasError('denda') ? 'is-invalid' : '') ?>" min="1" id="denda" name="denda" autofocus value="<?= old('denda') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('denda') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="ket" class="col-sm-4 col-form-label">Keterangan</label>
        <div class="col-sm-8">
            <textarea name="ket" id="ket" class="form-control <?= ($validation->hasError('ket') ? 'is-invalid' : '') ?>" autofocus cols="30" rows="5"><?= old('ket') ?></textarea>
            <small>Contoh: Hilang, Terlambat, Rusak, dll.</small>
            <div class="invalid-feedback">
                <?= $validation->getError('ket') ?>
            </div>
        </div>
    </div>
    <div class="alert alert-primary d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="#info-fill" />
        </svg>
        <div>
            <b>Denda</b> dan <b>Keterangan</b> hanya opsional. Jika terjadi keterlambatan pengembalian buku, masukan <b>Denda</b> dan <b>Keterangannya</b>.
            Apabila terjadi kehilangan buku, tetap input <b>Tanggal dikembalikannya buku</b> lalu isi juga
            <b>Denda</b> dan <b>Keterangannya</b>.
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary d-inline"><i class="fas fa-save"></i> Save</button>
        <button type="reset" class="btn btn-light d-inline"><i class="fas fa-undo-alt"></i> Reset</button>
    </div>
</form>
<?= $this->endSection() ?>