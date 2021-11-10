<?php
/**
 * @package My_Twitch
 * @version 0.0.1
 */
/*
Plugin Name: Simple Twitch Widget
Plugin URI: https://github.com/scawp/wordpress_twitch_plugin
Description: Simple Twitch Widget
Author: scawp
Version: 0.0.1
Author URI: https://github.com/scawp/wordpress_twitch_plugin
*/

class swp_twitch_widget extends WP_Widget {
  
	function __construct() {
		parent::__construct(
			'swp_twitch_widget', //ID
			__('Simple Twitch Widget', 'swp_twitch_widget_domain'), //Widget Name , Domain
			array( 'description' => __( 'Simple Twitch Widget', 'swp_twitch_widget_domain' ), ) 
		);
	}
		
	//Frontend code
	public function widget( $args, $instance ) {
		$widget_title = apply_filters( 'widget_widget_title', $instance['widget_title'] );
		$twitch_username = apply_filters( 'widget_twitch_username', $instance['twitch_username'] );
		$fullscreen = !$instance[ 'disable_fullscreen' ] ? 'true' : 'false';
		$auto_play = !$instance[ 'disable_auto_play' ] ? 'true' : 'false';
		$muted = $instance[ 'start_muted' ] ? 'true' : 'false';

		//The Theme may add code before (and after) the widget is displayed
		echo $args['before_widget'];

		?>
			<!-- Add a placeholder for the Twitch embed -->
			<p class="widget-title"><?php echo $widget_title;  ?></p>
			<div id="<?php echo $this->get_field_id( 'twitch_embed' ); ?>"></div> 

			<script src="https://player.twitch.tv/js/embed/v1.js"></script>
			<script type="text/javascript">
				var player = new Twitch.Player("<?php echo $this->get_field_id( 'twitch_embed' ); ?>", {
					channel: "<?php echo $twitch_username;  ?>",
					allowfullscreen: <?php echo $fullscreen;  ?>,
					layout: "video-with-chat",
					width: 854,
        			height: 480,
					autoplay: <?php echo $auto_play;  ?>,
					muted: <?php echo $muted;  ?>
				});
				
				//addEventListener("Twitch.Player.READY", setVolume);
				//player.addEventListener(Twitch.Player.READY, function() {
				//  player.setVolume(0.1);
				//});

				//player.setVolume(0.1);
			</script>
			
		<?php
		//The Theme may add code (before and) after the widget is displayed
		echo $args['after_widget'];
	}
						
	//Backend code
	public function form( $instance ) {
		$defaults = array( 'widget_title' => __('Live Stream:','swp_twitch_widget_domain'),
											 'twitch_username' => __( 'Channel Name', 'swp_twitch_widget_domain' )
								);
    	$instance = wp_parse_args( ( array ) $instance, $defaults );
    	$instance['disable_fullscreen'] = isset( $instance['disable_fullscreen'] ) ? (bool) $instance['disable_fullscreen'] : false;
	    $instance['disable_auto_play'] = isset( $instance['disable_auto_play'] ) ? (bool) $instance['disable_auto_play'] : false;
	    $instance['start_muted'] = isset( $instance['start_muted'] ) ? (bool) $instance['start_muted'] : false;

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
				<?php _e( 'Widget Title:' ); ?>
			</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo esc_attr( $instance['widget_title'] ); ?>" />

			<br />
			
			<label for="<?php echo $this->get_field_id( 'twitch_username' ); ?>">
				<?php _e( 'Twitch Username:' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitch_username' ); ?>" name="<?php echo $this->get_field_name( 'twitch_username' ); ?>" type="text" value="<?php echo esc_attr( $instance['twitch_username'] ); ?>" />

			<br />

			<input class="checkbox" id="<?php echo $this->get_field_id( 'disable_fullscreen' ); ?>" name="<?php echo $this->get_field_name( 'disable_fullscreen' ); ?>" type="checkbox" <?php checked( $instance[ 'disable_fullscreen' ] ); ?> />
			<label for="<?php echo $this->get_field_id( 'disable_fullscreen' ); ?>">
				<?php _e( 'Disable fullscreen button?' ); ?>
			</label>
			
			<br />

			<input class="checkbox" id="<?php echo $this->get_field_id( 'disable_auto_play' ); ?>" name="<?php echo $this->get_field_name( 'disable_auto_play' ); ?>" type="checkbox" <?php checked( $instance['disable_auto_play'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'disable_auto_play' ); ?>">
				<?php _e( 'Disable auto-play? (if applicable)' ); ?>
			</label>
			
			<br />

			<input class="checkbox" id="<?php echo $this->get_field_id( 'start_muted' ); ?>" name="<?php echo $this->get_field_name( 'start_muted' ); ?>" type="checkbox" <?php checked( $instance['start_muted'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'start_muted' ); ?>">
				<?php _e( 'Start with audio muted?' ); ?>
			</label>
		</p>
		<?php 
	}
				
	//Runs when updating widget
	public function update( $new_instance, $old_instance ) {
		return $new_instance;

		//ignore this for now

		$instance = array();
		$instance['widget_title'] = ( ! empty( $new_instance['widget_title'] ) ) ? strip_tags( $new_instance['widget_title'] ) : '';

		$instance['twitch_username'] = ( ! empty( $new_instance['twitch_username'] ) ) ? strip_tags( $new_instance['twitch_username'] ) : '';
		return $instance;
	}
} 
	 	 
//Register and add the widget
function load_swp_twitch_widget() {
	register_widget( 'swp_twitch_widget' );
}
add_action( 'widgets_init', 'load_swp_twitch_widget' );


/*
 * Declare Twitch oEmbed Gutenberg Block and add assets
 */
function twitch_enqueue_scripts() {
	wp_register_script(
		'my-twitch-block-editor',
		plugins_url( 'my-twitch-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'my-twitch-block.js' )
	);
	register_block_type( 'my-twitch-block', array('editor_script' => 'my-twitch-block-editor'));
}
add_action( 'init', 'twitch_enqueue_scripts' );