<?php
/**
 * comments.php
 *
 * This template file includes both the comments list and
 * the comment form
 *
 * @package The Black City
 * @since 1.00
 */
?>

<!-- begin comments -->
<?php tbcity_hook_comments_before(); ?>

<?php
	if ( post_password_required() ) {
		echo '<div id="comments">' . __( 'Enter your password to view comments.','tbcity' ) . '</div>';
		return;
	}
?>

<?php if ( comments_open() ) { ?>

	<div id="comments">
		<?php comments_number( __( 'No Comments','tbcity' ), __( '1 Comment','tbcity' ), __( '% Comments','tbcity' ) ); ?><span class="hide_if_print"> - <a href="#respond" title="<?php esc_attr_e( "Leave a comment",'tbcity' ); ?>"><?php _e( "Leave a comment",'tbcity' ); ?></a></span>
	</div>

<?php } elseif ( have_comments() ) { ?>

	<div id="comments">
		<?php comments_number( __( 'No Comments','tbcity' ), __( '1 Comment','tbcity' ), __( '% Comments','tbcity' ) ); ?>
	</div>

<?php } ?>

<?php if ( have_comments() ) { ?>

	<div id="commentlist-wrap">

		<?php tbcity_hook_comments_list_before(); ?>

		<ol id="commentlist">
			<?php wp_list_comments(); ?>
		</ol>

		<?php tbcity_hook_comments_list_after(); ?>

	</div>

<?php } ?>

<?php comment_form(); ?>
<br class="fixfloat" />

<!-- end comments -->

<?php tbcity_hook_comments_after(); ?>
