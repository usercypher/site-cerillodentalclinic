<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
$user_data = $data['user_data'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - <?= $app->htmlEncode($user_data ? $user_data['lastname'] . ', ' . $user_data['firstname'] . ' ' . $user_data['middlename'] : 'User not found') ?></title>
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
                            <a href="<?= $app->urlRoute('profile') ?>">Profile</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <div class="card" style="margin: auto; max-width: 400px;">
                        <?php if ($user_data): ?>
                        <?php 
                            $birthDate = $user_data['date_of_birth'];
        
                            // Extract birth date parts
                            list($birthYear, $birthMonth, $birthDay) = explode('-', $birthDate);
                            
                            // Get current date parts
                            $currentYear  = date('Y');
                            $currentMonth = date('m');
                            $currentDay   = date('d');
                            
                            // Initial age calculation
                            $age = $currentYear - $birthYear;
                            
                            // Adjust if birthday hasn't occurred yet this year
                            if ($birthMonth > $currentMonth || ($currentMonth == $birthMonth && $birthDay > $currentDay)) {
                                $age--;
                            }
                            $user_data['age'] = $age;
                        ?>
                        <div class="card-header text-center">
                            <figure class="avatar avatar-lg" data-initial="<?= strtoupper(substr($user_data['firstname'], 0, 1) . substr($user_data['lastname'], 0, 1)) ?>"></figure>
                            <div class="card-title h5 mt-10"><?= $app->htmlEncode($user_data['lastname'] . ', ' . $user_data['firstname'] . ' ' . $user_data['middlename']) ?></div>
                            <div class="card-subtitle"><?= $app->htmlEncode($user_data['role']) ?></div>
                        </div>
                        <div class="card-nav">
                            <div class="divider text-center"></div>
                        </div>
                        <div class="card-body">
                            <div class="columns">
                                <p class="column col-5"><b>Date of Birth:</b></p>
                                <p class="column col-7"><?php
                                    $date = new DateTime($user_data['date_of_birth']);
                                    echo $app->htmlEncode($date->format('M j, Y')); // e.g., Sep 29, 2025
                                ?></p>
                                
                                
                                <p class="column col-5"><b>Age:</b></p>
                                <p class="column col-7"><?= $app->htmlEncode($user_data['age']) ?></p>
                                
                                <p class="column col-5"><b>Contact:</b></p>
                                <p class="column col-7"><?= $app->htmlEncode($user_data['contact']) ?></p>
                                
                                <p class="column col-5"><b>Email:</b></p>
                                <p class="column col-7"><?= $app->htmlEncode($user_data['email']) ?></p>
                            </div>
                        </div>
                        <div class="card-footer">
                            
                        </div>
                        <?php else: ?>
                            <div class="card-header text-center">
                                <div class="card-title h5 mt-10">User not found.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>


    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>
</html>
