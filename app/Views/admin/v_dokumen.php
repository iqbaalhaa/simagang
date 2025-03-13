<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#modalTambahDokumen">
            <i class="fa fa-plus"></i>
            Tambah Dokumen
        </button>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('pesan')) : ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('pesan'); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="add-row" class="display table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokumen</th>
                        <th>Tanggal Upload</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($dokumen as $dok) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $dok['nama_dokumen']; ?></td>
                            <td><?= date('d/m/Y', strtotime($dok['tgl_upload'])); ?></td>
                            <td><?= $dok['keterangan']; ?></td>
                            <td>
                                <span class="badge <?= $dok['status'] == 'Aktif' ? 'badge-success' : 'badge-danger'; ?>">
                                    <?= $dok['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href="<?= base_url('uploads/dokumen/' . $dok['file_dokumen']); ?>" class="btn btn-info btn-sm" download>
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="<?= base_url('Admin/Dokumen/hapus/' . $dok['id_dokumen']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dokumen Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Admin/tambahDokumen'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Dokumen</label>
                        <input type="text" class="form-control" name="nama_dokumen" required>
                    </div>
                    <div class="form-group">
                        <label>File Dokumen</label>
                        <input type="file" class="form-control" name="file_dokumen" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
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