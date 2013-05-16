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
			case 'post title':
				tbcity_featured_title();
				break;
			case 'post date':
				tbcity_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ) ) );
				break;
		}
	?>

	<?php tbcity_hook_post_title_after(); ?>

	<div class="storycontent">

		<?php tbcity_hook_entry_content_top(); ?>

		<?php
			switch ( tbcity_get_opt( 'post_formats_standard_content' ) ) {
				case 'content':
					the_content();
					break;
				case 'excerpt':
					the_excerpt();
					break;
			}
		?>

		<?php tbcity_hook_entry_content_bottom(); ?>

	</div>

	<?php tbcity_hook_entry_bottom(); ?>

</div>

<?php tbcity_hook_entry_after(); ?>

<?php tbcity_last_comments( get_the_ID() ); ?>
