<?php 

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$users = $data['users'];

?>


    <table class="table table-scroll">
        <thead>
            <tr>
                <th></th>
                <th>id</th>
                <th>Name</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($users as $i => $user): ?>

            <tr>
                <td>
                    <label class="form-checkbox">
                        <input type="checkbox" name="user[id][]" value="<?= $app->htmlEncode($user['id']) ?>">
                        <i class="form-icon"></i>
                    </label>
                </td>
                <td><?= $app->htmlEncode($user['id']) ?></td>
                <td><?= $app->htmlEncode($user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename']) ?></td>
                <td><?= $app->htmlEncode($user['role']) ?></td>
                <td><?= $app->htmlEncode($user['contact']) ?></td>
                <td><?= $app->htmlEncode($user['email']) ?></td>
                <td>
                    <button
                        type="button"
                        class="btn btn-primary"
                        x-ref--user-edit-open-<?= $i ?>
                        x-on-click
                        x-rot--user-edit="active"
                        x-focus="-user-edit-tab-first"
                        x-tab="-user-edit-tab-first:-user-edit-tab-last"
                        x-set-window.x-on-key-window-escape="-user-edit-close"
                        x-set-window.x-run--user-edit-close="x-on-click"
                        x-set--user-edit-close.x-focus="-user-edit-open-<?= $i ?>"
                        x-val-user_id="<?= $app->htmlEncode($user['id']) ?>"
                        x-val-current_user_email="<?= $app->htmlEncode($user['email']) ?>"
                        x-val-user_firstname="<?= $app->htmlEncode($user['firstname']) ?>"
                        x-val-user_middlename="<?= $app->htmlEncode($user['middlename']) ?>"
                        x-val-user_lastname="<?= $app->htmlEncode($user['lastname']) ?>"
                        x-val-user_date_of_birth="<?= $app->htmlEncode($user['date_of_birth']) ?>"
                        x-val-user_role="<?= $app->htmlEncode($user['role']) ?>"
                        x-val-user_contact="<?= $app->htmlEncode($user['contact']) ?>"
                        x-val-user_email="<?= $app->htmlEncode($user['email']) ?>"
                    >
                        Edit
                    </button>
                </td>
            </tr>

            <?php endforeach; ?>

        </tbody>
    </table>
