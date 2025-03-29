<!doctype html>
<html <?php language_attributes(); ?> style="margin-top: 0 !important;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <?php wp_head(); ?>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/introjs.min.css">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.jpg" type="image/x-icon">
    <script src="<?php echo get_template_directory_uri(); ?>/js/intro.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/list.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
</head>
<body>

    <?php wp_body_open(); ?>

    <header>
        <div class="wrapper header-wrapper">
            <div class="header-menu-btn">
                <i class="far fa-bars"></i>
                <i class="fal fa-times"></i>
            </div>
            <div class="header-project-info">Проект ТФТЛ</div>
            <div class="header-logo-link-wrapper">
                <a class="header-logo-link" href="<?php echo home_url(); ?>">
                    <img src="<?php bloginfo('template_directory'); ?>/img/logo-sm.svg" alt="" class="header-logo-desktop">
                    <img src="<?php bloginfo('template_directory'); ?>/img/logo-sm-mobile.svg" alt="" class="header-logo-mobile">
                </a>
            </div>
            <div class="header-title-wrapper">
                <div class="header-title-box">
                    <div class="header-title-big">Никто</div>
                    <div class="header-title-small">не забыт</div>
                </div>
                <div class="header-title-box">
                    <div class="header-title-big">Ничто</div>
                    <div class="header-title-small">не забыто</div>
                </div>
                <div class="header-title-line"></div>
            </div>
        </div>

        <div class="mobile-menu">
            <div class="mm-bg-box">
                <div class="mm-bg"></div>
            </div>

            <div class="mobile-menu-link" data-name="info" data-display="flex">О проекте</div>
            <div class="mobile-menu-link" data-name="profiles" data-display="flex">Связь поколений</div>
            <div class="mobile-menu-link" data-name="map" data-display="flex">Карта нашей победы</div>
            <div class="mobile-menu-link" data-name="projects" data-display="flex">Истории наших побед</div>
            <a href="/form" class="mobile-menu-link" data-name="form" data-display="flex" style="color: white; text-decoration: none">Форма</a>
            <div class="mobile-menu-link mml-instruction" data-name="instruction" data-display="flex">Инструктаж по сайту</div>
        </div>
    </header>
    <section class="main-section">
        <div class="wrapper main-wrapper">