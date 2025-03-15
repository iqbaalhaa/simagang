<div class="card-header">
    <div class="d-flex align-items-center">
        <h4 class="card-title">LoA Journal</h4>
        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addLoAModal">
            <i class="fa fa-plus"></i>
            Tambah LoA
        </button>
    </div>
</div>
<div class="card-body">
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table id="add-row" class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Tanggal Upload</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($loa as $l) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $l['judul'] ?></td>
                        <td><?= $l['deskripsi'] ?></td>
                        <td>
                            <span class="badge badge-<?= $l['status'] == 'disetujui' ? 'success' : 
                                ($l['status'] == 'ditolak' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($l['status']) ?>
                            </span>
                        </td>
                        <td><?= $l['catatan'] ?? '-' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?></td>
                        <td>
                            <a href="<?= base_url('uploads/loa/' . $l['file_loa']) ?>" 
                               class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Lihat
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah LoA -->
<div class="modal fade" id="addLoAModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah LoA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Mahasiswa/tambahLoA') ?>" method="POST" 
                  enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>File LoA (PDF, max 2MB)</label>
                        <input type="file" class="form-control" name="file_loa" 
                               accept="application/pdf" required>
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