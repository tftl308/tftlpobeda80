<!doctype html>
<html <?php language_attributes(); ?> style="margin-top: 0 !important;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/content.css">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.jpg" type="image/x-icon">
</head>
<body>

    <?php wp_body_open(); ?>

    <header>
        <div class="wrapper header-wrapper">
            <a class="header-menu-btn" href="<?php echo home_url(); ?>">
                <i class="far fa-arrow-left"></i>
            </a>
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
    </header>
    <section class="main-section">
        <div class="wrapper main-wrapper project-main-wrapper">