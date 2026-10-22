<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];
$user = $data['user'];
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$appointment_status = $data['appointment_status'];
$searchQuery = $data['search'];
$statusQuery = $data['status'];
$fromDateQuery = $data['from_date'];
$toDateQuery = $data['to_date'];

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
                            <a>Appointment</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2 columns col-gapless">
                    <!-- Placeholder for user action buttons -->
                    <?php if ($user['role'] == "patient"): ?>
                    <div class="column p-1 col-12">
                        <a href="<?= $app->urlRoute('appointment/create') ?>" class="btn btn-success">+ Add Appointment</a>
                        <button
                            type="button"
                            class="btn btn-error"
                            x-ref--appointment-cancel-open
                            x-on-click
                            x-rot--appointment-cancel="active"
                            x-focus="-appointment-cancel-tab-first"
                            x-tab="-appointment-cancel-tab-first:-appointment-cancel-tab-last"
                            x-set-window.x-on-key-window-escape="-appointment-cancel-close"
                            x-set-window.x-run--appointment-cancel-close="x-on-click"
                        >
                            - Cancel Selected
                        </button>
                    </div>                    
                    <?php endif; ?>
                    <div class="column col-6 col-md-12 p-1">
                        <div class="has-icon-left" >
                            <input type="text" class="form-input" id="search" name="search" placeholder="Search..." value="<?php echo $searchQuery; ?>" oninput="debouncedSearch()">
                            <i class="form-icon icon icon-search"></i>
                        </div>
                    </div>
                    <div class="input-group column col-3 col-md-12 p-1">
                        <span class="input-group-addon">Status</span>
                        <select id="status" class="form-select" name="status" onchange="search()">

                            <option value="" <?php if ($statusQuery == '') echo 'selected'; ?>>All</option>
                            <?php foreach ($appointment_status as $status) : ?>
                                
                            <option value="<?= $app->htmlEncode($status) ?>" <?php if ($statusQuery == $status) echo 'selected'; ?>><?= $app->htmlEncode(ucwords($status)) ?></option>
                            <?php endforeach; ?>
    
                        </select>
                    </div>
                    <div class="input-group column col-4 col-md-12 p-1">
                        <span class="input-group-addon">From</span>
                        <input type="date" id="fromDate" class="form-input" value="<?= $fromDateQuery ?>" x-on-input x-ref--from-date x-var--from-date="this" />
                    </div>
                    <div class="input-group column col-4 col-md-12 p-1">
                        <span class="input-group-addon">To</span>
                        <input type="date" id="toDate" class="form-input" value="<?= $toDateQuery ?>" x-on-input x-ref--to-date x-var--to-date="this" />
                    </div>
                    <script>
                        (window.init = window.init || []).push(function () {
                            ElX.tap('-from-date', Utils.debounce(function(old, current, event) {
                                if (ElX.vars['-to-date'] >= current) {
                                    search();
                                } else {
                                    flashTpl([{"type": "error", "message": "Invalid date range: " + new Date(ElX.vars['-from-date']).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + " - " + new Date(ElX.vars['-to-date']).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}]);
                                    if (event) { event.target.value = old; }
                                    ElX.vars['-from-date'] = old;
                                }
                            }, 300));
                            ElX.tap('-to-date', Utils.debounce(function(old, current, event) {
                                if (current >= ElX.vars['-from-date']) {
                                    search();
                                } else {
                                    flashTpl([{"type": "error", "message": "Invalid date range: " + new Date(ElX.vars['-from-date']).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + " - " + new Date(ElX.vars['-to-date']).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}]);
                                    if (event) { event.target.value = old; }
                                    ElX.vars['-to-date'] = old;
                                }
                            }, 300));
                        });
                    </script>
                    <div class="column col-12 p-2"></div>
                    <div class="column col-12 p-2">
                        <h6><b>Legend:</b></h6>
                        <span class="chip">
                            <span class="avatar avatar-sm" style="background: red;"></span> Cancelled
                        </span>
                        <span class="chip">
                            <span class="avatar avatar-sm" style="background: blue;"></span> Approved
                        </span>
                        <span class="chip">
                            <span class="avatar avatar-sm" style="background: orange;"></span> Pending
                        </span>
                        <span class="chip">
                            <span class="avatar avatar-sm" style="background: green;"></span> Completed
                        </span>
                    </div>
                </div>
            </div>

            <div class="divider text-center"></div>

            <div class="container columns col-gapless">
                <div class="column col-12 p-2">
                    <?php if ($user['role'] == "patient"): ?>

                    <form method="POST" action="<?= $app->urlRoute('appointment/cancel?redirect=:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
                    <input type="hidden" name="message[sender_id]" value="<?= $app->htmlEncode($user['id']) ?>"/>
                    <div id="search-table" ></div>
                    <!-- appointment cancel modal -->
                    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--appointment-cancel>
                        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--appointment-cancel-close="x-on-click"></a>
                        <div class="modal-container">
                            <div class="modal-header">
                                <a class="btn btn-clear float-right" aria-label="Close" x-ref--appointment-cancel-close x-on-click x-tab x-rot--appointment-cancel="" x-focus="-appointment-cancel-open"></a>
                                <div class="modal-title h5">Cancel Appointment</div>
                            </div>
                            <div class="modal-body">
                                <div class="content">
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <textarea class="form-input" name="message[body]" class="message-box" rows="4" required x-ref--appointment-cancel-tab-first></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-error">Cancel</button>
                                <button class="btn" type="button" x-ref--appointment-cancel-tab-last x-on-click x-run--appointment-cancel-close="x-on-click">Close</button>
                            </div>
                        </div>
                    </div>
                    </form>
                    <?php else: ?>
                    <div id="search-table" ></div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </main>

    <?php if ($user['role'] != "patient"): ?>
    <!-- appointment edit status modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--appointment-edit>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--appointment-edit-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= $app->urlRoute('appointment/update/:redirect', array(':redirect' => ltrim($currentRoute, '/'))) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>"/>
            <input type="hidden" name="appointment[id]" value="" required x-ref-appointment_id />
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--appointment-edit-close x-on-click x-tab x-rot--appointment-edit="" x-focus="-appointment-edit-open"></a>
                <div class="modal-title h5">Edit Status</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <p><strong>Appointment Id: </strong><span x-ref-appointment_id></span></p>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="appointment[status]" x-ref-appointment_status x-ref--appointment-edit-tab-first>
                            <?php foreach ($appointment_status as $status) : ?>
                                
                            <option value="<?= $app->htmlEncode($status) ?>"><?= $app->htmlEncode(ucwords($status)) ?></option>
                            <?php endforeach; ?>
        
        
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message (optional)</label>
                        <textarea class="form-input" name="message[body]" class="message-box" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <button class="btn" type="button" x-ref--appointment-edit-tab-last x-on-click x-run--appointment-edit-close="x-on-click">Close</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- appointment view modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--appointment-view>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--appointment-view-close="x-on-click"></a>
        <div class="modal-container">
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--appointment-view-close x-on-click x-tab x-rot--appointment-view="" x-focus="-appointment-view-open"></a>
                <div class="modal-title h5">View</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <p><strong>Appointment Id</strong><br><span x-ref-appointment_id></span></p>
                    <p><strong>Schedule Date</strong><br><span x-ref-appointment_date></span></p>
                    <p><strong>Appointment Time</strong><br><span x-ref-appointment_time></span></p>
                    <p><strong>Patient Name</strong><br><span x-ref-appointment_user_name></span></p>
                    <p><strong>Patient Email</strong><br><span x-ref-appointment_email></span></p>
                    <p><strong>Service Name</strong><br><span x-ref-appointment_service_name></span></p>
                    <p><strong>Duration</strong><br><span x-ref-appointment_service_duration></span></p>
                    <p><strong>Status</strong><br><span x-ref-appointment_status></span></p>
                    <p><strong>Rate</strong><br><span x-ref-appointment_service_price></span></p>
                    <p><strong>Doctor</strong><br><span x-ref-appointment_doctor_name></span></p>
                    <p><strong>Doctor Email</strong><br><span x-ref-appointment_doctor_email></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" type="button" x-ref--appointment-view-tab-last x-on-click x-run--appointment-view-close="x-on-click">Close</button>
            </div>
        </div>
    </div>

    <script>
        (window.init = window.init || []).push(function () {
            var url = "<?= $app->urlRoute('appointment/search-table.ajax') ?>";
            var searchTable = new El('search-table');
            var request = new Request(new XMLHttpRequest());
            request.setCache(true).setCacheSize(2).setCacheTTL(3);
            
            // Function to show and hide the content with transitions
            function showContentWithTransition(content) {
                searchTable.el.classList.remove('visible'); // Fade out
                searchTable.html(content);
                setTimeout(function() {
                    searchTable.el.classList.add('visible'); // Fade in after content is set
                }, 300); // Wait for a short moment to apply the transition
            }
    
            // Main search function that updates the page dynamically
            window.search = function() {
                // Fetch search input and status filter
                var searchValue = document.getElementById("search").value;
                var statusValue = document.getElementById("status").value;
                var fromDateValue = document.getElementById("fromDate").value;
                var toDateValue = document.getElementById("toDate").value;
    
                url = new Url(url).setQuery('search', searchValue).setQuery('status', statusValue).setQuery('from-date', fromDateValue).setQuery('to-date', toDateValue).toString();
                new Url().setQuery('search', searchValue).setQuery('status', statusValue).setQuery('from-date', fromDateValue).setQuery('to-date', toDateValue).sync(true);
    
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
    
    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>

</body>

</html>