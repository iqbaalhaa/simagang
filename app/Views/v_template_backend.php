<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard | <?= $judul ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('backend') ?>/assets/img/favicon.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="<?= base_url('backend') ?>/assets/js/plugin/webfont/webfont.min.js"></script>

    <script>
        WebFont.load({
            google: {
                "families": ["Open+Sans:300,400,600,700"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"],
                urls: ['<?= base_url('backend') ?>/assets/css/fonts.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('backend') ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('backend') ?>/assets/css/azzara.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="<?= base_url('backend') ?>/assets/css/demo.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

    <!-- Di bagian head -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <!--
			Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
        <div class="main-header" data-background-color="light-blue">
            <!-- Logo Header -->
            <div class="logo-header">

                <a href="index.html" class="logo" style="font-size: 25px; font-weight:bold; color:white; margin-left: 20px;">
                    Si Magang
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fa fa-bars"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="fa fa-ellipsis-v"></i></button>
                <div class="navbar-minimize">
                    <button class="btn btn-minimize btn-rounded">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg">

                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <?php if (!empty($admin['foto'])): ?>
                                        <img src="<?= base_url('foto/admin/' . $admin['foto']) ?>" alt="..." class="avatar-img rounded-circle">
                                    <?php else: ?>
                                        <!-- Tampilkan inisial nama jika tidak ada foto -->
                                        <div class="avatar-initial rounded-circle" style="background: #1572E8; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <?= substr($admin['nama'] ?? 'A', 0, 1) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg">
                                            <?php 
                                            $adminNama = isset($admin['nama']) ? $admin['nama'] : 'Admin';
                                            $adminFoto = isset($admin['foto']) ? $admin['foto'] : '';
                                            ?>
                                            
                                            <?php if (!empty($adminFoto)): ?>
                                                <img src="<?= base_url('foto/admin/' . $adminFoto) ?>" alt="image profile" class="avatar-img rounded">
                                            <?php else: ?>
                                                <div class="avatar-initial rounded" style="background: #1572E8; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                                    <?= substr($adminNama, 0, 1) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="u-text">
                                            <h4><?= $adminNama ?></h4>
                                            <p class="text-muted">Administrator</p>
                                            <a href="<?= base_url('Auth/logout') ?>" class="btn btn-rounded btn-danger btn-sm">Logout</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= base_url('Admin/Profil') ?>">Profil Saya</a>
                                    <div class="dropdown-divider"></div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar">

            <div class="sidebar-background"></div>
            <div class="sidebar-wrapper scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user">
                        <div class="avatar-sm float-left mr-2">
                            <?php if (!empty($admin['foto'])): ?>
                                <img src="<?= base_url('foto/admin/' . $admin['foto']) ?>" alt="..." class="avatar-img rounded-circle">
                            <?php else: ?>
                                <!-- Tampilkan inisial nama jika tidak ada foto -->
                                <div class="avatar-initial rounded-circle" style="background: #1572E8; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <?= substr($admin['nama'] ?? 'A', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="info">
                            <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                                <span>
                                    <?= $admin['nama'] ?? 'Admin' ?>
                                    <span class="user-level">Administrator</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <ul class="nav">
                        <li class="nav-item active">
                            <a href="<?= base_url('Admin') ?>">
                                <i class="fas fa-desktop"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Menu</h4>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Admin/PengajuanMahasiswa') ?>">
                                <i class="fas fa-server"></i>
                                <p>Pengajuan Mahasiswa</p>
                                <?php if(isset($total_pengajuan) && $total_pengajuan > 0): ?>
                                    <span class="badge badge-count badge-success"><?= $total_pengajuan ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Admin/Instansi') ?>">
                                <i class="fas fa-building"></i>
                                <p>Instansi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#lapro">
                                <i class="fas fa-folder-open"></i>
                                <p>Laporan dan Project</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="lapro">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('Admin/Logbook') ?>">
                                            <span class="sub-item">Logbook</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('Admin/Absensi') ?>">
                                            <span class="sub-item">Absensi</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('Admin/LoA') ?>">
                                            <span class="sub-item">LoA Journal</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Admin/Dokumen') ?>">
                                <i class="fas fa-file-upload"></i>
                                <p>Dokumen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#charts">
                                <i class="fa fa-users"></i>
                                <p>Users</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="<?= base_url('Admin/DataAdmin') ?>">
                                            <span class="sub-item">Admin</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('Admin/DataDosen') ?>">
                                            <span class="sub-item">Dosen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('Admin/DataMahasiswa') ?>">
                                            <span class="sub-item">Mahasiswa</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Admin/ESertifikat') ?>">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <p>E-Sertifikat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('Admin/DataAngkatan') ?>">
                                <i class="fas fa-book"></i>
                                <p>Data Angkatan</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Tambahkan main-panel untuk wrapper konten -->
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title"><?= $judul ?></h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Load konten page di sini -->
                                    <?php if ($page) {
                                        echo view($page);
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

<!--   Core JS Files   -->
<script src="<?= base_url('backend') ?>/assets/js/core/jquery.3.2.1.min.js"></script>
<script src="<?= base_url('backend') ?>/assets/js/core/popper.min.js"></script>
<script src="<?= base_url('backend') ?>/assets/js/core/bootstrap.min.js"></script>

<!-- jQuery UI -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?= base_url('backend') ?>/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Moment JS -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/moment/moment.min.js"></script>

<!-- Chart JS -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Bootstrap Toggle -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url('backend') ?>/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Google Maps Plugin -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/gmaps/gmaps.js"></script>

<!-- Sweet Alert -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Azzara JS -->
<script src="<?= base_url('backend') ?>/assets/js/ready.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>

</html>