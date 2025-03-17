<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="logbook-detail-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Hari ke</th>
                        <th>Tanggal</th>
                        <th>Uraian Kegiatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logbook as $i => $l): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $l['nama_mahasiswa'] ?></td>
                        <td><?= $l['nim'] ?></td>
                        <td><?= $l['hari_ke'] ?></td>
                        <td><?= date('d/m/Y', strtotime($l['tanggal'])) ?></td>
                        <td style="white-space: pre-line;"><?= $l['uraian_kegiatan'] ?></td>
                        <td>
                            <span class="badge badge-<?= 
                                $l['paraf_pembimbing'] === 'disetujui' ? 'success' : 
                                ($l['paraf_pembimbing'] === 'ditolak' ? 'danger' : 'warning') 
                            ?>">
                                <?= ucfirst($l['paraf_pembimbing']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if($l['paraf_pembimbing'] !== 'disetujui'): ?>
                                <button type="button" 
                                        class="btn btn-success btn-sm paraf-btn" 
                                        data-id="<?= $l['id_logbook'] ?>" 
                                        data-action="disetujui">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button type="button" 
                                        class="btn btn-danger btn-sm paraf-btn" 
                                        data-id="<?= $l['id_logbook'] ?>" 
                                        data-action="ditolak">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-success btn-sm" disabled>
                                    <i class="fas fa-check"></i> Disetujui
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.paraf-btn').click(function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        const button = $(this);
        
        if (confirm('Apakah Anda yakin ingin ' + (action === 'disetujui' ? 'menyetujui' : 'menolak') + ' logbook ini?')) {
            $.ajax({
                url: '<?= base_url('Dosen/updateParafLogbook') ?>',
                type: 'POST',
                data: {
                    id_logbook: id,
                    status: action
                },
                success: function(response) {
                    if (response.status) {
                        const statusBadge = button.closest('tr').find('.badge');
                        statusBadge.removeClass('badge-warning badge-danger badge-success')
                            .addClass('badge-' + (action === 'disetujui' ? 'success' : 'danger'))
                            .text(action === 'disetujui' ? 'Disetujui' : 'Ditolak');

                        if (action === 'disetujui') {
                            const actionCell = button.closest('td');
                            actionCell.html(`
                                <button type="button" class="btn btn-success btn-sm" disabled>
                                    <i class="fas fa-check"></i> Disetujui
                                </button>
                            `);
                        }

                        alert('Status logbook berhasil diupdate');
                    } else {
                        alert('Gagal mengupdate status logbook');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupdate status');
                }
            });
        }
    });
});
</script>

<style>
.card {
    margin: 15px;
}

.table-responsive {
    padding: 0;
}

#logbook-detail-table {
    width: 100% !important;
}

.modal-xl {
    max-width: 95% !important;
}

.modal-body {
    padding: 20px;
}
</style> 