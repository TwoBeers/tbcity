<?php
/**
 * jetpack.php
 *
 * Jetpack support
 *
 * @package The Black City
 * @since 2.05
 */


class tbcity_For_Jetpack {

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}


	function init() {

		if ( tbcity_is_mobile() ) return;

		//Sharedaddy
		if ( function_exists( 'sharing_display' ) ) {
			remove_filter( 'the_content'			, 'sharing_display', 19 );
			remove_filter( 'the_excerpt'			, 'sharing_display', 19 );
			add_action( 'tbcity_hook_entry_bottom'	, array( $this, 'sharedaddy_display' ) );
			add_filter( 'tbcity_option_override'	, array( $this, 'sharedaddy_skip_options' ), 10, 2 );
		}

		//Likes
		if ( class_exists( 'Jetpack_Likes' ) ) {
			add_action		( 'tbcity_hook_entry_bottom'	, array( $this, 'likes' ) );
			remove_filter	( 'the_content'					, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
			add_filter		( 'tbcity_filter_likes'		, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
		}

		//Infinite Scroll
		$type = tbcity_get_opt( 'infinite_scroll_type' ) == 'auto' ? 'scroll' : 'click';
		add_theme_support( 'infinite-scroll', array(
			'type'		=> $type,
			'container'	=> 'posts_content',
			'render'	=> array( $this, 'infinite_scroll_render' ),
		) );

		if ( class_exists( 'The_Neverending_Home_Page' ) ) {
			add_filter( 'tbcity_option_override'	, array( $this, 'infinite_scroll_skip_options' ), 10, 2 );
			add_filter( 'infinite_scroll_results'	, array( $this, 'infinite_scroll_encode' ), 11, 1 );
		}

		//Carousel
		if ( class_exists( 'Jetpack_Carousel' ) ) {
			remove_filter( 'post_gallery'			, 'tbcity_gallery_shortcode', 10, 2 );
			add_filter( 'tbcity_option_override'	, array( $this, 'carousel_skip_options' ), 10, 2 );
		}

	}


	//print the "likes" button after post content
	function likes() {

		echo '<br class="fixfloat">' . apply_filters('tbcity_filter_likes','') . '<br class="fixfloat">';

	}


	//Set the code to be rendered on for calling posts,
	function infinite_scroll_render() {

		if ( isset( $_GET['page'] ) && $page = (int) $_GET['page'] )
			echo '<div class="page-reminder"><span>' . sprintf( __('Page %s','tbcity'), $page ) . '</span></div>';

		get_template_part( 'loop' );
	}


	//skip the built-in infinite-scroll feature
	function infinite_scroll_skip_options( $value, $name ) {

		if ( 'infinite_scroll' === $name ) return false;

		return $value;

	}


	//encodes html result to UTF8 (jetpack bug?)
	//http://localhost/wordpress/?infinity=scrolling&action=infinite_scroll&page=5&order=DESC
	function infinite_scroll_encode( $results ) {

		$results['html'] = utf8_encode( utf8_decode( $results['html'] ) );

		return $results;
	}


	//skip the Google+ option
	function sharedaddy_skip_options( $value, $name ) {

		if ( 'plusone' === $name ) return false;

		return $value;

	}


	//print the sharedaddy buttons after post content
	function sharedaddy_display() {

		echo sharing_display();

	}


	//skip the thickbox js module
	function carousel_skip_options( $value, $name ) {

		if ( 'js_thickbox' === $name ) return false;

		return $value;

	}

}

new tbcity_For_Jetpack;