<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Mahasiswa</p>
                            <h4 class="card-title"><?= $total_mahasiswa ?? 0 ?></h4>
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
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Instansi</p>
                            <h4 class="card-title"><?= $total_instansi ?? 0 ?></h4>
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
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Pengajuan Aktif</p>
                            <h4 class="card-title"><?= $total_pengajuan_aktif ?? 0 ?></h4>
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
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="col col-stats ml-3 ml-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Dosen</p>
                            <h4 class="card-title"><?= $total_dosen ?? 0 ?></h4>
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
                    <div class="card-title">Statistik Pengajuan Magang</div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="min-height: 375px">
                    <canvas id="statisticsChart"></canvas>
                </div>
                <div id="myChartLegend"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Status Pengajuan</div>
                <div class="card-category">Ringkasan status pengajuan magang</div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="pieChart" style="width: 50%; height: 50%"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card card-info bg-info-gradient">
            <div class="card-body">
                <h4 class="mb-1 fw-bold">Pengajuan Terbaru</h4>
                <div class="list-group">
                    <?php foreach (($pengajuan_terbaru ?? []) as $pengajuan): ?>
                    <div class="list-group-item border-0 p-2 mb-2 bg-opacity-25 rounded">
                        <h6 class="mb-1"><?= $pengajuan['nama_mahasiswa'] ?></h6>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Statistik Chart
    var ctx = document.getElementById('statisticsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($statistik['labels'] ?? []) ?>,
            datasets: [{
                label: "Pengajuan",
                borderColor: '#1d7af3',
                data: <?= json_encode($statistik['pengajuan'] ?? []) ?>,
                fill: false,
            }, {
                label: "Diterima",
                borderColor: '#59d05d',
                data: <?= json_encode($statistik['diterima'] ?? []) ?>,
                fill: false,
            }, {
                label: "Ditolak",
                borderColor: '#f3545d',
                data: <?= json_encode($statistik['ditolak'] ?? []) ?>,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Pie Chart
    var ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Menunggu', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    <?= $status_pengajuan['Menunggu'] ?? 0 ?>,
                    <?= $status_pengajuan['Diterima'] ?? 0 ?>,
                    <?= $status_pengajuan['Ditolak'] ?? 0 ?>
                ],
                backgroundColor: ['#ffa534', '#59d05d', '#f3545d']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
});
</script>