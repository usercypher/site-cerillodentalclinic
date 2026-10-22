<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$chatbots = $data['chatbots'];
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
                            <a>Chatbot</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <button
                        type="button"
                        class="btn btn-success"
                        x-ref--chatbot-add-open
                        x-on-click
                        x-rot--chatbot-add="active"
                        x-focus="-chatbot-add-tab-first"
                        x-tab="-chatbot-add-tab-first:-chatbot-add-tab-last"
                        x-set-window.x-on-key-window-escape="-chatbot-add-close"
                        x-set-window.x-run--chatbot-add-close="x-on-click"
                    >
                        + Add Knowledge
                    </button>
                    <button
                        type="button"
                        class="btn btn-error"
                        x-ref--chatbot-delete-open
                        x-on-click
                        x-rot--chatbot-delete="active"
                        x-focus="-chatbot-delete-tab-last"
                        x-tab="-chatbot-delete-tab-first:-chatbot-delete-tab-last"
                        x-set-window.x-on-key-window-escape="-chatbot-delete-close"
                        x-set-window.x-run--chatbot-delete-close="x-on-click"
                    >
                        - Delete Selected
                    </button>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form method="POST" action="<?= $app->urlRoute('chatbot/delete') ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <div >
                            <table class="table table-scroll">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Knowledge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
            
                                    <?php foreach ($chatbots as $i => $chatbot): ?>
        
                                    <tr>
                                        <td>
                                            <label class="form-checkbox">
                                                <input type="checkbox" name="chatbot[id][]" value="<?= $app->htmlEncode($chatbot['id']) ?>">
                                                <i class="form-icon"></i>
                                            </label>
                                        </td>
                                        <td><?= $app->htmlEncode($chatbot['knowledge']) ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                x-ref--chatbot-edit-open-<?= $i ?>
                                                x-on-click
                                                x-rot--chatbot-edit="active"
                                                x-focus="-chatbot-edit-tab-first"
                                                x-tab="-chatbot-edit-tab-first:-chatbot-edit-tab-last"
                                                x-set-window.x-on-key-window-escape="-chatbot-edit-close"
                                                x-set-window.x-run--chatbot-edit-close="x-on-click"
                                                x-set--chatbot-edit-close.x-focus="-chatbot-edit-open-<?= $i ?>"
                                                x-val-chatbot_id="<?= $app->htmlEncode($chatbot['id']) ?>"
                                                x-val-chatbot_knowledge="<?= $app->htmlEncode($chatbot['knowledge']) ?>"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
        
                                    <?php endforeach; ?>
            
                                </tbody>
                            </table>
                        </div>
                        <!-- delete chatbot modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--chatbot-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--chatbot-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--chatbot-delete-close x-on-click x-tab x-rot--chatbot-delete="" x-focus="-chatbot-delete-open"></a>
                                    <div class="modal-title h5">Delete Knowledge</div>
                                </div>
                                <div class="modal-body">
                                    Delete selected chatbot
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click x-run--chatbot-delete-close="x-on-click">Delete</button>
                                    <button class="btn" type="button" x-ref--chatbot-delete-tab-last x-on-click x-run--chatbot-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
    <!-- add chatbot modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--chatbot-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--chatbot-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('chatbot/store') ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--chatbot-add-close x-on-click x-tab x-rot--chatbot-add="" x-focus="-chatbot-add-open"></a>
                <div class="modal-title h5">Add Knowledge</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Knowledge</label>
                        <textarea class="form-input" name="chatbot[knowledge]" rows="4" x-ref--chatbot-add-tab-first></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Create</button>
                <button class="btn" type="button" x-ref--chatbot-add-tab-last x-on-click x-run--chatbot-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    <!-- chatbot edit modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--chatbot-edit>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--chatbot-edit-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('chatbot/update') ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="chatbot[id]" value="" required x-ref-chatbot_id>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--chatbot-edit-close x-on-click x-tab x-rot--chatbot-edit="" x-focus="-chatbot-edit-open"></a>
                <div class="modal-title h5">Edit Knowledge</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Knowledge</label>
                        <textarea class="form-input" name="chatbot[knowledge]" rows="4" x-ref-chatbot_knowledge x-ref--chatbot-edit-tab-first></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <button class="btn" type="button" x-ref--chatbot-edit-tab-last x-on-click x-run--chatbot-edit-close="x-on-click">Close</button>
            </div>
        </form>
    </div>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

    
</body>

</html>