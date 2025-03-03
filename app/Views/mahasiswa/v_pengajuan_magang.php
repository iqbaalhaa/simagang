<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addPengajuanModal">
            <i class="fa fa-plus"></i>
            Tambah Pengajuan
        </button>
    </div>
    <div class="card-body">
        <!-- Tampilkan pesan error/success -->
        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="pengajuan-table" class="display table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelompok</th>
                        <th>Ketua</th>
                        <th>Instansi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kelompok as $i => $k): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $k['nama_kelompok'] ?></td>
                        <td><?= $k['ketua_id'] == $mahasiswa['id_mahasiswa'] ? 'Anda' : $k['nama_ketua'] ?></td>
                        <td><?= $k['nama_instansi'] ?></td>
                        <td>
                            <span class="badge badge-<?= $k['status'] == 'pending' ? 'warning' : ($k['status'] == 'disetujui' ? 'success' : 'danger') ?>">
                                <?= ucfirst($k['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="form-button-action">
                                <button type="button" class="btn btn-info btn-sm" 
                                        onclick="lihatDetail(<?= $k['id'] ?>, '<?= $k['nama_kelompok'] ?>', '<?= $k['nama_instansi'] ?>', '<?= $k['status'] ?>')">
                                    <i class="fa fa-eye"></i>
                                </button>
                                
                                <?php if($k['ketua_id'] == $mahasiswa['id_mahasiswa'] && $k['status'] == 'pending'): ?>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="confirmDelete(<?= $k['id'] ?>, '<?= $k['nama_kelompok'] ?>')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengajuan -->
<div class="modal fade" id="addPengajuanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengajuan Magang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Mahasiswa/tambahPengajuanMagang') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kelompok</label>
                        <input type="text" class="form-control" name="nama_kelompok" required>
                    </div>
                    <div class="form-group">
                        <label>Instansi</label>
                        <select class="form-control" name="instansi_id" required>
                            <option value="">Pilih Instansi</option>
                            <?php foreach ($instansi as $i): ?>
                                <option value="<?= $i['id_instansi'] ?>"><?= $i['nama_instansi'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Anggota Kelompok</label>
                        <select class="form-control select2" name="anggota[]" multiple>
                            <?php foreach ($mahasiswa_tersedia as $m): ?>
                                <option value="<?= $m['id_mahasiswa'] ?>"><?= $m['nim'] . ' - ' . $m['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih maksimal 3 anggota</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Magang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>Nama Kelompok</th>
                        <td id="detail-nama-kelompok"></td>
                    </tr>
                    <tr>
                        <th>Instansi</th>
                        <td id="detail-instansi"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="detail-status"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengajuan magang <span id="delete-nama-kelompok"></span>?</p>
            </div>
            <div class="modal-footer">
                <form action="<?= base_url('Mahasiswa/hapusPengajuanMagang') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="delete-id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
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

    function lihatDetail(id, namaKelompok, instansi, status) {
        $('#detail-nama-kelompok').text(namaKelompok);
        $('#detail-instansi').text(instansi);
        $('#detail-status').text(status);
        $('#detailModal').modal('show');
    }

    function confirmDelete(id, namaKelompok) {
        $('#delete-id').val(id);
        $('#delete-nama-kelompok').text(namaKelompok);
        $('#deleteModal').modal('show');
    }
</script>