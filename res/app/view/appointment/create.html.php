<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$user = $data['user'];
$allSlots = $data['allSlots'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Appointment Create</title>
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
                            <a href="<?= $app->urlRoute('appointment') ?>">Appointment</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a>Create</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form id="appointment-selector" class="form-grid">
                        <!-- Step 1: Select Service -->
                        <label>Select Service:</label><br><br>
                        <div id="service-buttons" >
                            <?php foreach ($allSlots as $serviceName => $slotsByDate): ?>
                                <label class="chip _" x-on-click x-ref--appointment-data-service x-var--appointment-data-service="<?= $app->htmlEncode($serviceName) ?>"><?= $app->htmlEncode($serviceName) ?></label><br>
                            <?php endforeach; ?>
                        </div>
        
                    
                        <!-- Step 2: Select Date -->
                        <br><label>Select Date:</label><br>
                        <div id="date-buttons" style="margin-top: 1rem;"></div>
        
                    </form>

                </div>
                <div class="column col-12 p-2">
                    <div id="slots-container"></div>
                </div>
            </div>

        </div>
    </main>
    
    <!-- appointment create modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--appointment-create>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--appointment-create-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('appointment/store-not-regular?redirect=:redirect', array(':redirect' => 'appointment')) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input x-ref-appointment_user_id type="hidden" name="appointment[user_id]" value="">
            <input x-ref-appointment_service_id type="hidden" name="appointment[service_id]" value="">
            <input x-ref-appointment_schedule_id type="hidden" name="appointment[schedule_id]" value="">
            <input x-ref-appointment_time_start type="hidden" name="appointment[time_start]" value="">
            <input x-ref-appointment_time_end type="hidden" name="appointment[time_end]" value="">
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--appointment-create-close x-on-click x-tab x-rot--appointment-create="" x-focus="-appointment-create-open"></a>
                <div class="modal-title h5">Confirm Booking</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <p><strong>Service</strong><br><span x-ref-appointment_service_name_show></span></p>
                    <p><strong>Rate</strong><br><span x-ref-appointment_service_price_show></span></p>
                    <p><strong>Date</strong><br><span x-ref-appointment_date_show></span></p>
                    <p><strong>Time</strong><br><span x-ref-appointment_time_show></span></p>
                    <p><strong>Doctor</strong><br><span x-ref-appointment_doctor_name_show></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Confirm Booking</button>
                <button class="btn" type="button" x-ref--appointment-create-tab-last x-on-click x-run--appointment-create-close="x-on-click">Close</button>
            </div>
        </form>
    </div>

    <script>
        (window.init = window.init || []).push(function () {
            Utils.script(["<?= $app->urlWeb('asset/js/AppointmentSlots.js') ?>"], function () {
                new AppointmentSlots().init(<?= json_encode($allSlots, JSON_UNESCAPED_SLASHES) ?>, "<?= $user['id'] ?>", "<?= $app->urlRoute('profile/:id') ?>");
            });
        });
    </script>

   <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>