<?= $this->extend('template/template_auth') ?>

<?= $this->section('css_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<style>
    body {
        background-color: beige;
    }

    @media (min-width: 1200px) {

        .container,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl {
            max-width: 1000px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>

<div class="card mt-4">
    <div class="card-body">
        <div class="m-sm-4">
            <div class="text-center">
                <h1 class="h2" style="font-weight: 600;">Daftar</h1>
                <p class="lead">
                    Buat akun untuk menggunakan aplikasi
                </p>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <img src="<?= base_url('img/logo.png') ?>" alt="smkn 1 cikampek" class="img-fluid mt-3 mb-4" height="132" width="132" />
            </div>

            <form action="<?= route_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control form-control-lg <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" type="email" name="email" placeholder="Enter your email" value="<?= old('email') ?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input class="form-control form-control-lg <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" type="text" name="username" placeholder="Enter your username" value="<?= old('username') ?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" type="password" name="password" placeholder="Enter your password" autocomplete="off" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Repeat Password</label>
                    <input class="form-control form-control-lg <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" type="password" name="pass_confirm" placeholder="Enter your password" autocomplete="off" />
                </div>

                <div class="text-center mt-3">
                    <!-- <a href="index.html" class="btn btn-lg btn-primary">Sign in</a> -->
                    <button type="submit" class="btn btn-lg btn-primary mb-3">Register</button>

                    <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= route_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>