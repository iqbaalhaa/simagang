<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addAbsensiModal">
            <i class="fa fa-plus"></i> Tambah Absensi
        </button>
    </div>
    <div class="card-body">
        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="absensi-table" class="display table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Kegiatan</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($absensi as $i => $a): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                        <td><?= $a['jam_masuk'] ?? '-' ?></td>
                        <td><?= $a['jam_pulang'] ?? '-' ?></td>
                        <td><?= $a['kegiatan'] ?></td>
                        <td>
                            <span class="badge badge-<?= $a['status'] == 'hadir' ? 'success' : ($a['status'] == 'izin' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($a['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($a['bukti_kehadiran'])): ?>
                                <a href="<?= base_url('uploads/absensi/' . $a['bukti_kehadiran']) ?>" 
                                   class="btn btn-info btn-sm" target="_blank">
                                    <i class="fas fa-image"></i>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($a['jam_pulang'] === null && $a['status'] == 'hadir'): ?>
                                <a href="<?= base_url('Mahasiswa/absenPulang/' . $a['id_absensi']) ?>" 
                                   class="btn btn-warning btn-sm" 
                                   onclick="return confirm('Apakah Anda yakin ingin melakukan absen pulang?')">
                                    <i class="fas fa-clock"></i> Pulang
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Absensi -->
<div class="modal fade" id="addAbsensiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('Mahasiswa/tambahAbsensi') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status Kehadiran</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kegiatan</label>
                        <textarea class="form-control" name="kegiatan" rows="3" required></textarea>
                    </div>
                    <div class="form-group" id="bukti-field">
                        <label>Bukti Kehadiran</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="bukti_kehadiran" accept="image/*">
                            <label class="custom-file-label">Pilih file gambar</label>
                        </div>
                        <small class="form-text text-muted">Upload bukti kehadiran (wajib untuk izin/sakit)</small>
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

<script>
$(document).ready(function() {
    $('#absensi-table').DataTable({
        "order": [[1, "desc"], [2, "desc"]]
    });

    // Update label file yang dipilih
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Validasi form berdasarkan status
    $('#status').on('change', function() {
        let status = $(this).val();
        let buktiInput = $('input[name="bukti_kehadiran"]');
        
        if (status === 'izin' || status === 'sakit') {
            buktiInput.prop('required', true);
            $('#bukti-field').show();
        } else {
            buktiInput.prop('required', false);
            $('#bukti-field').hide();
        }
    });

    // Trigger change event pada load untuk set initial state
    $('#status').trigger('change');
});
</script> 