<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Tambah Dosen</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('Admin/simpanDosen') ?>" method="POST">
                        <div class="form-group">
                            <label>NIDN</label>
                            <input type="text" name="nidn" class="form-control" value="<?= old('nidn') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="<?= base_url('Admin/DataDosen') ?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 