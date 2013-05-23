<?php
/**
 * sidebar-single.php
 *
 * Template part file that contains the single post/page widget area
 *
 * @package The Black City
 * @since 1.00
 */
?>

<?php if ( ! is_active_sidebar( 'single-widgets-area' ) ) return; ?>

<?php tbcity_hook_sidebars_before( 'single' ); ?>

<div id="single-widgets-area" class="sidebar">

	<?php tbcity_hook_sidebar_top( 'single' ); ?>

	<?php dynamic_sidebar( 'single-widgets-area' ); ?>

	<br class="fixfloat" />

	<?php tbcity_hook_sidebar_bottom( 'single' ); ?>

</div>

<?php tbcity_hook_sidebars_after( 'single' ); ?>
