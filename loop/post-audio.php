<?php
/**
 * post-audio.php
 *
 * Template part file that contains the Audio Format entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<?php tbcity_hook_post_title_before(); ?>

	<?php $bz_first_link = tbcity_get_first_link(); ?>

	<?php
		switch ( tbcity_get_opt( 'post_formats_audio_title' ) ) {
			case 'post title':
				tbcity_featured_title();
				break;
			case 'post date':
				tbcity_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ) ) );
				break;
			case 'first link text':
				tbcity_featured_title( array( 'alternative' => $bz_first_link ? $bz_first_link['text'] : '' ) );
				break;
		}
	?>

	<?php tbcity_hook_post_title_after(); ?>

	<div class="storycontent">
		<?php
			switch ( tbcity_get_opt( 'post_formats_audio_content' ) ) {
				case 'audio player':
					( $bz_first_link ) ? tbcity_Audio_Player::audio_player( $bz_first_link['anchor'] ) : the_content();
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

