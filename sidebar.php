<?php
/**
 * sidebar.php
 *
 * Template part file that contains the default sidebar content
 *
 * @package The Black City
 * @since 1.00
 */
?>

<?php if ( tbcity_get_opt( 'sidebar_primary' ) == 'hidden' ) return; ?>

<?php tbcity_hook_sidebars_before( 'primary' ); ?>

<!-- begin primary sidebar -->

<div class="sidebar" id="sidebar-primary">

	<div class="inner">

		<?php tbcity_hook_sidebar_top( 'primary' ); ?>

		<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) { //if the widget area is empty, we print some standard wigets ?>

			<?php tbcity_default_widgets(); ?>

		<?php } ?>

		<br class="fixfloat" />

		<?php tbcity_hook_sidebar_bottom( 'primary' ); ?>

	</div>

</div>

<!-- end primary sidebar -->

<?php tbcity_hook_sidebars_after( 'primary' ); ?>
