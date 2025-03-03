
                    <div class="d-flex align-items-center">
                        <a href="<?= base_url('Admin/tambahMahasiswa') ?>" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-plus"></i> Tambah Mahasiswa
                        </a>
                    </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('pesan') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">NIM</th>
                                    <th style="width: 25%">Nama</th>
                                    <th style="width: 10%">Angkatan</th>
                                    <th style="width: 20%">Instansi</th>
                                    <th style="width: 15%">Foto</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; 
                                if(is_array($mahasiswa)): 
                                    foreach($mahasiswa as $mhs): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $mhs['nim'] ?></td>
                                    <td><?= $mhs['nama'] ?></td>
                                    <td><?= $mhs['angkatan'] ?></td>
                                    <td><?= $mhs['nama_instansi'] ?? '-' ?></td>
                                    <td>
                                        <?php if(!empty($mhs['foto'])): ?>
                                            <img src="<?= base_url('foto/mahasiswa/'.$mhs['foto']) ?>" width="100">
                                        <?php else: ?>
                                            <img src="<?= base_url('assets/img/profile.jpg') ?>" width="100">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="form-button-action">
                                            <button type="button" data-toggle="tooltip" title="Edit" 
                                                    class="btn btn-link btn-primary btn-lg" 
                                                    onclick="window.location.href='<?= base_url('Admin/editMahasiswa/'.$mhs['id_mahasiswa']) ?>'">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" data-toggle="tooltip" title="Hapus" 
                                                    class="btn btn-link btn-danger" 
                                                    onclick="confirmDelete('<?= base_url('Admin/deleteMahasiswa/'.$mhs['id_mahasiswa']) ?>')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; 
                                endif; ?>
                            </tbody>
                        </table>
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