<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($anggota)): ?>
                <?php $no = 1;
                foreach ($anggota as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a['nim'] ?></td>
                        <td><?= $a['nama'] ?></td>
                        <td>
                            <span class="badge badge-<?= $a['status'] == 'Ketua' ? 'primary' : 'info' ?>">
                                <?= $a['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data anggota kelompok</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div> 