<?php 

$currentRoute = $data['current_route'];

$app = $data['app'];
$output = $data['output'];

$flash = isset($data['flash']) ? $data['flash'] : array();
$csrfToken = $data['csrf_token'];
$redirect = $data['redirect'];
$services = $data['services'];
$users = $data['users'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Cerillo Dental Clinic | Davao Dentist - Book Online</title>

    <meta name="description" content="Need a dentist in Davao City? Book an appointment online at Cerillo Dental Clinic for braces, teeth cleaning, extractions & more. Trusted, fast & easy booking.">
    <meta name="author" content="Cerillo Dental Clinic">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->

    <meta property="og:url" content="https://cerillodentalclinic.dedyn.io/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Book a Dental Appointment in Davao | Cerillo Dental Clinic" />
    <meta property="og:description" content="Skip the wait. Book your dental appointment online at Cerillo Dental Clinic — serving Davao City with expert care in braces, cleaning, extractions & more." />
    <meta property="og:site_name" content="Cerillo Dental Clinic" />
    <meta property="og:locale" content="en_PH" />
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://cerillodentalclinic.dedyn.io/" />
    
    <!-- Favicon -->
    <link rel="icon" href="<?= $app->urlWeb('asset/img/favicon.ico') ?>" type="image/x-icon">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-exp.min.css') ?>">
    <link rel="stylesheet" href="<?= $app->urlWeb('asset/css/spectre-icons.min.css') ?>">
    
    <!-- Scripts -->
    <script src="<?= $app->urlWeb('asset/js/uc.js') ?>" defer></script>
    
    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "name": "Cerillo Dental Clinic",
                "url": "https://cerillodentalclinic.dedyn.io/",
                "logo": "<?= $app->urlWeb('asset/img/favicon.ico') ?>",
                "sameAs": [
                    "https://www.facebook.com/61560474300456/"
                ]
            },
            {
                "@type": "WebSite",
                "name": "Cerillo Dental Clinic",
                "url": "https://cerillodentalclinic.dedyn.io/",
                "publisher": {
                    "@type": "Organization",
                    "name": "Cerillo Dental Clinic"
                }
            },
            {
                "@type": "Dentist",
                "name": "Cerillo Dental Clinic",
                "url": "https://cerillodentalclinic.dedyn.io/",
                "logo": "<?= $app->urlWeb('asset/img/favicon.ico') ?>",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Door #3 St. John Paul II College of Davao, Ecoland",
                    "addressLocality": "Davao City",
                    "addressRegion": "Davao del Sur",
                    "postalCode": "8000",
                    "addressCountry": "PH"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+63-929-483-6473",
                    "contactType": "Customer Service",
                    "areaServed": "PH",
                    "availableLanguage": "English"
                }
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "How do I book a dental appointment online?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "You can register and log in at cerillodentalclinic.dedyn.io, then choose your preferred date and time."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Where are you located?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "We are located at Door #3 St. John Paul II College of Davao, Ecoland, Davao City."
                        }
                    }
                ]
            }
        ]
    }
    </script>
</head>

<body>
    <?php require($app->dirRoot('res/app/view/include/header.html.php')); ?>
    <header class="navbar" style="padding: 1em;">
        <section class="navbar-section column col-md-12">
            <a class="navbar-brand mr-2">Cerillo Dental Clinic</a>
        </section>
        <section class="navbar-section column col-md-12">
            <a href="#overview" class="btn btn-link">Overview</a>
            <a href="#services" class="btn btn-link">Services</a>
            <a href="#about" class="btn btn-link">About Us</a>
        </section>
    </header>
    <div class="hero" style="
        background-image: url('<?= $app->urlWeb('asset/img/hero.jpg') ?>');
        background-size: cover;
        background-position: center;
        width: auto;
    ">
        <div class="hero-body columns col-gapless">
            <div class="column col-6 col-md-12">
                <form class="card" style="margin: auto; max-width: 300px;" method="POST" action="<?= $app->urlRoute('user/login?:query', array(
                        ':query' => 'redirect=' . urlencode($redirect),
                    )) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>">
                    <div class="card-header">
                        <div class="card-title h5">Login</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" name="user[email]" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input class="form-input" type="password" name="user[password]" required />
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <button class="btn btn-primary column col-12">Login</button>
                        </div>
                        <center class="form-group">
                            <a class="column col-12"
                                x-ref--forgot-password-open
                                x-on-click
                                x-prevent
                                x-rot--forgot-password="active"
                                x-focus="-forgot-password-tab-first"
                                x-tab="-forgot-password-tab-first:-forgot-password-tab-last"
                                x-set-window.x-on-key-window-escape="-forgot-password-close"
                                x-set-window.x-run--forgot-password-close="x-on-click"
                            >Forgot Password?</a>
                        </center>
                        <div class="form-group">
                            <button class="btn btn-success column col-12"
                                type="button"
                                x-ref--register-open
                                x-on-click
                                x-rot--register="active"
                                x-focus="-register-tab-first"
                                x-tab="-register-tab-first:-register-tab-last"
                                x-set-window.x-on-key-window-escape="-register-close"
                                x-set-window.x-run--register-close="x-on-click"
                            >Register</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="column col-6 col-md-12">
                <div style="padding: 2em 1em; max-width: 400px; color: white; margin: auto;">
                    <h1>Your Go-To Dental Clinic in Davao City</h1>
                    <p>At Cerillo Dental Clinic, we offer quality and affordable dental care in Davao City. Whether you need teeth cleaning, tooth extraction, braces, dentures, or cosmetic dentistry, our expert dentist is here to help. Book your dental appointment online and skip the long queues.</p>
                </div>
            </div>
        </div>
    </div>
    <div id="overview" class="columns col-gapless">
        <div class="column col-6 col-md-12">
            <div 
                style="position:relative;text-align:right;width:100%;height:0;padding-bottom:66.66666666666666%;">
                <div 
                    style="overflow:hidden;background:none!important;width:100%;height:100%;position:absolute;top:0;left:0;">
                    <iframe 
                        frameborder="0" 
                        scrolling="no"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?origin=mfe&pb=!1m3!2m1!1sCerillo+Dental+Clinic!6i14!3m1!1sen!5m1!1sen"
                        style="width:100%!important;height:100%!important;position:absolute;top:0;left:0;">
                    </iframe>
                </div>
            </div>
        </div>
        <div class="column col-6 col-md-12" style="padding: 2em 1em;">
            <h1>Overview</h1>
            <p>Are you looking for a dentist who delivers uncompromising dental quality combined with warmth, understanding and personal attention? That’s what you’ll find in Cerillo Dental Practice of Ms. Maria Lourdes Cerillo, DDS. Dr. Cerillo is a top-rated Davao City area dentist who specializes in restoring and enhancing the natural beauty of your smile.</p>
        </div>
    </div>
    <div id="services" class="bg-gray" style="padding: 2em 1em;">
        <h1 class="text-center">Services</h1>
        <div class="columns col-gapless">
            <?php foreach ($services as $service) : ?>
            <div class="column col-4 col-md-12" style="padding: 1em;">
                <div class="card" style="max-width: 240px; margin: auto;">
                    <div class="card-header">
                        <div class="card-title h5"><?= $app->htmlEncode($service['name']) ?></div>
                        <div class="card-subtitle text-gray">Duration: <?= $app->htmlEncode($service['duration']) ?> min</div>
                    </div>
                    <div class="card-body">
                        <p><?= $app->htmlEncode($service['description']) ?></p>
                    </div>
                    <div class="card-footer">
                        <p class="text-primary">Rate: <?= $app->htmlEncode($service['price']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="columns col-gapless" style="padding: 2em 1em; overflow: hidden;">
        <div id="about" class="column col-6 col-md-12" style="padding: 2em 1em;">
            <h1>About Us</h1>
            <p>Cerillo Dental Clinic’s online appointment system is designed to make your dental care more convenient and accessible. Patients can easily register, log in, and schedule appointments at their preferred date and time, no need to wait in line or make phone calls. Once logged in, you can also view your dental history, manage upcoming visits, and receive important notifications about your treatment. Our goal is to provide seamless, patient-centered service with just a few clicks.</p>
        </div>
        <div class="column col-6 col-md-12" style="padding: 2em 1em;">
            <h1>Contact</h1>
            <ul>
                <li>Door #3 St. John Paul II College of Davao, Ecoland, Davao City 8000</li>
                <li>327 2139</li>
                <li>0929 483 6473</li>
                <li><a href="mailto:MariaLourdesCerrillo@cerillodentalclinic.com">MariaLourdesCerrillo@cerillodentalclinic.com</a></li>
                <li><a href="mailto:dentalclinic010100@gmail.com">dentalclinic010100@gmail.com</a></li>
                <li>
                    <a href="https://www.facebook.com/61560474300456/" target="_blank" rel="noopener">
                        Visit our Facebook Page
                    </a>
                </li>
            </ul>
        </div>
        <div class="column col-6 col-md-12" style="padding: 2em 1em;">
            <h1>Doctors & Staff</h1>
            <ul>
                <?php foreach ($users as $user) : ?>
                <li><a href="<?= $app->urlRoute('profile/:id', array(':id' => $user['id'])) ?>"><?= $app->htmlEncode($user['lastname'] . ', ' . $user['firstname'] . ' ' . $user['middlename']) ?> (<?= $user['role'] ?>)</a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="bg-gray text-center" style="padding: 2em 1em;">
        <h1>Frequently Asked Questions</h1>
        <h3>How do I book a dental appointment online?</h3>
        <p>You can register and log in at cerillodentalclinic.dedyn.io, then choose your preferred date and time.</p>
        <h3>Where are you located?</h3>
        <p>We are located at Door #3 St. John Paul II College of Davao, Ecoland, Davao City.</p>
    </div>

    <!-- Register Modal -->
    <div style="position: fixed; z-index: 899;"class="modal _" x-ref--register>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--register-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= ($app->urlRoute('user/register')) ?>" x-ref--register-form>
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>">
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--register-close x-on-click x-tab x-rot--register="" x-focus="-register-open"></a>
                <div class="modal-title h5">Register</div>
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
                <button class="btn btn-primary">Register</button>
                <button class="btn" type="button" x-ref--register-tab-last x-on-click x-run--register-close="x-on-click">Close</button>
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
    
    <!-- Forgot Password Modal -->
    <div style="position: fixed; z-index: 899;"class="modal modal-sm _" x-ref--forgot-password>
        <a class="modal-overlay" aria-label="Close" tabindex="-1" x-on-click x-run--forgot-password-close="x-on-click"></a>
        <form class="modal-container" method="POST" action="<?= ($app->urlRoute('user/forgot-password')) ?>">
            <input type="hidden" name="csrf_token" value="<?= $app->htmlEncode($csrfToken) ?>">
            <div class="modal-header">
                <a class="btn btn-clear float-right" aria-label="Close" x-ref--forgot-password-close x-on-click x-tab x-rot--forgot-password="" x-focus="-forgot-password-open"></a>
                <div class="modal-title h5">Forgot Password</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input class="form-input" type="email" name="user[email]" required x-ref--forgot-password-tab-first />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">submit</button>
                <button class="btn" type="button" x-ref--forgot-password-tab-last x-on-click x-run--forgot-password-close="x-on-click">Close</button>
            </div>
        </form>
    </div>

    <?php require($app->dirRoot('res/app/view/include/footer.html.php')) ?>

</body>
</html>

