<?php
/*
Template Name: Sezon
*/
?>

<?php get_header(); ?>

<?php the_post(); ?>

    <section class="hero single">
        <div class="background-image"
             style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/rpm_5.jpg);"></div>
    </section>

    <section class="page-header page">
        <div class="wooden">
            <div class="pos-center">
                <div class="title">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>



    <section id="entry" class="content with-sidebar">
        <article class="left">

            <div class="seasons-menu">
                <?php
                echo do_shortcode('[listmenu menu="Sezony"]');
                ?>
            </div>

            <?php the_content(); ?>

            <?php comments_template(); ?>

        </article>

        <?php get_sidebar(); ?>
    </section>


<?php get_footer(); ?>