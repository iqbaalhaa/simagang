<div class="container">
    <!-- Form Pencarian -->
    <div class="d-flex justify-content mb-3">
        <form method="GET" action="<?= base_url('Dosen/Penilaian') ?>" class="input-group" style="width: 300px;">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan NIM atau Nama" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
    </div>


    <table class="table table-hover">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Nilai Diberikan</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($mahasiswa)): ?>
                <?php foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?= $mhs['nim'] ?></td>
                        <td><?= $mhs['nama'] ?></td>
                        <td><?= isset($mhs['nilai']) ? $mhs['nilai'] : 'Belum dinilai' ?></td>
                        <td>
                            <input type="number" name="nilai[<?= $mhs['id_mahasiswa'] ?>]" class="form-control" min="0" max="100" placeholder="Masukkan nilai" value="<?= isset($mhs['nilai']) ? $mhs['nilai'] : '' ?>">
                        </td>
                        <td>
                            <button class="btn btn-primary" onclick="submitNilai(<?= $mhs['id_mahasiswa'] ?>)">Simpan</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada mahasiswa untuk dinilai</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= base_url('Dosen/Penilaian?page=' . $i . '&search=' . (isset($_GET['search']) ? $_GET['search'] : '')) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script>
function submitNilai(id_mahasiswa) {
    const nilai = document.querySelector(`input[name="nilai[${id_mahasiswa}]"]`).value;

    // Validasi nilai
    if (nilai === '' || isNaN(nilai) || nilai < 0 || nilai > 100) {
        alert('Masukkan nilai yang valid antara 0 dan 100.');
        return;
    }

    // Kirim data ke server menggunakan AJAX
    fetch('<?= base_url('Dosen/simpanNilai') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_mahasiswa: id_mahasiswa, nilai: nilai })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            alert(data.message);
            location.reload(); // Reload halaman untuk memperbarui data
        } else {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim data.');
    });
}
</script> 