<?php
/**
 * breadcrumb.php
 *
 * The breadcrumb class.
 * Supports Breadcrumb NavXT and Yoast Breadcrumbs plugins.
 *
 * @package The Black City
 * @since 2.00
 */


class Tbcity_Breadcrumb {

	function __construct() {

		add_action( 'tbcity_hook_content_before', array( $this, 'display' ) );

	}


	function display() {

		$output = '';

		if ( function_exists( 'bcn_display' ) ) { // Breadcrumb NavXT

			$output = bcn_display( true );

		} elseif ( function_exists( 'yoast_breadcrumb' ) ) { // Yoast Breadcrumbs

			$output = yoast_breadcrumb( '', '', false );

		}

		if ( $output )
			echo '<div id="breadcrumb-wrap" class="fixfloat">' . $output . '</div>';

	}

}

new Tbcity_Breadcrumb;