	<div id="sidebar-left" class="sidebar">
		<div class="search-form">
			<?php $search_text = __("Search...",'tbcity'); ?>
			<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
				<input type="text" value="<?php echo $search_text; ?>" name="s" id="s" onblur="if (this.value == '')
				{this.value = '<?php echo $search_text; ?>';}"
				onfocus="if (this.value == '<?php echo $search_text; ?>')
				{this.value = '';}" />
				<input type="hidden" id="searchsubmit" />
			</form>
		</div>
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
		<div class="side-left">
			<h4>Meta</h4>
			<ul>
				<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo __('Syndicate this site using RSS 2.0'); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo __('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
				<li><a href="http://wordpress.org/" title="<?php echo __('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>">WordPress.org</a></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>    <!-- END SUB_CONTENT -->
	<div id="sidebar-right" class="sidebar">
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
		<div class="side-right" style="padding-top: 10px;">
			<?php get_calendar(); ?>
		</div>
		<?php endif; ?>
	</div>