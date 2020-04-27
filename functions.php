<?php

if(!defined('MAZBIKE_THEME_DIR'))
    define('MAZBIKE_THEME_DIR', get_theme_root().'/'.get_template().'/');
if(!defined('MAZBIKE_THEME_URL'))
    define('MAZBIKE_THEME_URL', WP_CONTENT_URL.'/themes/'.get_template().'/');


require_once MAZBIKE_THEME_DIR.'libs/utils.php';
require_once MAZBIKE_THEME_DIR.'libs/posttypes.php';
require_once MAZBIKE_THEME_DIR.'libs/widgets.php';

add_theme_support( 'post-thumbnails' );

$customHeader = [
    'flex-width'    => true,
    'width'         => 1920,
    'flex-height'    => true,
    'height'        => 1080,
];
add_theme_support( 'custom-header', $customHeader );


if(!function_exists('_log')){
    function _log($message){

        if(defined('WP_DEBUG') && WP_DEBUG){
            $file_path = __DIR__.DIRECTORY_SEPARATOR.'app_dev2.log';

            $time = date('Y-m-d H:i:s');

            if(is_array($message) || is_object($message)){
                $message = print_r($message, TRUE);
            }

            $log_line = "$time\t{$message}\n";

            if(!file_put_contents($file_path, $log_line, FILE_APPEND)){
                throw new Exception("Plik dziennika '{$file_path}' nie może zostać otwary ani utworzony. Sprawdź uprawnienia.");
            }
        }
    }
}

function getSeasonYear($title){

    return preg_replace('/[^0-9]/', '', $title);;
}

function rpm_thumbs_setup() {
    add_image_size( 'main-large', 1024, 350, true );
    add_image_size( 'main-small', 576, 350, true );
}
add_action( 'after_setup_theme', 'rpm_thumbs_setup' );

function printPostCategories($post_id, array $categories = array()){
    $terms_list = array();
    foreach($categories as $cname){
        $tmp = get_the_terms($post_id, $cname);
        if(is_array($tmp)){
            $terms_list = array_merge($terms_list, $tmp);
        }
    }

    if($terms_list){
        foreach($terms_list as $term){
//            echo '<a href="'.get_term_link($term->slug, $term->taxonomy).'">'.$term->name.'</a>';
            echo $term->name;
        }
    }
}

function printLocationCategories($post_id){
    printPostCategories($post_id, array('locality'));
}

function getTotalDistance(){
    $reviewsNum = 0;
    $argsSchema = array( 'post_type' => 'route', 'posts_per_page' => -1 );
    $loopSchema = new WP_Query( $argsSchema );
    while ( $loopSchema->have_posts() ) : $loopSchema->the_post();
        $reviewrating       =   get_post_meta(get_the_ID(), "dystans", true);
        if (!empty($reviewrating)){
            $reviewsNum += $reviewrating;
        }
    endwhile;

    echo $reviewsNum;
}


if(function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'main-nav' => "Main Menu",
        'seasons-nav' => 'Sezony',
        'footer-nav' => "Footer Menu"
    ));
}

function mazbike_enqueue_script() {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_script( 'mazbike-scrollme', MAZBIKE_THEME_URL.'js/jquery.scrollme.min.js', array('jquery'), $version, true );
    wp_enqueue_script( 'mazbike-main', MAZBIKE_THEME_URL.'js/main.js', array('jquery', 'mazbike-scrollme'), $version, true );

}
function themeslug_enqueue_style() {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style( 'main', MAZBIKE_THEME_URL. 'styles/main_old.css', false, $version );
    wp_enqueue_style( 'main-compiled', MAZBIKE_THEME_URL. 'css/main.css', false, $version );
}

add_action( 'wp_enqueue_scripts', 'mazbike_enqueue_script' );
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );










/**
 * Register our sidebars and widgetized areas.
 *
 */
function mazbike_widgets_init() {

    register_sidebar( array(
        'name'          => 'Strony',
        'id'            => 'page_right_1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => 'Wycieczki',
        'id'            => 'trips_sidebar',
        'before_widget' => '<div id="%1$s" class="trips-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="trips-widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => 'Szukajka',
        'id'            => 'search_sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ) );

}
add_action( 'widgets_init', 'mazbike_widgets_init' );








// Custom Callback

function mazbike_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

    <div class="comment-wrap">
        <div class="comment-img">
            <?php echo get_avatar($comment,$args['avatar_size'],null,null,array('class' => array('img-responsive', 'img-circle') )); ?>
        </div>
        <div class="comment-body">
            <h4 class="comment-author"><?php echo get_comment_author_link(); ?></h4>
            <span class="comment-date"><?php printf(__('%1$s at %2$s', 'rowerem-po-mazowszu'), get_comment_date(),  get_comment_time()) ?></span>
            <?php if ($comment->comment_approved == '0') { ?><em><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> <?php _e('Comment awaiting approval', 'rowerem-po-mazowszu'); ?></em><br /><?php } ?>
            <?php comment_text(); ?>
            <span class="comment-reply"> <?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply', 'rowerem-po-mazowszu'), 'depth' => $depth, 'max_depth' => $args['max_depth'])), $comment->comment_ID); ?></span>
        </div>
    </div>
<?php }

// Enqueue comment-reply

add_action('wp_enqueue_scripts', 'mazbike_public_scripts');

function mazbike_public_scripts() {
    if (!is_admin()) {
        if (is_singular() && get_option('thread_comments')) { wp_enqueue_script('comment-reply'); }
    }
}

// Enqueue fontawesome

add_action('wp_enqueue_scripts', 'mazbike_public_styles');

function mazbike_public_styles() {
    wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0', 'all');
}

function mazbike_showArchiveYearAndMonth(){
    $year     = get_query_var('year');
    $monthnum = get_query_var('monthnum');

    $date = "";

    if($monthnum){
        $date .= $GLOBALS['wp_locale']->get_month($monthnum). ' ';
    }

    if($year){
        $date .= $year;
    }

    echo $date;
}

function mazbike_get_taxonomy_name($query_params){

    if(isset($query_params['locality'])){
        $term = get_term_by('slug', get_query_var('locality'), 'locality');
        $name = $term->name;
    }

    if(isset($query_params['tag'])){
        $term = get_term_by('slug', get_query_var('tag'), 'post_tag');
        $name = $term->name;
    }

    _log($term);
    $name = $name . ' ' . $term->ID;
    return $name;
}




add_filter( 'pre_get_posts', 'slug_cpt_tag_archives' );
function slug_cpt_tag_archives( $query ) {
    if ( $query->is_tag() && $query->is_main_query()  )  {
        $query->set( 'post_type',
            array(
                'trip'
            )
        );
    }

    return $query;

}

function get_custom_post_type_template( $archive_template ) {
    global $post;

        if ( is_tax( 'trip' ) ) {
    echo "asdf;lkasdf;lkasjf;lkj";
        }

    if ( is_post_type_archive ( 'post_tag' ) ) {
        echo "asdf;lkasdf;lkasjf;lkj";
        $archive_template = dirname( __FILE__ ) . '/post-type-template.php';
    }

    return $archive_template;
}

add_filter( 'archive_template', 'get_custom_post_type_template' ) ;

add_filter( 'archive_template ', 'slug_tax_page_one' );
function slug_tax_page_one( $template ) {
//    if ( is_tax( 'trip' ) ) {
        echo "asdf;lkasdf;lkasjf;lkj";
//        global $wp_query;
//        $page = $wp_query->query_vars['paged'];
//        if ( $page = 0 ) {
            $template = get_stylesheet_directory(). '/archive.php';
//        }
//    }

    return $template;

}


add_filter( 'pre_get_posts' , 'remove_warsaw_from_mazowsze' );
function remove_warsaw_from_mazowsze( $query ) {

    if( $query->is_admin )
        return $query;

    if ($query->is_archive) {
        if(  get_query_var( 'locality', false ) == 'mazowsze' ) {

            set_query_var('post_type', 'route');

            if(count($query->tax_query->queries)){
                foreach($query->tax_query->queries as $i => $taxQuery ){
                    if(isset($taxQuery['taxonomy']) && $taxQuery['taxonomy'] == 'locality'){
                        $tax_query = array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'locality',
                                'field' => 'slug',
                                'terms' => 'mazowsze',
                                'operator' => 'IN'
                            ),
                            array(
                                'taxonomy' => 'locality',
                                'field' => 'slug',
                                'terms' => array('warszawa'),
                                'operator' => 'NOT IN',
                            ));

                        $query->tax_query->queries[$i] = $tax_query;
                    }
                }
            }

            $query->query_vars['tax_query'] = $query->tax_query->queries;
        }
    }



    return $query;
}

//function my_tax_query( $query ) {
//    $tax_query = array(
//        'taxonomy' => 'locality',
//        'terms'    => 'mazowsze',
//        'field'    => 'slug',
//        'operator' => 'IN',
//    );
//    $query->tax_query->queries[] = $tax_query;
//    $query->query_vars['tax_query'] = $query->tax_query->queries;
//}
//add_action( 'pre_get_posts', 'my_tax_query' );