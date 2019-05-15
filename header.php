<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if(is_search()): ?>
        <meta name="robots" content="noindex, nofollow" />
    <?php endif; ?>

    <title>
        <?php if(is_front_page() || is_home()){
            echo get_bloginfo('name');
        } else{
            echo wp_title('');
        }?>
    </title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" >

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-31419933-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-31419933-1');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N532FQ');</script>
    <!-- End Google Tag Manager -->

    <?php wp_head(); ?>

</head>


<body <?php body_class('init'); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N532FQ"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="loading"></div>
<span style="display: none;" class="wpinfo"></span>

<!-- Forked from a template on Tutorialzine: https://tutorialzine.com/2016/06/freebie-landing-page-template-with-flexbox -->
<header class="header">
    <h2><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/rowerem_po_mazowszu.png" alt="Rowerem po Mazowszu"></a></h2>

    <div id="toggle">
        <i class="fas fa-bars"></i>
    </div>
    <div id="popout">
        <?php
        wp_nav_menu( array(
                'theme_location' => 'main-nav',
            'menu_class' => 'nav-menu',
            'name' => 'Main Menu',
            'menu_id' => 'main-nav',
            'container' => 'nav' )
        );
        ?>
    </div>

<!--    --><?php //wp_nav_menu(array(
//        'name' => 'Main Menu',
//        'menu_id' => 'main-nav',
//        'container' => 'nav'
//    ));
//    ?>
</header>

