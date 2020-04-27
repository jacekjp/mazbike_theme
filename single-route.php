<?php get_header(); ?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) :

        the_post();

        ?>



        <?php

        $routeId = get_the_ID();

        ?>



        <section class="hero">
        <div id="map" class="map"> Mapa </div>
        <script>
        var map;
        var boundsArray = [];

        function CenterControl(controlDiv, map) {

            // Set CSS for the control border.
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginRight = '8px';
            controlUI.style.marginBottom = '8px';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Pobierz trasę';
            controlDiv.appendChild(controlUI);

            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.color = 'rgb(25,25,25)';
            controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
            controlText.style.fontSize = '16px';
            controlText.style.lineHeight = '38px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.innerHTML = '<a href="https://rowerempomazowszu.pl/team/wp-content/uploads/gpx/<?php echo  get_post_meta($routeId, 'gpx', true); ?>">GPX</a>';
            controlUI.appendChild(controlText);

        }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 52.237049, lng: 21.017532},
                zoom: 8,
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

            var centerControlDiv = document.createElement('div');
            var centerControl = new CenterControl(centerControlDiv, map);

            centerControlDiv.index = -1;
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(centerControlDiv);

            var thePanorama = map.getStreetView();
            thePanorama.setOptions({
//                addressControlOptions: {
//                    position: google.maps.ControlPosition.BOTTOM_LEFT
//                },
//                fullscreenControl: false,
//                fullscreenControlOptions: {
//                    position: google.maps.ControlPosition.BOTTOM_LEFT
//                }
            });

            google.maps.event.addListener(thePanorama, 'visible_changed', function() {
                var myMap = document.getElementById('map');
                if (thePanorama.getVisible()) {


                    myMap.style.height = '100%';
                    myMap.style.width = '100%';
                    myMap.style['z-index'] = 100;
                    myMap.style.position = 'fixed';


                } else {

                    myMap.style['z-index'] = 1;
                    myMap.style.position = 'absolute';

                }

            });

//            var ctaLayer = new google.maps.KmlLayer({
//                url:  'https://rowerempomazowszu.pl/team/wp-content/uploads/gpx/<?php //echo  get_post_meta($routeId, 'gpx', true); ?>//'
//                ,color: '#779922'
//                }
//            );
//            ctaLayer.setMap(map);


            var gpxFileData;
            readGpxFile("<?php echo  get_post_meta($routeId, 'gpx', true); ?>").success(function(fData){

                gpxFileData = fData;
                drowTrack(gpxFileData, <?php echo $routeId ?>);
            });



//            var myloc = new google.maps.Marker({
//                clickable: false,
//                icon: new google.maps.MarkerImage('https://rowerempomazowszu.pl/team/wp-content/themes/mazbike/img/top.png',
//                    new google.maps.Size(40,40),
//                    new google.maps.Point(0,18),
//                    new google.maps.Point(11,11)),
//                shadow: null,
//                zIndex: 999,
//                map: map
//            });
//
//            if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {
//                var me = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
//                myloc.setPosition(me);
//            }, function(error) {
//            console.log({
//                error: error
//            });
//            });

        }

        function drowTrack(gpxFileData, tr_id){

//            console.log('gpxFileData');
//            console.log(gpxFileData);

            var points = [];
            var tmp_marker = [];

            var lats = [];
            var lons = [];
            jQuery(gpxFileData).find("trkpt").each(function() {
                var lat = jQuery(this).attr("lat");
                var lon = jQuery(this).attr("lon");
                var p = new google.maps.LatLng(lat, lon);
                points.push(p);
                lats.push(lat);
                lons.push(lon);
                tmp_marker.push([lat, lon]);
            });

            var maxPoint =  new google.maps.LatLng(Math.max.apply(Math, lats), Math.max.apply(Math, lons));
            var minPoint =  new google.maps.LatLng(Math.min.apply(Math, lats), Math.min.apply(Math, lons));
            boundsArray.push([tr_id+"_max", maxPoint],[tr_id+"_min", minPoint]);

//            console.log('points');
//            console.log(points);
            var poly = new google.maps.Polyline({
                path: points,
                strokeColor: "#4C1E6D",
                strokeOpacity: .7,
                strokeWeight: 4
            });

            poly.setMap(map);

//            google.maps.event.addListener(poly, 'mouseover', function(event) {
//                $('#current-info').html(tr_title);
//                poly.setOptions({
//                    strokeColor: "red",
//                    strokeWeight: 6
//                });
//
//            });
//            google.maps.event.addListener(poly, 'mouseout', function(event) {
//                $('#current-info').html("");
//                poly.setOptions({
//                    strokeColor: "green",
//                    strokeWeight: 4
//                });
//
//            });
//            google.maps.event.addListener(poly, 'click', function(event) {
//                polyArray[tr_id].setMap(null);
//                polyArray[tr_id] = 0;
//                reduceBounds([tr_id+"_max",tr_id+"_min"]);
//                boundsAdjust(boundsArray);
//            });


            boundsAdjust(boundsArray);
        }

        function readGpxFile(file){
            return jQuery.ajax({
                async: true,
                type: "GET",
                url: "/team/wp-content/uploads/gpx/"+file,
                dataType: "xml"
            })
        }

        function boundsAdjust(points){
            var bounds = new google.maps.LatLngBounds ();
            if(points.length > 0){
                for(i in points){
                    bounds.extend(points[i][1]);
                }
                map.fitBounds(bounds);
            }
        }


        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACNnTkm0EmNOzoH6HXOYzjk2DcrR5wP8c&callback=initMap"
                async defer></script>
        </section>



        <section class="post-header trip">
            <div class="wooden">
                <div class="pos-center">
                    <div class="title">
                        <h1>Trasa: <?php the_title(); ?> | <span class="no-wrap"><?php echo  get_post_meta($routeId, 'dystans', true); ?> km</span></h1>
                        <p><?php the_date(); ?></p>
                    </div>
                </div>
            </div>

        </section>

        <section id="entry" class="content">

        </section>

    <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>
