<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
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
            <h3 class="text-center"><b>LOGIN</b></h3>
            <div class="login-form">
                <form action="<?= base_url('auth/login') ?>" method="post">
                    <div class="form-group form-floating-label">
                        <input id="username" name="username" type="text" class="form-control input-border-bottom" required>
                        <label for="username" class="placeholder">Username</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="password" name="password" type="password" class="form-control input-border-bottom" required>
                        <label for="password" class="placeholder">Password</label>
                        <div class="show-password">
                            <i class="flaticon-interface"></i>
                        </div>
                    </div>
                    <div class="row form-sub m-0">
                        <a href="#" class="link float-right">Lupa Password ?</a>
                    </div>
                    <div class="form-action mb-3">
                        <button type="submit" class="btn btn-primary btn-rounded btn-login">LOGIN</button>
                    </div>
                    <div class="login-account">
                        <span class="msg">Apakah kamu mempunyai akun?</span>
                        <a href="<?= base_url('Auth/register') ?>" id="show-signup" class="link">Register</a>
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
    <script>
        $(document).ready(function() {
            // Debugging
            $('.btn-login').click(function(e) {
                console.log('Tombol login ditekan');
            });
        });
    </script>
</body>

</html>