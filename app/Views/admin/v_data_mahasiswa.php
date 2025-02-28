<div class="table-responsive">
    <table id="add-row" class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Ketua</th>
                <th>Kelompok</th>
                <th>Instansi</th>
                <th style="width: 10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Kelompok 1</td>
                <td>PT. ABC</td>
                <td>
                    <div class="form-button-action">
                        <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Hapus">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <!-- Tambahkan data lainnya di sini -->
        </tbody>
    </table>
</div>

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