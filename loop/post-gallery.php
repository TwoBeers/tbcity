<?php
/**
 * post-gallery.php
 *
 * Template part file that contains the Gallery Format entry
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
		switch ( tbcity_get_opt( 'post_formats_gallery_title' ) ) {
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
		<?php
			switch ( tbcity_get_opt( 'post_formats_gallery_content' ) ) {
				case 'presentation':
					if ( ! tbcity_gallery_preview() ) the_content();
					break;
				case 'content':
					the_content();
					break;
				case 'excerpt':
					the_excerpt();
					break;
			}
		?>
	</div>

	<?php tbcity_hook_entry_bottom(); ?>

</div>

<?php tbcity_hook_entry_after(); ?>

<?php tbcity_last_comments( get_the_ID() ); ?>
