<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Absensi Kelompok Bimbingan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="absensi-table" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok</th>
                                    <th>Instansi</th>
                                    <th>Ketua</th>
                                    <th>NIM Ketua</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelompok as $i => $k): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $k['nama_kelompok'] ?></td>
                                    <td><?= $k['nama_instansi'] ?></td>
                                    <td><?= $k['nama_ketua'] ?></td>
                                    <td><?= $k['nim_ketua'] ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" 
                                                onclick="lihatAbsensi(<?= $k['id'] ?>)">
                                            <i class="fas fa-eye"></i> Lihat Absensi
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Absensi -->
    <div class="modal fade" id="modalAbsensi" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Absensi Kelompok</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="absensiContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#absensi-table').DataTable();
});

function lihatAbsensi(id_pengajuan) {
    $.ajax({
        url: '<?= base_url('Dosen/getAbsensiKelompok') ?>/' + id_pengajuan,
        type: 'GET',
        success: function(response) {
            if (response.status) {
                let html = '<div class="accordion" id="accordionAbsensi">';
                
                response.anggota.forEach((anggota, index) => {
                    html += `
                        <div class="card">
                            <div class="card-header" id="heading${index}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" 
                                            data-target="#collapse${index}">
                                        ${anggota.nama} (${anggota.nim})
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse${index}" class="collapse" 
                                 data-parent="#accordionAbsensi">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Status</th>
                                                <th>Kegiatan</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                    
                    const absensi = response.absensi[anggota.id_mahasiswa];
                    if (absensi && absensi.length > 0) {
                        absensi.forEach(a => {
                            html += `
                                <tr>
                                    <td>${formatTanggal(a.tanggal)}</td>
                                    <td>${a.jam_masuk || '-'}</td>
                                    <td>${a.jam_pulang || '-'}</td>
                                    <td>
                                        <span class="badge badge-${
                                            a.status == 'hadir' ? 'success' : 
                                            (a.status == 'izin' ? 'warning' : 'danger')
                                        }">
                                            ${ucfirst(a.status)}
                                        </span>
                                    </td>
                                    <td>${a.kegiatan}</td>
                                    <td>
                                        ${a.bukti_kehadiran ? 
                                            `<a href="<?= base_url('uploads/absensi/') ?>/${a.bukti_kehadiran}" 
                                                target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-image"></i>
                                            </a>` : '-'}
                                    </td>
                                </tr>`;
                        });
                    } else {
                        html += `<tr><td colspan="6" class="text-center">Belum ada data absensi</td></tr>`;
                    }
                    
                    html += `</tbody></table></div></div></div>`;
                });
                
                html += '</div>';
                $('#absensiContent').html(html);
                $('#modalAbsensi').modal('show');
            } else {
                alert('Gagal memuat data absensi');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseText);
            alert('Terjadi kesalahan saat memuat data');
        }
    });
}

function formatTanggal(tanggal) {
    const date = new Date(tanggal);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script> 