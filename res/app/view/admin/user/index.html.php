<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$userRole = $data['user_role'];
$searchQuery = $data['search'];
$roleQuery = $data['role'];
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
        #search-table {
            visibility: hidden;
            overflow-x: auto;
            white-space: nowrap;
            max-width: 100%;
            opacity: 0;
            transform: translateY(20px); 
        }


        #search-table.visible {
            visibility: visible;
            opacity: 1;
            transform: translateY(0); /* Move to original position when visible */
            transition: opacity 0.3s ease-out, transform 0.3s ease-out; /* Smooth fade and slide effect */
        }

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
                            <a>User</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2 columns col-gapless">
                    <div class="column p-1 col-12">
                        <button
                            type="button"
                            class="btn btn-success"
                            x-ref--user-add-open
                            x-on-click
                            x-rot--user-add="active"
                            x-focus="-user-add-tab-first"
                            x-tab="-user-add-tab-first:-user-add-tab-last"
                            x-set-window.x-on-key-window-escape="-user-add-close"
                            x-set-window.x-run--user-add-close="x-on-click"
                        >
                            + Add User
                        </button>
                        <button
                            type="button"
                            class="btn btn-error"
                            x-ref--user-delete-open
                            x-on-click
                            x-rot--user-delete="active"
                            x-focus="-user-delete-tab-last"
                            x-tab="-user-delete-tab-first:-user-delete-tab-last"
                            x-set-window.x-on-key-window-escape="-user-delete-close"
                            x-set-window.x-run--user-delete-close="x-on-click"
                        >
                            - Delete Selected
                        </button>
                    </div>
                    <div class="column col-6 col-md-12 p-1">
                        <div class="has-icon-left" >
                            <input class="form-input" type="text" id="search" name="search" placeholder="Search..." value="<?php echo $searchQuery; ?>" oninput="debouncedSearch()">
                            <i class="form-icon icon icon-search"></i>
                        </div>
                    </div>
                    <div class="input-group column col-3 col-md-12 p-1">
                        <span class="input-group-addon">Role</span>
                        <select class="form-select"id="role" name="role" onchange="search()">

                            <option value="" <?php if ($roleQuery == '') echo 'selected'; ?>>All</option>
                            <?php foreach ($userRole as $role) : ?>
                                
                            <option value="<?= $app->htmlEncode($role) ?>" <?php if ($roleQuery == $role) echo 'selected'; ?>><?= $app->htmlEncode(ucwords($role)) ?></option>
                            <?php endforeach; ?>
    
    
                        </select>
                    </div>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <form method="POST" action="<?= $app->urlRoute('user/delete-batch') ?>">
                        <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                        <div id="search-table"></div>
    
                        <!-- user delete modal -->
                        <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--user-delete>
                            <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--user-delete-close="x-on-click"></a>
                            <div class="modal-container" >
                                <div class="modal-header">
                                    <a class="btn btn-clear float-right" aria-label="Close" x-ref--user-delete-close x-on-click x-tab x-rot--user-delete="" x-focus="-user-delete-open"></a>
                                    <div class="modal-title h5">Delete User</div>
                                </div>
                                <div class="modal-body">
                                    Delete selected users
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-error" x-on-click x-run--user-delete-close="x-on-click">Delete</button>
                                    <button class="btn" type="button" x-ref--user-delete-tab-last x-on-click x-run--user-delete-close="x-on-click">Close</button>
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script>
        (window.init = window.init || []).push(function () {
            var url = "<?= $app->urlRoute('user/search-table.ajax') ?>";
            var searchTable = new El('search-table');
            var request = new Request(new XMLHttpRequest());
            request.setCache(true).setCacheSize(2).setCacheTTL(3);
            
            // Function to show and hide the content with transitions
            function showContentWithTransition(content) {
                searchTable.el.classList.remove('visible'); // Fade out
                searchTable.html(content); // Update content
                setTimeout(function() {
                    searchTable.el.classList.add('visible'); // Fade in after content is set
                }, 300); // Wait for a short moment to apply the transition
            }
    
            // Main search function that updates the page dynamically
            window.search = function() {
                // Fetch search input and role filter
                var searchValue = document.getElementById("search").value;
                var roleValue = document.getElementById("role").value;
    
                url = new Url(url).setQuery('search', searchValue).setQuery('role', roleValue).toString();
                new Url().setQuery('search', searchValue).setQuery('role', roleValue).sync(true);
    
                searchTable.html("<div class=\"loading loading-lg\"></div>");
    
                request.addCallback(function(request, response) {
                    if (response.code === 0) {
                        showContentWithTransition('No internet connection.');
                    } else {
                        showContentWithTransition(response.content);
                        ElX.init(document.querySelectorAll('#search-table *'));
                        ElX.clean();
                    }
                });
    
                request.send(url, {
                    'method': 'POST',
                });
            }
    
            // Debounce function to limit the frequency of search
            window.debouncedSearch = Utils.debounce(window.search, 500);

            window.search();
        });
    </script>

    <!-- user add modal -->
    <div style="position: fixed; z-index: 899;"class="modal _" x-ref--user-add>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--user-add-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('user/store?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--user-add-close x-on-click x-tab x-rot--user-add="" x-focus="-user-add-open"></a>
                <div class="modal-title h5">Add User</div>
            </div>
            <div class="modal-body">
                <div class="content columns">
                    <div class="form-group column col-4 col-md-12">
                        <label class="form-label">Firstname</label>
                        <input class="form-input" type="text" name="user[firstname]" required x-ref--register-tab-first>
                    </div>
                    <div class="form-group column col-3 col-md-12">
                        <label class="form-label">Middlename</label>
                        <input class="form-input" type="text" name="user[middlename]">
                    </div>
                    <div class="form-group column col-5 col-md-12">
                        <label class="form-label">Lastname</label>
                        <input class="form-input" type="text" name="user[lastname]" required>
                    </div>
                    
                    <div class="form-group column col-12">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="user[role]" required>
                            <?php foreach ($userRole as $role): ?>
                                <option value="<?= $app->htmlEncode($role) ?>"><?= $app->htmlEncode($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group column col-6 col-md-12">
                        <label class="form-label">Date of Birth</label>
                        <input class="form-input" type="date" name="user[date_of_birth]" required>
                    </div>
                    <div class="form-group column col-6 col-md-12">
                        <label class="form-label">Contact</label>
                        <input class="form-input" type="number" name="user[contact]" required>
                    </div>

                    <div class="form-group column col-12">
                        <label class="form-label">Email</label>
                        <input class="form-input" type="email" name="user[email]" required>
                    </div>
                    <div class="form-group column col-12">
                        <label class="form-label">Password</label>
                        <input class="form-input" type="password" name="user[password]" required x-on-input x-var--register-password="this">
                    </div>
                    <div class="form-group column col-12">
                        <label class="form-label">Confirm Password</label>
                        <input class="form-input" type="password" name="confirm_password" required x-on-input x-ref--register-confirm-password x-var--register-confirm-password="this">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Create</button>
                <button class="btn" type="button" x-ref--user-add-tab-last x-on-click x-run--user-add-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    <script>
        (window.init = window.init || []).push(function () {
            var password = ElX.x("-register-password");
            var confirmPassword = ElX.x("-register-confirm-password");
            
            var validatePassword = function () {
                if (confirmPassword.value() !== "" && password.value() !== confirmPassword.value()) {
                    confirmPassword.ref()[0].setCustomValidity("Please make sure your password and confirmation match.");
                } else {
                    confirmPassword.ref()[0].setCustomValidity("");
                }
            };
            confirmPassword.tap(validatePassword);
            password.tap(validatePassword);
        });
    </script>

    <!-- user edit modal -->
    <div style="position: fixed; z-index: 899;"class="modal _" x-ref--user-edit>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--user-edit-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('user/update?redirect=:redirect', array(':redirect' => $currentRoute)) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="user[id]" value="" required x-ref-user_id >
            <input type="hidden" name="current_email" value="" required x-ref-current_user_email>
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--user-edit-close x-on-click x-tab x-rot--user-edit="" x-focus="-user-edit-open"></a>
                <div class="modal-title h5">Edit User</div>
            </div>
            <div class="modal-body">
                <div class="content columns">
                    <div class="form-group column col-4 col-md-12">
                        <label class="form-label">Firstname</label>
                        <input class="form-input" type="text" name="user[firstname]" value="" x-ref-user_firstname  required x-ref--user-edit-tab-first>
                    </div>
                    <div class="form-group column col-3 col-md-12">
                        <label class="form-label">Middlename</label>
                        <input class="form-input" type="text" name="user[middlename]" value="" x-ref-user_middlename>
                    </div>
                    <div class="form-group column col-5 col-md-12">
                        <label class="form-label">Lastname</label>
                        <input class="form-input" type="text" name="user[lastname]" value="" required x-ref-user_lastname>
                    </div>
                    
                    <div class="form-group column col-12">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="user[role]" required x-ref-user_role >
                            <?php foreach ($userRole as $role): ?>
                                <option value="<?= $app->htmlEncode($role) ?>"><?= $app->htmlEncode($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group column col-6 col-md-12">
                        <label class="form-label">Date of Birth</label>
                        <input class="form-input" type="date" name="user[date_of_birth]" value="" required x-ref-user_date_of_birth >
                    </div>
                    <div class="form-group column col-6 col-md-12">
                        <label class="form-label">Contact</label>
                        <input class="form-input" type="number" name="user[contact]" value="" required x-ref-user_contact >
                    </div>

                    <div class="form-group column col-12">
                        <label class="form-label">Email</label>
                        <input class="form-input" type="text"  name="user[email]" value="" required x-ref-user_email>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <button class="btn" type="button" x-ref--user-edit-tab-last x-on-click x-run--user-edit-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    
    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>