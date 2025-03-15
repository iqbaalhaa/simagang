<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php if(session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
                    <?php endif; ?>
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="logbook-table" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Hari ke</th>
                                    <th>Hari/Tanggal</th>
                                    <th>Uraian Kegiatan</th>
                                    <th>Paraf Pembimbing</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logbook as $i => $l): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $l['nama_mahasiswa'] ?></td>
                                    <td><?= $l['hari_ke'] ?></td>
                                    <td><?= date('l, d/m/Y', strtotime($l['tanggal'])) ?></td>
                                    <td><?= nl2br($l['uraian_kegiatan']) ?></td>
                                    <td>
                                        <select class="form-control paraf-select" 
                                                data-id="<?= $l['id_logbook'] ?>"
                                                <?= $l['paraf_pembimbing'] === 'disetujui' ? 'disabled' : '' ?>>
                                            <option value="belum" <?= $l['paraf_pembimbing'] === 'belum' ? 'selected' : '' ?>>
                                                Belum
                                            </option>
                                            <option value="disetujui" <?= $l['paraf_pembimbing'] === 'disetujui' ? 'selected' : '' ?>>
                                                Disetujui
                                            </option>
                                            <option value="ditolak" <?= $l['paraf_pembimbing'] === 'ditolak' ? 'selected' : '' ?>>
                                                Ditolak
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="editLogbook(<?= $l['id_logbook'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteLogbook(<?= $l['id_logbook'] ?>)">
                                            <i class="fas fa-trash"></i>
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
</div>

<script>
$(document).ready(function() {
    $('#logbook-table').DataTable({
        "order": [[2, "asc"], [3, "asc"]]
    });

    $('.paraf-select').change(function() {
        const id = $(this).data('id');
        const status = $(this).val();
        
        if (confirm('Apakah Anda yakin ingin mengubah status paraf?')) {
            $.ajax({
                url: '<?= base_url('Admin/updateParafLogbook') ?>',
                type: 'POST',
                data: {
                    id_logbook: id,
                    status: status
                },
                success: function(response) {
                    if (response.status) {
                        alert('Status paraf berhasil diupdate');
                        if (status === 'disetujui') {
                            $(`select[data-id="${id}"]`).prop('disabled', true);
                        }
                    } else {
                        alert('Gagal mengupdate status paraf');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupdate status');
                }
            });
        } else {
            // Kembalikan ke nilai sebelumnya
            $(this).val($(this).find('option:selected').val());
        }
    });
});

function editLogbook(id) {
    // Implementasi edit logbook
    alert('Edit logbook dengan ID: ' + id);
}

function deleteLogbook(id) {
    if (confirm('Apakah Anda yakin ingin menghapus logbook ini?')) {
        // Implementasi delete logbook
        alert('Hapus logbook dengan ID: ' + id);
    }
}
</script> 