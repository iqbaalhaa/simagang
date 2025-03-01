<!-- app/Views/admin/register.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Register Mahasiswa</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('backend') ?>/assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="<?= base_url('backend') ?>/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Open+Sans:300,400,600,700"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"],
                urls: ['<?= base_url('backend') ?>/assets/css/fonts.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('backend') ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('backend') ?>/assets/css/azzara.min.css">
</head>

<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <img src="<?= base_url('backend') ?>/assets/img/logosi.png" alt="LogoSI" style="display: block; margin: 0 auto; width: 100px;">
            <br>
            <h3 class="text-center">Register Mahasiswa</h3>
            <div class="login-form">
                <form method="post" action="<?= base_url('Auth/store') ?>">
                    <div class="form-group form-floating-label">
                        <input id="username" name="username" type="text" class="form-control input-border-bottom" required>
                        <label for="username" class="placeholder">Username</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="email" name="email" type="email" class="form-control input-border-bottom" required>
                        <label for="email" class="placeholder">Email</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="passwordsignin" name="passwordsignin" type="password" class="form-control input-border-bottom" required>
                        <label for="passwordsignin" class="placeholder">Password</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="confirmpassword" name="confirmpassword" type="password" class="form-control input-border-bottom" required>
                        <label for="confirmpassword" class="placeholder">Konfirmasi Password</label>
                    </div>
                    <div class="form-action mb-3">
                        <button type="submit" class="btn btn-primary btn-rounded">Register</button>
                    </div>
                    <div class="login-account">
                        <span class="msg">Sudah punya akun?</span>
                        <a href="<?= base_url('Auth') ?>" class="link">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url('backend') ?>/assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/core/bootstrap.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/ready.js"></script>
</body>

</html>