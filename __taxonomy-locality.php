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
                <?php $term = get_term_by('slug', get_query_var('locality'), 'locality'); $name = $term->name; ?>
                <h1>Archiwum <?php echo $name; ?></h1>
            </div>
        </div>
    </div>
</section>


<section id="entry" class="results destinations content with-sidebar">
    <article class="left">

        <?php
        if ( have_posts() ) :
        while ( have_posts() ) :

        the_post();

        ?>

            <ul class="grid">

<!--TODO wspolne archiwum dla lokality i tagow-->

            <?php
            $routeId = $post->ID;
            $connected = new WP_Query( array(
                'relationship' => array(
                    'id' => 'trips_to_routs',
                    'to' => get_the_ID(), // You can pass object ID or full object
                ),
                'nopaging'     => true,
            ) );


            while ( $connected->have_posts() ) : setup_postdata($connected->the_post());

                ?>

                <li id="location-<?php the_ID(); ?>" <?php post_class(($i % 3 == 1) ? 'large' : 'small'); ?>>
                    <a href="<?php the_permalink(); ?>">
                        <div style="background-image: url(<?php the_post_thumbnail_url('large'); ?>);">
                            <p class="title"><?php the_title(); ?> |  <?php echo  get_post_meta($routeId, 'dystans', true); ?> km | <?php the_date(); ?></p>
                        </div>
                    </a>
                </li>

                <?php



                wp_reset_postdata();
            $i++;
            endwhile;


            ?>






        </ul>



        <?php
        endwhile;
        endif;
        ?>

        <div class="pagination">
            <?php
            generatePagination(get_query_var('paged'), $wp_query);
            ?>
        </div>
    </article>

    <?php get_sidebar(); ?>
</section>


<?php get_footer(); ?>
