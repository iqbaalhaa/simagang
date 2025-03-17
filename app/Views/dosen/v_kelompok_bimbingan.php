<div class="table-responsive">
    <table id="kelompok-table" class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Ketua</th>
                <th>NIM Ketua</th>
                <th>Nama Kelompok</th>
                <th>Instansi</th>
                <th>Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelompok as $i => $k): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $k['nama_ketua'] ?></td>
                <td><?= $k['nim_ketua'] ?></td>
                <td><?= $k['nama_kelompok'] ?></td>
                <td><?= $k['nama_instansi'] ?></td>
                <td>
                    <?php if (!empty($k['surat_permohonan'])) : ?>
                        <a href="<?= base_url('uploads/surat_permohonan/' . $k['surat_permohonan']) ?>"
                            class="btn btn-info btn-sm mb-1" target="_blank">
                            <i class="fas fa-file"></i> Surat Permohonan
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($k['surat_pengantar'])) : ?>
                        <a href="<?= base_url('uploads/surat_pengantar/' . $k['surat_pengantar']) ?>"
                            class="btn btn-info btn-sm mb-1" target="_blank">
                            <i class="fas fa-file"></i> Surat Pengantar
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($k['surat_balasan'])) : ?>
                        <a href="<?= base_url('uploads/surat_balasan/' . $k['surat_balasan']) ?>"
                            class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-file"></i> Surat Balasan
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge badge-<?= $k['status'] == 'pending' ? 'warning' : ($k['status'] == 'disetujui' ? 'success' : 'danger') ?>">
                        <?= ucfirst($k['status']) ?>
                    </span>
                </td>
                <td>
                    <button type="button" 
                            class="btn btn-primary btn-sm"
                            onclick="lihatDetail(<?= $k['id'] ?>)">
                        <i class="fas fa-users"></i> Lihat Anggota
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Anggota Kelompok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detail-content">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#kelompok-table').DataTable({
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});

function lihatDetail(id) {
    $.ajax({
        url: '<?= base_url('Dosen/getDetailKelompok') ?>/' + id,
        type: 'GET',
        success: function(response) {
            $('#detail-content').html(response);
            $('#detailModal').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat mengambil data!'
            });
        }
    });
}
</script> 