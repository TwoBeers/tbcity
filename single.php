<?php get_header(); ?>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			
			<div class="entry">
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
					<?php 
					wp_link_pages(array('before'=>'				
					<div class="entrytitle_wrap" style="min-height: 1px; margin-left: -25px; padding-bottom: 0pt;">
					<div class="entrydate" style="height:30px; width:100%"></div>
					<div class="pagelink">Pages:', 'after'=>'</div></div>','pagelink'=>'<span>%</span>')); 
					?>
				</div>
				<div id="showcomments">
					<?php comments_template(); ?>
				</div>
			</div>
			<div id="nav-global"> <!-- start page navigator -->
				<div class="nav-previous">
					<?php next_post_link('&laquo; %link') ?>
				</div>
				<div class="nav-next">
					<?php previous_post_link('%link &raquo;') ?>
				</div>
			</div> <!-- end page navigator -->

			<?php endwhile; endif; ?>
		</div>

<?php get_footer(); ?>
