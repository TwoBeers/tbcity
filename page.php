<?php get_header(); ?>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="entry" id="entry-<?php the_ID(); ?>">
				<div class="entrytitle_wrap">
					<div class="entrydate"><?php edit_post_link(__('Edit'),'<span class="editthis">',' | </span>'); ?>
						<span class="jumper"><a href="#" title="top">top</a></span>
					</div>
					<div class="entrytitle" style="padding:0 10px">
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
					<?php tbcity_multipages(); ?>
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

			<?php endwhile; ?>
			<?php endif; ?>
		</div>

<?php get_footer(); ?>
