<?php
/**
 * plugin.php
 *
 * Plugins support
 *
 * @package The Black City
 * @since 2.00
 */


/**
 * Functions and hooks for Jetpack integration
 */
class TBCity_For_Jetpack {

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}


	function init() {

		if ( tbcity_is_mobile() ) return;

		//Sharedaddy
		if ( function_exists( 'sharing_display' ) ) {
			remove_filter	( 'the_content'							, 'sharing_display', 19 );
			remove_filter	( 'the_excerpt'							, 'sharing_display', 19 );
			add_action		( 'tbcity_hook_entry_content_bottom'	, array( $this, 'sharedaddy_display' ) );
			add_filter		( 'tbcity_option_tbcity_plusone'		, '__return_false' );
		}

		//Likes
		if ( class_exists( 'Jetpack_Likes' ) ) {
			add_action		( 'tbcity_hook_entry_content_bottom'	, array( $this, 'likes' ) );
			remove_filter	( 'the_content'							, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
			add_filter		( 'tbcity_filter_likes'					, array( Jetpack_Likes::init(), 'post_likes' ), 30, 1);
		}

		//Infinite Scroll
		add_theme_support( 'infinite-scroll', array(
			'type'		=> 'click',
			'container'	=> 'posts_content',
			'render'	=> array( $this, 'infinite_scroll_render' ),
		) );

		if ( class_exists( 'The_Neverending_Home_Page' ) ) {
			add_filter		( 'infinite_scroll_results'				, array( $this, 'infinite_scroll_encode' ), 11, 1 );
		}

	}


	//Set the code to be rendered on for calling posts,
	function infinite_scroll_render() {

		get_template_part( 'loop' );

	}


	//encodes html result to UTF8 (jetpack bug?)
	//http://localhost/wordpress/?infinity=scrolling&action=infinite_scroll&page=5&order=DESC
	function infinite_scroll_encode( $results ) {

		$results['html'] = utf8_encode( utf8_decode( $results['html'] ) );

		return $results;
	}


	//print the "likes" button after post content
	function likes() {

		echo apply_filters('tbcity_filter_likes','');

	}


	//print the sharedaddy buttons after post content
	function sharedaddy_display() {

		echo sharing_display();

	}

}

new TBCity_For_Jetpack;



/**
 * Functions and hooks for bbPress integration
 */
class TBCity_bbPress {

	function __construct() {

		add_action( 'wp_head', array( $this, 'init' ), 999 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! ( function_exists( 'is_bbpress' ) && is_bbpress() ) ) return;

		remove_action( 'tbcity_hook_entry_before'					, 'tbcity_single_nav' );
		remove_action( 'tbcity_hook_entry_after'					, 'tbcity_single_widgets_area', 99 );
		remove_action( 'tbcity_hook_entry_content_bottom'			, 'tbcity_last_comments', 999 );
		remove_action( 'tbcity_hook_entry_content_bottom'			, 'tbcity_link_pages' );
		remove_action( 'tbcity_hook_post_title_top'					, 'tbcity_entry_date' );
		remove_action( 'tbcity_hook_post_title_wrap_after'			, 'tbcity_extrainfo' );
		remove_action( 'tbcity_hook_post_title_wrap_top'			, 'tbcity_entry_thumb' );


	}

}

new TBCity_bbPress;



/**
 * Functions and hooks for BuddyPress integration
 */
 
class TBCity_BuddyPress {

	function __construct() {

		add_action( 'wp_head', array( $this, 'init' ), 999 );

	}

	/**
	 * Filters and hooks initialization
	 */
	function init() {

		if ( ! ( function_exists( 'is_buddypress' ) && is_buddypress() ) ) return;

		add_filter( 'tbcity_skip_widget_post_details'	, '__return_true' );
		add_filter( 'tbcity_skip_widget_share_this'		, '__return_true' );

	}

}

new TBCity_BuddyPress;

