<?= $this->extend('form/form') ?>

<?= $this->section('form') ?>
<form action="<?= $action_url ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="mb-3 row">
        <label for="username" class="col-sm-4 col-form-label">Username for login</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('username') ? 'is-invalid' : '') ?>" id="username" name="username" autofocus value="<?= $username ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('username') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="email" class="col-sm-4 col-form-label">Email for login</label>
        <div class="col-sm-8">
            <input type="email" class="form-control <?= ($validation->hasError('email') ? 'is-invalid' : '') ?>" id="email" name="email" autofocus value="<?= $email ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('email') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="password" class="col-sm-4 col-form-label">Password</label>
        <div class="col-sm-8">
            <input type="password" class="form-control <?= ($validation->hasError('password') ? 'is-invalid' : '') ?>" id="password" name="password" autofocus value="">
            <small>Biarkan kosong jika password tidak ingin dirubah!</small>
            <div class="invalid-feedback">
                <?= $validation->getError('password') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="firstname" class="col-sm-4 col-form-label">Firstname</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('firstname') ? 'is-invalid' : '') ?>" id="firstname" name="firstname" autofocus value="<?= $firstname ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('firstname') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="lastname" class="col-sm-4 col-form-label">Lastname</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('lastname') ? 'is-invalid' : '') ?>" id="lastname" name="lastname" autofocus value="<?= $lastname ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('lastname') ?>
            </div>
        </div>
    </div>
    <?php if (in_groups('super admin') == false && in_groups('admin') == false) : ?>
        <div class="mb-3 row">
            <label for="nis" class="col-sm-4 col-form-label">NIS (Nomor Induk Siswa)</label>
            <div class="col-sm-8">
                <input type="number" class="form-control <?= ($validation->hasError('nis') ? 'is-invalid' : '') ?>" id="nis" name="nis" min="0" required autofocus value="<?= $nis ?>">
                <div class="invalid-feedback">
                    <?= $validation->getError('nis') ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <div class="mb-3 row">
        <label for="tlp" class="col-sm-4 col-form-label">Telepon</label>
        <div class="col-sm-8">
            <input type="number" class="form-control <?= ($validation->hasError('tlp') ? 'is-invalid' : '') ?>" id="tlp" name="tlp" min="0" autofocus value="<?= $tlp ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('tlp') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="jk" class="col-sm-4 col-form-label">Jenis kelamin</label>
        <div class="col-sm-8">
            <select name="jk" id="jk" class="form-control form-select <?= ($validation->hasError('jk') ? 'is-invalid' : '') ?>">
                <?php if ($jk != NULL) : ?>
                    <option value="<?= $jk ?>"><?= ($jk == 1) ? 'Laki-laki' : 'Perempuan' ?></option>
                <?php else : ?>
                    <option value="">Pilih Jenis Kelamin</option>
                <?php endif ?>
                <option value="1">Laki-laki</option>
                <option value="0">Perempuan</option>
            </select>
            <div class="invalid-feedback">
                <?= $validation->getError('jk') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="about" class="col-sm-4 col-form-label">Tentang anda</label>
        <div class="col-sm-8">
            <textarea name="about" id="about" class="form-control <?= ($validation->hasError('about') ? 'is-invalid' : '') ?>" maxlength="100" autofocus cols="30" rows="5"><?= $about ?></textarea>
            <div class="invalid-feedback">
                <?= $validation->getError('about') ?>
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary d-inline"><i class="fas fa-save"></i> Save</button>
        <button type="reset" class="btn btn-light d-inline"><i class="fas fa-undo-alt"></i> Reset</button>
    </div>
</form>
<?= $this->endSection() ?>