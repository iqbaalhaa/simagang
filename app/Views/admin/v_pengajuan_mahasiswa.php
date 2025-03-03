<div class="card">
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
                        <th>Nama Ketua</th>
                        <th>NIM Ketua</th>
                        <th>Nama Kelompok</th>
                        <th>Instansi</th>
                        <th>Status</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengajuan as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $p['nama_ketua'] ?></td>
                        <td><?= $p['nim_ketua'] ?></td>
                        <td><?= $p['nama_kelompok'] ?></td>
                        <td><?= $p['nama_instansi'] ?></td>
                        <td>
                            <span class="badge badge-<?= $p['status'] == 'pending' ? 'warning' : ($p['status'] == 'disetujui' ? 'success' : 'danger') ?>">
                                <?= ucfirst($p['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="form-button-action">
                                <button type="button" class="btn btn-info btn-sm" 
                                        onclick="lihatDetail(<?= $p['id'] ?>)">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <?php if($p['status'] == 'pending'): ?>
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="updateStatus(<?= $p['id'] ?>, 'disetujui')">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="updateStatus(<?= $p['id'] ?>, 'ditolak')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="confirmDelete(<?= $p['id'] ?>, '<?= $p['nama_kelompok'] ?>')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Magang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Informasi Kelompok</h6>
                <table class="table">
                    <tr>
                        <th width="30%">Nama Kelompok</th>
                        <td id="detail-nama-kelompok"></td>
                    </tr>
                    <tr>
                        <th>Instansi Tujuan</th>
                        <td id="detail-instansi"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="detail-status"></td>
                    </tr>
                </table>

                <h6 class="mt-4">Anggota Kelompok</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="detail-anggota">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Admin/updateStatusPengajuan') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="id" id="status-pengajuan-id">
                    <input type="hidden" name="status" id="status-value">
                    <p>Apakah Anda yakin ingin <span id="status-action"></span> pengajuan magang ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Update Status</button>
                </div>
            </form>
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
            <form action="<?= base_url('Admin/deletePengajuan') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete-id">
                    <p>Apakah Anda yakin ingin menghapus pengajuan magang kelompok <span id="delete-nama-kelompok" class="font-weight-bold"></span>?</p>
                    <p class="text-danger">Perhatian: Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#pengajuan-table').DataTable({
        "pageLength": 10,
        "order": [[0, "asc"]]
    });
});

function lihatDetail(id) {
    // Ajax request untuk mengambil detail pengajuan
    $.ajax({
        url: '<?= base_url('Admin/getDetailPengajuan') ?>/' + id,
        method: 'GET',
        success: function(response) {
            $('#detail-nama-kelompok').text(response.pengajuan.nama_kelompok);
            $('#detail-instansi').text(response.pengajuan.nama_instansi);
            $('#detail-status').html(`<span class="badge badge-${response.pengajuan.status == 'pending' ? 'warning' : (response.pengajuan.status == 'disetujui' ? 'success' : 'danger')}">${response.pengajuan.status}</span>`);
            
            // Render anggota kelompok
            let anggotaHtml = '';
            response.anggota.forEach((anggota, index) => {
                anggotaHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${anggota.nim}</td>
                        <td>${anggota.nama}</td>
                        <td>${anggota.is_ketua ? 'Ketua' : 'Anggota'}</td>
                    </tr>
                `;
            });
            $('#detail-anggota').html(anggotaHtml);
            
            $('#detailModal').modal('show');
        },
        error: function() {
            alert('Terjadi kesalahan saat mengambil data');
        }
    });
}

function updateStatus(id, status) {
    $('#status-pengajuan-id').val(id);
    $('#status-value').val(status);
    $('#status-action').text(status === 'disetujui' ? 'menyetujui' : 'menolak');
    $('#statusModal').modal('show');
}

function confirmDelete(id, namaKelompok) {
    $('#delete-id').val(id);
    $('#delete-nama-kelompok').text(namaKelompok);
    $('#deleteModal').modal('show');
}
</script>