<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Edit Instansi</h2>
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

                    <form action="<?= base_url('Admin/updateInstansi') ?>" method="POST">
                        <input type="hidden" name="id_instansi" value="<?= $instansi['id_instansi'] ?>">
                        
                        <div class="form-group">
                            <label>Nama Instansi</label>
                            <input type="text" name="nama_instansi" class="form-control" value="<?= old('nama_instansi', $instansi['nama_instansi']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?= old('alamat', $instansi['alamat']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('Admin/DataInstansi') ?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 