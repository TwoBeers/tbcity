<?php
/**
 * index.php
 *
 * This file is the master/default template file, used for Inedx/Archives/Search
 *
 * @package The Black City
 * @since 1.00
 */


get_header(); ?>

<?php tbcity_hook_content_before(); ?>

<div id="posts_content">

	<?php tbcity_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'index' ); ?>

	<?php tbcity_hook_content_bottom(); ?>

</div>

<?php tbcity_hook_content_after(); ?>

<?php get_footer(); ?>
