<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Lupa Password</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('backend') ?>/assets/img/favicon.png" type="image/x-icon" />

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
            <h3 class="text-center"><b>LUPA PASSWORD</b></h3>
            <div class="login-form">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('info')) : ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('Auth/prosesLupaPassword') ?>" method="POST">
                    <div class="form-group form-floating-label">
                        <input id="email" name="email" type="email" class="form-control input-border-bottom" required>
                        <label for="email" class="placeholder">Email</label>
                    </div>
                    <div class="form-action mb-3">
                        <button type="submit" class="btn btn-primary btn-rounded btn-login">Kirim Link Reset</button>
                    </div>
                    <div class="login-account">
                        <span class="msg">Ingat password Anda?</span>
                        <a href="<?= base_url('Auth') ?>" class="link">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url('backend') ?>/assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/core/popper.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/core/bootstrap.min.js"></script>
    <script src="<?= base_url('backend') ?>/assets/js/ready.js"></script>
</body>

</html> 