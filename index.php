<?php get_header(); ?>


			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="entry" id="entry-<?php the_ID(); ?>"> <!-- start entry container -->
				<div class="entrytitle_wrap">
					<div class="entrydate"><?php the_author(); ?> , <?php the_time(__('F j, Y')); ?>
						<?php edit_post_link(__('Edit'),'<span class="editthis"> | ','</span>'); ?>
						 | <span class="jumper"><a href="#" title="top">top</a></span>
					</div>
					<?php
					//start gravatar
					$email = get_the_author_meta('user_email');
					echo get_avatar($email, 50, $default=get_bloginfo('stylesheet_directory').'/images/user.png');
					//end gravatar
					?>
					<div class="entrytitle">
						<h1><a href="<?php the_permalink() ?>" rel="bookmark">
							<?php 
								$post_title = the_title_attribute('echo=0');
								if (!$post_title) {
									_e('(no title)');
								} else {
									echo $post_title;
								}
							?>
						</a></h1>
						<span class="commentslink">
							<?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); ?>
						</span>
					</div>
					<div class="entrymeta">
						<div class="postinfo">
							<span><img src="<?php echo get_bloginfo('stylesheet_directory')."/images/star.png" ?>" /><?php echo __('Categories') . ': '; the_category(', ') ?></span>
							<?php if (has_tag()) {?><span><img src="<?php echo get_bloginfo('stylesheet_directory')."/images/tag.png" ?>" /><?php the_tags( 'Tags: ', ', ', ''); ?></span><?php }?>
						</div>
					</div>
				</div>
				<div class="entrybody">
				<?php if (post_password_required()) { 
					tbcity_bot_msg(get_the_content());
				} else { 
					the_content(__('(more...)')); 
				} ?>
				</div>
			</div> <!-- end entry container -->

			<?php endwhile; ?>
			<div id="nav-global"> <!-- start page navigator -->
				<div class="nav-previous">
					<?php previous_posts_link(__('&laquo; Newer Posts','tbcity')); ?>
				</div>
				<div class="nav-next">
					<?php next_posts_link(__('Older Posts &raquo;','tbcity')); ?>
				</div>
			</div> <!-- end page navigator -->
			<?php endif; ?>
			
		</div> <!-- end center block -->

<?php get_footer(); ?>