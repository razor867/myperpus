<?= $this->extend('form/form') ?>

<?= $this->section('form') ?>
<form action="<?= $action_url ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="mb-3 row">
        <label for="username" class="col-sm-4 col-form-label">Username</label>
        <div class="col-sm-8">
            <input type="text" class="form-control <?= ($validation->hasError('username') ? 'is-invalid' : '') ?>" id="username" name="username" autofocus value="<?= ($is_edit) ? $username : old('username') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('username') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="email" class="col-sm-4 col-form-label">Email</label>
        <div class="col-sm-8">
            <input type="mail" class="form-control <?= ($validation->hasError('email') ? 'is-invalid' : '') ?>" id="email" name="email" autofocus value="<?= ($is_edit) ? $email : old('email') ?>">
            <div class="invalid-feedback">
                <?= $validation->getError('email') ?>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="password" class="col-sm-4 col-form-label">Password</label>
        <div class="col-sm-8">
            <div class="input-group">
                <input type="password" class="form-control <?= ($validation->hasError('password') ? 'is-invalid' : '') ?>" id="password" name="password" autofocus value="" aria-label="password" aria-described="show-password">
                <button class="btn btn-outline-secondary show-ps" type="button" id="show-password"><i class="fas fa-eye-slash eye-pass"></i></button>
                <div class="invalid-feedback">
                    <?= $validation->getError('password') ?>
                </div>
            </div>
            <small>Biarkan kosong jika password tidak ingin dirubah!</small>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="pass_confirm" class="col-sm-4 col-form-label">Repeat Password</label>
        <div class="col-sm-8">
            <div class="input-group">
                <input type="password" class="form-control <?= ($validation->hasError('pass_confirm') ? 'is-invalid' : '') ?>" id="pass_confirm" name="pass_confirm" autofocus value="" aria-label="pass_confirm" aria-described="show-pass_confirm">
                <button class="btn btn-outline-secondary show-ps" type="button" id="show-pass_confirm"><i class="fas fa-eye-slash eye-pass-conf"></i></button>
                <div class="invalid-feedback">
                    <?= $validation->getError('pass_confirm') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="group_id" class="col-sm-4 col-form-label">Role</label>
        <div class="col-sm-8">
            <select name="group_id" id="group_id" class="form-control form-select <?= ($validation->hasError('group_id') ? 'is-invalid' : '') ?>">
                <?php if ($is_edit) : ?>
                    <option value="<?= $group_id ?>"><?= $group_name->name ?></option>
                <?php else : ?>
                    <option value="">Pilih Role</option>
                <?php endif ?>
                <?php foreach ($group as $grp) : ?>
                    <option value="<?= encode($grp->id) ?>"><?= $grp->name ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">
                <?= $validation->getError('group_id') ?>
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary d-inline"><i class="fas fa-save"></i> Save</button>
        <button type="reset" class="btn btn-light d-inline"><i class="fas fa-undo-alt"></i> Reset</button>
    </div>
</form>
<?= $this->endSection() ?>