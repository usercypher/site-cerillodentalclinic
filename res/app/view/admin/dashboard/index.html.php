<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

// Assigning variables for better traceability
$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$notifications = $data['notifications'];
$user = $data['user'];
$latestMessageTime = empty($data['notifications']) ? '0000-00-00 00:00:00' : $data['notifications'][0]['sent_at'] ;

$apptByStatus = $data['appt_by_status'];
$dates = $data['dates'];
$apptPerDay = $data['appt_per_day'];
$services = $data['services'];
$serviceTotals = $data['service_totals'];
$filter = $data['filter'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="icon" href="<?= $app->urlWeb('asset/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-exp.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-icons.min.css') ?>">
    <script src="<?= $app->urlWeb('asset/js/uc.js') ?>" defer></script>
    <style>
        canvas {
            max-width: 100%;
            height: auto;
            display: block;
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
                            <a>Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="column col-12 p-2">
                    <!-- Placeholder for user action buttons -->
                    <a href="<?= $app->urlRoute('settings') ?>" class="btn">Settings</a>
                </div>
                <div class="column col-12 p-2">
                    <select style="max-width: 100px;"class="form-select float-right" x-on-input x-var-filter="this">
                        <option value="weekly" <?= $filter === 'weekly' ? 'selected' : ''?>>Weekly</option>
                        <option value="monthly" <?= $filter === 'monthly' ? 'selected' : ''?>>Monthly</option>
                        <option value="yearly" <?= $filter === 'yearly' ? 'selected' : ''?>>Yearly</option>
                    </select>
                    <script>
                        (window.init = window.init || []).push(function () {
                            ElX.tap("filter", function (old, current) {
                                window.location.href = new Url().setQuery("filter", current).toString();
                            });
                        });
                    </script>
                </div>
            </div>

            <div class="divider text-center"></div>

            <!-- Dashboard -->
            <div class="container columns col-gapless">
                <div class="column col-4 col-md-12 p-2">
                    <div class="card">
                        <div class="card-header">
                        <div class="card-title h3 text-primary"><?= count($notifications) ?></div>
                            <div class="card-subtitle text-gray">New Notification</div>
                        </div>
                        <div class="card-body">
                        </div>
                        <div class="card-footer bg-primary text-light">
                            <span>Notifications</span>
                        </div>
                    </div>
                </div>
                                <!-- Notifications -->
                <div class="column col-4 col-md-12 p-2">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Notifications</div>
                        </div>
                        <div class="panel-body" id="notification-container">
                            <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="tile p-1">
                                <div class="tile-icon">
                                    <figure class="avatar avatar-lg s-rounded" data-initial="<?= $app->htmlEncode(strtoupper(substr($notification['subject'], 0, 2))) ?>"></figure>
                                </div>
                                <div class="tile-content">
                                    <p class="tile-title text-bold"><a href="<?= $app->urlRoute('notification/:id', array(':id' => $app->htmlEncode($notification['id']))) ?>"><?= $app->htmlEncode($notification['subject']) ?></a></p>
                                    <p class="tile-subtitle" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?= $app->htmlEncode($notification['body']) ?></p>
                                </div>
                                <div class="tile-action text-gray">
                                    <?= date('M d', strtotime($notification['sent_at'])) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="panel-footer">
                            <!-- contents -->
                        </div>
                    </div>
                </div>
                <script>
                    (window.init = window.init || []).push(function () {
                        Utils.script(["<?= $app->urlWeb('asset/js/MessageNotificationList.js') ?>"], function () {
                            new MessageNotificationList().init("<?= $app->urlRoute('notification/:id') ?>", "<?= $app->urlRoute('message/notification/list') ?>", "<?= $latestMessageTime ?>");
                        });
                    });
                </script>
                <div class="column col-4 col-md-12 p-2">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="chartStatus" style="margin: auto;"></canvas>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="container columns col-gapless">
                
                <div class="column col-6 col-md-12 p-2">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="chartDaily" style="margin: auto;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="column col-6 col-md-12 p-2">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="chartServices" style="margin: auto;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
        (window.init = window.init || []).push(function () {
            Utils.script(["<?= $app->urlWeb('asset/js/chart.js') ?>"], function () {
                // ---- Data from PHP ----
                var filter = '<?= $filter ?>';
                var apptByStatus = <?= json_encode($apptByStatus) ?>;
                var dates = <?= json_encode($dates) ?>;
                var apptPerDay = <?= json_encode($apptPerDay) ?>;
                var services = <?= json_encode($services) ?>;
                var serviceTotals = <?= json_encode($serviceTotals) ?>;
                
                var colors = {
                    'approved': '#36A2EB', // blue
                    'cancelled': '#FF6384', // red
                    'pending': '#FF9F40', // orange
                    'completed': '#4BC0C0', // green
                };
                
                new Chart(document.getElementById('chartStatus'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(apptByStatus),
                        datasets: [{
                            data: Object.values(apptByStatus),
                            backgroundColor: Object.keys(apptByStatus).map(status => colors[status.toLowerCase()])
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Appointments by Status (' + filter + ')'
                            }
                        }
                    }
                });
                
                var chartData = [];
                var statuses = ['approved', 'cancelled', 'pending', 'completed'];
                statuses.forEach(status => {
                    var tempData = [];
                    dates.forEach(date => {
                        tempData.push(apptPerDay[date] && apptPerDay[date][status] ? apptPerDay[date][status] : 0);
                    });
                    chartData.push({
                        label: status.charAt(0).toUpperCase() + status.slice(1),
                        data: tempData,
                        fill: false,
                        borderColor: colors[status],
                        pointBackgroundColor: colors[status],
                        pointBorderColor: colors[status],
                        tension: 0.1
                    });
                });
                
                // ---- Appointments per Day ----
                new Chart(document.getElementById('chartDaily'), {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: chartData
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Appointments Per Day (' + filter + ')'
                            }
                        }
                    }
                });
                
                // ---- Top Services ----
                new Chart(document.getElementById('chartServices'), {
                    type: 'bar',
                    data: {
                        labels: services,
                        datasets: [{ label: 'Appointments', data: serviceTotals, backgroundColor: '#4BC0C0' }]
                    },
                    options: { plugins: { title: { display: true, text: 'Top Services (' + filter + ')' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
        });
    
    </script>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')); ?>
</body>
</html>
