			<?php get_sidebar(); ?>
			
			<div id="footer"> <!-- start footer container -->
				<div id="credits">
					<div id="f_btitle"><h1 style="text-align:center;"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1></div><hr>
					<div id="f_credits">
					<a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>" rel="nofollow"><div id="f_rss"></div></a>
					<div id="f_wplogo"></div>
						<small><?php _e('All logos, trademarks and posts in this site are property of their respective owners, all the rest &copy;','tbcity'); ?> <?php echo date("Y"); ?>  <strong><?php bloginfo('name'); ?></strong> <?php _e('All rights reserved','tbcity'); ?><br />
						TBCity theme v<?php $theme_data = get_theme_data(get_bloginfo('stylesheet_url')); echo $theme_data['Version']; ?> by <a href="http://www.twobeers.net/" title="TB Project Blog">TwoBeers Crew</a></small>
					</div>
					<?php wp_footer(); ?>
				</div>
			</div> <!-- end footer container -->
		</div> <!-- end main container -->
	</div> <!-- end wrap -->
</body>
</html>