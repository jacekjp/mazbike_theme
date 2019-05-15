<?php

/**
* Adds Search_Mazbike widget.
*/
class Search_Mazbike_Widget extends WP_Widget {

/**
* Register widget with WordPress.
*/
function __construct() {
parent::__construct(
'search_mazbike_widget', // Base ID
esc_html__( 'Search Mazbike', 'text_domain' ), // Name
array( 'description' => esc_html__( 'A Search Mazbike Widget', 'text_domain' ), ) // Args
);
}

/**
* Front-end display of widget.
*
* @see WP_Widget::widget()
*
* @param array $args     Widget arguments.
* @param array $instance Saved values from database.
*/
public function widget( $args, $instance ) {
echo $args['before_widget'];
if ( ! empty( $instance['title'] ) ) {
echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
}
?>
    <div id="sidebar-search" class="widget search">

        <form class="search" method="get" action="/szukaj">
            <input type="text" name="search" id="search" placeholder="Szukaj..">
            <button type="submit" class="btn"><i class="fas fa-search"></i></button>
        </form>

    </div>
<?
echo $args['after_widget'];
}

/**
* Back-end widget form.
*
* @see WP_Widget::form()
*
* @param array $instance Previously saved values from database.
*/
public function form( $instance ) {
$title = ! empty( $instance['title'] ) ? $instance['title'] : "";
?>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<?php
}

/**
 * Sanitize widget form values as they are saved.
 *
 * @see WP_Widget::update()
 *
 * @param array $new_instance Values just sent to be saved.
 * @param array $old_instance Previously saved values from database.
 *
 * @return array Updated safe values to be saved.
 */
public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

    return $instance;
}

} // class Search_Mazbike_Widget

// register Search_Mazbike_Widget widget
function register_search_mazbike_widget() {
    register_widget( 'Search_Mazbike_Widget' );
}
add_action( 'widgets_init', 'register_search_mazbike_widget' );