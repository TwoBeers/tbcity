<?php
/**
 * sidebar-error404.php
 *
 * Template part file that contains the 404 page widget area
 *
 * @package The Black City
 * @since 2.05
 */
?>

<?php if ( ! is_active_sidebar( 'error404-widgets-area' ) ) return; ?>

<?php tbcity_hook_sidebars_before( 'error404' ); ?>

<div id="error404-widgets-area" class="sidebar">

	<?php tbcity_hook_sidebar_top( 'error404' ); ?>

	<p><?php _e( 'Here is something that might help:', 'tbcity' ); ?></p>

	<?php dynamic_sidebar( 'error404-widgets-area' ); ?>

	<br class="fixfloat" />

	<?php tbcity_hook_sidebar_bottom( 'error404' ); ?>

</div>

<?php tbcity_hook_sidebars_after( 'error404' ); ?>
