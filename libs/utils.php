<?php

function getQueryParams(){
    global $query_string;
    $parts = explode('&', $query_string);

    $return = array();
    foreach($parts as $part){
        $tmp = explode('=', $part);
        $return[$tmp[0]] = trim(urldecode($tmp[1]));
    }

    return $return;
}

function getQuerySingleParam($name){
    $qparams = getQueryParams();

    if(isset($qparams[$name])){
        return $qparams[$name];
    }

    return NULL;
}

function getCurrentPageUrl(){
    $pageURL = 'http';

    if(isset($_SERVER["HTTPS"])){
        if($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
    }

    $pageURL .= "://";

    if($_SERVER["SERVER_PORT"] != "80"){
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }else{
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    return $pageURL;
}

add_filter('posts_where', 'title_like_posts_where', 10, 2);

function title_like_posts_where( $where, &$wp_query ) {
    global $wpdb;

    if ($post_title_like = $wp_query->get('post_title_like')){
        $where .= ' AND '.$wpdb->posts.'.post_title LIKE \'%'.esc_sql(like_escape($post_title_like)).'%\'';
    }

    return $where;
}

function generatePagination($paged, $loop){
    $big = 999999999; // need an unlikely integer

    //https://codex.wordpress.org/Function_Reference/paginate_links
    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, $paged ),
        'total' => $loop->max_num_pages,
        'prev_text' => '«',
        'next_text' => '»',
        'type' => 'list'
    ) );
}

function polishPlural($singularNominativ, $pluralNominativ, $pluralGenitive, $value) {
    if ($value == 1) {
        return $singularNominativ;
    } else if ($value % 10 >= 2 && $value % 10 <= 4 && ($value % 100 < 10 || $value % 100 >= 20)) {
        return $pluralNominativ;
    } else {
        return $pluralGenitive;
    }
}

