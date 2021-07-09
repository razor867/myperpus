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

    <title>Myperpus | <?= $title ?></title>

    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <?= $this->renderSection('css_custom') ?>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="<?= base_url() ?>">
                    <span class="align-middle">Myperpus</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Pages
                    </li>

                    <?php if (user()->update_bio == 1) : ?>
                        <li class="sidebar-item <?= ($menu == 'dashboard') ? 'active' : '' ?>">
                            <a class="sidebar-link" href="<?= base_url() ?>">
                                <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= ($menu == 'buku') ? 'active' : '' ?>">
                            <a class="sidebar-link" href="<?= base_url('buku') ?>">
                                <i class="align-middle" data-feather="book"></i> <span class="align-middle">Buku</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= ($menu == 'kategori') ? 'active' : '' ?>">
                            <a class="sidebar-link" href="<?= base_url('category') ?>">
                                <i class="align-middle" data-feather="tag"></i> <span class="align-middle">Kategori</span>
                            </a>
                        </li>
                    <?php endif ?>

                    <li class="sidebar-item <?= ($menu == 'profile') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="<?= base_url('home/profile') ?>">
                            <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
                        </a>
                    </li>

            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <?php if (in_groups('super admin')) : ?>
                                    <img src="<?= base_url('img/superadmin.png') ?>" class="avatar img-fluid rounded me-1" alt="<?= user()->username ?>" />
                                <?php elseif (in_groups('admin')) : ?>
                                    <img src="<?= (user()->jk == 1) ? base_url('img/admin.png') : base_url('img/admincewe.png') ?>" class="avatar img-fluid rounded me-1" alt="<?= user()->username ?>" />
                                <?php else : ?>
                                    <img src="<?= (user()->jk == 1) ? base_url('img/usercowo.png') : base_url('img/usercewe.png') ?>" class="avatar img-fluid rounded me-1" alt="<?= user()->username ?>" />
                                <?php endif ?>

                                <span class="text-dark"><?= user()->username ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php if (user()->update_bio == 1) : ?>
                                    <a class="dropdown-item" href="<?= base_url('home/profile') ?>"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                                    <div class="dropdown-divider"></div>
                                <?php endif ?>

                                <!-- <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="pages-settings.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a> -->
                                <a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="align-middle me-1" data-feather="log-out"></i> Log out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"><?= $title_page ?></h1>

                    <?= $this->renderSection('main_content') ?>

                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-12">
                            <p class="mb-0">
                                &copy; Myperpus 2021 | SMKN 1 CIKAMPEK
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?= $this->renderSection('modal_custom') ?>

    <script src="<?= base_url('js/app.js') ?>"></script>
    <!-- <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script> -->

    <?= $this->renderSection('js_plugins') ?>

    <?= $this->renderSection('js_custom') ?>

</body>

</html>