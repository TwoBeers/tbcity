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

	if( ! tbcity_get_opt( 'quickbar' ) ) return;

?>
	<div id="dropdown" class="css"> <!-- start dropdown menu -->

		<div id="dropper"><span><i class="icon-ellipsis-vertical"></i> Menu</span></div>

		<div id="cssmenu">

			<?php if ( ! dynamic_sidebar( 'quickbar-widgets-area' ) ) { ?>

				<?php
					if ( class_exists( 'TBCity_Widget_User_Quick_Links' ) )
						the_widget( 'TBCity_Widget_User_Quick_Links',  array( 'title' => '%s' ), array(
							'before_widget'	=> '<div class="menuitem widget tb_user_quick_links">',
							'after_widget'	=> '</div></div>',
							'before_title'	=> '<span class="menuitem-trigger">',
							'after_title'	=> '</span><div class="ddmcontent">'
						) );
				?>

				<?php
					if ( class_exists( 'WP_Widget_Recent_Posts' ) )
						the_widget( 'WP_Widget_Recent_Posts', '', array(
							'before_widget'	=> '<div class="menuitem widget widget_recent_entries">',
							'after_widget'	=> '</div></div>',
							'before_title'	=> '<span class="menuitem-trigger">',
							'after_title'	=> '</span><div class="ddmcontent">'
						) );
				?>

				<?php
					if ( class_exists( 'WP_Widget_Recent_Comments' ) )
						the_widget( 'WP_Widget_Recent_Comments', '', array(
							'before_widget'	=> '<div class="menuitem widget widget_recent_comments">',
							'after_widget'	=> '</div></div>',
							'before_title'	=> '<span class="menuitem-trigger">',
							'after_title'	=> '</span><div class="ddmcontent">'
						) );
				?>

				<?php
					if ( class_exists( 'TBCity_Widget_Pop_Categories' ) )
						the_widget( 'TBCity_Widget_Pop_Categories', array( 'title' => __( 'Popular Categories', 'tbcity' ), 'number' => 10 ), array(
							'before_widget'	=> '<div class="menuitem widget tb_categories">',
							'after_widget'	=> '</div></div>',
							'before_title'	=> '<span class="menuitem-trigger">',
							'after_title'	=> '</span><div class="ddmcontent">'
						) );
				?>

				<?php
					if ( class_exists( 'TBCity_Widget_Clean_Archives' ) )
						the_widget( 'TBCity_Widget_Clean_Archives', array( 'title' => __( 'Archives', 'tbcity' ), 'number' => 10, 'month_style' => 'number' ), array(
							'before_widget'	=> '<div class="menuitem widget tb_clean_archives">',
							'after_widget'	=> '</div></div>',
							'before_title'	=> '<span class="menuitem-trigger">',
							'after_title'	=> '</span><div class="ddmcontent">'
						) );
				?>

			<?php } ?>

		</div>

	</div> <!-- end dropdown menu -->
<?php

}
