<div class="card-header">
    <div class="d-flex align-items-center">
        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-plus"></i>
            Add Row
        </button>
    </div>
</div>
<div class="card-body">
    <!-- Modal -->
    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">
                            New</span>
                        <span class="fw-light">
                            Row
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small">Create a new row using this form, make sure you fill them all</p>
                    <form>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Name</label>
                                    <input id="addName" type="text" class="form-control" placeholder="fill name">
                                </div>
                            </div>
                            <div class="col-md-6 pr-0">
                                <div class="form-group form-group-default">
                                    <label>Position</label>
                                    <input id="addPosition" type="text" class="form-control" placeholder="fill position">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    <label>Office</label>
                                    <input id="addOffice" type="text" class="form-control" placeholder="fill office">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer no-bd">
                    <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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