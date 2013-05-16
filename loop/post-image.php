<?php
/**
 * post-image.php
 *
 * Template part file that contains the Image Format entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<?php tbcity_hook_post_title_before(); ?>

	<?php $bz_first_img = tbcity_get_first_image(); ?>

	<?php
		switch ( tbcity_get_opt( 'post_formats_image_title' ) ) {
			case 'post title':
				tbcity_featured_title();
				break;
			case 'post date':
				tbcity_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ) ) );
				break;
			case 'first image title':
				tbcity_featured_title( array( 'alternative' => $bz_first_img ? $bz_first_img['title'] : '' ) );
				break;
		}
	?>

	<?php tbcity_hook_post_title_after(); ?>

	<div class="storycontent">
		<?php
			switch ( tbcity_get_opt( 'post_formats_image_content' ) ) {
				case 'first image':
					if ( $bz_first_img ) {
						?><a href="<?php echo $bz_first_img['src']; ?>" title="<?php echo esc_attr( $bz_first_img['title'] ); ?>"><?php echo $bz_first_img['img']; ?></a><br /><?php
						the_excerpt();
					} else {
						the_content();
					}
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
