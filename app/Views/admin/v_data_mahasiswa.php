<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h4 class="card-title">Data Mahasiswa</h4>
            <a href="<?= base_url('Admin/tambahMahasiswa') ?>" class="btn btn-primary btn-round ml-auto">
                <i class="fa fa-plus"></i>
                Tambah Mahasiswa
            </a>
        </div>
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
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Angkatan</th>
                        <th>Instansi</th>
                        <th>Foto</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($mahasiswa as $mhs) : 
                        // Skip data non-mahasiswa
                        if (empty($mhs['nim'])) continue;
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $mhs['nim']; ?></td>
                            <td><?= $mhs['nama']; ?></td>
                            <td><?= $mhs['email'] ?? '-'; ?></td>
                            <td><?= $mhs['angkatan']; ?></td>
                            <td><?= $mhs['instansi'] ?? '-'; ?></td>
                            <td>
                                <?php if (!empty($mhs['foto'])) : ?>
                                    <img src="<?= base_url('foto/mahasiswa/' . $mhs['foto']) ?>" alt="Foto Mahasiswa" class="img-thumbnail" width="50">
                                <?php else : ?>
                                    <img src="<?= base_url('foto/default.jpg') ?>" alt="Default" class="img-thumbnail" width="50">
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a href="<?= base_url('Admin/editMahasiswa/' . $mhs['id_mahasiswa']) ?>" class="btn btn-link btn-primary btn-lg" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('Admin/deleteMahasiswa/' . $mhs['id_mahasiswa']) ?>" class="btn btn-link btn-danger" data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fa fa-times"></i>
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

<!-- Script untuk konfirmasi hapus -->
<script>
function confirmDelete(deleteUrl) {
    swal({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        buttons: {
            confirm: {
                text: 'Ya, Hapus!',
                className: 'btn btn-success'
            },
            cancel: {
                visible: true,
                className: 'btn btn-danger'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            window.location.href = deleteUrl;
        }
    });
}

$(document).ready(function() {
    $('#add-row').DataTable({
        "pageLength": 10,
    });
});
</script>