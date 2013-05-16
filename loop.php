<?php
/**
 * loop.php
 *
 * The main loop that displays posts.
 *
 *
 * @package The Black City
 * @since 2.04
 */
?>

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post();

		get_template_part( 'loop/post', tbcity_get_post_format( $post->ID ) );

	} //end while ?>

	<?php tbcity_navigate_archives(); ?>

<?php } else { ?>

	<?php get_template_part( 'loop/post-none' ); ?>

<?php } //endif ?>
