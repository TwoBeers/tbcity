<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
	
	<?php tbcity_disable_flash_header_user(); ?>
	
	<?php $tbcity_header_details = get_tbcity_header_details(); ?>

	<style type="text/css">
		#portrait-bg { background: url(<?php bloginfo('stylesheet_directory'); ?>/images/bg-portrait.png) center no-repeat; }
		#portrait-img { background: url(<?php get_tbcity_portrait_images(); ?>) center no-repeat; }
		#btitle { background: transparent url(<?php echo $tbcity_header_details[2]; ?>)  top left no-repeat; }
		#header { background: #000000 url(<?php echo $tbcity_header_details[1]; ?>) repeat-x left bottom; }
	</style>
	<link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_directory').'/print.css' ?>" type="text/css" media="print"  />
	

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php wp_head(); ?>
	
</head>

<body>
<?php
	tbcity_disable_flash_header_text();
?>
<div id="wrap"> <!-- start wrap -->
	<?php
	echo $tbcity_header_details[0];
	?>

	<div id="middle"> <!-- start main container -->

		<div id="frontstage"> <!-- start main menu -->
			<div id="rss-big"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"></a></div>
			<div id="portrait-bg">
				<div id="portrait-img">
					<span id="hidden_title"><?php bloginfo('name'); ?></span>
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/dancer.png" id="logo" />
				</div>
			</div>
			<div id="menu">
				<?php
					if ( function_exists('wp_nav_menu') ){
						wp_nav_menu( array( 'menu_id' => 'mainmenu', 'fallback_cb' => 'tbcity_pages_menu', 'before' => '<div>', 'after' => '</div>', 'theme_location' => 'primary' ) );
					}else{
						tbcity_pages_menu();
					}
				?>
			</div>
		</div> <!-- end main menu -->

		<div id="sub_content"> <!-- Start SUB_CONTENT -->
		
		<div id="content"> <!-- start center block -->

			<div id="DropDown"> <!-- start dropdown menu -->
				<div id="menupop">
					<div id="dropper">Menu&raquo;</div>
					<div id="cssMenu">
						<div class="menuitem"><?php _e('User','tbcity'); //user ?> 
							<div class="ddmcontainer">
								<h4><?php _e('User','tbcity'); ?></h4>
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
								</div>
								<div class="ddmcontainer-bottom"></div>
							</div>
						</div>
						<div class="menuitem"><?php _e('Recent Posts'); //Recent Posts ?>
							<div class="ddmcontainer">
								<h4><?php _e('Recent Posts'); ?></h4>
								<div id="mainContainer3" class="ddmcontent">
									<ul>
										<?php get_tbcity_recententries(); ?>
									</ul>
								</div>
								<div class="ddmcontainer-bottom"></div>
							</div>
						</div>
						<div class="menuitem"><?php _e('Recent Comments'); // Recent Comments ?>
							<div class="ddmcontainer">
								<h4><?php _e('Recent Comments');?></h4>
								<div id="mainContainer" class="ddmcontent">
									<ul>
										<?php get_tbcity_recentcomments(); ?>
									</ul>
								</div>
								<div class="ddmcontainer-bottom"></div>
							</div>
						</div>
						<div class="menuitem"><?php _e('Categories'); //Categories ?>
							<div class="ddmcontainer">
								<h4><?php _e('Categories'); ?></h4>
								<div id="mainContainer4" class="ddmcontent">
									<ul>
										<?php wp_list_categories('orderby=name&title_li=&hide_empty=1&show_count=1') ?>
									</ul>
								</div>
								<div class="ddmcontainer-bottom"></div>
							</div>
						</div>
						<div class="menuitem"><?php _e('Archives'); //Archives ?>
							<div class="ddmcontainer">
								<h4><?php _e('Archives'); ?></h4>
								<div id="mainContainer2" class="ddmcontent">
									<ul><?php wp_get_archives('type=monthly&format=custom&before=<li class="ddmcontent-item">&after=</li>&limit=7&show_post_count=true'); ?></ul>
								</div>
								<div class="ddmcontainer-bottom"></div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end dropdown menu -->
<!-- end header -->