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

	<?php if ( have_posts() && ( 1 === tbcity_is_allcat() ) ) {

		while ( have_posts() ) {

			the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<?php tbcity_featured_title( array( 'featured' => false, 'alternative' => '<i class="icon-folder-close"></i> ' . get_the_title() ) ); ?>

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

			<?php tbcity_featured_title( array( 'featured' => false, 'alternative' => '<i class="icon-folder-close"></i> ' . __( 'Categories','tbcity' ) ) ); ?>

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
