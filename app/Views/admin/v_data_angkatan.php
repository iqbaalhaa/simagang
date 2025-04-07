<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

            <div class="card-body">
                <!-- Filter and Export Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filterAngkatan">Filter Angkatan:</label>
                            <select class="form-control" id="filterAngkatan" style="width: 200px;">
                                <option value="">Semua Angkatan</option>
                                <?php
                                if (isset($angkatan_list) && is_array($angkatan_list)) {
                                    foreach ($angkatan_list as $angkatan) {
                                        echo "<option value='{$angkatan}'>{$angkatan}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button id="exportExcel" class="btn btn-success mr-2">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button id="exportPDF" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data Mahasiswa -->
                <div class="table-responsive">
                    <table id="mahasiswaTable" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Angkatan</th>
                                <th>Email</th>
                                <th>Instansi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($mahasiswa as $mhs): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $mhs['nim'] ?? '-' ?></td>
                                <td><?= $mhs['nama'] ?></td>
                                <td><?= $mhs['angkatan'] ?? '-' ?></td>
                                <td><?= $mhs['email'] ?? '-' ?></td>
                                <td><?= $mhs['instansi'] ?? '-' ?></td>
                                <td>
                                    <?php
                                    // Cek status magang dari pengajuan_magang
                                    $status = '-';
                                    if (isset($status_magang[$mhs['id_mahasiswa']])) {
                                        $status_class = '';
                                        switch ($status_magang[$mhs['id_mahasiswa']]) {
                                            case 'pending':
                                                $status = 'Menunggu';
                                                $status_class = 'badge badge-warning';
                                                break;
                                            case 'disetujui':
                                                $status = 'Aktif Magang';
                                                $status_class = 'badge badge-success';
                                                break;
                                            case 'ditolak':
                                                $status = 'Ditolak';
                                                $status_class = 'badge badge-danger';
                                                break;
                                        }
                                        echo "<span class='{$status_class}'>{$status}</span>";
                                    } else {
                                        echo "<span class='badge badge-secondary'>Belum Mendaftar</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#mahasiswaTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'print',
                className: 'btn btn-secondary btn-sm'
            }
        ],
        pageLength: 10,
        order: [[3, 'desc']], // Sort by angkatan column by default
    });

    // Filter berdasarkan angkatan
    $('#filterAngkatan').on('change', function() {
        var angkatan = $(this).val();
        table.column(3).search(angkatan).draw();
    });

    // Handle PDF export button click
    $('#exportPDF').on('click', function() {
        var angkatan = $('#filterAngkatan').val();
        var url = '<?= base_url('Admin/cetakPDFAngkatan') ?>';
        if (angkatan) {
            url += '?angkatan=' + angkatan;
        }
        window.open(url, '_blank');
    });

    // Handle Excel export button click
    $('#exportExcel').on('click', function() {
        var angkatan = $('#filterAngkatan').val();
        
        // Get filtered data
        var data = table
            .rows(function(idx, data, node) {
                return angkatan === '' || data[3] === angkatan;
            })
            .data();
            
        // Create a new workbook
        var wb = XLSX.utils.book_new();
        
        // Convert data to array format
        var excelData = [];
        // Add headers
        excelData.push(['No', 'NIM', 'Nama', 'Angkatan', 'Email', 'Instansi', 'Status']);
        
        // Add data rows
        data.each(function(row) {
            excelData.push([
                row[0],
                row[1],
                row[2],
                row[3],
                row[4],
                row[5],
                row[6]
            ]);
        });
        
        // Create worksheet
        var ws = XLSX.utils.aoa_to_sheet(excelData);
        
        // Set column widths
        var wscols = [
            {wch: 5},  // No
            {wch: 12}, // NIM
            {wch: 30}, // Nama
            {wch: 10}, // Angkatan
            {wch: 30}, // Email
            {wch: 30}, // Instansi
            {wch: 15}  // Status
        ];
        ws['!cols'] = wscols;
        
        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Data Mahasiswa');
        
        // Generate filename with angkatan if filtered
        var filename = 'Data_Mahasiswa' + (angkatan ? '_Angkatan_' + angkatan : '') + '.xlsx';
        
        // Save the file
        XLSX.writeFile(wb, filename);
    });
});
</script> 