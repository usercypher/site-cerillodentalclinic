<?php 
$currentRoute = $data['current_route'];
$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$notifications = $data['notifications'];
$user = $data['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notifications</title>
    <link rel="icon" href="<?= $app->urlWeb('asset/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-exp.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-icons.min.css') ?>">
    <script src="<?= $app->urlWeb('asset/js/uc.js') ?>" defer></script>
    <style>
        .notification-item.read {
            background-color: #f5f5f5;
            color: #666;
        }

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
                            <a>Notification</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--message-delete-open
                        x-on-click
                        x-rot--message-delete="active"
                        x-focus="-message-delete-tab-last"
                        x-tab="-message-delete-tab-first:-message-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-message-delete-close"
                        x-set-window.x-run--message-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="columns col-gapless">
                <form class="column col-12" method="POST" action="<?= $app->urlRoute('message/delete?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                    <div id="notification-container">
                        <?php foreach ($notifications as $message): ?>
                        <div class="tile <?= $message['read_at'] ? 'bg-gray' : '' ?> p-2">
                            <div class="tile-icon">
                                <label class="form-checkbox">
                                    <input type="checkbox" name="message[id][]" value="<?= $app->htmlEncode($message['id']) ?>">
                                    <i class="form-icon avatar avatar-lg text-primary" data-initial="<?= $app->htmlEncode(strtoupper(substr($message['subject'], 0, 2))) ?>" ></i>
                                </label>
                                <div style="width: 64px;"></div>
                            </div>
                            <div class="tile-content">
                                <p class="tile-title"><?= $app->htmlEncode($message['subject']) ?></p>
                                <p class="tile-subtitle" 
                                    style="display: -webkit-box;
                                          -webkit-line-clamp: 3;
                                          -webkit-box-orient: vertical;
                                          overflow: hidden;
                                          text-overflow: ellipsis;">
                                    <?= $app->htmlEncode($message['body']) ?>
                                </p>
                                <p>
                                    <a class="btn btn-primary" href="<?= $app->urlRoute('notification/:id', array(':id' => $app->htmlEncode($message['id']))) ?>">Read</a>
                                </p>
                            </div>
                            <div class="tile-action text-gray">
                                <?= date('M d', strtotime($message['sent_at'])) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--message-delete>
                        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--message-delete-close="x-on-click"></a>
                        <div class="modal-container" >
                            <div class="modal-header">
                                <a class="btn btn-clear float-right" aria-label="Close" x-ref--message-delete-close x-on-click x-tab x-rot--message-delete="" x-focus="-message-delete-open"></a>
                                <div class="modal-title h5">Delete Notification</div>
                            </div>
                            <div class="modal-body">
                                Delete selected notifications?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-error" x-on-click x-run--message-delete-close="x-on-click">Delete</button>
                                <button class="btn" type="button" x-ref--message-delete-tab-last x-on-click x-run--message-delete-close="x-on-click">Close</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>

</html>
