<?= $this->extend('template/template') ?>

<?= $this->section('css_custom') ?>
<style>
    a.card_link:hover {
        text-decoration: none;
    }

    a.card_link .card:hover {
        background-color: #e4e4e4;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js_plugins') ?>
<?= $this->endSection() ?>

<?= $this->section('js_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('modal_custom') ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<div class="row">
    <div class="<?= (in_groups('anggota') ? 'col-sm-4' : 'col-sm-3') ?>">
        <a class="card_link" href="<?= base_url('buku') ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Buku</h5>
                        </div>

                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="book"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3"><?= $total_buku ?></h1>
                    <div class="mb-0">
                        <span class="text-muted">Saat ini</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="<?= (in_groups('anggota') ? 'col-sm-4' : 'col-sm-3') ?>">
        <a class="card_link" href="<?= base_url('approval') ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Persetujuan Pending</h5>
                        </div>

                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="clipboard"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3"><?= $total_persetujuan ?></h1>
                    <div class="mb-0">
                        <span class="text-muted">Saat ini</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="<?= (in_groups('anggota') ? 'col-sm-4' : 'col-sm-3') ?>">
        <a class="card_link" href="<?= base_url('peminjaman') ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Peminjaman</h5>
                        </div>

                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="arrow-up-right"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3"><?= $total_peminjaman ?></h1>
                    <div class="mb-0">
                        <span class="text-muted">Saat ini</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php if (!in_groups('anggota')) : ?>
        <div class="col-sm-3">
            <a class="card_link" href="<?= base_url('users') ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Total Users</h5>
                            </div>

                            <div class="col-auto">
                                <div class="stat text-primary">
                                    <i class="align-middle" data-feather="users"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3"><?= $total_pengguna ?></h1>
                        <div class="mb-0">
                            <span class="text-muted">Saat ini</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>