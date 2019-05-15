<?php get_header(); ?>

<?php $i = 1;

global $wp_query;

$total_results = $wp_query->found_posts;
?>

<section class="hero single">
    <div class="background-image"
         style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/rpm_5.jpg);"></div>
</section>


<section class="page-header archive">
    <div class="wooden">
        <div class="pos-center">
            <div class="title">
                <h1>Archiwum <?php echo mazbike_get_taxonomy_name(getQueryParams()); ?> (<?php echo $total_results; ?>)</h1>
            </div>
        </div>
    </div>
</section>


<section id="entry" class="results destinations content with-sidebar">
    <article class="left">

        <ul class="grid">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :

                the_post();

                ?>



                    <!--TODO wspolne archiwum dla lokality i tagow-->

                    <?php
                    _log('------$post1-----');
//                    _log($post);
                    $routeId = $post->ID;
                    $connected = new WP_Query( array(
                        'relationship' => array(
                            'id' => 'trips_to_routs',
                            'to' => get_the_ID(), // You can pass object ID or full object
                        ),
                        'nopaging'     => true,
                    ) );


                    while ( $connected->have_posts() ) : setup_postdata($connected->the_post());
                        _log('------$post2-----');
//                        _log($post);
                        ?>

                        <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                            <a href="<?php the_permalink(); ?>">
                                <div style="background-image: url(<?php the_post_thumbnail_url('large'); ?>);">
                                    <p class="title"><?php the_title(); ?> |  <span class="no-wrap"><?php echo  get_post_meta($routeId, 'dystans', true); ?> km</span> | <span class="no-wrap"><?php the_date(); ?></span></p>
                                </div>
                            </a>
                        </li>

                        <?php
                        wp_reset_postdata();
                        $i++;
                    endwhile;
                    ?>




            <?php
            endwhile;
        endif;
        ?>
        </ul>
        <div class="pagination">
            <?php
            generatePagination(get_query_var('paged'), $wp_query);
            ?>
        </div>
    </article>

    <?php get_sidebar(); ?>
</section>


<?php get_footer(); ?>
