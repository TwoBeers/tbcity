<?php get_header(); ?>
	<div class="commentsblock"> <!-- Trackbacks -->
		<h3 class="content_headings h_warning"><?php _e('Page not found'); ?></h3>
		<?php tbcity_bot_msg('
			<p>'. __('Sorry, but you are looking for something that isn&#8217;t here.','tbcity') . ' ' . __('Try this:','tbcity') . '</p>
			<ul>
				<li>'. __('Visit the','tbcity') . '  <a href="' . get_bloginfo('url') . ' ">home page</a></li>
				<li>'. __('Search the site using the search box on the left','tbcity') . ' </li>
				<li>'. __('Navigate through the blog by the menu on the top','tbcity') . ' </li>
			</ul>
		'); ?>

	</div>
			<div id="nav-global"></div>
		</div>
<?php get_footer(); ?>