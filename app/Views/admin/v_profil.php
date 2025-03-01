<div class="row">
    <div class="col-md-12">
        <div class="card card-with-nav">
            <div class="card-body">
                <?php if(session()->getFlashdata('pesan')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('pesan') ?>
                    </div>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('Admin/updateProfil') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_admin" value="<?= isset($admin['id_admin']) ? $admin['id_admin'] : '' ?>">
                    <input type="hidden" name="foto_lama" value="<?= isset($admin['foto']) ? $admin['foto'] : '' ?>">
                    
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= isset($admin['nama']) ? $admin['nama'] : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?= isset($admin['username']) ? $admin['username'] : '' ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= isset($admin['email']) ? $admin['email'] : '' ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <?php if(isset($admin['foto']) && $admin['foto']): ?>
                            <img src="<?= base_url('foto/admin/'.$admin['foto']) ?>" width="100px" class="mt-2">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile_preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>