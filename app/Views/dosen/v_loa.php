
            <div class="table-responsive">
                <table id="loa-table" class="display table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            <th>Instansi</th>
                            <th>Ketua Kelompok</th>
                            <th>NIM Ketua</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loa as $i => $item): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $item['nama_kelompok'] ?></td>
                                <td><?= $item['nama_instansi'] ?></td>
                                <td><?= $item['nama_mahasiswa'] ?></td>
                                <td><?= $item['nim'] ?></td>
                                <td><?= $item['judul'] ?></td>
                                <td><?= $item['deskripsi'] ?></td>
                                <td>
                                    <a href="<?= base_url('uploads/loa/' . $item['file_loa']) ?>" 
                                       class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i> Lihat
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $item['status'] == 'disetujui' ? 'success' : 
                                        ($item['status'] == 'ditolak' ? 'danger' : 'warning') 
                                    ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td><?= $item['catatan'] ?? '-' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

<script>
$(document).ready(function() {
    $('#loa-table').DataTable();
});
</script> 