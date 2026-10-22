<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

// Assigning variables for better traceability
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$notifications = $data['notifications'];
$user = $data['user'];
$latestMessageTime = empty($data['notifications']) ? '0000-00-00 00:00:00' : $data['notifications'][0]['sent_at'] ;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
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
                            <a>Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <!-- Placeholder for user action buttons -->
                    <a href="<?= $app->urlRoute('settings') ?>" class="btn">Settings</a>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-4 col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                        <div class="card-title h3 text-primary"><?= count($notifications) ?></div>
                            <div class="card-subtitle text-gray">New Notification</div>
                        </div>
                        <div class="card-body">
                        </div>
                        <div class="card-footer bg-primary text-light">
                            <span>Notifications</span>
                        </div>
                    </div>
                </div>
                <div class="column col-4 col-md-12">
                </div>
                <div class="column col-4 col-md-12">
                </div>
            </div>
            <div class="container columns col-gapless">
                <!-- Notifications -->
                <div class="column col-4 col-md-12 p-2">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Notifications</div>
                        </div>
                        <div class="panel-body" id="notification-container">
                            <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="tile p-1">
                                <div class="tile-icon">
                                    <figure class="avatar avatar-lg s-rounded" data-initial="<?= $app->htmlEncode(strtoupper(substr($notification['subject'], 0, 2))) ?>"></figure>
                                </div>
                                <div class="tile-content">
                                    <p class="tile-title text-bold"><a href="<?= $app->urlRoute('notification/:id', array(':id' => $app->htmlEncode($notification['id']))) ?>"><?= $app->htmlEncode($notification['subject']) ?></a></p>
                                    <p class="tile-subtitle" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?= $app->htmlEncode($notification['body']) ?></p>
                                </div>
                                <div class="tile-action text-gray">
                                    <?= date('M d', strtotime($notification['sent_at'])) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="panel-footer">
                            <!-- contents -->
                        </div>
                    </div>
                </div>
                <script>
                    (window.init = window.init || []).push(function () {
                        Utils.script(["<?= $app->urlWeb('asset/js/MessageNotificationList.js') ?>"], function () {
                            new MessageNotificationList().init("<?= $app->urlRoute('notification/:id') ?>", "<?= $app->urlRoute('message/notification/list') ?>", "<?= $latestMessageTime ?>");
                        });
                    });
                </script>
                <div class="column col-4 col-md-12">
                </div>
                <div class="column col-4 col-md-12">
                </div>
            </div>

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>
</html>
