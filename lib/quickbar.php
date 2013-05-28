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
		<div id="dropper">
			<span><i class="icon-reorder"></i> Menu</span>
		</div>
		<div id="cssmenu">
			<div class="menuitem">
				<span class="menuitem-trigger"><i class="icon-user"></i> <?php _e( 'User', 'tbcity' ); //user ?> </span>
				<div class="ddmcontent">
					<ul id="usertools">
						<li id="logged">
							<?php
							if ( is_user_logged_in() ) {
								global $current_user;
								get_currentuserinfo();
								echo get_avatar( sanitize_email( $current_user->user_email ), 50, $default = get_bloginfo( 'stylesheet_directory' ) . '/images/user.png', 'user-avatar' );
								printf( __( 'Logged in as %s', 'tbcity' ), '<strong>' . $current_user->display_name . '</strong>' );
							}else{
								echo get_avatar( '', 50, $default = get_bloginfo( 'stylesheet_directory' ) . '/images/user.png', 'user-avatar' );
								echo __( 'Not logged in', 'tbcity' );
							}
							?>
						</li>
						<?php wp_register(); ?>
						<?php if (is_user_logged_in()) { ?>
							<?php if ( current_user_can( 'read' ) ) { ?>
								<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile', 'tbcity' ); ?></a></li>
								<?php if ( current_user_can( 'publish_posts' ) ) { ?>
									<li><a title="<?php _e( 'Add New Post', 'tbcity' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post', 'tbcity' ); ?></a></li>
								<?php } ?>
								<?php if ( current_user_can( 'moderate_comments' ) ) {
									$awaiting_mod = wp_count_comments();
									$awaiting_mod = $awaiting_mod->moderated;
									$awaiting_mod = $awaiting_mod ? ' (' . number_format_i18n( $awaiting_mod ) . ')' : '';
								?>
									<li><a title="<?php _e( 'Comments', 'tbcity' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'tbcity' ); ?></a><?php echo $awaiting_mod; ?></li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<li><?php wp_loginout(); ?></li>
					</ul>
					<br class="fixfloat" />
				</div>
			</div>
			<div class="menuitem">
				<span class="menuitem-trigger"><i class="icon-file-alt"></i> <?php _e( 'Recent Posts', 'tbcity' ); //Recent Posts ?></span>
				<div class="ddmcontent">
					<ul>
						<?php tbcity_recent_entries(); ?>
					</ul>
					<br class="fixfloat" />
				</div>
			</div>
			<div class="menuitem">
				<span class="menuitem-trigger"><i class="icon-comment"></i> <?php _e( 'Recent Comments', 'tbcity' ); // Recent Comments ?></span>
				<div class="ddmcontent">
					<ul>
						<?php tbcity_recent_comments(); ?>
					</ul>
					<br class="fixfloat" />
				</div>
			</div>
			<div class="menuitem">
				<span class="menuitem-trigger"><i class="icon-folder-close"></i> <?php _e( 'Categories', 'tbcity' ); //Categories ?></span>
				<div class="ddmcontent">
					<ul>
						<?php wp_list_categories( 'orderby=count&title_li=&hide_empty=1&show_count=1&number=10&hierarchical=0&order=DESC' ) ?>
					</ul>
					<br class="fixfloat" />
				</div>
			</div>
			<div class="menuitem">
				<span class="menuitem-trigger"><i class="icon-inbox"></i> <?php _e( 'Archives', 'tbcity' ); //Archives ?></span>
				<div class="ddmcontent">
					<ul><?php wp_get_archives( 'type=monthly&limit=10&show_post_count=true' ); ?></ul>
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

	if ( $comments ) {
		foreach ( $comments as $comment ) {
			//if ( post_password_required( get_post( $comment->comment_post_ID ) ) ) continue; // uncomment to skip comments on protected posts

			$post_id = $comment->comment_post_ID;

			if ( post_password_required( $post_id ) )
				$comment_author = __( 'someone', 'tbcity' ); //hide comment author in protected posts
			else
				$comment_author = $comment->comment_author;

			$post_title = get_the_title( $post_id );

			echo '<li>' . sprintf( __( '%s in %s', 'tbcity' ), $comment->comment_author, '<a href="' . get_permalink( $post_id ) . '#comment-' . $comment->comment_ID . '">' . $post_title . '</a>' ) . '</li>';
		}
	} else {
		echo '<li>' . __( 'No comments yet', 'tbcity' ) . '</li>';
	}

}


// Get Recent Entries
function tbcity_recent_entries() {
	global $post;

	$lastposts = get_posts('numberposts=10');

	if ($lastposts) {
		foreach( $lastposts as $post ) {
			//if ( post_password_required( $post ) ) continue; // uncomment to skip protected posts

			setup_postdata( $post );

			echo '<li>' . sprintf( __( '%s by %s', 'tbcity' ), '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">' . get_the_title() . '</a>', get_the_author() ) . '</li>';

		}
	} else {
		echo '<li>' . __( 'No posts yet', 'tbcity' ) . '</li>';
	}

	wp_reset_postdata();

}
