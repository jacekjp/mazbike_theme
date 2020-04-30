<?php
/*
Template Name: Sezon
*/
?>

<?php get_header(); ?>

<?php the_post(); ?>

    <section class="hero">
        <div id="map" class="map"> Mapa </div>
        <script>
        var map, infowindow, contentString, currentPoly;
        var polyArray = [];
        var boundsArray = [];


        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 52.237049, lng: 21.017532},
                zoom: 9,
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

            infowindow = new google.maps.InfoWindow({
                content: ""
            });

            google.maps.event.addListener(infowindow,'closeclick',function(){
                currentPoly.setOptions({
                    strokeColor: "#4C1E6D",
                    strokeOpacity: 1,
                    strokeWeight: 4
                });
                currentPoly = undefined;
            });

            var centerControlDiv = document.createElement('div');

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



//            showMazowsze();

            <?php

             $query = new WP_Query(array(
                'post_type' => 'route',
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'date_query' => array(array('year' => getSeasonYear(get_the_title())))

        //    'post_status' => array('publish', 'pending', 'draft', 'auto-draft')
        ));

         MB_Relationships_API::each_connected(array(
                    'id' => 'trips_to_routs',
                    'to' => $query->posts,
                    'property' => 'trip',
                ));


            $routes = array();

           while ( $query->have_posts() ) : $query->the_post();

                $routeId = get_the_ID();

                $terms = get_the_terms( $routeId, 'locality' );

                $isMazowsze = false;

                foreach($terms as $term) {

                    if($term->name == 'Mazowsze') {
                        $isMazowsze = true;
                    }
                }

                $tripYear = date("Y");
                $tripLink = "#";

                foreach ( $post->trip as $post ) : setup_postdata( $post );

                  $tripYear = get_the_date('Y',$post );
                  $tripLink = get_permalink($post);
                  $tripTitle = get_the_title($post);

                endforeach;
                wp_reset_postdata(); // Set $post back to original post



            $routes[] = [
                'route_id' => $routeId,
                'route_title' => get_the_title(),
                'route_file' => get_post_meta($routeId, 'gpx', true),
                'route_is_planned' => get_post_meta($routeId, 'planowana', true),
                'route_year_diff' => date("Y") - $tripYear,
                'trip_title' => $tripTitle,
                'trip_link' => $tripLink,
                'meta' => get_post_meta($routeId),
                'is_mazowsze' => $isMazowsze
            ];


            endwhile;

            $js_routes = json_encode($routes);
            wp_reset_query();
                 ?>

            var routes = <?php echo $js_routes ?>;
            console.log(routes);

            for (var i = 0; i < routes.length; i++) {
//                readGpxFile(routes[i]);
                drawTrackNew(routes[i]);
            }
        }

        function drawTrackNew(route){

            if(route.meta.route_path == null) {
                return;
            }

            var geoData = JSON.parse(route.meta.route_path[0]);
            var points = [];
            var tmp_marker = [];

            var tr_id = route['route_id'];
            var tr_title = route['route_title'];
            var tr_is_planned = route['route_is_planned'];
            var is_mazowsze = route['is_mazowsze'];

            if(tr_is_planned == 1) return;

            var tr_year_diff = route['route_year_diff'];

            var lats = [];
            var lons = [];
            for (var i = 0; i < geoData.geometry.coordinates[0].length; i++) {
                var coords = geoData.geometry.coordinates[0][i];

                var lat = coords[0];
                var lon = coords[1];
                var p = new google.maps.LatLng(lat, lon);
                points.push(p);
                lats.push(lat);
                lons.push(lon);
                tmp_marker.push([lat, lon]);
            }

            var maxPoint =  new google.maps.LatLng(Math.max.apply(Math, lats), Math.max.apply(Math, lons));
            var minPoint =  new google.maps.LatLng(Math.min.apply(Math, lats), Math.min.apply(Math, lons));
            if(is_mazowsze){
                boundsArray.push([tr_id+"_max", maxPoint],[tr_id+"_min", minPoint]);
            }


            var strokeColor = "#4C1E6D";
//    tr_year_diff = (tr_year_diff < 8 )? tr_year_diff : 7;
//    var strokeOpacity = 1 - 0.1 * tr_year_diff;
//    strokeOpacity = strokeOpacity.toFixed(1);
            var strokeOpacity = 0.7;
            var strokeWeight = 4;

            if(tr_is_planned == 1) {
                strokeColor = "red";
                strokeOpacity = 1;
                strokeWeight = 2;
            }

            if(tr_title == 'mazowsze') {
                strokeColor = "black";
                strokeOpacity = 1;
                strokeWeight = 1;
            }

            var poly = new google.maps.Polyline({
                path: points,
                strokeColor: strokeColor,
                strokeOpacity: strokeOpacity,
                strokeWeight: strokeWeight
            });

            poly.setMap(map);
            polyArray[tr_id] = poly;

            if(tr_title !== 'mazowsze'){
                google.maps.event.addListener(poly, 'mouseover', function(event) {
//                jQuery('#current-info').html(tr_title);
                    poly.setOptions({
//                    strokeColor: "red",
                        strokeOpacity: 1,
                        strokeWeight: 5
                    });

                });
                google.maps.event.addListener(poly, 'mouseout', function(event) {
//                jQuery('#current-info').html("");

                    poly.setOptions({
//                    strokeColor: "green",
                        strokeOpacity: strokeOpacity,
                        strokeWeight: strokeWeight
                    });

                    if(typeof currentPoly !== "undefined")
                    {
                        currentPoly.setOptions({
                            strokeColor: "#792",
                            strokeWeight: 5
                        });
                    }



                });

                var infowindowstring = "<div style='color:#792;padding:5px;'><a href='" + route.trip_link + "'>" + route.trip_title + " | " + route.meta.dystans + "km</a></div>";

                google.maps.event.addListener(poly, 'click', function(event) {

                    if(typeof currentPoly !== "undefined")
                    {
                        currentPoly.setOptions({
                            strokeColor: "#4C1E6D",
                            strokeOpacity: 0.7,
                            strokeWeight: 4
                        });
                    }
                    currentPoly = poly;

                    infowindow.setContent(infowindowstring);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map );
                    poly.setOptions({
                        strokeColor: "#792",
                        strokeOpacity: 1,
                        strokeWeight: 5
                    });


//                polyArray[tr_id].setMap(null);
//                polyArray[tr_id] = 0;
//                reduceBounds([tr_id+"_max",tr_id+"_min"]);
//                boundsAdjust(boundsArray);
                });
            }




            boundsAdjust(boundsArray);
        }

        function drawTrack(gpxFileData, route){

            var points = [];
            var tmp_marker = [];

            var tr_id = route['route_id'];
            var tr_title = route['route_title'];
            var tr_is_planned = route['route_is_planned'];
            var is_mazowsze = route['is_mazowsze'];

            if(tr_is_planned == 1) return;

            var tr_year_diff = route['route_year_diff'];

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
               if(is_mazowsze){
                   boundsArray.push([tr_id+"_max", maxPoint],[tr_id+"_min", minPoint]);
               }


            var strokeColor = "#4C1E6D";
//    tr_year_diff = (tr_year_diff < 8 )? tr_year_diff : 7;
//    var strokeOpacity = 1 - 0.1 * tr_year_diff;
//    strokeOpacity = strokeOpacity.toFixed(1);
            var strokeOpacity = 0.7;
            var strokeWeight = 4;

            if(tr_is_planned == 1) {
                strokeColor = "red";
                strokeOpacity = 1;
                strokeWeight = 2;
            }

            if(tr_title == 'mazowsze') {
                strokeColor = "black";
                strokeOpacity = 1;
                strokeWeight = 1;
            }

            var poly = new google.maps.Polyline({
                path: points,
                strokeColor: strokeColor,
                strokeOpacity: strokeOpacity,
                strokeWeight: strokeWeight
            });

            poly.setMap(map);
            polyArray[tr_id] = poly;

            if(tr_title !== 'mazowsze'){
                google.maps.event.addListener(poly, 'mouseover', function(event) {
//                jQuery('#current-info').html(tr_title);
                    poly.setOptions({
//                    strokeColor: "red",
                        strokeOpacity: 1,
                        strokeWeight: 5
                    });

                });
                google.maps.event.addListener(poly, 'mouseout', function(event) {
//                jQuery('#current-info').html("");

                    poly.setOptions({
//                    strokeColor: "green",
                        strokeOpacity: strokeOpacity,
                        strokeWeight: strokeWeight
                    });

                    if(typeof currentPoly !== "undefined")
                    {
                        currentPoly.setOptions({
                            strokeColor: "#792",
                            strokeWeight: 5
                        });
                    }



                });

                var infowindowstring = "<div style='color:#792;padding:5px;'><a href='" + route.trip_link + "'>" + route.trip_title + " | " + route.meta.dystans + "km</a></div>";

                google.maps.event.addListener(poly, 'click', function(event) {

                    if(typeof currentPoly !== "undefined")
                    {
                        currentPoly.setOptions({
                            strokeColor: "#4C1E6D",
                            strokeOpacity: 0.7,
                            strokeWeight: 4
                        });
                    }
                    currentPoly = poly;

                    infowindow.setContent(infowindowstring);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map );
                    poly.setOptions({
                        strokeColor: "#792",
                        strokeOpacity: 1,
                        strokeWeight: 5
                    });


//                polyArray[tr_id].setMap(null);
//                polyArray[tr_id] = 0;
//                reduceBounds([tr_id+"_max",tr_id+"_min"]);
//                boundsAdjust(boundsArray);
                });
            }




    boundsAdjust(boundsArray);
        }

        function readGpxFile(route){
            return jQuery.ajax({
                async: true,
                type: "GET",
                url: "/team/wp-content/uploads/gpx/"+route['route_file'],
                dataType: "xml",
                success: function(fData){
                    drawTrack(fData, route);
                }
            })
        }

        function showMazowsze(){
            var route = [];
            route['route_file'] = 'mazowsze.gpx';
            route['route_id'] = '0';
            route['route_title'] = 'mazowsze';
            route['route_year_diff'] = 0;
            readGpxFile(route);
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