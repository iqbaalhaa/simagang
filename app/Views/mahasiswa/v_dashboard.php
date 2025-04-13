<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Nilai</p>
                            <h4 class="card-title">
                                <?php if (!empty($nilai)): ?>
                                    <?php foreach ($nilai as $n): ?>
                                        <?= $n['nilai'] ?><br>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </h4>
                            <?php if (isset($nilai) && !empty($nilai)): ?>
                            <a href="<?= base_url('Mahasiswa/downloadSertifikat') ?>" class="btn btn-primary">
                                <i class="fas fa-download"></i> Download E-Sertifikat
                            </a>
                            <?php else: ?>
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-download"></i> E-Sertifikat Belum Tersedia
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Dosen Pembimbing</p>
                            <h4 class="card-title"><?= $nama_dosen ?? '-' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Logbook</p>
                            <h4 class="card-title"><?= $total_logbook ?? 0 ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Absensi</p>
                            <h4 class="card-title"><?= $total_absensi ?? 0 ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Riwayat Absensi</div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($riwayat_absensi) && !empty($riwayat_absensi)) : ?>
                                <?php foreach ($riwayat_absensi as $absensi) : ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($absensi['tanggal'])) ?></td>
                                        <td><?= $absensi['kegiatan'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $absensi['status'] == 'hadir' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($absensi['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data absensi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-1 fw-bold">Informasi Profil</h4>
                <div class="card-list">
                    <div class="item-list">
                        <div class="info-user ml-3">
                            <div class="username">NIM</div>
                            <div class="status"><?= $mahasiswa['nim'] ?? '-' ?></div>
                        </div>
                    </div>
                    <div class="item-list">
                        <div class="info-user ml-3">
                            <div class="username">Nama</div>
                            <div class="status"><?= $mahasiswa['nama'] ?? '-' ?></div>
                        </div>
                    </div>
                    <div class="item-list">
                        <div class="info-user ml-3">
                            <div class="username">Download E Sertifikat</div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>