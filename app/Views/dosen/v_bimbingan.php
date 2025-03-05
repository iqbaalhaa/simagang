<div class="row">
    <div class="col-md-12">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Bimbingan Mahasiswa</h4>
                    <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addBimbinganModal">
                        <i class="fa fa-plus"></i>
                        Tambah Bimbingan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-bimbingan" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($bimbingan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= $row['nama_mahasiswa'] ?></td>
                                <td><?= $row['nim'] ?></td>
                                <td><?= $row['catatan'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $row['status'] == 'selesai' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editBimbingan(<?= $row['id_bimbingan'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteBimbingan(<?= $row['id_bimbingan'] ?>)">
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

<!-- Modal Tambah Bimbingan -->
<div class="modal fade" id="addBimbinganModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bimbingan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('dosen/tambahBimbingan') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mahasiswa</label>
                        <select name="id_mahasiswa" class="form-control" required>
                            <option value="">Pilih Mahasiswa</option>
                            <?php foreach ($mahasiswa as $mhs): ?>
                                <option value="<?= $mhs['id_mahasiswa'] ?>">
                                    <?= $mhs['nim'] ?> - <?= $mhs['nama'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Bimbingan</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Bimbingan</label>
                        <textarea name="catatan" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                        </select>
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

<!-- Modal Edit Bimbingan -->
<div class="modal fade" id="editBimbinganModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bimbingan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('dosen/updateBimbingan') ?>" method="POST">
                <input type="hidden" name="id_bimbingan" id="edit_id_bimbingan">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Bimbingan</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Bimbingan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                        </select>
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
// Inisialisasi DataTable
$(document).ready(function() {
    $('#tabel-bimbingan').DataTable();
});

// Fungsi untuk edit bimbingan
function editBimbingan(id) {
    $.ajax({
        url: '<?= base_url('dosen/getBimbingan') ?>/' + id,
        type: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            $('#edit_id_bimbingan').val(data.id_bimbingan);
            $('#edit_tanggal').val(data.tanggal);
            $('#edit_catatan').val(data.catatan);
            $('#edit_status').val(data.status);
            $('#editBimbinganModal').modal('show');
        }
    });
}

// Fungsi untuk delete bimbingan
function deleteBimbingan(id) {
    swal({
        title: 'Apakah Anda yakin?',
        text: "Data bimbingan akan dihapus permanen!",
        type: 'warning',
        buttons:{
            confirm: {
                text : 'Ya, Hapus!',
                className : 'btn btn-success'
            },
            cancel: {
                visible: true,
                className: 'btn btn-danger'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            window.location.href = '<?= base_url('dosen/deleteBimbingan') ?>/' + id;
        }
    });
}
</script> 