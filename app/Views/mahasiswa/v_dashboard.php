<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Bimbingan</p>
                            <h4 class="card-title"><?= $total_bimbingan ?? 0 ?></h4>
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
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Bimbingan Selesai</p>
                            <h4 class="card-title"><?= $bimbingan_selesai ?? 0 ?></h4>
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
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Bimbingan Pending</p>
                            <h4 class="card-title"><?= $bimbingan_pending ?? 0 ?></h4>
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
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Riwayat Bimbingan Terakhir</div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Catatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($riwayat_bimbingan) && !empty($riwayat_bimbingan)) : ?>
                                <?php foreach ($riwayat_bimbingan as $bimbingan) : ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($bimbingan['tanggal'])) ?></td>
                                        <td><?= $bimbingan['catatan'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $bimbingan['status'] == 'selesai' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($bimbingan['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data bimbingan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info bg-info-gradient">
            <div class="card-body">
                <h4 class="mb-1 fw-bold">Progress Bimbingan</h4>
                <div id="task-complete" class="chart-circle mt-4 mb-3"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card-title fw-mediumbold">Informasi Profil</div>
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
                            <div class="username">Program Studi</div>
                            <div class="status"><?= $mahasiswa['prodi'] ?? '-' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi chart progress bimbingan
    var progressPercentage = <?= isset($total_bimbingan) && $total_bimbingan > 0 
        ? ($bimbingan_selesai / $total_bimbingan) * 100 
        : 0 ?>;

    $('#task-complete').sparkline([progressPercentage, 100 - progressPercentage], {
        type: 'pie',
        height: '150px',
        sliceColors: ['#2BB930', '#e9ecef']
    });
});
</script>