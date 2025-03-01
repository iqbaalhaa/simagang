<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Instansi</h4>
                        <a href="<?= base_url('Admin/tambahInstansi') ?>" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-plus"></i> Tambah Instansi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('pesan') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 30%">Nama Instansi</th>
                                    <th style="width: 45%">Alamat</th>
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                $instansiData = isset($instansi) ? $instansi : [];
                                if(is_array($instansiData) && !empty($instansiData)): 
                                    foreach($instansiData as $ins): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $ins['nama_instansi'] ?></td>
                                    <td><?= $ins['alamat'] ?? '-' ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="<?= site_url('Admin/editInstansi/'.$ins['id_instansi']) ?>" class="btn btn-link btn-primary btn-lg" data-toggle="tooltip" title="" data-original-title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" data-toggle="tooltip" title="" 
                                                    class="btn btn-link btn-danger" 
                                                    data-original-title="Hapus"
                                                    onclick="confirmDelete('<?= site_url('Admin/deleteInstansi/'.$ins['id_instansi']) ?>')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; 
                                else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data instansi</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk konfirmasi hapus -->
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