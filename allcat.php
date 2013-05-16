<?php
/**
 * Template Name: List of Categories
 *
 * allcat.php
 *
 * The template file used to display the whole category list 
 * as a page.
 *
 * @package The Black City
 * @since 1.00
 */


get_header(); ?>

<?php tbcity_hook_content_before(); ?>

<div id="posts_content">

	<?php tbcity_hook_content_top(); ?>

	<?php if ( have_posts() && ! tbcity_is_allcat() ) {

		while ( have_posts() ) {

			the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<div class="post_meta_container"><span class="pmb_format btn"><i class="icon-folder-close"></i></span></div>

				<?php tbcity_featured_title(); ?>

				<div class="storycontent">

					<?php the_content(); ?>

					<ul>
						<?php wp_list_categories( 'title_li=' ); ?>
					</ul>

				</div>

			</div>

		<?php } //end while ?>

	<?php } else { ?>

		<div class="hentry post">

			<div class="post_meta_container"><span class="pmb_format btn"><i class="icon-folder-close"></i></span></div>

			<h2 class="storytitle"><?php _e( 'Categories','tbcity' ); ?></h2>

			<div class="storycontent">
				<ul>
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			</div>

		</div>

	<?php } //endif ?>

	<?php tbcity_hook_content_bottom(); ?>

</div>

<?php tbcity_hook_content_after(); ?>

<?php get_footer(); ?>
