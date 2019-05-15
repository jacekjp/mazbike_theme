<?php get_header(); ?>


    <section class="hero single">
        <div class="background-image"
             style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/awaria.jpg);"></div>
    </section>

    <section id="header" class="search">

        <form class="search" method="get" action="/szukaj/">
            <input type="text" name="search" id="search" value="<?php echo $search; ?>" placeholder="Gdzie chesz jechać?">
            <button type="submit" class="btn">Szukaj</button>
        </form>

    </section>

    <section id="header error404" class="entry">

        <div class="wooden">
            <div class="pos-center">
                <header>
                    <h3>UPS - Nie ma takiej strony :(</h3>
                </header>
            </div>
        </div>

    </section>

    <section id="entry" class="content">

    </section>


<?php get_footer(); ?>