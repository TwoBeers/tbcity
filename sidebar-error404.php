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

<?php tbcity_hook_sidebars_before( 'error404' ); ?>

<div class="ul_fwa">

	<?php tbcity_hook_sidebar_top( 'error404' ); ?>

	<?php dynamic_sidebar( 'error404-widgets-area' ); ?>

	<?php tbcity_hook_sidebar_bottom( 'error404' ); ?>

</div>

<?php tbcity_hook_sidebars_after( 'error404' ); ?>
