<?php
/**
 * 404.php
 *
 * This file is the Error 404 Page template file, which is output whenever
 * the server encounters a "404 - file not found" error.
 *
 * @package The Black City
 * @since 1.00
 */


get_header(); ?>

<?php tbcity_hook_content_before(); ?>

<div id="posts_content">

	<?php tbcity_hook_content_top(); ?>

	<div class="hentry not-found" id="post-404-not-found">

		<div class="storycontent">

			<h2><i class="icon-32 icon-placeholder"></i> <?php _e( 'Error 404','tbcity' ); ?> - <?php _e( 'Page not found', 'tbcity' ); ?></h2>

			<p><?php _e( "Sorry, you're looking for something that isn't here", 'tbcity' ); ?>: <u><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p>

			<br />

			<?php if ( ! is_active_sidebar( 'error404-widgets-area' ) ) { ?>

				<p><?php _e( "There are several links scattered around the page, maybe they can help you on finding what you're looking for.", 'tbcity' ); ?></p>

				<p><?php _e( 'Perhaps using the search form will help too...', 'tbcity' ); ?></p>

				<?php get_search_form(); ?>

			<?php } ?>

		</div>

		<?php get_sidebar( 'error404' ); ?>

		<br class="fixfloat" />

	</div>

	<?php tbcity_hook_content_bottom(); ?>

</div>

<?php tbcity_hook_content_after(); ?>

<?php get_footer(); ?>
