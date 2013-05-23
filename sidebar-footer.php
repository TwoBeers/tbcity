<?php
/**
 * sidebar-footer.php
 *
 * Template part file that contains the footer widget area
 *
 * @package The Black City
 * @since 1.00
 */
?>

<?php if ( ! is_active_sidebar( 'footer-widgets-area' ) ) return; ?>

<?php tbcity_hook_sidebars_before( 'footer' ); ?>

<div id="footer-widgets-area" class="sidebar">

	<?php tbcity_hook_sidebar_top( 'footer' ); ?>

	<?php dynamic_sidebar( 'footer-widgets-area' ); ?>

	<br class="fixfloat" />

	<?php tbcity_hook_sidebar_bottom( 'footer' ); ?>

</div>

<?php tbcity_hook_sidebars_after( 'footer' ); ?>
