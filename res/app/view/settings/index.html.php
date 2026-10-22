<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
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
                <div class="column col-12 p-2">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a>Settings</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider text-center"></div>

            <div class="container columns col-gapless">
                <!-- Update Profile -->
                <div class="column col-6 col-md-12 p-2">
                    <form class="card" style="margin: auto; max-width: 400px;" action="<?= $app->urlRoute('user/update?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>" method="POST" x-on-key="enter" x-prevent>
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="user[id]" value="<?= $app->htmlEncode($user['id']) ?>" />
                        <input type="hidden" name="current_email" value="<?= $app->htmlEncode($user['email']) ?>" />
                        <div class="card-header">
                            <div class="card-title h5">Update Profile</div>
                        </div>

                        <div class="card-body columns">
                            <div class="form-group column col-12">
                                <label class="form-label">Firstname</label>
                                <input class="form-input" type="text" name="user[firstname]" value="<?= $app->htmlEncode($user['firstname']) ?>" required />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Middlename</label>
                                <input class="form-input" type="text" name="user[middlename]" value="<?= $app->htmlEncode($user['middlename']) ?>" />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Lastname</label>
                                <input class="form-input" type="text" type="text" name="user[lastname]" value="<?= $app->htmlEncode($user['lastname']) ?>" required />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Date of Birth</label>
                                <input class="form-input" type="date" name="user[date_of_birth]" value="<?= $app->htmlEncode($user['date_of_birth']) ?>" required />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Contact</label>
                                <input class="form-input" type="number" name="user[contact]" value="<?= $app->htmlEncode($user['contact']) ?>" required />
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Email</label>
                                <input class="form-input" type="email" name="user[email]" value="<?= $app->htmlEncode($user['email']) ?>" required />
                            </div>
                            
                        </div>
                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary column col-12"
                                x-ref--update-profile-open
                                x-on-click
                                x-rot--update-profile="active"
                                x-focus="-update-profile-tab-last"
                                x-tab="-update-profile-tab-first:-update-profile-tab-last"
                                x-set-window.x-on-key-window-escape="-update-profile-close"
                                x-set-window.x-run--update-profile-close="x-on-click"
                            >
                                Save Changes
                            </button>

                            <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--update-profile>
                                <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--update-profile-close="x-on-click"></a>
                                <div class="modal-container" >
                                    <div class="modal-header">
                                        <a class="btn btn-clear float-right" aria-label="Close" x-ref--update-profile-close x-on-click x-tab x-rot--update-profile=""></a>
                                        <div class="modal-title h5">Update Profile</div>
                                    </div>
                                    <div class="modal-body">
                                        Update Profile?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" x-on-click x-run--update-profile-close="x-on-click">Save Changes</button>
                                        <button class="btn" type="button" x-ref--update-profile-tab-last x-on-click x-run--update-profile-close="x-on-click">Close</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <!-- Update Password -->
                <div class="column col-6 col-md-12 p-2">
                    <form class="card" style="margin: auto; max-width: 400px;" action="<?= $app->urlRoute('user/update?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>" method="POST" x-on-key="enter" x-prevent>
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="user[id]" value="<?= $app->htmlEncode($user['id']) ?>" />
                        <div class="card-header">
                            <div class="card-title h5">Change Password</div>
                        </div>

                        <div class="card-body columns">
                            <div class="form-group column col-12">
                                <label class="form-label">Current Password</label>
                                <input class="form-input" type="password" name="current_password" required/>
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">New Password</label>
                                <input class="form-input" type="password" name="user[password]" required x-on-input x-var--update-password-password="this"/>
                            </div>
                            <div class="form-group column col-12">
                                <label class="form-label">Confirm Password</label>
                                <input class="form-input" type="password" name="confirm_password" required x-on-input x-ref--update-password-confirm-password x-var--update-password-confirm-password="this"/>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary column col-12"
                                x-ref--update-password-open
                                x-on-click
                                x-rot--update-password="active"
                                x-focus="-update-password-tab-last"
                                x-tab="-update-password-tab-first:-update-password-tab-last"
                                x-set-window.x-on-key-window-escape="-update-password-close"
                                x-set-window.x-run--update-password-close="x-on-click"
                            >
                                Save Changes
                            </button>

                            <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--update-password>
                                <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--update-password-close="x-on-click"></a>
                                <div class="modal-container" >
                                    <div class="modal-header">
                                        <a class="btn btn-clear float-right" aria-label="Close" x-ref--update-password-close x-on-click x-tab x-rot--update-password=""></a>
                                        <div class="modal-title h5">Change Password</div>
                                    </div>
                                    <div class="modal-body">
                                        Change Password?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" x-on-click x-run--update-password-close="x-on-click">Save Changes</button>
                                        <button class="btn" type="button" x-ref--update-password-tab-last x-on-click x-run--update-password-close="x-on-click">Close</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <script>
                    (window.init = window.init || []).push(function () {
                        var password = ElX.x("-update-password-password");
                        var confirmPassword = ElX.x("-update-password-confirm-password");
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
                
                <!-- Delete Account -->
                <div class="column col-6 col-md-12 p-2">
                    <form class="card" style="margin: auto; max-width: 400px;" action="<?= $app->urlRoute('user/delete?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>" method="POST" x-on-key="enter" x-prevent>
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="user[id]" value="<?= $app->htmlEncode($user['id']) ?>" />
                        <div class="card-header">
                            <div class="card-title h5">Delete Account</div>
                        </div>

                        <div class="card-body columns">
                            <div class="form-group column col-12">
                                <label class="form-label">Confirm Password</label>
                                <input class="form-input" type="password" name="user[password]" required/>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-error column col-12"
                                x-ref--delete-account-open
                                x-on-click
                                x-rot--delete-account="active"
                                x-focus="-delete-account-tab-last"
                                x-tab="-delete-account-tab-first:-delete-account-tab-last"
                                x-set-window.x-on-key-window-escape="-delete-account-close"
                                x-set-window.x-run--delete-account-close="x-on-click"
                            >
                                Delete Account
                            </button>

                            <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--delete-account>
                                <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--delete-account-close="x-on-click"></a>
                                <div class="modal-container" >
                                    <div class="modal-header">
                                        <a class="btn btn-clear float-right" aria-label="Close" x-ref--delete-account-close x-on-click x-tab x-rot--delete-account=""></a>
                                        <div class="modal-title h5">Delete Account</div>
                                    </div>
                                    <div class="modal-body">
                                        Delete Account?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-error" x-on-click x-run--delete-account-close="x-on-click">Delete Account</button>
                                        <button class="btn" type="button" x-ref--delete-account-tab-last x-on-click x-run--delete-account-close="x-on-click">Close</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>
</html>
