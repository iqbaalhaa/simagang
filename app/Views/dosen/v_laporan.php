<div class="card-header">
    <div class="d-flex align-items-center">
        <h4 class="card-title">Laporan Magang Mahasiswa</h4>
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
                    <th>Mahasiswa</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($laporan as $l) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $l['nama_mahasiswa'] ?></td>
                        <td><?= $l['judul'] ?></td>
                        <td><?= $l['deskripsi'] ?></td>
                        <td>
                            <span class="badge badge-<?= $l['status'] == 'disetujui' ? 'success' : 
                                ($l['status'] == 'ditolak' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($l['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?></td>
                        <td>
                            <a href="<?= base_url('uploads/laporan/' . $l['file_laporan']) ?>" 
                               class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Lihat
                            </a>
                            <?php if ($l['status'] == 'pending') : ?>
                                <button type="button" class="btn btn-success btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#reviewModal<?= $l['id_laporan'] ?>">
                                    <i class="fas fa-check"></i> Review
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Modal Review Laporan -->
                    <div class="modal fade" id="reviewModal<?= $l['id_laporan'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Review Laporan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= base_url('Dosen/reviewLaporan/' . $l['id_laporan']) ?>" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="disetujui">Disetujui</option>
                                                <option value="ditolak">Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <textarea class="form-control" name="catatan" rows="4" required></textarea>
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
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 