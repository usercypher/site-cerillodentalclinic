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
                    <form class="card" style="margin: auto; max-width: 400px;" method="POST" action="<?= $app->urlRoute('user/otp-store?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>" x-on-key="enter" x-prevent>
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>">
                        <div class="card-header">
                            <div class="card-title h5">Complete Registration</div>
                            <div class="card-subtitle">
                                We’ve sent a verification code to: 
                                <strong><?= $app->htmlEncode($userData['email']) ?></strong>
                            </div>
                        </div>
                        
                        <div class="card-body columns">
                            <div class="form-group column col-12">
                                <label class="form-label">Verification Code</label>
                                <input class="form-input" type="number" name="otp_token" required />
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="form-group">
                                <button
                                    type="button"
                                    class="btn btn-primary column col-12"
                                    x-ref--register-open
                                    x-on-click
                                    x-rot--register="active"
                                    x-focus="-register-tab-last"
                                    x-tab="-register-tab-first:-register-tab-last"
                                    x-set-window.x-on-key-window-escape="-register-close"
                                    x-set-window.x-run--register-close="x-on-click"
                                >
                                    Verify & Complete Registration
                                </button>
                            </div>
                        
                            <div class="form-group">
                                <p>
                                    Didn’t receive the code?
                                    <a href="<?= $app->urlRoute('') ?>">Resend Code</a>
                                </p>
                            </div>
                        
                            <div style="position: fixed; z-index: 899;" class="modal modal-sm _" x-ref--register>
                                <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--register-close="x-on-click"></a>
                                <div class="modal-container">
                                    <div class="modal-header">
                                        <a class="btn btn-clear float-right" aria-label="Close" x-ref--register-close x-on-click x-tab x-rot--register=""></a>
                                        <div class="modal-title h5">Confirm Registration</div>
                                    </div>
                                    <div class="modal-body">
                                        Submit the verification code and complete your registration?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" x-on-click x-run--register-close="x-on-click">Confirm</button>
                                        <button class="btn" type="button" x-ref--register-tab-last x-on-click x-run--register-close="x-on-click">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                
        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')) ?>

</body>
</html>

