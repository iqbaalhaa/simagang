<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Admin</h4>
                        <a href="<?= base_url('Admin/tambahAdmin') ?>" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('pesan') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%">No</th>
                                    <th style="width: 10%">Foto</th>
                                    <th style="width: 25%">Username</th>
                                    <th style="width: 40%">Nama</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; 
                                if(is_array($list_admin)): 
                                    foreach($list_admin as $adm): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if(!empty($adm['foto'])): ?>
                                                <img src="<?= base_url('foto/admin/' . $adm['foto']) ?>" 
                                                     alt="Foto Admin" class="img-thumbnail" 
                                                     style="max-width: 50px;">
                                            <?php else: ?>
                                                <div class="avatar-initial" 
                                                     style="background: #1572E8; color: white; width: 40px; height: 40px; 
                                                            display: flex; align-items: center; justify-content: center; 
                                                            border-radius: 50%;">
                                                    <?= substr($adm['nama'] ?? 'A', 0, 1) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($adm['username'] ?? '') ?></td>
                                        <td><?= esc($adm['nama'] ?? '') ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title="" 
                                                        class="btn btn-link btn-primary btn-lg" 
                                                        data-original-title="Edit"
                                                        onclick="window.location.href='<?= base_url('Admin/editAdmin/'.$adm['id_admin']) ?>'">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title="" 
                                                        class="btn btn-link btn-danger" 
                                                        data-original-title="Hapus"
                                                        onclick="confirmDelete('<?= base_url('Admin/deleteAdmin/'.$adm['id_admin']) ?>')">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(deleteUrl) {
    swal({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
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