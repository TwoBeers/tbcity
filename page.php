<?php
/**
 * page.php
 *
 * The single page template file, used to display single pages.
 *
 * @package The Black City
 * @since 1.00
 */


get_header(); ?>

<?php tbcity_hook_content_before(); ?>

<div id="posts_content">

	<?php tbcity_hook_content_top(); ?>

	<?php if ( have_posts() ) {

		while ( have_posts() ) {

			the_post(); ?>

			<?php tbcity_hook_entry_before(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<?php tbcity_hook_entry_top(); ?>

				<?php tbcity_hook_post_title_before(); ?>

				<?php tbcity_featured_title( array( 'featured' => true ) ); ?>

				<?php tbcity_hook_post_title_after(); ?>

				<?php get_template_part( 'content', 'standard' ); ?>

				<?php tbcity_hook_entry_bottom(); ?>

			</div>

			<?php tbcity_hook_entry_after(); ?>

			<?php comments_template(); // Get comments.php template ?>

		<?php } //end while ?>

	<?php } else { ?>

		<?php get_template_part( 'loop/post-none' ); ?>

	<?php } //endif ?>

	<?php tbcity_hook_content_bottom(); ?>

</div>

<?php tbcity_hook_content_after(); ?>

<?php get_footer(); ?>
