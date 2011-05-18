<!-- begin comments -->
<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) ) die ('Please do not load this page directly. Thanks!');
		
if ( post_password_required() ) { ?>
	<div class="commentsblock">
		<h3 id="comments" class="content_headings h_info"><?php _e('Protected'); ?></h3>
<?php _e('This post is password protected. Enter the password to view comments.'); ?>
	</div>
<?php
	return;
}
?>

<?php if ( comments_open() ) : ?>

<div class="commentsblock">
	<h3 id="comments" class="content_headings h_comment"><?php comments_number(__('No Comments'), __('1 Comment'), __('% Comments')); ?></h3>

<?php if ( have_comments() ) : ?>
	<ol id="commentlist">
		<?php wp_list_comments('type=comment'); ?>
		<?php wp_list_comments('type=pings'); ?>
	</ol>
<?php endif; ?>
</div>

<div id="respond">
<div class="comment-body" style="background-position: 50px 0pt; padding-left: 80px;">
<?php 
if ( is_user_logged_in() ) {
	$user = get_userdata($user_ID);
	$email = $user->user_email;
} else {
	$email = '';
}
	echo get_avatar($email, 50, $default=get_bloginfo('stylesheet_directory').'/images/user.png');
?>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() ) );?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>
<p><?php printf(__('Logged in as %s','tbcity'), '<strong>'.$user_identity.'</strong>'); ?>. <?php comment_form_title( __('Leave a Comment'), __('Leave a Reply to %s') ); ?></p>

<?php else : ?>

<p><?php comment_form_title( __('Leave a Comment'), __('Leave a Reply to %s') ); ?></p>
<p><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)');?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website'); ?></small></label></p>

<?php endif; ?>
<!--<p></p>-->

<p style="text-align: center;"><textarea tabindex="4" rows="8" cols="58" id="comment" name="comment" style="width: 99%;"></textarea></p>
<p style="color:#999999; text-align: center;">
	<small><?php printf(__('You may use these <abbr title=\"HyperText Markup Language\">HTML</abbr> tags and attributes: %s'), allowed_tags()); ?></small>
</p>

<div>
<input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Say It!'); ?>" title="<?php esc_attr_e('Say It!'); ?>" />
<div id="cancel-comment-reply" style="display: inline;"> 
	<small><?php cancel_comment_reply_link(__('Cancel reply','tbcity')) ?></small>
</div>
<?php comment_id_fields(); ?>
</div>
<?php do_action('comment_form', $post->ID); ?>

</form>
<div class="fixfloat"></div> 
<?php endif; // If registration required and not logged in ?>
</div>
</div>
<?php endif; //comments open ?> 
<?php if ( pings_open() ) : ?>
	<div class="commentsblock ping_ad"> <!-- Trackbacks -->
		<h3 class="content_headings h_info"><?php _e('TrackBack & PingBack'); ?></h3>
		<div id="new_trackback">
			<?php tbcity_bot_msg('<p>' . __('Pingback and Trackback are allowed:','tbcity') . ' <a href="' . get_trackback_url() . '" rel="trackback">' . __('TrackBack URL') . '</a></p>'); ?>
		</div>
	</div>
<?php endif; ?>
<!-- end comments -->
