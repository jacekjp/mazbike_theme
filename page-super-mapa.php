<?php get_header(); ?>

<?php the_post(); ?>


<section class="hero" style="padding: 0;">
<div id="map" class="map" style="width: 100vw; height: 100vh;"> Mapa </div>
<script>
var map;
var polyArray = [];
var boundsArray = [];


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

    <?php

     $query = new WP_Query(array(
    'post_type' => 'route',
   'posts_per_page'   => -1,
//    'post_status' => 'publish'
));

$routes = array();

while ($query->have_posts()) {
    $query->the_post();
    $routeId = get_the_ID();

 $routes[] = [
    'route_id' => $routeId,
    'route_title' => get_the_title(),
    'route_file' => get_post_meta($routeId, 'gpx', true)
];

}
$js_routes = json_encode($routes);
wp_reset_query();
     ?>

    var routes = <?php echo $js_routes ?>;

    var i;
    for (i = 0; i < routes.length; i++) {

        readGpxFile(routes[i]['route_file']).success(function(fData){

            gpxFileData = fData;
            drowTrack(gpxFileData, <?php echo $routeId ?>);
        });
    }
}

function drowTrack(gpxFileData, tr_id){

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

    var poly = new google.maps.Polyline({
        path: points,
        strokeColor: "#4C1E6D",
        strokeOpacity: .7,
        strokeWeight: 4
    });

    poly.setMap(map);
    polyArray[tr_id] = poly;

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
                <?php the_content(); ?>
            </article>
    </section>


<?php get_footer(); ?>
