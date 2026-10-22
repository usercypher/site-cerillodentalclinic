<?php

$noLoginRoutes = array(
    '' => true,
    'home' => true,
);

?>

    <span
        x-ref-window
        x-on-key-window="escape"        
    ></span>

<?php if (!isset($noLoginRoutes[trim($currentRoute, '/')])) : ?>
<?php 

$h1_option = array(
    'doctor' => 'Doctor Panel',
    'admin' => 'Admin Panel',
    'staff' => 'Staff Panel',
    'patient' => 'Patient Panel',
);

$role = isset($user) ? $user['role'] : 'guest';

$h1 = isset($h1_option[$role]) ? $h1_option[$role] : 'Guest Panel'; 

?>
    <header class="navbar" style="padding-top: 1.5em; padding-right: 1.5em;">
        <section class="navbar-section">
            <button class="off-canvas-toggle btn btn-primary btn-action" style="margin: 0.5em">
                <i class="icon icon-menu" x-on-click x-rot--sidebar="_ active"></i>
            </button>
        </section>
        <section class="navbar-section">
            <i class="navbar-brand mr-2"><?= $h1 ?></i>
        </section>
    </header>

<?php endif; ?>