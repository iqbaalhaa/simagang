<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
                    <?php if(session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table id="kelompok-table" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok</th>
                                    <th>Instansi</th>
                                    <th>Ketua Kelompok</th>
                                    <th>NIM Ketua</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelompok as $i => $k): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $k['nama_kelompok'] ?></td>
                                    <td><?= $k['nama_instansi'] ?></td>
                                    <td><?= $k['nama_ketua'] ?></td>
                                    <td><?= $k['nim_ketua'] ?></td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-info btn-sm lihat-logbook" 
                                                data-id="<?= $k['id'] ?>"
                                                data-kelompok="<?= $k['nama_kelompok'] ?>">
                                            <i class="fas fa-book"></i> Lihat Logbook
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>

    <!-- Modal Logbook -->
    <div class="modal fade" id="logbookModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Logbook Kelompok: <span id="kelompokName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="logbookContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#kelompok-table').DataTable({
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    $('.lihat-logbook').click(function() {
        const id = $(this).data('id');
        const namaKelompok = $(this).data('kelompok');
        
        $('#kelompokName').text(namaKelompok);
        
        $.ajax({
            url: '<?= base_url('Dosen/getLogbookKelompok') ?>/' + id,
            type: 'GET',
            success: function(response) {
                $('#logbookContent').html(response);
                $('#logbookModal').modal('show');
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat data logbook');
            }
        });
    });
});
</script>