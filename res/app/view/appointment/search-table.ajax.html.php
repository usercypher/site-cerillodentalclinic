<?php 

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$appointments = $data['appointments'];

$colorByStatus = array(
    'cancelled' => 'red',
    'approved' => 'blue', 
    'pending'   => 'orange',
    'completed' => 'green',
);
?>

    <table class="table table-scroll">
        <thead>
            <tr>
                <th></th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Service Name</th>
                <th>Doctor</th>
                <th>Doctor Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($appointments) > 0): ?>
                <?php foreach ($appointments as $i => $appointment): ?>
                <tr style="border-left: solid 8px <?= $colorByStatus[$appointment['status']] ?>;">
                    <td>
                        <?php if ($appointment['status'] !== 'cancelled' && $appointment['status'] !== 'completed'): ?>
                        <label class="form-checkbox">
                            <input type="checkbox" name="appointment[id][]" value="<?= $app->htmlEncode($appointment['id']) ?>">
                            <i class="form-icon"></i>
                        </label>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $date = new DateTime($appointment['date']);
                            echo $app->htmlEncode($date->format('M j, Y')); // e.g., Sep 29, 2025
                        ?>
                    </td>
                    <td>
                        <?php
                            $startTime = new DateTime($appointment['time_start']);
                            $endTime = new DateTime($appointment['time_end']);
                            echo $app->htmlEncode($startTime->format('g:i A')) . ' - ' . $app->htmlEncode($endTime->format('g:i A')); // e.g., 10:00 AM - 11:30 AM
                        ?>
                    </td>
                    <td><?= $app->htmlEncode(ucfirst($appointment['status'])) ?></td>
                    <td><?= $app->htmlEncode($appointment['service_name']) ?></td>
                    <td><a href="<?= $app->htmlEncode($app->urlRoute('profile/:id', array(':id' => $appointment['doctor_id']))) ?>"><?= $app->htmlEncode($appointment['doctor_lastname']) ?>, <?= $app->htmlEncode($appointment['doctor_firstname']) ?> <?= $app->htmlEncode($appointment['doctor_middlename']) ?></a></td>
                    <td><?= $app->htmlEncode($appointment['doctor_email']) ?></td>
                    <td style="white-space: nowrap;">

                        <button
                            type="button"
                            class="btn"
                            x-ref--appointment-view-open-<?= $i ?>
                            x-on-click
                            x-rot--appointment-view="active"
                            x-focus="-appointment-view-tab-last"
                            x-tab="-appointment-view-tab-last:-appointment-view-tab-last"
                            x-set-window.x-on-key-window-escape="-appointment-view-close"
                            x-set-window.x-run--appointment-view-close="x-on-click"
                            x-set--appointment-view-close.x-focus="-appointment-view-open-<?= $i ?>"
                            x-val-appointment_id="<?= $app->htmlEncode($appointment['id']) ?>"
                            x-val-appointment_date="<?= $app->htmlEncode($date->format('M j, Y')) ?>"
                            x-val-appointment_time="<?= $app->htmlEncode($startTime->format('g:i A')) . ' - ' . $app->htmlEncode($endTime->format('g:i A')) ?>"
                            x-val-appointment_user_name="<?= $app->htmlEncode($appointment['lastname']) ?>, <?= $app->htmlEncode($appointment['firstname']) ?> <?= $app->htmlEncode($appointment['middlename']) ?>"
                            x-val-appointment_email="<?= $app->htmlEncode($appointment['email']) ?>"
                            x-val-appointment_service_name="<?= $app->htmlEncode($appointment['service_name']) ?>"
                            x-val-appointment_service_duration="<?= $app->htmlEncode($appointment['service_duration']) ?>"
                            x-val-appointment_status="<?= $app->htmlEncode($appointment['status']) ?>"
                            x-val-appointment_service_price="<?= $app->htmlEncode($appointment['service_price']) ?>"
                            x-val-appointment_doctor_name="<?= $app->htmlEncode($appointment['doctor_lastname']) ?>, <?= $app->htmlEncode($appointment['doctor_firstname']) ?> <?= $app->htmlEncode($appointment['doctor_middlename']) ?>"
                            x-val-appointment_doctor_email="<?= $app->htmlEncode($appointment['doctor_email']) ?>"
                        >
                            View
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align: center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
