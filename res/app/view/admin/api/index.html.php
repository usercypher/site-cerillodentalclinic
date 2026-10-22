<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$apis = $data['apis'];
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
                            <a>Api</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-success"
                        x-ref--api-add-open
                        x-on-click
                        x-rot--api-add="active"
                        x-focus="-api-add-tab-first"
                        x-tab="-api-add-tab-first:-api-add-tab-last"
                        x-set-window.x-on-key-window-escape="-api-add-close"
                        x-set-window.x-run--api-add-close="x-on-click"
                    >
                        + Add Api key
                    </button>
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--api-delete-open
                        x-on-click
                        x-rot--api-delete="active"
                        x-focus="-api-delete-tab-last"
                        x-tab="-api-delete-tab-first:-api-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-api-delete-close"
                        x-set-window.x-run--api-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form method="POST" action="<?= $app->urlRoute('api/delete') ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <div >
                            <table class="table table-scroll">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Api key</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
            
                                    <?php foreach ($apis as $i => $api): ?>
        
                                    <tr>
                                        <td>
                                            <label class="form-checkbox">
                                                <input type="checkbox" name="api[id][]" value="<?= $app->htmlEncode($api['id']) ?>">
                                                <i class="form-icon"></i>
                                            </label>
                                        </td>
                                        <td><?= $app->htmlEncode($api['content']) ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                x-ref--api-edit-open-<?= $i ?>
                                                x-on-click
                                                x-rot--api-edit="active"
                                                x-focus="-api-edit-tab-first"
                                                x-tab="-api-edit-tab-first:-api-edit-tab-last"
                                                x-set-window.x-on-key-window-escape="-api-edit-close"
                                                x-set-window.x-run--api-edit-close="x-on-click"
                                                x-set--api-edit-close.x-focus="-api-edit-open-<?= $i ?>"
                                                x-val-api_id="<?= $app->htmlEncode($api['id']) ?>"
                                                x-val-api_content="<?= $app->htmlEncode($api['content']) ?>"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
        
                                    <?php endforeach; ?>
            
                                </tbody>
                            </table>
                        </div>
                        <!-- delete api modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--api-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--api-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--api-delete-close x-on-click x-tab x-rot--api-delete="" x-focus="-api-delete-open"></a>
                                    <div class="modal-title h5">Delete Api Key</div>
                                </div>
                                <div class="modal-body">
                                    Delete selected api
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click x-run--api-delete-close="x-on-click">Delete</button>
                                    <button class="btn" type="button" x-ref--api-delete-tab-last x-on-click x-run--api-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
    
    <!-- add api modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--api-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--api-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('api/store') ?>">
            <input type="hidden" name="api[user_id]" value="<?= $app->htmlEncode($user['id']) ?>"/>
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--api-add-close x-on-click x-tab x-rot--api-add="" x-focus="-api-add-open"></a>
                <div class="modal-title h5">Add API Key</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">API Key</label>
                        <div class="input-group">
                            <input class="form-input" type="text" id="api_content" name="api[content]" readonly />
                            <button type="button" class="btn btn-primary input-group-btn btn-action" onclick="generateClick();"><i class="icon icon-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Create</button>
                <button class="btn" type="button" x-ref--api-add-tab-last x-on-click x-run--api-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
 
    <script>
        (function() {
            function generateRandomApiKey(length = 32) {
                const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let apiKey = '';
                for (let i = 0; i < length; i++) {
                    apiKey += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                return apiKey;
            }
            
            window.generateClick = function () {
                const newKey = generateRandomApiKey();
                document.getElementById('api_content').value = newKey;
            }
        })();
    </script>


    <!-- api edit modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--api-edit>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--api-edit-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('api/update') ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="api[id]" value="" required x-ref-api_id>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--api-edit-close x-on-click x-tab x-rot--api-edit="" x-focus="-api-edit-open"></a>
                <div class="modal-title h5">Edit Api Key</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Api</label>
                        <input class="form-input" type="text" name="api[content]" placeholder="Api key" x-ref-api_content x-ref--api-edit-tab-first/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <button class="btn" type="button" x-ref--api-edit-tab-last x-on-click x-run--api-edit-close="x-on-click">Close</button>
            </div>
        </form>
    </div>


    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>