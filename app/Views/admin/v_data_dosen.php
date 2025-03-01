<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Dosen</h4>
                        <a href="<?= base_url('Admin/tambahDosen') ?>" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-plus"></i> Tambah Dosen
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
                                    <th style="width: 35%">NIDN/NIP/NI PPPK</th>
                                    <th style="width: 40%">Nama</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; 
                                if(is_array($dosen)): 
                                    foreach($dosen as $dsn): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $dsn['nidn'] ?></td>
                                    <td><?= $dsn['nama'] ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <button type="button" data-toggle="tooltip" title="" 
                                                    class="btn btn-link btn-primary btn-lg" 
                                                    data-original-title="Edit"
                                                    onclick="window.location.href='<?= base_url('Admin/editDosen/'.$dsn['id_dosen']) ?>'">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" data-toggle="tooltip" title="" 
                                                    class="btn btn-link btn-danger" 
                                                    data-original-title="Hapus"
                                                    onclick="confirmDelete('<?= base_url('Admin/deleteDosen/'.$dsn['id_dosen']) ?>')">
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