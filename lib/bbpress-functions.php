<?php
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
