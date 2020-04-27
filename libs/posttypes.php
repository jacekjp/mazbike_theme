<?php

add_action('init', 'mazbike_init_posttypes');

function mazbike_init_posttypes() {

//    Rejestracja Wycieczek

    $trips_args = array(
        'labels' => array(
            'name' => 'Wycieczki',
            'singular_name' => 'wycieczki',
            'all_items' => 'Wszystkie wycieczki',
            'add_new' => 'Dodaj wycieczkę',
            'add_new_item' => 'Dodaj nową wycieczkę',
            'edit_item' => 'Edytuj wycieczkę',
            'new_item' => 'Nowa wycieczka',
            'view_item' => 'Zobacz wycieczkę',
            'search_items' => 'Szukaj w wycieczkach',
            'not_found' =>  'Nie znaleziono żadnych wycieczek',
            'not_found_in_trash' => 'Nie znaleziono żadnych wycieczek w koszu',
            'parent_item_colon' => ''
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
//        'rewrite' => false,
//        'rewrite' => [
//            'slug' => '',
//            'with_front' => false
//        ],
//        'rewrite' => array(
//            'slug' => '%year%/%monthnum%',
////            'slug' => 'wycieczka',
//            'with_front' => false,
//        ),
                'rewrite' => array(
//            'slug' => '',
            'slug' => 'wycieczki',
            'with_front' => false,
        ),
        'cptp_permalink_structure' => '%year%/%monthnum%/%postname%',
//        'cptp_permalink_structure' => '%postname%',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-palmtree',
        'supports' => array(
            'title','editor','author','thumbnail','excerpt','trackbacks', 'comments','custom-fields','revisions'
        ),
        'taxonomies' => array('post_tag'),
        'has_archive' => true
    );

    register_post_type('trip', $trips_args);

    //    Rejestracja Tras

    $routes_args = array(
        'labels' => array(
            'name' => 'Trasy',
            'singular_name' => 'trasy',
            'all_items' => 'Wszystkie trasy',
            'add_new' => 'Dodaj trasę',
            'add_new_item' => 'Dodaj nową trasę',
            'edit_item' => 'Edytuj trasę',
            'new_item' => 'Nowa trasa',
            'view_item' => 'Zobacz trasę',
            'search_items' => 'Szukaj w trasach',
            'not_found' =>  'Nie znaleziono żadnych tras',
            'not_found_in_trash' => 'Nie znaleziono żadnych tras w koszu',
            'parent_item_colon' => ''
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array(
            'title','editor','author','thumbnail','excerpt','trackbacks', 'comments','custom-fields','revisions'
        ),
        'has_archive' => true
    );

    register_post_type('route', $routes_args);

    //    Rejestracja Miejsc

    $locations_args = array(
        'labels' => array(
            'name' => 'Miejsca',
            'singular_name' => 'miejsca',
            'all_items' => 'Wszystkie miejsca',
            'add_new' => 'Dodaj miejsce',
            'add_new_item' => 'Dodaj nową miejsce',
            'edit_item' => 'Edytuj miejsce',
            'new_item' => 'Nowe miejsce',
            'view_item' => 'Zobacz miejsce',
            'search_items' => 'Szukaj w miejscach',
            'not_found' =>  'Nie znaleziono żadnych miejsc',
            'not_found_in_trash' => 'Nie znaleziono żadnych miejsc w koszu',
            'parent_item_colon' => ''
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-location',
        'supports' => array(
            'title','editor','author','thumbnail','excerpt','trackbacks', 'comments','custom-fields','revisions'
        ),
        'has_archive' => true
    );

    register_post_type('location', $locations_args);
}

add_action('init', 'mazbike_init_taxonomies');

function mazbike_init_taxonomies() {

    register_taxonomy(
        'locality',
        array('location', 'route'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Miejscowości',
                'singular_name' => 'Miejscowość',
                'search_items' => 'Wyszukaj miejscowości',
                'popular_items' => 'Popularne miejscowości',
                'all_items' => 'Wszystkie miejscowości',
                'edit_item' => 'Edutuj miejscowość',
                'update_item' => 'Aktualizuj miejscowość',
                'add_new_item' => 'Dodaj nową miejscowość',
                'new_item_name' => 'Nazwa nowej miejscowości',
                'separate_items_with_commas' => 'Oddziel miejscowości przecinkami',
                'add_or_remove_items' => 'Dodaj lub usuń miejscowości',
                'choose_from_most_used' => 'Wybierz spośród najczęścież odwiedzanych miejscowości',
                'menu_name' => 'Miejscowości'
            ),
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'locality')
        )
    );

    register_taxonomy(
        'location_type',
        array('location'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Rodzaje',
                'singular_name' => 'Rodzaj',
                'search_items' => 'Wyszukaj rodzaje',
                'popular_items' => 'Popularne rodzaje',
                'all_items' => 'Wszystkie rodzaje',
                'edit_item' => 'Edutuj rodzaj',
                'update_item' => 'Aktualizuj rodzaj',
                'add_new_item' => 'Dodaj nowy rodzaj',
                'new_item_name' => 'Nazwa nowego rodzaju',
                'separate_items_with_commas' => 'Oddziel rodzaje przecinkami',
                'add_or_remove_items' => 'Dodaj lub usuń rodzaje',
                'choose_from_most_used' => 'Wybierz spośród najczęścież wybieranych rodzai',
                'menu_name' => 'Rodzaje'
            ),
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'location_type')
        )
    );

}

add_action( 'mb_relationships_init', function() {
    MB_Relationships_API::register( array(
        'id'   => 'trips_to_routs',
//        'from' => 'trip',
        'from' => array(
            'object_type' => 'post',
            'post_type' => 'trip',
            'admin_column' => true,
            'meta_box' => array(
                'title' => 'Trasa',
                'field_title' => 'test'
            )
        ),
        'to'   => array(
            'object_type' => 'post',
            'post_type' => 'route',
            'admin_column' => true,
            'meta_box' => array(
                'title' => 'Wycieczka',
                'field_title' => 'test'
            )

        ),
    ) );
} );