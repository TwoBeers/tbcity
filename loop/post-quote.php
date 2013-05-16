<?php
/**
 * post-quote.php
 *
 * Template part file that contains the Quote Format entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<?php $bz_first_quote = tbcity_get_blockquote(); ?>

	<?php tbcity_hook_post_title_before(); ?>

	<?php
		switch ( tbcity_get_opt( 'post_formats_quote_title' ) ) {
			case 'post title':
				tbcity_featured_title();
				break;
			case 'post date':
				tbcity_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ) ) );
				break;
			case 'short quote excerpt':
				tbcity_featured_title( array( 'alternative' => $bz_first_quote ? '&ldquo;'.$bz_first_quote['quote'].'&rdquo;' : wp_trim_words( $post->post_content, 5, '...' ) ) );
				break;
		}
	?>

	<?php tbcity_hook_post_title_after(); ?>

	<div class="storycontent">
		<?php
			switch ( tbcity_get_opt( 'post_formats_quote_content' ) ) {
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
