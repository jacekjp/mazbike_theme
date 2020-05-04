<?php get_header(); ?>


<section class="hero single">
    <div class="background-image"
         style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/archiwum.jpg);"></div>
</section>


<section class="page-header trips">
    <div class="wooden">
        <div class="pos-center">
            <div class="title">
                <h1>Nasze Wycieczki</h1>
                <p><?php mazbike_showArchiveYearAndMonth(); ?></p>
            </div>
        </div>
    </div>

</section>


<section id="entry" class="results destinations content with-sidebar">
    <article class="left">

        <?php
        $query_params = getQueryParams();

        $query_params['post_type'] = 'trip';
        $query_params['posts_per_page'] = 15;

        if(isset($query_params['search'])) {
//            $query_params['post_title_like'] = $query_params['search'];
            $query_params['s'] = $query_params['search'];
            unset($query_params['search']);
            unset($query_params['pagename']);
        }

        $loop = new WP_Query($query_params);
        global $wp_query;
        $total_results = $loop->found_posts;

        if($total_results){
            MB_Relationships_API::each_connected(array(
                'id' => 'trips_to_routs',
                'from' => $loop->posts,
//                'property' => 'route',
            ));
        }

        ?>

        <ul class="grid">

            <?php $i = 1; if($loop->have_posts()) : ?>
                <?php while ($loop->have_posts()) : $loop->the_post(); ?>

                    <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                        <a href="<?php the_permalink(); ?>">

                            <?php
                            foreach($post->connected as $route) break; // break loop after first iteration
                            ?>

                            <div style="background-image: url(<?php the_post_thumbnail_url(($i % 3 == 1) ? 'main-large' : 'main-small'); ?>);" class="">
                                <p class="title"><?php the_title(); ?> | <span class="no-wrap"><?php echo  get_post_meta($route->ID, 'dystans', true); ?> km |</span> <span class="no-wrap"><?php the_date(); ?></span></p>
                            </div>
                        </a>
                    </li>

                    <?php $i++; endwhile; ?>

            <?php else: ?>
                <p>Brak rezultatów</p>
            <?php endif; ?>

        </ul>

        <div class="pagination">
            <?php
            generatePagination(get_query_var('paged'), $loop);
            ?>
        </div>

    </article>

    <?php get_sidebar('trips'); ?>
</section>


<?php get_footer(); ?>
