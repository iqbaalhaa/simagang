<div class="page-inner">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Logbook</h4>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addLogbookModal">
                            <i class="fa fa-plus"></i> Tambah Logbook
                        </button>
                    </div>
                </div>
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
                                    <td><?= $l['hari_ke'] ?></td>
                                    <td><?= date('l, d/m/Y', strtotime($l['tanggal'])) ?></td>
                                    <td><?= nl2br($l['uraian_kegiatan']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $l['paraf_pembimbing'] == 'disetujui' ? 'success' : 
                                            ($l['paraf_pembimbing'] == 'ditolak' ? 'danger' : 'warning') ?>">
                                            <?= ucfirst($l['paraf_pembimbing']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($l['paraf_pembimbing'] !== 'disetujui'): ?>
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="editLogbook(<?= $l['id_logbook'] ?>, <?= $l['hari_ke'] ?>, 
                                                             '<?= $l['tanggal'] ?>', '<?= addslashes($l['uraian_kegiatan']) ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="hapusLogbook(<?= $l['id_logbook'] ?>)">
                                                <i class="fas fa-trash"></i>
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
        </div>
    </div>
</div>

<!-- Modal Tambah Logbook -->
<div class="modal fade" id="addLogbookModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Logbook</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Mahasiswa/tambahLogbook') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hari ke</label>
                        <input type="number" class="form-control" name="hari_ke" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label>Uraian Kegiatan</label>
                        <textarea class="form-control" name="uraian_kegiatan" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Logbook -->
<div class="modal fade" id="editLogbookModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Logbook</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLogbookForm" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hari ke</label>
                        <input type="number" class="form-control" name="hari_ke" id="edit_hari_ke" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="edit_tanggal" required>
                    </div>
                    <div class="form-group">
                        <label>Uraian Kegiatan</label>
                        <textarea class="form-control" name="uraian_kegiatan" id="edit_uraian_kegiatan" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#logbook-table').DataTable({
        "order": [[1, "asc"]]
    });
});

function editLogbook(id, hari_ke, tanggal, uraian_kegiatan) {
    $('#editLogbookForm').attr('action', '<?= base_url('Mahasiswa/editLogbook') ?>/' + id);
    $('#edit_hari_ke').val(hari_ke);
    $('#edit_tanggal').val(tanggal);
    $('#edit_uraian_kegiatan').val(uraian_kegiatan);
    $('#editLogbookModal').modal('show');
}

function hapusLogbook(id) {
    if (confirm('Apakah Anda yakin ingin menghapus logbook ini?')) {
        window.location.href = '<?= base_url('Mahasiswa/hapusLogbook') ?>/' + id;
    }
}
</script> 