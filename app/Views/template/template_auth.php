<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="<?= base_url('img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('img/favicon.ico') ?>" type="image/x-icon">

    <title>Myperpus | <?= empty(preg_match('/login/i', current_url())) ? 'Register' : 'Login' ?></title>

    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <?= $this->renderSection('css_custom') ?>

</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <?= $this->renderSection('main_content') ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="<?= base_url('js/app.js') ?>"></script>

    <?= $this->renderSection('js_plugins') ?>

    <?= $this->renderSection('js_custom') ?>
</body>

</html>