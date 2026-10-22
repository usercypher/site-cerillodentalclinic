<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
$schedule = $data['schedule'];
$slots = $data['slots'];
$doctor = $data['doctor'];

$formattedDate = new DateTime($schedule['date']);

$colorByType = array(
    'regular' => 'blue',
    'break' => 'orange', 
    'cancelled'   => 'red',
    'open slot' => 'green',
);
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
                            <a href="<?= $app->urlRoute('admin/schedule/:user_id', array(':user_id' => $doctor['id'])) ?>"><?= $app->htmlEncode($doctor['lastname'] . ', ' . $doctor['firstname'] . ' ' . $doctor['middlename']) ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a><?= $app->htmlEncode($formattedDate->format('M j, Y')) ?></a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <b>Name:</b> <?= $app->htmlEncode($doctor['lastname'] . ', ' . $doctor['firstname'] . ' ' . $doctor['middlename']) ?>
                </div>
                <div class="column col-12 p-2">
                    <strong>Schedule:</strong> <?= $app->htmlEncode($formattedDate->format('M j, Y')) ?>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-primary"
                        x-ref--slot-add-open-cancelled
                        x-on-click
                        x-rot--slot-add="active"
                        x-focus="-slot-add-tab-first"
                        x-tab="-slot-add-tab-first:-slot-add-tab-last"
                        x-set-window.x-on-key-window-escape="-slot-add-close"
                        x-set-window.x-run--slot-add-close="x-on-click"
                        x-set--slot-add-close.x-focus="-slot-add-open-cancelled"
                        x-val-appointment-type-h2="Add Cancelled Slot" 
                        x-val-appointment-type="cancelled" 
                    >
                        + Add Cancelled slot
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        x-ref--slot-add-open-break
                        x-on-click
                        x-rot--slot-add="active"
                        x-focus="-slot-add-tab-first"
                        x-tab="-slot-add-tab-first:-slot-add-tab-last"
                        x-set-window.x-on-key-window-escape="-slot-add-close"
                        x-set-window.x-run--slot-add-close="x-on-click"
                        x-set--slot-add-close.x-focus="-slot-add-open-break"
                        x-val-appointment-type-h2="Add Break slot" 
                        x-val-appointment-type="break" 
                    >
                        + Add Break slot
                    </button>
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--slot-delete-open
                        x-on-click
                        x-rot--slot-delete="active"
                        x-focus="-slot-delete-tab-last"
                        x-tab="-slot-delete-tab-first:-slot-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-slot-delete-close"
                        x-set-window.x-run--slot-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
                <div class="column col-12 p-2"></div>
                <div class="column col-12 p-2">
                    <h6><b>Legend:</b></h6>
                    <span class="chip">
                        <span class="avatar avatar-sm" style="background: red;"></span> Cancelled
                    </span>
                    <span class="chip">
                        <span class="avatar avatar-sm" style="background: blue;"></span> Booked
                    </span>
                    <span class="chip">
                        <span class="avatar avatar-sm" style="background: orange;"></span> Break
                    </span>
                    <span class="chip">
                        <span class="avatar avatar-sm" style="background: green;"></span> Open slot
                    </span>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form method="POST" action="<?= $app->urlRoute('appointment/delete?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <input type="hidden" name="appointment[schedule_id]" value="<?= $app->htmlEncode($schedule['id']) ?>"/>
        
                        <div >
                            <table class="table table-scroll">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Patient</th>
                                    </tr>
                                </thead>
                                <tbody>
                            
                                    <?php foreach ($slots as $slot): ?>

                                        <tr style="border-left: solid 8px <?= $colorByType[$slot['type']] ?>;">
                                            <td>
                                                <?php if ($slot['type'] != 'open slot' && $slot['type'] != 'regular') : ?>
                                                <label class="form-checkbox">
                                                    <input type="checkbox" name="appointment[id][]" value="<?= $app->htmlEncode($slot['id']) ?>">
                                                    <i class="form-icon"></i>
                                                </label>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $startTime = new DateTime($slot['time_start']);
                                                    $endTime = new DateTime($slot['time_end']);
                                                    echo $app->htmlEncode($startTime->format('g:i A')) . ' - ' . $app->htmlEncode($endTime->format('g:i A'));
                                                ?>
                                            </td>
                                            <td><?= $app->htmlEncode($slot['type'] === 'regular' ? 'booked' : $slot['type']) ?></td>
                                            <td>
                                                <?php if ($slot['type'] === 'regular'): ?>
                                                    <a href="<?= $app->htmlEncode($app->urlRoute('profile/:id', array(':id' => $slot['patient_id']))) ?>"><?= $app->htmlEncode($slot['patient_lastname'] . ", " . $slot['patient_firstname'] . " " . $slot['patient_middlename']) ?></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                            
                                    <?php endforeach; ?>
                            
                                </tbody>
                            </table>
                        </div>
        
        
                        <!-- slot delete modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--slot-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--slot-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--slot-delete-close x-on-click x-tab x-rot--slot-delete="" x-focus="-slot-delete-open"></a>
                                    <div class="modal-title h5">Delete Slot</div>
                                </div>
                                <div class="modal-body">
                                    Delete selected slots
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click x-run--slot-delete-close="x-on-click">Delete</button>
                                    <button class="btn" type="button" x-ref--slot-delete-tab-last x-on-click x-run--slot-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- slot add modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--slot-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--slot-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('appointment/store-not-regular?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="appointment[schedule_id]" value="<?= $app->htmlEncode($schedule['id']) ?>"/>
            <input type="hidden" name="appointment[type]" value="" x-ref-appointment-type/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--slot-add-close x-on-click x-tab x-rot--slot-add="" x-focus="-slot-add-open"></a>
                <div class="modal-title h5" x-ref-appointment-type-h2></div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea class="form-input" name="message[body]" class="message-box" rows="4" required x-ref--slot-add-tab-first></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time (start)</label>
                        <input class="form-input" type="time" name="appointment[time_start]"  min="<?= $app->htmlEncode(date('H:i', strtotime($schedule['time_start']))) ?>" max="<?= $app->htmlEncode(date('H:i', strtotime($schedule['time_end']))) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time (end)</label>
                        <input class="form-input" type="time" name="appointment[time_end]" min="<?= $app->htmlEncode(date('H:i', strtotime($schedule['time_start']))) ?>" max="<?= $app->htmlEncode(date('H:i', strtotime($schedule['time_end']))) ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Create</button>
                <button class="btn" type="button" x-ref--slot-add-tab-last x-on-click x-run--slot-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    
    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>