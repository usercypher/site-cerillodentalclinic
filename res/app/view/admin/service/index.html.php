<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$services = $data['services'];
$appointmentStatus = $data['appointment_status'];
$user = $data['user'];

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
                            <a>Service</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-success"
                        x-ref--service-add-open
                        x-on-click
                        x-rot--service-add="active"
                        x-focus="-service-add-tab-first"
                        x-tab="-service-add-tab-first:-service-add-tab-last"
                        x-set-window.x-on-key-window-escape="-service-add-close"
                        x-set-window.x-run--service-add-close="x-on-click"
                    >
                        + Add Service
                    </button>
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--service-delete-open
                        x-on-click
                        x-rot--service-delete="active"
                        x-focus="-service-delete-tab-last"
                        x-tab="-service-delete-tab-first:-service-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-service-delete-close"
                        x-set-window.x-run--service-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form method="POST" action="<?= $app->urlRoute('service/delete') ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <div >
                            <table class="table table-scroll">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Duration</th>
                                        <th>Rate</th>
                                        <th>Default Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
            
                                    <?php foreach ($services as $i => $service): ?>
        
                                    <tr>
                                        <td>
                                            <label class="form-checkbox">
                                                <input type="checkbox" name="service[id][]" value="<?= $app->htmlEncode($service['id']) ?>">
                                                <i class="form-icon"></i>
                                            </label>
                                        </td>
                                        <td><?= $app->htmlEncode($service['name']) ?></td>
                                        <td><?= $app->htmlEncode($service['description']) ?></td>
                                        <td><?= $app->htmlEncode($service['duration']) ?> min</td>
                                        <td><?= $app->htmlEncode(number_format($service['price'], 2)) ?></td>
                                        <td><?= $app->htmlEncode($service['appointment_status_default']) ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                x-ref--service-edit-open-<?= $i ?>
                                                x-on-click
                                                x-rot--service-edit="active"
                                                x-focus="-service-edit-tab-first"
                                                x-tab="-service-edit-tab-first:-service-edit-tab-last"
                                                x-set-window.x-on-key-window-escape="-service-edit-close"
                                                x-set-window.x-run--service-edit-close="x-on-click"
                                                x-set--service-edit-close.x-focus="-service-edit-open-<?= $i ?>"
                                                x-val-service_id="<?= $app->htmlEncode($service['id']) ?>"
                                                x-val-current_service_name="<?= $app->htmlEncode($service['name']) ?>"
                                                x-val-service_name="<?= $app->htmlEncode($service['name']) ?>"
                                                x-val-service_description="<?= $app->htmlEncode($service['description']) ?>"
                                                x-val-service_price="<?= $app->htmlEncode($service['price']) ?>"
                                                x-val-service_duration="<?= $app->htmlEncode($service['duration']) ?>"
                                                x-val-appointment_status_default="<?= $app->htmlEncode($service['appointment_status_default']) ?>"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
        
                                    <?php endforeach; ?>
            
                                </tbody>
                            </table>
                        </div>
    
                        <!-- service delete modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--service-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--service-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--service-delete-close x-on-click x-tab x-rot--service-delete="" x-focus="-service-delete-open"></a>
                                    <div class="modal-title h5">Delete Service</div>
                                </div>
                                <div class="modal-body">
                                    Delete selected services
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click x-run--service-delete-close="x-on-click">Delete</button>
                                    <button class="btn" type="button" x-ref--service-delete-tab-last x-on-click x-run--service-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
    
                    </form>
                </div>
                    
            </div>

        </div>
    </main>
    
    <!-- add service modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--service-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--service-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('service/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--service-add-close x-on-click x-tab x-rot--service-add="" x-focus="-service-add-open"></a>
                <div class="modal-title h5">Add Service</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input class="form-input" type="text" name="service[name]" required x-ref--service-add-tab-first >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-input" name="service[description]" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (minutes)</label>
                        <input class="form-input" type="number" name="service[duration]" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate</label>
                        <input class="form-input" type="number" name="service[price]" required step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default status</label>
                        <select class="form-select" name="service[appointment_status_default]" required>
                            <?php foreach ($appointmentStatus as $status): ?>
                                <option value="<?= $app->htmlEncode($status) ?>" <?= $status == 'approved' ? 'selected' : '' ?> ><?= $app->htmlEncode($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Create</button>
                <button class="btn" type="button" x-ref--service-add-tab-last x-on-click x-run--service-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    <!-- service edit modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--service-edit>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--service-edit-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('service/update') ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="service[id]" value="" required x-ref-service_id>
            <input type="hidden" name="current[service][name]" value="" required x-ref-current_service_name>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--service-edit-close x-on-click x-tab x-rot--service-edit="" x-focus="-service-edit-open"></a>
                <div class="modal-title h5">Edit Service</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input class="form-input" type="text" name="service[name]" value="" required x-ref-service_name x-ref--service-edit-tab-first>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-input" name="service[description]" rows="4" x-ref-service_description></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (minutes)</label>
                        <input class="form-input" type="number" name="service[duration]" value="" required x-ref-service_duration>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate</label>
                        <input class="form-input" type="number" name="service[price]" value="" required step="0.01" min="0" x-ref-service_price >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default status</label>
                        <select class="form-select" name="service[appointment_status_default]" required x-ref-appointment_status_default >
                            <?php foreach ($appointmentStatus as $status): ?>
                                <option value="<?= $app->htmlEncode($status) ?>"><?= $app->htmlEncode($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <button class="btn" type="button" x-ref--service-edit-tab-last x-on-click x-run--service-edit-close="x-on-click">Close</button>
            </div>
        </form>
    </div>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>