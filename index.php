<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$bannerCfg = $config['banner'];
$contactCfg = $config['contact'];
$social = $config['social'];

$banner = [
    'title' => localized($bannerCfg, 'title'),
    'title_highlight' => localized($bannerCfg, 'title_highlight'),
    'subtitle' => localized($bannerCfg, 'subtitle'),
    'feature_1' => localized($bannerCfg, 'feature_1'),
    'feature_2' => localized($bannerCfg, 'feature_2'),
    'feature_3' => localized($bannerCfg, 'feature_3'),
    'cta_text' => localized($bannerCfg, 'cta_text'),
    'image' => $bannerCfg['image'] ?? '',
];

$contact = [
    'phone' => $contactCfg['phone'] ?? '',
    'whatsapp' => $contactCfg['whatsapp'] ?? '',
    'email' => $contactCfg['email'] ?? '',
    'address' => localized($contactCfg, 'address'),
    'cta_banner_text' => localized($contactCfg, 'cta_banner_text'),
    'cta_image' => $contactCfg['cta_image'] ?? '',
];

$heroImage = public_upload_url($banner['image'] ?? '');
$ctaImage = cta_image_url($contact['cta_image'] ?? '');
$waUrl = whatsapp_link($contact['whatsapp'], __('wa_prefill'));
$phoneUrl = phone_tel($contact['phone']);
$lang = lang_attr();
$dir = lang_dir();

$cities = [
    'city_riyadh', 'city_jeddah', 'city_makkah', 'city_madinah', 'city_dammam', 'city_khobar', 'city_dhahran',
    'city_taif', 'city_tabuk', 'city_abha', 'city_khamis', 'city_hail', 'city_buraidah', 'city_jubail',
    'city_yanbu', 'city_najran', 'city_jazan',
];

$vehicleIcons = [
    'v_sedan' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 14h16v3.2c0 .4-.3.8-.8.8H4.8c-.5 0-.8-.4-.8-.8V14Z" stroke="currentColor" stroke-width="1.6"/><path d="m5 14 1.7-5.2A1.5 1.5 0 0 1 8.1 7.8h7.8c.6 0 1.2.4 1.4 1L19 14" stroke="currentColor" stroke-width="1.6"/><circle cx="7.2" cy="16.2" r="1.1" fill="currentColor"/><circle cx="16.8" cy="16.2" r="1.1" fill="currentColor"/></svg>',
    'v_suv' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 13.5h16v3.5c0 .4-.3.8-.8.8H4.8c-.5 0-.8-.4-.8-.8v-3.5Z" stroke="currentColor" stroke-width="1.6"/><path d="M5 13.5 6.5 8.2A1.5 1.5 0 0 1 7.9 7h8.2c.6 0 1.2.4 1.4 1l1.5 5.5" stroke="currentColor" stroke-width="1.6"/><path d="M8 7.2V5.8M16 7.2V5.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="7.2" cy="16" r="1.1" fill="currentColor"/><circle cx="16.8" cy="16" r="1.1" fill="currentColor"/></svg>',
    'v_luxury' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 4.5 17.2 9.8 12 19.5 6.8 9.8 12 4.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M6.8 9.8h10.4" stroke="currentColor" stroke-width="1.6"/></svg>',
    'v_sport' => '<svg viewBox="0 0 24 24" fill="none"><path d="M3.5 14.2h17v2.8c0 .5-.4.9-.9.9H4.4c-.5 0-.9-.4-.9-.9v-2.8Z" stroke="currentColor" stroke-width="1.6"/><path d="M4.2 14.2 6 9.5c.2-.5.7-.8 1.2-.8h9.6c.5 0 1 .3 1.2.8l1.8 4.7" stroke="currentColor" stroke-width="1.6"/><path d="M8 10.5h8" stroke="currentColor" stroke-width="1.4"/><circle cx="7" cy="16.2" r="1.1" fill="currentColor"/><circle cx="17" cy="16.2" r="1.1" fill="currentColor"/></svg>',
    'v_classic' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 14.5h16v3c0 .4-.3.8-.8.8H4.8c-.5 0-.8-.4-.8-.8v-3Z" stroke="currentColor" stroke-width="1.6"/><path d="M5.2 14.5 7 10.2c.2-.5.7-.9 1.3-.9h7.4c.6 0 1.1.4 1.3.9l1.8 4.3" stroke="currentColor" stroke-width="1.6"/><path d="M9 9.3V7.8h6v1.5" stroke="currentColor" stroke-width="1.5"/><circle cx="7.2" cy="16.4" r="1.1" fill="currentColor"/><circle cx="16.8" cy="16.4" r="1.1" fill="currentColor"/></svg>',
    'v_other' => '<svg viewBox="0 0 24 24" fill="none"><rect x="5" y="6.5" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M9 12h6M12 9v6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
];

$methodIcons = [
    'm_carrier' => '<svg viewBox="0 0 24 24" fill="none"><path d="M3.5 15h12.5v3.2c0 .4-.3.8-.8.8H4.3c-.4 0-.8-.4-.8-.8V15Z" stroke="currentColor" stroke-width="1.6"/><path d="M16 12.5h3.2L21 15.2V18c0 .4-.3.8-.8.8H16v-6.3Z" stroke="currentColor" stroke-width="1.6"/><circle cx="7" cy="18.2" r="1.15" fill="currentColor"/><circle cx="18" cy="18.2" r="1.15" fill="currentColor"/><path d="M5 12.5h8.5V15H5v-2.5Z" stroke="currentColor" stroke-width="1.5"/></svg>',
    'm_closed' => '<svg viewBox="0 0 24 24" fill="none"><rect x="5.5" y="10" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8.5 10V8.2a3.5 3.5 0 0 1 7 0V10" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="14.5" r="1.2" fill="currentColor"/></svg>',
    'm_open' => '<svg viewBox="0 0 24 24" fill="none"><path d="M4 15.5h16v2.8c0 .4-.3.7-.7.7H4.7c-.4 0-.7-.3-.7-.7v-2.8Z" stroke="currentColor" stroke-width="1.6"/><path d="M5 15.5 6.8 10.8c.2-.5.7-.8 1.2-.8h8c.5 0 1 .3 1.2.8L19 15.5" stroke="currentColor" stroke-width="1.6"/><circle cx="7.5" cy="17.5" r="1.1" fill="currentColor"/><circle cx="16.5" cy="17.5" r="1.1" fill="currentColor"/></svg>',
    'm_vip' => '<svg viewBox="0 0 24 24" fill="none"><path d="M5 16.5h14l-1.2-7.2L14.5 12 12 7.5 9.5 12 6.2 9.3 5 16.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M6.5 18h11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
    'm_express' => '<svg viewBox="0 0 24 24" fill="none"><path d="M13 4.5 7.5 13h4.2L11 19.5 16.5 11h-4.2L13 4.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
];

$ctaParts = preg_split('/\s*[—\-–]\s*/u', (string) $contact['cta_banner_text'], 2);
$ctaTitle = trim($ctaParts[0] ?? '');
$ctaSub = trim($ctaParts[1] ?? '');
$footerCopy = str_replace(':year', (string) date('Y'), __('footer_copy'));
$gallery = load_gallery();
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NXBBZZ45');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(__('site_title')) ?></title>
    <meta name="description" content="<?= e(__('site_desc')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/logo-transparent.png" type="image/png">
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXBBZZ45"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="#home">
            <img src="assets/img/logo-transparent.png" alt="ROSE VIP">
            <div class="brand-text">
                <strong>ROSE VIP</strong>
                <span><?= e(__('brand_tagline')) ?></span>
            </div>
        </a>

        <button class="menu-toggle" type="button" aria-label="<?= e(__('menu')) ?>">☰</button>

        <nav class="nav" id="main-nav">
            <a href="#home"><?= e(__('nav_home')) ?></a>
            <a href="#services"><?= e(__('nav_services')) ?></a>
            <a href="#works"><?= e(__('nav_works')) ?></a>
            <a href="#about"><?= e(__('nav_about')) ?></a>
            <a href="#contact"><?= e(__('nav_contact')) ?></a>
            <a class="btn btn-primary" href="#quote"><?= e(__('nav_order')) ?></a>
        </nav>

        <div class="header-actions">
            <div class="lang-switch">
                <a class="<?= $lang === 'ar' ? 'active' : '' ?>" href="<?= e(switch_lang_url('ar')) ?>">عربي</a>
                <a class="<?= $lang === 'en' ? 'active' : '' ?>" href="<?= e(switch_lang_url('en')) ?>">EN</a>
            </div>
            <a class="btn btn-primary header-cta" href="#quote"><?= e(__('nav_order')) ?></a>
        </div>
    </div>
</header>

<main>
    <section class="hero" id="home">
        <div class="hero-bg" style="background-image:url('<?= e($heroImage) ?>')"></div>
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="hero-copy">
                <h1>
                    <span class="title-main"><?= e($banner['title']) ?></span>
                    <?php if ($banner['title_highlight'] !== ''): ?>
                        <span class="title-accent"><?= e($banner['title_highlight']) ?></span>
                    <?php endif; ?>
                </h1>
                <p class="hero-sub"><?= e($banner['subtitle']) ?></p>

                <div class="hero-features">
                    <div class="hero-feature">
                        <div class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 21s-7-4.35-7-10a4 4 0 0 1 7-2.65A4 4 0 0 1 19 11c0 5.65-7 10-7 10Z" stroke="currentColor" stroke-width="1.8"/><path d="M9.5 11.5h5M12 9v5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        </div>
                        <p><?= e($banner['feature_1']) ?></p>
                    </div>
                    <div class="hero-feature">
                        <div class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.8"/><path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p><?= e($banner['feature_2']) ?></p>
                    </div>
                    <div class="hero-feature">
                        <div class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 3.5 19 6.5v5.2c0 4.4-2.9 7.6-7 8.8-4.1-1.2-7-4.4-7-8.8V6.5L12 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m9.2 12 1.9 1.9 3.7-3.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p><?= e($banner['feature_3']) ?></p>
                    </div>
                </div>

                <a class="btn btn-primary hero-cta" href="<?= e($waUrl) ?>" target="_blank" rel="noopener">
                    <svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2C6.55 2 2.1 6.45 2.1 11.94c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.49 0 9.94-4.45 9.94-9.94 0-2.65-1.03-5.14-2.94-7zM12.04 20.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.35c0-4.54 3.7-8.23 8.24-8.23a8.2 8.2 0 0 1 5.82 2.41 8.17 8.17 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23m4.52-6.16c-.25-.12-1.47-.72-1.7-.8-.23-.09-.39-.12-.56.12-.16.25-.64.8-.78.96-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.42h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74 2.49 1.08 2.49.72 2.94.67.45-.04 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.17-.48-.29"/></svg>
                    <?= e($banner['cta_text']) ?>
                </a>
            </div>
        </div>
    </section>

    <section class="section services" id="services">
        <div class="container">
            <div class="section-head">
                <p class="section-eyebrow"><?= e(__('services_eyebrow')) ?></p>
                <h2><?= e(__('services_title')) ?></h2>
            </div>
            <div class="services-grid">
                <article class="service-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M8 30h32v5.5a2.5 2.5 0 0 1-2.5 2.5H10.5A2.5 2.5 0 0 1 8 35.5V30Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M10 30 13.5 18.5A3 3 0 0 1 16.4 16.5h15.2a3 3 0 0 1 2.9 2L38 30" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M14 22.5h20M16.5 18.5v-2.2A1.8 1.8 0 0 1 18.3 14.5h4.4a1.8 1.8 0 0 1 1.8 1.8v2.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                            <circle cx="15" cy="35.5" r="2.5" fill="currentColor"/>
                            <circle cx="33" cy="35.5" r="2.5" fill="currentColor"/>
                        </svg>
                    </div>
                    <h3><?= e(__('service_1_title')) ?></h3>
                    <p><?= e(__('service_1_desc')) ?></p>
                </article>
                <article class="service-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M24 7 35.5 18.2 24 41 12.5 18.2 24 7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M12.5 18.2h23" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            <path d="m18.5 18.2 5.5-11.2 5.5 11.2M18.5 18.2 24 41M29.5 18.2 24 41" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('service_2_title')) ?></h3>
                    <p><?= e(__('service_2_desc')) ?></p>
                </article>
                <article class="service-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M9 41V15.8L24 7l15 8.8V41" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M9 41h30" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            <path d="M17.5 41V27.5h13V41" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M16 19.5h5M27 19.5h5M16 25h5M27 25h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('service_3_title')) ?></h3>
                    <p><?= e(__('service_3_desc')) ?></p>
                </article>
                <article class="service-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M24 7 38 13v10.8c0 8.8-5.8 15.2-14 17.4C15.8 39 10 32.6 10 23.8V13L24 7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="m17.8 24.2 4.3 4.3 8.2-8.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('service_4_title')) ?></h3>
                    <p><?= e(__('service_4_desc')) ?></p>
                </article>
            </div>
        </div>
    </section>

    <section class="section works" id="works">
        <div class="container">
            <div class="section-head">
                <p class="section-eyebrow"><?= e(__('works_eyebrow')) ?></p>
                <h2><?= e(__('works_title')) ?></h2>
            </div>

            <?php if (!$gallery): ?>
                <p class="works-empty"><?= e(__('works_empty')) ?></p>
            <?php else: ?>
                <div class="works-grid">
                    <?php foreach ($gallery as $item): ?>
                        <?php
                        $caption = current_lang() === 'en'
                            ? (string) ($item['caption_en'] ?? '')
                            : (string) ($item['caption_ar'] ?? '');
                        if ($caption === '' && current_lang() === 'en') {
                            $caption = (string) ($item['caption_ar'] ?? '');
                        }
                        $imgUrl = public_upload_url($item['image'] ?? '');
                        ?>
                        <figure class="works-item">
                            <button type="button" class="works-thumb" data-full="<?= e($imgUrl) ?>" data-caption="<?= e($caption) ?>">
                                <img src="<?= e($imgUrl) ?>" alt="<?= e($caption !== '' ? $caption : __('works_eyebrow')) ?>" loading="lazy">
                            </button>
                            <?php if ($caption !== ''): ?>
                                <figcaption><?= e($caption) ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="lightbox" id="works-lightbox" hidden>
        <button type="button" class="lightbox-close" aria-label="Close">&times;</button>
        <img src="" alt="">
        <p class="lightbox-caption"></p>
    </div>

    <section class="section quote" id="quote">
        <div class="container">
            <div class="quote-head">
                <p class="quote-eyebrow"><?= e(__('quote_eyebrow')) ?></p>
                <h2><?= e(__('quote_title')) ?></h2>
                <span class="quote-divider" aria-hidden="true"></span>
            </div>

            <div class="quote-card">
                <div id="quote-alert" class="alert" hidden></div>

                <form id="quote-form" method="post" action="api/submit-quote.php"
                      data-sending="<?= e(__('sending')) ?>"
                      data-request="<?= e(__('request_quote')) ?>"
                      data-error="<?= e(__('connection_error')) ?>">
                    <div class="form-grid">
                        <div class="field">
                            <label for="vehicle_type"><?= e(__('vehicle_type')) ?></label>
                            <select id="vehicle_type" name="vehicle_type" required>
                                <option value=""><?= e(__('choose_vehicle')) ?></option>
                                <?php $i = 0; foreach ($vehicleIcons as $key => $icon): ?>
                                    <option value="<?= e(__($key)) ?>" <?= $i === 0 ? 'selected' : '' ?>><?= e(__($key)) ?></option>
                                <?php $i++; endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="transport_method"><?= e(__('transport_method')) ?></label>
                            <select id="transport_method" name="transport_method" required>
                                <option value=""><?= e(__('choose_method')) ?></option>
                                <?php $i = 0; foreach ($methodIcons as $key => $icon): ?>
                                    <option value="<?= e(__($key)) ?>" <?= $i === 0 ? 'selected' : '' ?>><?= e(__($key)) ?></option>
                                <?php $i++; endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="from_city"><?= e(__('from_city')) ?></label>
                            <select id="from_city" name="from_city" required>
                                <option value=""><?= e(__('choose_city')) ?></option>
                                <?php foreach ($cities as $cityKey): ?>
                                    <option value="<?= e(__($cityKey)) ?>"><?= e(__($cityKey)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="phone"><?= e(__('phone')) ?></label>
                            <input type="tel" id="phone" name="phone" placeholder="05XXXXXXX" required pattern="05[0-9]{8}">
                        </div>
                        <div class="field field-date">
                            <label for="transport_date"><?= e(__('transport_date')) ?></label>
                            <input type="date" id="transport_date" name="transport_date" required>
                        </div>
                        <div class="field">
                            <label for="to_city"><?= e(__('to_city')) ?></label>
                            <select id="to_city" name="to_city" required>
                                <option value=""><?= e(__('choose_city')) ?></option>
                                <?php foreach ($cities as $cityKey): ?>
                                    <option value="<?= e(__($cityKey)) ?>"><?= e(__($cityKey)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="chips-layout">
                        <div class="choice-block">
                            <h3><?= e(__('vehicle_type')) ?></h3>
                            <div class="choice-grid vehicles">
                                <?php $i = 0; foreach ($vehicleIcons as $key => $icon): ?>
                                    <button type="button" class="choice-chip<?= $i === 0 ? ' active' : '' ?>" data-target="vehicle_type" data-value="<?= e(__($key)) ?>">
                                        <span class="chip-icon"><?= $icon ?></span>
                                        <span class="chip-label"><?= e(__($key)) ?></span>
                                    </button>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>

                        <div class="choice-block">
                            <h3><?= e(__('transport_method')) ?></h3>
                            <div class="choice-grid methods">
                                <?php $i = 0; foreach ($methodIcons as $key => $icon): ?>
                                    <button type="button" class="choice-chip<?= $i === 0 ? ' active' : '' ?>" data-target="transport_method" data-value="<?= e(__($key)) ?>">
                                        <span class="chip-icon"><?= $icon ?></span>
                                        <span class="chip-label"><?= e(__($key)) ?></span>
                                    </button>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary quote-submit" type="submit">
                        <?= e(__('request_quote')) ?>
                        <span class="submit-arrow" aria-hidden="true"><?= $dir === 'rtl' ? '←' : '→' ?></span>
                    </button>
                    <p class="form-note">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="6" y="10" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 10V8.3a3.5 3.5 0 0 1 7 0V10" stroke="currentColor" stroke-width="1.7"/></svg>
                        <?= e(__('privacy_note')) ?>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <section class="section why" id="about">
        <div class="container">
            <div class="section-head why-head">
                <h2><?= e(__('why_title_before')) ?> <span class="brand-pink">Rose VIP</span> <?= e(__('why_title_after')) ?></h2>
            </div>
            <div class="why-grid">
                <article class="why-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M24 7 38 13v10.8c0 8.8-5.8 15.2-14 17.4C15.8 39 10 32.6 10 23.8V13L24 7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="m17.8 24.2 4.3 4.3 8.2-8.2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('why_1_title')) ?></h3>
                    <p><?= e(__('why_1_desc')) ?></p>
                </article>
                <article class="why-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M24 40s-11-9.2-11-18a11 11 0 1 1 22 0c0 8.8-11 18-11 18Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <circle cx="24" cy="22" r="4" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <h3><?= e(__('why_2_title')) ?></h3>
                    <p><?= e(__('why_2_desc')) ?></p>
                </article>
                <article class="why-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <rect x="12" y="8" width="24" height="32" rx="3" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M18 16h12M18 22h12M18 28h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="m18 34 2.5 2.5L25 32" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('why_3_title')) ?></h3>
                    <p><?= e(__('why_3_desc')) ?></p>
                </article>
                <article class="why-card">
                    <div class="icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path d="M16 28v-5a8 8 0 1 1 16 0v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M14 28h4v8h-2a2 2 0 0 1-2-2v-6Zm20 0h-4v8h2a2 2 0 0 0 2-2v-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M24 36v3a4 4 0 0 0 4 4h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3><?= e(__('why_4_title')) ?></h3>
                    <p><?= e(__('why_4_desc')) ?></p>
                </article>
            </div>
        </div>
    </section>

    <section class="cta-banner" id="contact">
        <div class="cta-banner-bg" style="background-image:url('<?= e($ctaImage) ?>')"></div>
        <div class="cta-banner-overlay"></div>
        <div class="container cta-banner-inner">
            <h2><?= e($ctaTitle) ?></h2>
            <?php if ($ctaSub !== ''): ?>
                <p class="cta-sub"><?= e($ctaSub) ?></p>
            <?php endif; ?>
            <div class="cta-actions">
                <a class="btn btn-primary cta-wa" href="<?= e($waUrl) ?>" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2C6.55 2 2.1 6.45 2.1 11.94c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.49 0 9.94-4.45 9.94-9.94 0-2.65-1.03-5.14-2.94-7zM12.04 20.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.35c0-4.54 3.7-8.23 8.24-8.23a8.2 8.2 0 0 1 5.82 2.41 8.17 8.17 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.23 8.23m4.52-6.16c-.25-.12-1.47-.72-1.7-.8-.23-.09-.39-.12-.56.12-.16.25-.64.8-.78.96-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.42h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74 2.49 1.08 2.49.72 2.94.67.45-.04 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.17-.48-.29"/></svg>
                    <?= e(__('cta_whatsapp')) ?>
                </a>
                <a class="btn btn-outline cta-call" href="<?= e($phoneUrl) ?>">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8.2 4.8c.4-.4 1-.5 1.5-.3l2.1.9c.5.2.8.7.8 1.2v2.1c0 .4-.2.8-.6 1-.8.5-1.2 1.3-1.1 2.2.3 2.3 2.1 4.1 4.4 4.4.9.1 1.7-.3 2.2-1.1.2-.4.6-.6 1-.6h2.1c.5 0 1 .3 1.2.8l.9 2.1c.2.5.1 1.1-.3 1.5l-1.2 1.2c-.4.4-1 .6-1.6.5C12.4 20.3 3.7 11.6 4.3 4.4c-.1-.6.1-1.2.5-1.6L8.2 4.8Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    <?= e(__('cta_call')) ?>
                </a>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a class="footer-logo" href="#home">
                <img src="assets/img/logo-transparent.png" alt="ROSE VIP">
                <div class="footer-logo-text">
                    <strong><span class="rose">ROSE</span> <span class="vip">VIP</span></strong>
                    <span><?= e(__('brand_tagline')) ?></span>
                </div>
            </a>
            <p><?= e(__('footer_about')) ?></p>
            <div class="socials">
                <a href="<?= e($waUrl) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2C6.55 2 2.1 6.45 2.1 11.94c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.49 0 9.94-4.45 9.94-9.94 0-2.65-1.03-5.14-2.94-7z"/></svg>
                </a>
                <?php if (!empty($social['instagram'])): ?>
                    <a href="<?= e($social['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="4.5" y="4.5" width="15" height="15" rx="4" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="1.7"/><circle cx="16.7" cy="7.4" r="1" fill="currentColor"/></svg>
                    </a>
                <?php endif; ?>
                <?php if (!empty($social['x'])): ?>
                    <a href="<?= e($social['x']) ?>" target="_blank" rel="noopener" aria-label="X">
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M6 5.5h3.1l3.2 4.5L16.2 5.5H18l-4.5 5.8L18.5 18.5h-3.1l-3.5-4.9-4 4.9H6l4.8-6.1L6 5.5Z"/></svg>
                    </a>
                <?php endif; ?>
                <?php if (!empty($social['snapchat'])): ?>
                    <a href="<?= e($social['snapchat']) ?>" target="_blank" rel="noopener" aria-label="Snapchat">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 4.5c2.8 0 5 2 5 5.2 0 1.7-.4 2.7.5 3.6.4.4.8.7.4 1.2-.3.4-1.2.2-1.7.5-.7.4-.5 1.3-1.4 1.7-.7.3-1.4-.1-2-.1s-1.3.4-2 .1c-.9-.4-.7-1.3-1.4-1.7-.5-.3-1.4-.1-1.7-.5-.4-.5 0-.8.4-1.2.9-.9.5-1.9.5-3.6 0-3.2 2.2-5.2 5-5.2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-col">
            <h4><?= e(__('footer_services')) ?></h4>
            <ul>
                <li><a href="#services"><?= e(__('service_1_title')) ?></a></li>
                <li><a href="#services"><?= e(__('service_2_title')) ?></a></li>
                <li><a href="#services"><?= e(__('service_3_title')) ?></a></li>
                <li><a href="#services"><?= e(__('footer_all_services')) ?></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4><?= e(__('footer_links')) ?></h4>
            <ul>
                <li><a href="#home"><?= e(__('nav_home')) ?></a></li>
                <li><a href="#services"><?= e(__('nav_services')) ?></a></li>
                <li><a href="#works"><?= e(__('nav_works')) ?></a></li>
                <li><a href="#about"><?= e(__('nav_about')) ?></a></li>
                <li><a href="#contact"><?= e(__('nav_contact')) ?></a></li>
            </ul>
        </div>

        <div class="footer-col footer-contact">
            <h4><?= e(__('footer_contact')) ?></h4>
            <ul>
                <li>
                    <a href="<?= e($phoneUrl) ?>">
                        <span><?= e($contact['phone']) ?></span>
                        <span class="contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M8.2 4.8c.4-.4 1-.5 1.5-.3l2.1.9c.5.2.8.7.8 1.2v2.1c0 .4-.2.8-.6 1-.8.5-1.2 1.3-1.1 2.2.3 2.3 2.1 4.1 4.4 4.4.9.1 1.7-.3 2.2-1.1.2-.4.6-.6 1-.6h2.1c.5 0 1 .3 1.2.8l.9 2.1c.2.5.1 1.1-.3 1.5l-1.2 1.2c-.4.4-1 .6-1.6.5C12.4 20.3 3.7 11.6 4.3 4.4c-.1-.6.1-1.2.5-1.6L8.2 4.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="mailto:<?= e($contact['email']) ?>">
                        <span><?= e($contact['email']) ?></span>
                        <span class="contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="m5.5 8 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                </li>
                <li>
                    <span class="contact-static">
                        <span><?= e($contact['address']) ?></span>
                        <span class="contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 20s-7-5.8-7-11a7 7 0 1 1 14 0c0 5.2-7 11-7 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="12" cy="9.2" r="2.3" stroke="currentColor" stroke-width="1.7"/></svg>
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container"><?= e($footerCopy) ?></div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
