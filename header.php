<?php
/**
 * header.php
 *
 * Template part file that contains the HTML document head and 
 * opening HTML body elements, as well as the site header and 
 * the "breadcrumb" bar.
 *
 *
 * @package The Black City
 * @since 1.00
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?> itemscope itemtype="http://schema.org/Blog">

	<head profile="http://gmpg.org/xfn/11">

		<?php tbcity_hook_head_top(); ?>

		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

		<?php tbcity_hook_head_bottom(); ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php tbcity_hook_body_top(); ?>

		<?php tbcity_hook_header_before(); ?>

		<div id="head">

			<?php tbcity_hook_header_top(); ?>

			<?php echo tbcity_get_header(); ?>

			<?php tbcity_hook_header_bottom(); ?>

		</div>

		<?php tbcity_hook_header_after(); ?>

		<div id="main">

			<div id="content">
