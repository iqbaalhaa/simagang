<div class="card">
    <div class="card-header">
        <h4 class="card-title">Daftar Dokumen</h4>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('pesan')) : ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('pesan'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error'); ?>
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
                        <?php if ($dok['status'] == 'Aktif') : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $dok['nama_dokumen']; ?></td>
                                <td><?= date('d/m/Y', strtotime($dok['tgl_upload'])); ?></td>
                                <td><?= $dok['keterangan']; ?></td>
                                <td>
                                    <span class="badge badge-success">
                                        <?= $dok['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('Mahasiswa/downloadFile/' . $dok['id_dokumen']); ?>" class="btn btn-primary btn-sm">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 