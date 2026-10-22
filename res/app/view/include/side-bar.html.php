
        
        <div class="off-canvas-sidebar _" style="padding: 0.5em;" x-ref--sidebar style="padding: 0;">
            <nav class="menu" style="overflow: auto; border: 0; margin: 0;">
                <li class="menu-item">
                    <div class="tile tile-centered">
                        <div class="tile-icon"><div class="avatar text-center" data-initial="<?= strtoupper(isset($user) ? substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1) : 'G') ?>"></div></div>
                        <div class="tile-content"><?= 'Hi! ' . (isset($user) ? $user['firstname'] : 'Guest') ?></div>
                    </div>
                </li>
                <li class="divider"></li>
<?php if (isset($user)) : ?>
    <?php if ($user['role'] === 'doctor') : ?>

                <div class="menu-item">
                    <a href="<?= $app->urlRoute('doctor/dashboard') ?>" class="<?= strpos($currentRoute, 'doctor/dashboard') !== false ? 'active' : '' ?>">Dashboard</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('doctor/appointment') ?>" class="<?= strpos($currentRoute, 'doctor/appointment') !== false  ? 'active' : '' ?>">Appoinment</a>
                </div>

    <?php elseif ($user['role'] === 'admin') : ?>

                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/dashboard') ?>" class="<?= strpos($currentRoute, 'admin/dashboard') !== false  ? 'active' : '' ?>">Dashboard</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/user') ?>" class="<?= strpos($currentRoute, 'admin/user') !== false ? 'active' : '' ?>">User</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/schedule') ?>" class="<?= strpos($currentRoute, 'admin/schedule') !== false  ? 'active' : '' ?>">Schedule</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/service') ?>" class="<?= strpos($currentRoute, 'admin/service') !== false  ? 'active' : '' ?>">Service</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/chatbot') ?>" class="<?= strpos($currentRoute, 'admin/chatbot') !== false  ? 'active' : '' ?>">Chatbot</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('admin/api') ?>" class="<?= strpos($currentRoute, 'admin/api') !== false  ? 'active' : '' ?>">Api</a>
                </div>

    <?php elseif ($user['role'] === 'staff') : ?>

                <div class="menu-item">
                    <a href="<?= $app->urlRoute('staff/dashboard') ?>" class="<?= strpos($currentRoute, 'staff/dashboard') !== false ? 'active' : '' ?>">Dashboard</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('staff/appointment') ?>" class="<?= strpos($currentRoute, 'staff/appointment') !== false  ? 'active' : '' ?>">Appoinment</a>
                </div>

    <?php elseif ($user['role'] === 'patient') : ?>


                <div class="menu-item">
                    <a href="<?= $app->urlRoute('dashboard') ?>" class="<?= strpos($currentRoute, 'dashboard') !== false  ? 'active' : '' ?>">Dashboard</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('appointment') ?>" class="<?= strpos($currentRoute, 'appointment') !== false  ? 'active' : '' ?>">Appoinment</a>
                </div>

    <?php endif; ?>


                <div class="menu-item">
                    <a href="<?= $app->urlRoute('notification') ?>" class="<?= strpos($currentRoute, 'notification') !== false  ? 'active' : '' ?>">Notification</a>
                    <div class="menu-badge">
                        <label class="label label-primary" id="notification-badge">0</label>
                    </div>
                </div>
                
                <script>
                    (window.init = window.init || []).push(function () {
                        Utils.script(["<?= $app->urlWeb('asset/js/MessageNotificationCount.js') ?>"], function () {
                            new MessageNotificationCount().init("<?= $app->urlRoute('message/notification/count') ?>");
                        });
                    });
                </script>

                <div class="menu-item">
                    <a href="<?= $app->urlRoute('profile') ?>" class="<?= strpos($currentRoute, 'profile') !== false  ? 'active' : '' ?>">Profile</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('settings') ?>" class="<?= strpos($currentRoute, 'settings') !== false  ? 'active' : '' ?>">Settings</a>
                </div>
                <div class="menu-item">
                    <a
                        href="#"
                        x-ref--logout-open
                        x-on-click
                        x-prevent
                        x-rot--logout="active"
                        x-focus="-logout-tab-last"
                        x-tab="-logout-tab-first:-logout-tab-last"
                        x-set-window.x-on-key-window-escape="-logout-close"
                        x-set-window.x-run--logout-close="x-on-click"
                        x-set--logout-close.x-focus="-logout-open"
                    >
                        Logout
                    </a>
                </div>

<?php else: ?>

                <div class="menu-item">
                    <a href="<?= $app->urlRoute('home') ?>" class="<?= strpos($currentRoute, 'home') !== false  ? 'active' : '' ?>">Home</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('profile') ?>" class="<?= strpos($currentRoute, 'profile') !== false  ? 'active' : '' ?>">Profile</a>
                </div>
                <div class="menu-item">
                    <a href="<?= $app->urlRoute('reset-password') ?>" class="<?= strpos($currentRoute, 'reset-password') !== false  ? 'active' : '' ?>">Reset Password</a>
                </div>
    
<?php endif; ?>
            <br>
            <br>
            <br>
            </nav>
        </div>
        <a class="off-canvas-overlay" x-on-click x-rot--sidebar="_ active"></a>


        <!-- logout modal -->
        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--logout>
            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--logout-close="x-on-click"></a>
            <div class="modal-container" >
                <div class="modal-header">
                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--logout-close x-on-click x-tab x-rot--logout="" x-focus="-logout-open"></a>
                    <div class="modal-title h5">Logout</div>
                </div>
                <div class="modal-body">
                    <div class="content">
                        Do you want to logout?
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-error" href="<?= $app->urlRoute('user/logout') ?>">Logout</a>
                    <button class="btn" type="button" x-ref--logout-tab-last x-on-click x-run--logout-close="x-on-click">Close</button>
                </div>
            </div>
        </div>
        