<?php
/**
 * custom-header.php
 *
 * The custom header support
 *
 * @package The Black City
 * @since 2.04
 */



class Tbcity_Parallax_Header {

	function __construct() {

		add_action( 'wp_enqueue_scripts'	, array( $this, 'script' ) );
		add_action( 'wp_enqueue_scripts'	, array( $this, 'stylesheet' ) );
		add_filter( 'tbcity_filter_header'	, array( $this, 'parallax_header' ) );
		add_action( 'wp_head'				, array( $this, 'daylight_to_css' ) );

	}

	function stylesheet() {

		wp_enqueue_style( 'tbcity-parallax-header-style', get_template_directory_uri() . '/lib/header/parallax-header.css', false, tbcity_get_info( 'version' ), 'screen' );

	}

	function script() {

		wp_enqueue_script( 'tbcity-parallax-header-script', get_template_directory_uri() . '/lib/header/parallax-header.dev.js', array('jquery'), tbcity_get_info( 'version' ), true );

	}

	function daylight_to_css() {

		$data = array();

		$data['latitude']			= tbcity_get_opt( 'latitude' );
		$data['longitude']			= tbcity_get_opt( 'longitude' );
		$data['zenith']				= 91; //The best Overall figure for zenith is 90+(50/60) degrees for true sunrise/sunset; Civil twilight 96 degrees; Nautical twilight 102 degrees; Astronical twilight at 108 degrees;
		$data['offset']				= get_option( 'gmt_offset' ); //WP offset setting
		$data['timestamp']			= current_time( 'timestamp', 0 ); //WP current time
		$data['day']				= strftime( "%Y %m %d", $data['timestamp'] );

		$data['time']				= strftime( "%H:%M", $data['timestamp'] );
		$data['sunrise']			= date_sunrise( $data['timestamp'], SUNFUNCS_RET_STRING, $data['latitude'], $data['longitude'], $data['zenith'], $data['offset'] );
		$data['sunset']				= date_sunset( $data['timestamp'], SUNFUNCS_RET_STRING, $data['latitude'], $data['longitude'], $data['zenith'], $data['offset'] );

		$data['time_parsed']		= date_parse_from_format('H:i',$data['time']);
		$data['sunrise_parsed']		= date_parse_from_format('H:i',$data['sunrise']);
		$data['sunset_parsed']		= date_parse_from_format('H:i',$data['sunset']);

		$data['time_decimal']	= $data['time_parsed']['hour'] + ( $data['time_parsed']['minute'] / 60  ) ;
		$data['sunrise_decimal']	= $data['sunrise_parsed']['hour'] + ( $data['sunrise_parsed']['minute'] / 60 ) ;
		$data['sunset_decimal']	= $data['sunset_parsed']['hour'] + ( $data['sunset_parsed']['minute'] / 60 ) ;

		$output = '';

		// the sun
		if ( ( $data['time_decimal'] < $data['sunrise_decimal'] ) || ( $data['time_decimal'] > $data['sunset_decimal'] ) ) {
			$style = 'display: none;';
		} else {
			$percent = ( $data['time_decimal'] - $data['sunrise_decimal'] ) / ( $data['sunset_decimal'] - $data['sunrise_decimal'] );
			$style = 'background-position: ' . round( 5 + 90 * $percent ) . '% ' . round( -200 * sin( deg2rad( 180 * $percent ) ) + 180 ) . 'px;';
		}

		$output .= '.sun {'.$style.'}';

		// the moon
		if ( ( $data['time_decimal'] > $data['sunrise_decimal'] ) && ( $data['time_decimal'] < $data['sunset_decimal'] ) ) {
			$style = 'display: none;';
		} else {
			$nightpos = ( $data['time_decimal'] < $data['sunrise_decimal'] ) ? 24 + $data['time_decimal'] : $data['time_decimal'];
			$percent = ( $nightpos - $data['sunset_decimal'] ) / ( 24 - $data['sunset_decimal'] + $data['sunrise_decimal'] );
			$style = 'background-position: ' . round( 5 + 90 * $percent ) . '% ' . round( -150 * pow( sin( deg2rad( 180 * $percent ) ), 3 ) + 180 ) . 'px;';
		}

		$output .= '.moon {'.$style.'}';

		// the stars
		if ( ( $data['time_decimal'] > $data['sunrise_decimal'] ) && ( $data['time_decimal'] < $data['sunset_decimal'] ) ) {
			$style = 'display: none;';
		} else {
			$nightpos = ( $data['time_decimal'] < $data['sunrise_decimal'] ) ? 24 + $data['time_decimal'] : $data['time_decimal'];
			$percent = ( $nightpos - $data['sunset_decimal'] ) / ( 24 - $data['sunset_decimal'] + $data['sunrise_decimal'] );
			$style = 'opacity: ' . $percent . '; filter: alpha(opacity=' . round( $percent * 100 ) . ');';
		}

		$output .= '.stars {'.$style.'}';

		// the clouds
		if ( ( $data['time_decimal'] < ( $data['sunrise_decimal'] - 1 ) ) || ( $data['time_decimal'] > ( $data['sunset_decimal'] + 1 ) ) ) {
			$multiplier = 0;
		} else {
			$percent = ( $data['time_decimal'] - $data['sunrise_decimal'] + 1 ) / ( $data['sunset_decimal'] - $data['sunrise_decimal'] + 2 );
			$multiplier = 0.9 * pow( sin( deg2rad( 180 * $percent ) ), 1 );
		}
		$multiplier = 0.1 + $multiplier;
		$style = 'opacity: ' . $multiplier . '; filter: alpha(opacity=' . round( $multiplier * 100 ) . ');';

		$output .= '.clouds {'.$style.'}';

		// the sky
		if ( ( $data['time_decimal'] < ( $data['sunrise_decimal'] - 1 ) ) || ( $data['time_decimal'] > ( $data['sunset_decimal'] + 1 ) ) ) {
			$light_multiplier = 0;
			$red_multiplier = 0;
		} else {
			$percent = ( $data['time_decimal'] - $data['sunrise_decimal'] + 1 ) / ( $data['sunset_decimal'] - $data['sunrise_decimal'] + 2 );
			$light_multiplier = pow( sin( deg2rad( 180 * $percent ) ), 0.3 );
			$red_multiplier = min( abs( $data['time_decimal'] - $data['sunrise_decimal'] ),  abs( $data['time_decimal'] - $data['sunset_decimal'] ) );
			$red_multiplier = ( $red_multiplier < 1 ) ? pow( 1 - $red_multiplier, 3 ) : 0;
		}
		$style = 'background-color: #' . dechex( round ( ( 13 * $light_multiplier ) + ( 7 * $red_multiplier ) ) ) . dechex( 1 + round ( 13 * $light_multiplier ) + ( -1 * $red_multiplier ) ) . dechex( 2 + round ( 13 * $light_multiplier ) ) . ';';

		$output .= '.parallax {'.$style.'}';

		echo '<style>' . $output . '</style>';

	}

	function parallax_header() {

		$site_title = '<h1><a href="' . home_url() . '/">' . get_bloginfo( 'name' ) . '</a></h1>';

?>
	<div class="parallax">
		<div class="stars"></div>
		<div class="sun"></div>
		<div class="moon"></div>
		<div class="mouse-move clouds"></div>
		<div class="mouse-move city city-layer1"></div>
		<div class="mouse-move city city-layer2"></div>
		<div class="site-title"><?php echo $site_title; ?></div>
	</div>
<?php

	return '';

	}
}

new Tbcity_Parallax_Header;

