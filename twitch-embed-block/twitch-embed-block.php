<?php
/**
 * @link              https://github.com/scawp/wordpress_twitch_plugin
 * @since             0.1
 * @package           Twitch Embed Block
 *
 * Plugin Name:       Twitch Embed Block
 * Plugin URI:        https://github.com/scawp/wordpress_twitch_plugin
 * Description:       An Embed Block for twitch
 * Version:           0.1
 * Author:            scawp
 * Author URI:        https://github.com/scawp/wordpress_twitch_plugin
 * License:           MIT
 * Text Domain:       twitch-embed-block
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function embed_twitch_embed_video( $attributes ) {
	$twitch_channel = trim( $attributes['twitch_channel'] );
	$content = '';
	if ( '' === trim( $twitch_channel ) ) {
		$content = '<p>' . esc_html__( 'Use the Sidebar to add the Channel of your Twitch Stream.', 'embed-block-for-twitch' ) . '</p>';
	} else {
		$content = 	'<p class="widget-title">'.  $widget_title .' </p>
			<div id="'. ( "twitch_embed" ) .'"></div> 
			<script src="https://player.twitch.tv/js/embed/v1.js"></script>
			<script type="text/javascript">
				var player = new Twitch.Player("'. ( "twitch_embed" ) .'", {
					channel: "'.$twitch_channel.'",
					width: 800,
					height: 600
				});
			</script>';
	}
	return apply_filters( 'the_content', $content );
}

/*
 * Declare Twitch oEmbed Gutenberg Block and add assets
 */
function embed_twitch_enqueue_scripts() {
	wp_register_script(
		'embed_twitch-video-editor',
		plugins_url( 'twitch-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'twitch-block.js' )
	);
	register_block_type( 'embed-block-for-twitch/video', array(
		'editor_script'   => 'embed_twitch-video-editor',
		'render_callback' => 'embed_twitch_embed_video',
		'attributes'      => array(
			'twitch_channel' => array( 'type' => 'string' ),
		),
	) );
}
add_action( 'init', 'embed_twitch_enqueue_scripts' );
