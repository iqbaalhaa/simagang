<div class="row">
    <div class="col-md-12">
        <div class="card card-with-nav">
            <div class="card-body">
                <form action="<?= base_url('Admin/updateProfile') ?>" method="post" enctype="multipart/form-data">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name" placeholder="Name" value="Hizrian">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="hello@example.com">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input type="text" class="form-control" value="admin" name="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-default">
                                <label>Upload Foto Profil (3x4 cm):</label>
                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Preview Foto:</label>
                            <img id="profile_preview" src="<?= base_url('uploads/default.png') ?>" style="width: 2.5cm; height: 3.3cm; object-fit: cover; border: 1px solid #ccc;" alt="Foto Profil">
                        </div>
                    </div>
                    <div class="text-left mt-3 mb-3">
                        <button class="btn btn-success">Simpan</button>
                    </div>
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