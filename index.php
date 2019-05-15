<?php get_header(); ?>



<section class="hero">
<!--    <div class="background-image" style="background-image: url(--><?php //echo get_template_directory_uri(); ?><!--/img/main/droga11.jpg); transform: rotate(180deg);"></div>-->
<!--    <div class="background-image" style="background-image: url(--><?php //echo get_template_directory_uri(); ?><!--/img/main/droga-2.jpg);"></div>-->
<!--    <div class="background-image" style="background-image: url(--><?php //echo get_template_directory_uri(); ?><!--/img/main/P1160505-2.jpg);">);"></div>-->
    <div class="background-image" style="background-image: url('https://rowerempomazowszu.pl/team/wp-content/uploads/2019/04/droga-3.jpg');"></div>
    <div class="hero-content-area">
        <h1>Wycieczki rowerowe po Warszawie i okolicach. Tych bliższych i dalszych.</h1>
    </div>
</section>

<section class="search">
    <form class="search" method="get" action="/szukaj/">
        <input type="text" name="search" id="search" placeholder="Gdzie chesz jechać?">
        <button type="submit" class="btn">Szukaj</button>
    </form>
</section>



<section class="destinations warsaw">
    <h2 class="title"><a href="/locality/warszawa/">WARSZAWA</a></h2>

    <hr class="smooth">

    <ul class="grid">

        <?php

        $warszawa = new WP_Query(
            array(
                'post_type' => 'route',
                'posts_per_page' => '3',
//                'orederby' => 'date',
//                'order' => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'locality',
                        'field' => 'slug',
                        'terms' => 'warszawa',
                    ),
                ),
            )
        );

        MB_Relationships_API::each_connected(array(
            'id' => 'trips_to_routs',
            'to' => $warszawa->posts,
            'property' => 'trip',
        ));

        ?>

        <?php $i = 1;
        if ($warszawa->have_posts()) : ?>
            <?php while ($warszawa->have_posts()) : $warszawa->the_post(); ?>

                <?php $routeID = $post->ID; ?>

                <?php foreach ($post->trip as $post) : setup_postdata($post); ?>
                    <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                        <a href="<?php the_permalink(); ?>">

                            <div style="background-image: url(<?php the_post_thumbnail_url('large'); ?>);" class="scrollme animateme"
                                 data-when="span"
                                 data-from="0.5"
                                 data-to="0"
                                 data-opacity="0"
                                 data-scale="0.8"
                                 data-rotatez="0">
                                <p class="title"><?php the_title(); ?>
                                    | <span class="no-wrap"><?php echo  get_post_meta($routeID, 'dystans', true); ?> km</span></p>
                            </div>
                        </a>
                    </li>
                <?php endforeach;
                wp_reset_postdata();
                ?>

                <?php $i++; endwhile; ?>
        <?php else: ?>
            <p>Brak rezultatów</p>
        <?php endif; ?>

    </ul>
</section>

<section class="tag-cloud">
    <div style="text-align: center;">
        <div class="text-green scrollme animateme"
             data-when="enter"
             data-from="0.75"
             data-to="0"
             data-opacity="0"
             data-translatex="-1000"
             data-rotatez="0"
             style="margin: 0 auto; width: 60%;">
            <?php

            $args = array(
                'smallest'                  => 8,
                'largest'                   => 22,
                'unit'                      => 'pt',
                'number'                    => 45,
                'format'                    => 'flat',
                'separator'                 => "\n",
                'orderby'                   => 'name',
                'order'                     => 'ASC',
                'exclude'                   => array(520, 521), //Mazowsze i Warszawa
                'include'                   => null,
//        'topic_count_text_callback' => default_topic_count_text,
                'link'                      => 'view',
                'taxonomy'                  => 'locality',
                'echo'                      => true,
                'show_count'                  => 0,
                'child_of'                  => null, // see Note!
            );

            wp_tag_cloud( $args );

            ?>
        </div>
    </div>
</section>

<section class="destinations masovia">
    <h2 class="title"><a href="/locality/mazowsze/">MAZOWSZE</a></h2>

    <hr class="smooth">

    <ul class="grid">
        <?php

        $mazowsze = new WP_Query(
            array(
                'post_type' => 'route',
                'posts_per_page' => '3',
//                'orederby' => 'date',
//                'order' => 'ASC',
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'locality',
                        'field' => 'slug',
                        'terms' => 'mazowsze',
                    ),
                    array(
                        'taxonomy' => 'locality',
                        'field' => 'slug',
                        'terms' => array('warszawa'),
                        'operator' => 'NOT IN',
                    ),
                ),
            )
        );

        MB_Relationships_API::each_connected(array(
            'id' => 'trips_to_routs',
            'to' => $mazowsze->posts,
            'property' => 'trip',
        ));

        ?>

        <?php $i = 1;
        if ($mazowsze->have_posts()) : ?>
            <?php while ($mazowsze->have_posts()) : $mazowsze->the_post(); ?>

                <?php $routeID = $post->ID; ?>

                <?php foreach ($post->trip as $post) : setup_postdata($post); ?>
                    <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                        <a href="<?php the_permalink(); ?>">
                            <div style="background-image: url(<?php the_post_thumbnail_url('large'); ?>);" class="scrollme animateme"
                                 data-when="span"
                                 data-from="0.5"
                                 data-to="0"
                                 data-opacity="0"
                                 data-scale="0.8"
                                 data-rotatez="0">
                                <p class="title"><?php the_title(); ?>
                                    | <span class="no-wrap"><?php echo  get_post_meta($routeID, 'dystans', true); ?> km</span></p>
                            </div>
                        </a>
                    </li>
                <?php endforeach;
                wp_reset_postdata();
                ?>

                <?php $i++; endwhile; ?>
        <?php else: ?>
            <p>Brak rezultatów</p>
        <?php endif; ?>
    </ul>
</section>


<section id="social" class="social scrollme animateme"
         data-when="span"
         data-from="0.75"
         data-to="0"
         data-opacity="0">
    <ul class="grid">
        <li>
            <div class="stat no-wrap"><i class="far fa-calendar-alt fa-3x"></i><span class="counter-value" data-count="<?php echo date('Y')-2009; ?>"><?php echo date('Y')-2009; ?></span> Lat</div>

        </li>
        <li>
            <?php $tripsCount = wp_count_posts( 'trip' )->publish; ?>
            <div class="stat no-wrap stat-center"><i class="far fa-map fa-3x"></i><span class="counter-value" data-count="<?php echo $tripsCount; ?>"><?php echo $tripsCount; ?></span> <?php echo polishPlural('Wyczieczkę', 'Wycieczki', 'Wycieczek', $tripsCount);  ?></div>
        </li>
        <li>
            <div class="stat no-wrap"><i class="fas fa-bicycle fa-3x"></i><span class="counter-value" data-count="<?php getTotalDistance(); ?>"><?php getTotalDistance(); ?></span> km</div>
        </li>

    </ul>
</section>

<section class="about scrollme animateme"
         data-when="span"
         data-from="0.75"
         data-to="0"
         data-opacity="0"
         >

    <div>
        <h2 class="title"><img src="https://new.rowerempomazowszu.pl/team/wp-content/themes/mazbike/img/LogoGreen.png" class="logo" alt="Mazbike"></h2>
        <div class="info">
            <div class="mazbike-image" style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/mazbike.jpg);"></div>
            <p class="i">
                Działalność Mazbike to połączenie przyjemności, którą czerpiemy z jazdy na rowerze, z odkrywaniem naszego
                regionu, czyli Mazowsza. Na stronie dzielimy się naszymi wrażeniami, doświadczeniami i praktycznymi
                informacjami. Znaleźć tu można opisy wycieczek o różnych stopniach trudności, przebytych na różnorakich
                dystansach, a także foto-relacje z imprez rowerowych, takich jak Masa Krytyczna czy Tematyczne Rajdy
                Rowerowe po Warszawie.
            </p>

        </div>
    </div>




    <ul class="grid stats">
        <li>
            <a href="https://www.facebook.com/Mazbike/" target="_blank">
                <i class="fab fa-facebook-square fa-3x"></i>
            </a>


        </li>
        <li>
            <a href="https://www.instagram.com/mazbike/" target="_blank">
                <i class="fab fa-instagram fa-3x"></i>
            </a>


        </li>

    </ul>

</section>

<section class="contact scrollme animateme"
         data-when="span"
         data-from="0.75"
         data-to="0"
         data-opacity="0">
    <div>

        <h2 class="title">Kontakt</h2>

        <hr class="smooth">
        <div>
        <p>Szukasz informacji na temat Warszawy i Mazowsza?</p>
        <p>
            Planujesz wycieczkę rowerową i nie wiesz gdzie pojechać? Na stronie znajdziesz wiele inspiracji zarówno na krótką przejażdżkę, jak i na dalszy wypad, podczas którego będziesz mógł zobaczyć wspaniałe miejsca.
        </p>
        <p class="attention">
            Jeśli znasz jakieś ciekawostki związane z Mazowszem lub chciałbyś podzielić się z nami pomysłem na rowerowe wycieczki zachęcamy do kontaktu.
        </p>
        <p>
            Możesz to zrobić wypełniając poniższy formularz lub pisząc na adres: <a href="mailto:mazbike@rowerempomazowszu.pl">mazbike@rowerempomazowszu.pl</a>
        </p>

        </div>
        <?php
        echo do_shortcode('[contact-form-7 id="5412" title="Strona Główna"]');
        ?>
    </div>



</section>

<section class="instagram">
    <?php
    echo do_shortcode('[instagram-feed]');
    ?>
</section>

<?php get_footer(); ?>
