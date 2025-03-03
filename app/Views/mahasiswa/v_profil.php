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

                <form action="<?= base_url('Mahasiswa/updateProfil') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_user" value="<?= isset($mahasiswa['id_user']) ? $mahasiswa['id_user'] : '' ?>">
                    <input type="hidden" name="id_mahasiswa" value="<?= isset($mahasiswa['id_mahasiswa']) ? $mahasiswa['id_mahasiswa'] : '' ?>">
                    <input type="hidden" name="foto_lama" value="<?= isset($mahasiswa['foto']) ? $mahasiswa['foto'] : '' ?>">
                    
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control" value="<?= isset($mahasiswa['nim']) ? $mahasiswa['nim'] : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= isset($mahasiswa['nama']) ? $mahasiswa['nama'] : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" name="angkatan" class="form-control" value="<?= isset($mahasiswa['angkatan']) ? $mahasiswa['angkatan'] : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Instansi</label>
                        <input type="text" name="instansi" class="form-control" value="<?= isset($mahasiswa['instansi']) ? $mahasiswa['instansi'] : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?= isset($mahasiswa['username']) ? $mahasiswa['username'] : '' ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= isset($mahasiswa['email']) ? $mahasiswa['email'] : '' ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Informasi Upload Foto:</strong>
                            <ul class="mb-0">
                                <li>Ukuran File Maksimal 2MB</li>
                                <li>Format yang didukung: PNG, JPEG, JPG, GIF</li>
                            </ul>
                        </div>
                        <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                        <?php if(isset($mahasiswa['foto']) && $mahasiswa['foto']): ?>
                            <img id="profile_preview" src="<?= base_url('foto/mahasiswa/'.$mahasiswa['foto']) ?>" width="100px" class="mt-2">
                        <?php else: ?>
                            <img id="profile_preview" src="<?= base_url('assets/img/profile.jpg') ?>" width="100px" class="mt-2">
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