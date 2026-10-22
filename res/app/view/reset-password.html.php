<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$userData = $data['user_data'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dental Clinic - Home</title>
    <link rel="icon" href="<?= $app->urlWeb('asset/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-exp.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-icons.min.css') ?>">
    <script src="<?= $app->urlWeb('asset/js/uc.js') ?>" defer></script>
    <style>

    </style>
</head>

<body>
    <main class="off-canvas off-canvas-sidebar-show" style="min-height: 100vh;">
        
        <?php require($app->dirRoot('res/app/view/include/side-bar.html.php')); ?>

        <div class="off-canvas-content" style="padding: 0; width: 1px;">
            <?php require($app->dirRoot('res/app/view/include/header.html.php')); ?>
            
            <div class="container columns col-gapless">
                <!-- Update Password -->
                <div class="column col-12 p-2">
                    <form class="card" style="margin: auto; max-width: 400px;" method="POST" action="<?= $app->urlRoute('user/otp-update?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>" x-on-key="enter" x-prevent>
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>">
                        <input type="hidden" name="user[id]" value="<?= $app->htmlEncode($userData['id']) ?>">
                        <div class="card-header">
                            <div class="card-title h5">Reset Password</div>
                            <div class="card-subtitle">An OTP was sent to: <strong><?= $app->htmlEncode($userData['email']) ?></strong></div>
                        </div>

                        <div class="card-body columns">
                            <div class="form-group column col-12">
                                <label class="form-label">Reset token</label>
                                <input class="form-input" type="number" name="otp_token" required />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">New Password</label>
                                <input class="form-input" type="password" name="user[password]" required x-on-input x-var--reset-password-password="this"/>
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Confirm Password</label>
                                <input class="form-input" type="password" name="confirm_password" required x-on-input x-ref--reset-password-confirm-password x-var--reset-password-confirm-password="this"/>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="form-group">
                            <button
                                type="button"
                                class="btn btn-primary column col-12"
                                x-ref--reset-password-open
                                x-on-click
                                x-rot--reset-password="active"
                                x-focus="-reset-password-tab-last"
                                x-tab="-reset-password-tab-first:-reset-password-tab-last"
                                x-set-window.x-on-key-window-escape="-reset-password-close"
                                x-set-window.x-run--reset-password-close="x-on-click"
                            >
                                Reset Password
                            </button>
                            </div>
                            <div class="form-group">
                                <p>
                                    Didn't receive the code? 
                                    <a href="<?= $app->urlRoute('', ['id' => $userData['id']]) ?>">Resend it</a>
                                </p>
                            </div>

                            <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--reset-password>
                                <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--reset-password-close="x-on-click"></a>
                                <div class="modal-container" >
                                    <div class="modal-header">
                                        <a class="btn btn-clear float-right" aria-label="Close" x-ref--reset-password-close x-on-click x-tab x-rot--reset-password=""></a>
                                        <div class="modal-title h5">Reset Password</div>
                                    </div>
                                    <div class="modal-body">
                                        Reset Password?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" x-on-click x-run--reset-password-close="x-on-click">Reset Password</button>
                                        <button class="btn" type="button" x-ref--reset-password-tab-last x-on-click x-run--reset-password-close="x-on-click">Close</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <script>
                    (window.init = window.init || []).push(function () {
                        var password = ElX.x("-reset-password-password");
                        var confirmPassword = ElX.x("-reset-password-confirm-password");
                        var validatePassword = function () {
                            if (confirmPassword.value() !== "" && password.value() !== confirmPassword.value()) {
                                confirmPassword.ref()[0].setCustomValidity("Please make sure your password and confirmation match.");
                            } else {
                                confirmPassword.ref()[0].setCustomValidity("");
                            }
                        };
                        confirmPassword.tap(validatePassword);
                        password.tap(validatePassword);
                    });
                </script>
                

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')) ?>

</body>
</html>

