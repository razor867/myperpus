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
                <h1 class="h2" style="font-weight: 600;">Selamat Datang!!</h1>
                <p class="lead">
                    Silahkan login terlebih dahulu
                </p>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <img src="<?= base_url('img/logo.png') ?>" alt="smkn 1 cikampek" class="img-fluid mt-3 mb-4" height="132" width="132" />
            </div>

            <form action="<?= route_to('login') ?>" method="post">
                <?= csrf_field() ?>

                <?php if ($config->validFields === ['email']) : ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control form-control-lg <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" type="email" name="login" placeholder="Enter your email" />
                        <div class="invalid-feedback">
                            <?= session('errors.login') ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="mb-3">
                        <label class="form-label">Email or Username</label>
                        <input class="form-control form-control-lg <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" type="text" name="login" placeholder="Enter your email or username" />
                        <div class="invalid-feedback">
                            <?= session('errors.login') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" type="password" name="password" placeholder="Enter your password" />
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?>
                    </div>

                    <?php if ($config->activeResetter) : ?>
                        <small>
                            <a href="<?= route_to('forgot') ?>">Forgot password?</a>
                        </small>
                    <?php endif; ?>

                    <small>
                        Hubungi admin jika anda melupakan password (082155573287)
                    </small>
                </div>

                <?php if ($config->allowRemembering) : ?>
                    <div>
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" <?php if (old('remember')) : ?> checked <?php endif ?>>
                            <span class="form-check-label">
                                Remember me next time
                            </span>
                        </label>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-3">
                    <!-- <a href="index.html" class="btn btn-lg btn-primary">Sign in</a> -->
                    <button type="submit" class="btn btn-lg btn-primary mb-3">Sign in</button>

                    <?php if ($config->allowRegistration) : ?>
                        <p><a href="<?= route_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a></p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>