<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$notification = $data['notification'];
$user = $data['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notification</title>
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
                            <a href="<?= $app->urlRoute('notification') ?>">Notification</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a>View</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider text-center"></div>

            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <h3><?= $app->htmlEncode($notification['subject']) ?></h3>
                    <p class="text-gray"><?= date("F j, Y, g:i A", strtotime($notification['sent_at'])) ?></p>
                    <p><?= nl2br($app->htmlEncode($notification['body'])) ?></p>
                    
                </div>
            </div>

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>
</html>
