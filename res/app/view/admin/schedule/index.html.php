<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
$users = $data['users'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
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
                            <a>Schedule</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <div >
                        <table class="table table-scroll">
                            <thead>
                                <tr>
                                    <th>Doctors</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
        
                                <?php foreach ($users as $user): ?>
        
                                    <tr>
                                        <td>
                                            <?php
                                                echo $app->htmlEncode($user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename']);
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="<?= $app->urlRoute('admin/schedule/:user_id', array(':user_id' => rawurlencode($user['id']))) ?>">View</a>
                                        </td>
                                    </tr>
        
                                <?php endforeach; ?>
        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>