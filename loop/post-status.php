<?php
/**
 * post-status.php
 *
 * Template part file that contains the Status Format entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<div class="storycontent">
		<?php echo get_avatar( $post->post_author, 50, $default=get_option('avatar_default'), get_the_author() ); ?>
		<div class="status-subcont">
			<div class="details"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( printf( __( 'View all posts by %s', 'tbcity' ), get_the_author() ) ); ?>"><?php echo get_the_author(); ?></a> - <?php echo tbcity_friendly_date(); ?></div>
			<?php the_content(); ?>
			<br class="fixfloat" />
		</div>
	</div>

	<?php tbcity_hook_entry_bottom(); ?>

</div>

<?php tbcity_hook_entry_after(); ?>
