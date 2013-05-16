<?php
/**
 * audio-player.php
 *
 * the html5/swf audio player code
 *
 * @package The Black City
 * @since 2.00
 */


class tbcity_Audio_Player {

	function __construct() {

		add_action( 'template_redirect', array( $this, 'append_to_entry' ) );

	}


	function append_to_entry(){

		if ( is_admin() || tbcity_is_mobile() || ! tbcity_get_opt( 'js_swfplayer' ) || ! is_single() ) return;

		add_action( 'tbcity_hook_entry_bottom', array( $this, 'audio_player' ) );

	}


	function scripts(){

		wp_enqueue_script( 'tbcity-audioplayer-script', get_template_directory_uri() . '/js/audio-player.dev.js', array( 'jquery', 'swfobject' ), tbcity_get_info( 'version' ), true );

		$data = array(
			'unknown_media' => esc_attr( __( 'unknown media format', 'tbcity' ) ),
			'player_path' => get_template_directory_uri().'/resources/audio-player/player.swf',
		);
		wp_localize_script( 'tbcity-audioplayer-script', 'tbcityAudioPlayer_l10n', $data );

	}


	function audio_player( $text = '' ) {
		global $post;

		if ( post_password_required() ) return;

		$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.(mp3|ogg|m4a)))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";

		if ( $text != '')
			preg_match_all( $pattern, $text, $result );
		elseif ( is_attachment() )
			preg_match_all( $pattern, wp_get_attachment_link( $post->ID ), $result );
		else
			preg_match_all( $pattern, $post->post_content, $result );

		if ( $result[0] )
			self::scripts(); // Add js

		$instance = 0;

		foreach ($result[0] as $key => $value) {
			$instance++;

?>
	<div class="bz-player-container">
		<small><?php echo $result[0][$key];?></small>
		<div class="bz-player-content">
			<audio controls="" id="sw-player-<?php echo $instance . '-' . $post->ID; ?>" class="no-player">
				<source src="<?php echo $result[3][$key];?>" />
				<span class="bz-player-notice"><?php _e( 'this audio type is not supported by your browser','tbcity' ); ?></span>
			</audio>
		</div>
	</div>
<?php

		}
	}

}

new tbcity_Audio_Player;
