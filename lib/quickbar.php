<?php
/**
 * quickbar.php
 *
 * the quickbar
 *
 * @package The Black City
 * @since 2.00
 */


function tbcity_quickbar() {

?>
	<div id="dropdown" class="css"> <!-- start dropdown menu -->
			<div id="dropper"><span><i class="icon-reorder"></i> Menu</span></div>
			<div id="cssmenu">
				<div class="menuitem"><span class="menuitem-trigger"><i class="icon-user"></i> <?php _e('User','tbcity'); //user ?> </span>
					<div id="mainContainer1" class="ddmcontent">
						<ul id="usertools">
							<li id="logged">
								<?php
								if (is_user_logged_in()) {
									global $current_user;
									get_currentuserinfo();
									echo get_avatar($current_user->user_email, 50, $default=get_bloginfo('stylesheet_directory').'/images/user.png');
									printf(__('Logged in as %s','tbcity'), '<strong>'.$current_user->display_name.'</strong>');
								}else{
									echo get_avatar('', 50, $default=get_bloginfo('stylesheet_directory').'/images/user.png');
									echo __('Not logged in','tbcity');
								}
								?>
							</li>
							<?php wp_register(); ?>
							<?php if (is_user_logged_in()) {?>
							<li><a href="<?php echo get_option('siteurl')?>/wp-admin/profile.php"><?php _e('Your Profile'); ?></a></li>
							<li><a title="<?php _e('Add New Post'); ?>" href="<?php bloginfo("url")?>/wp-admin/post-new.php"><?php _e('New Post'); ?></a></li>
							<?php } ?>
							<li><?php wp_loginout(); ?></li>
						</ul>
						<br class="fixfloat" />
					</div>
				</div>
				<div class="menuitem"><span class="menuitem-trigger"><i class="icon-file-alt"></i> <?php _e('Recent Posts'); //Recent Posts ?></span>
					<div id="mainContainer3" class="ddmcontent">
						<ul>
							<?php tbcity_recent_entries(); ?>
						</ul>
						<br class="fixfloat" />
					</div>
				</div>
				<div class="menuitem"><span class="menuitem-trigger"><i class="icon-comment"></i> <?php _e('Recent Comments'); // Recent Comments ?></span>
					<div id="mainContainer" class="ddmcontent">
						<ul>
							<?php tbcity_recent_comments(); ?>
						</ul>
						<br class="fixfloat" />
					</div>
				</div>
				<div class="menuitem"><span class="menuitem-trigger"><i class="icon-folder-close"></i> <?php _e('Categories'); //Categories ?></span>
					<div id="mainContainer4" class="ddmcontent">
						<ul>
							<?php wp_list_categories('orderby=count&title_li=&hide_empty=1&show_count=1&number=10&hierarchical=0&order=DESC') ?>
						</ul>
						<br class="fixfloat" />
					</div>
				</div>
				<div class="menuitem"><span class="menuitem-trigger"><i class="icon-inbox"></i> <?php _e('Archives'); //Archives ?></span>
					<div id="mainContainer2" class="ddmcontent">
						<ul><?php wp_get_archives('type=monthly&format=custom&before=<li class="ddmcontent-item">&after=</li>&limit=10&show_post_count=true'); ?></ul>
						<br class="fixfloat" />
					</div>
				</div>
			</div>
	</div> <!-- end dropdown menu -->
<?php
}


// Get Recent Comments
function tbcity_recent_comments() {

	$comments = get_comments('status=approve&number=10&type=comment');

	if ($comments) {
		foreach ($comments as $comment) {
			$post_title = get_the_title($comment->comment_post_ID);

			if ( ! $post_title )
				$post_title = __('(no title)');

			echo '<li><span class="intr">'. $comment->comment_author . ' in </span><a href="' . get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . $post_title . '</a></li>';
		}
	}
	else{
		_e('<li>No comments yet</li>','tbcity');
	}

}


// Get Recent Entries
function tbcity_recent_entries() {

	$lastposts = get_posts('numberposts=10');
	if ($lastposts) {
		 foreach($lastposts as $post) :
			setup_postdata($post);
			$post_title = get_the_title($post->ID);

			if ( ! $post_title )
				$post_title = __('(no title)');

			echo "<li><a href=\"".get_permalink($post->ID)."\" title=\"$post_title\">$post_title</a><span class=\"intr\"> " . __('by','tbcity') . " ".get_the_author().'</span></li>';
		endforeach;
	}
	else{
		_e('<li>No posts yet</li>','tbcity');
	}

}
