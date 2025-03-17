<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
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
            <table id="tabel-dosen" class="display table table-striped table-hover table-bordered">
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

<!-- Script untuk konfirmasi hapus dan inisialisasi DataTable -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#tabel-dosen')) {
        $('#tabel-dosen').DataTable().destroy();
    }
    
    // Initialize DataTable
    $('#tabel-dosen').DataTable({
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});

function confirmDelete(deleteUrl) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl;
        }
    });
}
</script>