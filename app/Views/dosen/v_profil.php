<div class="row">
    <div class="col-md-12">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('Dosen/updateProfil') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_user" value="<?= $dosen['id_user'] ?>">
            <input type="hidden" name="foto_lama" value="<?= $dosen['foto'] ?>">
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= $dosen['nama'] ?>" required>
            </div>

            <div class="form-group">
                <label>NIDN</label>
                <input type="text" name="nidn" class="form-control" value="<?= $dosen['nidn'] ?>" required>
            </div>

            <div class="form-group">
                <label>Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <?php if (!empty($dosen['foto'])) : ?>
                    <img src="<?= base_url('foto/dosen/' . $dosen['foto']) ?>" class="mt-2" style="max-height: 100px;">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Profil</button>
        </form>
    </div>
</div> 