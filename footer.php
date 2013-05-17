<?php
/**
 * footer.php
 *
 * Template part file that contains the site footer and
 * closing HTML body elements
 *
 * @package The Black City
 * @since 1.00
 */
?>


			</div><!-- close content -->
			<?php get_sidebar(); // show primary widgets area ?>
			<br class="fixfloat" />
		</div><!-- close main -->
<!-- begin footer -->

			<?php tbcity_hook_footer_before(); ?>

			<div id="footer">

				<?php tbcity_hook_footer_top(); ?>

				<?php get_sidebar( 'footer' ); // show footer widgets area ?>

				<div id="credits">
					<?php echo tbcity_get_credits(); ?>
				</div>

				<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

				<?php tbcity_hook_footer_bottom(); ?>

			</div><!-- close footer -->

			<?php tbcity_hook_footer_after(); ?>

		<?php tbcity_hook_body_bottom(); ?>

		<?php wp_footer(); ?>

	</body>

</html>