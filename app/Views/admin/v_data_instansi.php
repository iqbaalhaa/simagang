<div class="d-flex align-items-center mb-3">
    <a href="<?= base_url('Admin/tambahInstansi') ?>" class="btn btn-primary btn-round ml-auto">
        <i class="fas fa-plus"></i> Tambah Instansi
    </a>
</div>

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
    <table id="add-row" class="display table table-striped table-hover table-bordered">
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
                        <a href="<?= site_url('Admin/editInstansi/'.$ins['id_instansi']) ?>" class="btn btn-link btn-primary btn-lg" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" data-toggle="tooltip" title="Hapus" 
                                class="btn btn-link btn-danger" 
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
<!-- Datatables -->
<script src="<?= base_url('backend') ?>/assets/js/plugin/datatables/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable({});

        $('#multi-filter-select').DataTable({
            "pageLength": 5,
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select class="form-control"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });

        $('#add-row').DataTable({
            "pageLength": 5,
        });

        var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $('#addRowButton').click(function() {
            $('#add-row').dataTable().fnAddData([
                $("#addName").val(),
                $("#addPosition").val(),
                $("#addOffice").val(),
                action
            ]);
            $('#addRowModal').modal('hide');

        });
    });
</script>