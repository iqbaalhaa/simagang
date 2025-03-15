<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-inner">

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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($loa as $item) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $item['nama_mahasiswa']; ?></td>
                                <td><?= $item['judul']; ?></td>
                                <td><?= $item['deskripsi']; ?></td>
                                <td>
                                    <a href="<?= base_url('uploads/loa/' . $item['file_loa']); ?>" class="btn btn-info btn-sm" download>
                                        <i class="fa fa-download"></i>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($item['status'] == 'pending') : ?>
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton<?= $item['id_loa']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog"></i> Aksi
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="actionButton<?= $item['id_loa']; ?>">
                                                <a class="dropdown-item text-success approve-btn" href="#" data-id="<?= $item['id_loa']; ?>">
                                                    <i class="fa fa-check"></i> Setujui
                                                </a>
                                                <a class="dropdown-item text-danger reject-btn" href="#" data-id="<?= $item['id_loa']; ?>">
                                                    <i class="fa fa-times"></i> Tolak
                                                </a>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <span class="badge badge-<?= $item['status'] == 'disetujui' ? 'success' : 'danger'; ?>">
                                            <?= ucfirst($item['status']); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#catatanModal<?= $item['id_loa']; ?>">
                                        <i class="fa fa-comment"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Catatan -->
                            <div class="modal fade" id="catatanModal<?= $item['id_loa']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tambah Catatan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="<?= base_url('Admin/updateCatatanLoA/' . $item['id_loa']); ?>" method="post">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Catatan</label>
                                                    <textarea class="form-control" name="catatan" rows="4"><?= $item['catatan']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk update status
    function updateStatus(id, status) {
        $.ajax({
            url: '<?= base_url('Admin/updateStatusLoA'); ?>',
            type: 'POST',
            data: {
                id_loa: id,
                status: status
            },
            success: function(response) {
                if(response.status) {
                    Swal.fire(
                        'Berhasil!',
                        'Status LoA telah diperbarui.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                }
            },
            error: function() {
                Swal.fire(
                    'Gagal!',
                    'Terjadi kesalahan saat mengupdate status.',
                    'error'
                );
            }
        });
    }

    // Event handler untuk tombol setujui
    document.querySelectorAll('.approve-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyetujui LoA ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(id, 'disetujui');
                }
            });
        });
    });

    // Event handler untuk tombol tolak
    document.querySelectorAll('.reject-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menolak LoA ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(id, 'ditolak');
                }
            });
        });
    });
});
</script> 