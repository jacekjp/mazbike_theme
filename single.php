<?php get_header(); ?>

<?php the_post(); ?>

<section class="hero">
    <div id="map" class="map"> Mapa </div>
    <script>
        var map;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
//                center: {lat: -34.397, lng: 150.644},
//                zoom: 8
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.BOTTOM_LEFT
                },
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }

            });
//            console.log('map');
//            console.log(map);
            var ctaLayer = new google.maps.KmlLayer('https://rowerempomazowszu.pl/team/wp-content/uploads/gpx/2018-09-Ostroleka2.gpx');

            ctaLayer.setMap(map);

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACNnTkm0EmNOzoH6HXOYzjk2DcrR5wP8c&callback=initMap"
            async defer></script>
</section>

<section class="post-header">
    <div class="wooden">
        <div class="pos-center">
            <div class="title">
                <h3>single.php | <?php the_title(); ?></h3>
            </div>
        </div>
    </div>

</section>

<section id="entry" class="content">
    <div class="pos-center">
        <article class="">

            <?php the_content(); ?>

        </article>

    </div>
</section>

<?php get_footer(); ?>
