<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
$schedules = $data['schedules'];
$doctor = $data['doctor'];
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
                            <a href="<?= $app->htmlEncode($app->urlRoute('admin/schedule')) ?>">Schedule</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a><?= $app->htmlEncode($doctor['lastname'] . ', ' . $doctor['firstname'] . ' ' . $doctor['middlename']) ?></a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <b>Name:</b> <?= $app->htmlEncode($doctor['lastname'] . ', ' . $doctor['firstname'] . ' ' . $doctor['middlename']) ?>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-success"
                        x-ref--schedule-add-open
                        x-on-click
                        x-rot--schedule-add="active"
                        x-focus="--schedule-add-tab-first"
                        x-tab="--schedule-add-tab-first:--schedule-add-tab-last"
                        x-set-window.x-on-key-window-escape="--schedule-add-close"
                        x-set-window.x-run--schedule-add-close="x-on-click"
                    >
                        + Add Schedule
                    </button>
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--schedule-delete-open
                        x-on-click
                        x-rot--schedule-delete="active"
                        x-focus="-schedule-delete-tab-first"
                        x-tab="-schedule-delete-tab-first:-schedule-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-schedule-delete-close"
                        x-set-window.x-run--schedule-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form  method="POST" action="<?= $app->urlRoute('schedule/delete?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <div >
                            <table class="table table-scroll">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
            
                                    <?php foreach ($schedules as $schedule): ?>
            
                                        <tr>
                                            <td>
                                                <label class="form-checkbox">
                                                    <input type="checkbox" name="schedule[id][]" value="<?= $app->htmlEncode($schedule['id']) ?>">
                                                    <i class="form-icon"></i>
                                                </label>
                                            </td>
                                            <td>
                                                <?php
                                                    $date = new DateTime($schedule['date']);
                                                    echo $app->htmlEncode($date->format('M j, Y')); // e.g., Sep 29, 2025
                                                ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="<?= $app->urlRoute('admin/schedule/:user_id/edit/:schedule_id', array(':user_id' => $doctor['id'], ':schedule_id' => rawurlencode($schedule['id']))) ?>">View</a>
                                            </td>
                                        </tr>
            
                                    <?php endforeach; ?>
            
                                </tbody>
                            </table>
                        </div>
        
                        <!-- schedule delete modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--schedule-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--schedule-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--schedule-delete-close x-on-click x-tab x-rot--schedule-delete="" x-focus="-schedule-delete-open"></a>
                                    <div class="modal-title h5">Delete Schedule</div>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Mesage</label>
                                        <textarea class="form-input" name="message[body]" rows="4" required x-ref--schedule-delete-tab-first></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click>Delete</button>
                                    <button class="btn" type="button" x-ref--schedule-delete-tab-last x-on-click x-run--schedule-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </main>


    <!-- schedule add modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--schedule-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--schedule-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('schedule/store?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="schedule[user_id]" value="<?= $app->htmlEncode($doctor['id']) ?>"/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--schedule-add-close x-on-click x-tab x-rot--schedule-add="" x-focus="-schedule-add-open"></a>
                <div class="modal-title h5">Add Schedule</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input class="form-input" type="date" name="schedule[date]" required x-ref--schedule-add-tab-first>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time (start)</label>
                        <input class="form-input" type="time" name="schedule[time_start]" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time (end)</label>
                        <input class="form-input" type="time" name="schedule[time_end]" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Create</button>
                <button class="btn" type="button" x-ref--schedule-add-tab-last x-on-click x-run--schedule-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>