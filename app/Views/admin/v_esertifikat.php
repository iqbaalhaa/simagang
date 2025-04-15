<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Sukses!</h5>
        <?= session()->getFlashdata('pesan') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<style>
    .filter-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .filter-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
    }
    .filter-card .card-body {
        padding: 20px;
    }
    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }
    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 8px 16px;
        font-weight: 500;
    }
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    .table-responsive {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }
    .table {
        margin-bottom: 0;
    }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 12px;
    }
    .table tbody td {
        padding: 12px;
        vertical-align: middle;
    }
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="filter-card">
    <div class="card-header">
        <h3 class="card-title">Filter Data</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('Admin/ESertifikat') ?>" method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cari Mahasiswa</label>
                        <input type="text" name="search" class="form-control" placeholder="NIM / Nama" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kelompok</label>
                        <select name="kelompok" class="form-control select2">
                            <option value="">Semua Kelompok</option>
                            <?php foreach ($kelompok as $k) : ?>
                                <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelompok']) && $_GET['kelompok'] == $k['id']) ? 'selected' : '' ?>>
                                    <?= $k['nama_kelompok'] ?> - <?= $k['nama_instansi'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Angkatan</label>
                        <select name="angkatan" class="form-control">
                            <option value="">Semua Angkatan</option>
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--) : ?>
                                <option value="<?= $i ?>" <?= (isset($_GET['angkatan']) && $_GET['angkatan'] == $i) ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table id="tabel-sertifikat" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($mahasiswa as $mhs) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $mhs['nim'] ?></td>
                    <td><?= $mhs['nama'] ?></td>
                    <td><?= $mhs['instansi'] ?></td>
                    <td><?= $mhs['nilai'] ?></td>
                    <td>
                        <a href="<?= base_url('Admin/generateSertifikat/' . $mhs['id_mahasiswa']) ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generate Sertifikat
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Pilih Kelompok",
        allowClear: true
    });
    
    $('#tabel-sertifikat').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>",
                "next": "<i class='fas fa-chevron-right'></i>"
            }
        }
    });
});
</script> 