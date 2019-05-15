<?php
/*
Template Name: Search Page
*/
?>

<?php

$search = getQuerySingleParam('search');

?>

<?php get_header(); ?>

    <section class="hero single">
        <div class="background-image"
             style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/search/S3.JPG);"></div>
    </section>

    <section id="header" class="search">

                <form class="search" method="get" action="/szukaj/">
                    <input type="text" name="search" id="search" value="<?php echo $search; ?>" placeholder="Gdzie chesz jechać?">
                    <button type="submit" class="btn">Szukaj</button>
                </form>

    </section>



    <section id="entry" class="results destinations content with-sidebar">
        <article class="left">



            <?php
            $query_params = getQueryParams();

            $query_params['post_type'] = 'trip';
            $query_params['posts_per_page'] = 15;

            if(isset($query_params['search']) && !empty($query_params['search'])) {
                $query_params['s'] = $query_params['search'];
            }

            unset($query_params['search']);
            unset($query_params['pagename']);

            $loop = new WP_Query($query_params);
            global $wp_query;
            $total_results = $loop->found_posts;

            if($total_results){
                MB_Relationships_API::each_connected(array(
                    'id' => 'trips_to_routs',
                    'from' => $loop->posts,
                ));
            }

            ?>

            <h2 class="title">Znaleziono <?php echo $total_results; ?> <?php echo polishPlural('wyczieczkę', 'wycieczki', 'wycieczek', $total_results)  ?>: </h2>
            <hr>

            <ul class="grid">

                <?php $i = 1; if($loop->have_posts()) : ?>
                    <?php while ($loop->have_posts()) : $loop->the_post(); ?>

                        <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                            <a href="<?php the_permalink(); ?>">
                                <div style="background-image: url(<?php the_post_thumbnail_url('large'); ?>);">
                                    <p class="title"><?php the_title(); ?> | <span class="no-wrap"><?php echo  get_post_meta($post->connected[0]->ID, 'dystans', true); ?> km |</span> <span class="no-wrap"><?php the_date(); ?></span></p>
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

        <?php get_sidebar('search'); ?>
    </section>

<?php get_footer(); ?>