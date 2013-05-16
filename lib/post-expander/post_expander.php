<?php
/**
 * post_expander.php
 *
 * @package The Black City
 * @since 2.00
 */


class Tbcity_Post_Expander {

	function __construct() {

		add_action( 'init'					, array( $this, 'check_post_vars' ) );
		add_action( 'wp_enqueue_scripts'	, array( $this, 'stylesheet' ) );
		add_action( 'wp_enqueue_scripts'	, array( $this, 'script' ) );

	}

	function stylesheet() {

		wp_enqueue_style( 'tb-post-expander-style', get_template_directory_uri() . '/lib/post_expander/post_expander.css', false, tbcity_get_info( 'version' ), 'all' );

	}

	function script() {

		wp_enqueue_script( 'tb-post-expander', get_template_directory_uri() . '/lib/post_expander/post_expander.dev.js', array( 'jquery' ), tbcity_get_info( 'version' ), true );
		$data = array(
			'post_expander' => esc_js( __( 'Post loading, please wait...','tbcity' ) ),
		);
		wp_localize_script( 'tb-post-expander', 'tb_post_expander_i10n', $data );

	}

	function check_post_vars ( ) {

		if ( isset( $_POST["tbcity_post_expander"] ) )
			add_action( 'wp', 'get_post' );

	}

	function get_post (  ) {

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}

		die();

	}


}

new Tbcity_Post_Expander;
