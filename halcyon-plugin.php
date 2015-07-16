<?php
/*
Plugin Name: Play Store App List
Description: Adds widget which contains a list of android apps. Each item contains links directly to the Play Store.
Version:     1.0.0
Author:      Yannick Signer
Author URI:  http://www.halcyon.ch
License:     Apache 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
'wpb_widget',

__('WPBeginner Widget', 'wpb_widget_domain'),

array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), )
);
}

public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance['title'] );
  $pkgnames = explode(";", $instance['pkgnames']);
  echo $args['before_widget'];

  if (!empty( $title )){
    echo $args['before_title'] . $title . $args['after_title'];
  }

  echo '<div style="margin: 0px;margin-left: -20px;padding: 0px;">';
  echo '<ul style="list-style-type: none;width: 300px;">';

  foreach ($pkgnames as $value) {
    $obj = json_decode(file_get_contents("http://halcyon.ch/icon_service/index.php?pkg=" . $value));

    echo '<a href="https://play.google.com/store/apps/details?id='.$value.'" target="_blank">';
    echo '<li style ="padding: 10px;overflow: auto;">';
    echo '<img src="'.$obj->src.'" height="60" width="60" style="float: left;margin: 0 15px 0 0;" />';
    echo '<h4 style="font: bold 14px/1.5 Helvetica, Verdana, sans-serif;">'.$obj->name.'</h4>';
    echo '<p style="font: 10px/1.5 Helvetica, Verdana, sans-serif;">'.$obj->package.'</p>';
    echo '</li>';
    echo '</a>';
  }

  echo '</ul>';
  echo '</div>';

  echo $args['after_widget'];
}

public function form( $instance ) {
  if (isset($instance[ 'title' ])) {
    $title = $instance[ 'title' ];
  }
  else {
    $title = __( 'New title', 'wpb_widget_domain' );
  }

  if (isset($instance[ 'pkgnames' ])) {
    $pkgnames = $instance[ 'pkgnames' ];
  }
  else {
    $pkgnames = __( '', 'wpb_widget_domain' );
  }

?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

<label for="<?php echo $this->get_field_id( 'pkgnames' ); ?>"><?php _e( 'Packagenames:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'pkgnames' ); ?>" name="<?php echo $this->get_field_name( 'pkgnames' ); ?>" type="text" value="<?php echo esc_attr( $pkgnames ); ?>" />
</p>
<?php
}

public function update( $new_instance, $old_instance ) {
  $instance = array();
  $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
  $instance['pkgnames'] = ( ! empty( $new_instance['pkgnames'] ) ) ? strip_tags( $new_instance['pkgnames'] ) : '';
  return $instance;
}

}

function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

?>
