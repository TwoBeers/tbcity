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

		//Infinite Scroll
		add_theme_support( 'infinite-scroll', array(
			'type'		=> 'click',
			'container'	=> 'posts_content',
			'render'	=> array( $this, 'infinite_scroll_render' ),
		) );

		if ( class_exists( 'The_Neverending_Home_Page' ) ) {
			add_filter( 'infinite_scroll_results'	, array( $this, 'infinite_scroll_encode' ), 11, 1 );
		}

		//Carousel
		if ( class_exists( 'Jetpack_Carousel' ) ) {
			remove_filter( 'post_gallery'			, 'tbcity_gallery_shortcode', 10, 2 );
		}

	}


	//print the "likes" button after post content
	function likes() {

		echo '<br class="fixfloat">' . apply_filters('tbcity_filter_likes','') . '<br class="fixfloat">';

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

}

new tbcity_For_Jetpack;