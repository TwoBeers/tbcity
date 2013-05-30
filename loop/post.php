<?php
/**
 * post.php
 *
 * Template part file that contains the Standard entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<?php tbcity_hook_post_title_before(); ?>

	<?php
		switch ( tbcity_get_opt( 'post_formats_standard_title' ) ) {
			case 'title':
				tbcity_featured_title();
				break;
			case 'date':
				tbcity_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ) ) );
				break;
			case 'none':
				tbcity_featured_title( array( 'micro' => true ) );
				break;
		}
	?>

	<?php tbcity_hook_post_title_after(); ?>

	<?php get_template_part( 'content', 'standard' ); ?>

	<?php tbcity_hook_entry_bottom(); ?>

</div>

<?php tbcity_hook_entry_after(); ?>
