<?php
/**
 * functions.php
 *
 * Contains almost all of the Theme's setup functions and custom functions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package The Black City
 * @since 1.00
 */


/* Custom actions - WP hooks */

add_action( 'after_setup_theme'						, 'tbcity_setup' );
add_action( 'wp_enqueue_scripts'					, 'tbcity_stylesheet' );
add_action( 'wp_head'								, 'tbcity_custom_style' );
add_action( 'wp_enqueue_scripts'					, 'tbcity_scripts' );
add_action( 'admin_bar_menu'						, 'tbcity_admin_bar_plus', 999 );
add_action( 'comment_form_comments_closed'			, 'tbcity_comments_closed' );
add_action( 'template_redirect'						, 'tbcity_allcat' );
add_action( 'comment_form_before'					, 'tbcity_enqueue_comments_reply' );


/* Custom actions - theme hooks */

add_action( 'tbcity_hook_header_after'				, 'tbcity_primary_menu' );
add_action( 'tbcity_hook_body_top'					, 'tbcity_1st_secondary_menu' );
add_action( 'tbcity_hook_footer_top'				, 'tbcity_2nd_secondary_menu' );
add_action( 'tbcity_hook_content_before'			, 'tbcity_quickbar' );
add_action( 'tbcity_hook_entry_before'				, 'tbcity_navigate_attachments' );
add_action( 'tbcity_hook_entry_before'				, 'tbcity_single_nav' );
add_action( 'tbcity_hook_entry_after'				, 'tbcity_single_widgets_area', 99 );
add_action( 'tbcity_hook_entry_content_bottom'		, 'tbcity_last_comments', 999 );
add_action( 'tbcity_hook_entry_content_bottom'		, 'tbcity_link_pages' );
add_action( 'tbcity_hook_comments_list_before'		, 'tbcity_navigate_comments' );
add_action( 'tbcity_hook_comments_list_after'		, 'tbcity_navigate_comments' );
add_action( 'tbcity_hook_body_top'					, 'tbcity_initialize_scripts' );
add_action( 'tbcity_hook_post_title_top'			, 'tbcity_entry_date' );
add_action( 'tbcity_hook_post_title_wrap_after'		, 'tbcity_extrainfo' );
add_action( 'tbcity_hook_post_title_wrap_top'		, 'tbcity_entry_thumb' );


/* Custom filters - WP hooks */

add_filter( 'use_default_gallery_style'				, '__return_false' );
add_filter( 'embed_oembed_html'						, 'tbcity_wmode_transparent', 99, 3);
add_filter( 'img_caption_shortcode'					, 'tbcity_img_caption_shortcode', 10, 3 );
add_filter( 'the_content'							, 'tbcity_clear_float' );
add_filter( 'body_class'							, 'tbcity_body_classes' );
add_filter( 'post_class'							, 'tbcity_post_classes' );
add_filter( 'comment_form_default_fields'			, 'tbcity_comments_form_fields');
add_filter( 'comment_form_defaults'					, 'tbcity_comment_form_defaults' );
add_filter( 'wp_get_attachment_link'				, 'tbcity_get_attachment_link', 10, 6 );
add_filter( 'get_comment_author_link'				, 'tbcity_add_quoted_on' );
add_filter( 'user_contactmethods'					, 'tbcity_new_contactmethods', 10, 1 );
add_filter( 'the_title'								, 'tbcity_titles_filter', 10, 2 );
add_filter( 'excerpt_length'						, 'tbcity_excerpt_length' );
add_filter( 'excerpt_mblength'						, 'tbcity_excerpt_length' );
add_filter( 'excerpt_more'							, 'tbcity_excerpt_more' );
add_filter( 'the_excerpt'							, 'tbcity_non_empty_excerpt' );
add_filter( 'the_content_more_link'					, 'tbcity_more_link', 10, 2 );
add_filter( 'wp_title'								, 'tbcity_filter_wp_title' );
add_filter( 'avatar_defaults'						, 'tbcity_addgravatar' );
add_filter( 'wp_list_categories'					, 'tbcity_wrap_categories_count' );
add_filter( 'get_archives_link'						, 'tbcity_wrap_categories_count' );
add_filter( 'comment_reply_link'					, 'tbcity_comment_reply_link' );
add_filter( 'wp_nav_menu_items'						, 'tbcity_add_home_link', 10, 2 );
add_filter( 'wp_nav_menu_objects'					, 'tbcity_add_menu_parent_class' );
add_filter( 'page_css_class'						, 'tbcity_add_parent_class', 10, 4 );
add_filter( 'page_css_class'						, 'tbcity_add_selected_class_to_page_item', 10, 1 );
add_filter( 'nav_menu_css_class'					, 'tbcity_add_selected_class_to_menu_item', 10, 2 );
add_filter( 'comment_form_logged_in'				, 'tbcity_add_avatar_to_logged_in', 10, 3 );
add_filter( 'get_search_form'						, 'tbcity_search_form' );
add_filter( 'widget_categories_args'				, 'tbcity_widget_categories_args' );


/* get the theme options */

$tbcity_opt = get_option( 'tbcity_options' );


/* theme infos */

function tbcity_get_info( $field ) {
	static $infos;

	if ( !isset( $infos ) ) {

		$infos['theme'] =			wp_get_theme( 'tbcity' );
		$infos['current_theme'] =	wp_get_theme();
		$infos['version'] =			$infos['theme']? $infos['theme']['Version'] : '';

	}

	return $infos[$field];
}


/* load modules (accordingly to http://justintadlock.com/archives/2010/11/17/how-to-load-files-within-wordpress-themes) */

require_once( 'lib/options.php' ); // load options

require_once( 'lib/admin.php' ); // load admin functions

require_once( 'lib/hooks.php' ); // load the custom hooks module

require_once( 'lib/widgets.php' ); // load the custom widgets module

require_once( 'lib/header/parallax-header.php' ); // load the custom header module

require_once( 'lib/custom-header.php' ); // load the custom header module

require_once( 'lib/breadcrumb.php' ); // load the breadcrumb module

//require_once( 'lib/audio-player/audio-player.php' ); // load the audio player module

require_once( 'lib/plug-n-play.php' ); // load the plugins support module

require_once( 'lib/quickbar.php' ); // load the quickbar module

$tbcity_is_mobile = false;
//if ( tbcity_get_opt( 'mobile_css' ) ) require_once( 'lib/mobile/core-mobile.php' ); // load mobile functions


/* conditional tags */

function tbcity_is_mobile() { // mobile
	global $tbcity_is_mobile;

	return $tbcity_is_mobile;

}

function tbcity_is_allcat() { //is "all category" page
	static $is_allcat;

	if ( !isset( $is_allcat ) )
		$is_allcat = isset( $_GET['allcat'] ) && md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ? true : false;

	if ( is_page_template('allcat.php') ) $is_allcat = 1;

	return $is_allcat;

}


// Set the content width based on the theme's design
if ( ! isset( $content_width ) ) {
	$content_width = 1024;
}


// Add style element for custom theme options
if ( !function_exists( 'tbcity_custom_style' ) ) {
	function tbcity_custom_style(){
		global $is_gecko;

		if ( tbcity_is_mobile() ) return; // skip if in mobile view

?>
<style type="text/css">
	body,
	select {
		font-size: <?php echo tbcity_get_opt( 'font_size' ); ?>px;
<?php if ( tbcity_get_opt( 'google_font_family' ) && tbcity_get_opt( 'google_font_body' ) ) { ?>
		font-family: <?php echo tbcity_get_opt( 'google_font_family' ); ?>;
<?php } else { ?>
		font-family: <?php echo tbcity_get_opt( 'font_family' ); ?>;
<?php } ?>
	}
<?php if ( tbcity_get_opt( 'google_font_family' ) && tbcity_get_opt( 'google_font_post_title' ) ) { ?>
	h2.storytitle {
		font-family: <?php echo tbcity_get_opt( 'google_font_family' ); ?>;
	}
<?php } ?>
<?php if ( tbcity_get_opt( 'google_font_family' ) && tbcity_get_opt( 'google_font_post_content' ) ) { ?>
	.storycontent {
		font-family: <?php echo tbcity_get_opt( 'google_font_family' ); ?>;
	}
<?php } ?>

	a {
		color: <?php echo tbcity_get_colors(0); ?>;
	}
	input[type=button]:hover,
	input[type=submit]:hover,
	input[type=reset]:hover,
	input[type=button]:focus,
	input[type=submit]:focus,
	input[type=reset]:focus,
	button:hover,
	button:focus,
	div.navigation-links a:hover,
	div.navigation-links a:active,
	#respond input#submit:hover,
	#respond input#submit:active,
	a.comment-reply-link:hover,
	a.more-link:hover,
	div#buddypress button:hover,
	div#buddypress a.button:hover,
	div#buddypress input[type="submit"]:hover,
	div#buddypress input[type="button"]:hover,
	div#buddypress input[type="reset"]:hover,
	div#buddypress ul.button-nav li a:hover,
	div#buddypress div.generic-button a:hover,
	div#buddypress .comment-reply-link:hover,
	div a.bp-title-button:hover {
		background: <?php echo tbcity_get_colors(0); ?>;
	}
	#mainmenu li.selected > a {
		border-left:3px solid <?php echo tbcity_get_colors(0); ?>;
	}
	#mainmenu li a:hover {
		border-color: <?php echo tbcity_get_colors(0); ?>;
	}
	.storytitle a:hover {
		text-shadow:1px 1px 0 <?php echo tbcity_get_colors(0); ?>;
	}

	#mainmenu > li.current_page_ancestor > a {
		border-left: 3px solid <?php echo tbcity_get_colors(1); ?>;
	}
	#respond #author:focus,
	#respond #email:focus,
	#respond #url:focus,
	#respond #comment:focus
	{
		border:1px solid <?php echo tbcity_get_colors(2); ?>;
	}
	input[type=button],
	input[type=submit],
	input[type=reset],
	button,
	div.navigation-links a,
	a.more-link,
	#respond input#submit,
	a.comment-reply-link,
	div#buddypress button,
	div#buddypress a.button,
	div#buddypress input[type="submit"],
	div#buddypress input[type="button"],
	div#buddypress input[type="reset"],
	div#buddypress ul.button-nav li a,
	div#buddypress div.generic-button a,
	div#buddypress .comment-reply-link,
	div a.bp-title-button {
		background: <?php echo tbcity_get_colors(2); ?>;
<?php if ( tbcity_get_opt( 'google_font_family' ) && tbcity_get_opt( 'google_font_body' ) ) { ?>
		font-family: <?php echo tbcity_get_opt( 'google_font_family' ); ?>;
<?php } ?>
	}
	.bypostauthor > .comment-body  {
		border-left: 2px groove <?php echo tbcity_get_colors(2); ?>;
	}
	#mainmenu > li a {
		border-left:3px solid <?php echo tbcity_get_colors(2); ?>;
	}
	.sticky .entrytitle_wrap {
		border-left: 5px solid <?php echo tbcity_get_colors(2); ?>;
		background: <?php echo tbcity_get_colors(3); ?>; /* Old browsers */
		background: -moz-linear-gradient(left, <?php echo tbcity_get_colors(3); ?> 0%, #666666 50%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, right top, color-stop(0%, <?php echo tbcity_get_colors(3); ?>), color-stop(50%, #666666)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(left, <?php echo tbcity_get_colors(3); ?> 0%, #666666 50%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(left, <?php echo tbcity_get_colors(3); ?> 0%, #666666 50%); /* Opera 11.10+ */
		background: -ms-linear-gradient(left, <?php echo tbcity_get_colors(3); ?> 0%, #666666 50%); /* IE10+ */
		background: linear-gradient(to right, <?php echo tbcity_get_colors(3); ?> 0%, #666666 50%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo tbcity_get_colors(3); ?>', endColorstr='#666666',GradientType=1 ); /* IE6-9 */
	}
	.ddmcontent a {
		color: <?php echo tbcity_get_colors(2); ?>;
	}
	.ddmcontent a:hover {
		color: <?php echo tbcity_get_colors(1); ?>;
	}

<?php if ( $is_gecko ) { ?>
	.navigation-links,
	#infinite-handle span,
	div.navigation-links a,
	a.more-link,
	.comment-body,
	#respond input#submit,
	.comment #respond,
	a.comment-reply-link,
	fieldset,
	input[type=button],
	input[type=submit],
	input[type=reset],
	button {
		border-color: #444;
	}

<?php } ?>

</style>
<!-- InternetExplorer really sucks! -->
<!--[if lte IE 8]>
<style type="text/css">
	.attachment-thumbnail,
	.wp-caption img,
	.gallery-item img,
	.storycontent img,
	.widget img.size-full,
	.widget img.attachment-full,
	.widget img.size-medium,
	.widget img.attachment-medium {
		width:auto;
	}
	.widget .avatar {
		max-width: 64px;
	}
</style>
<![endif]-->
<?php

	}
}


// get js modules
if ( !function_exists( 'tbcity_get_js_modules' ) ) {
	function tbcity_get_js_modules() {

		$modules = array();

		if ( tbcity_get_opt( 'js_basic_extra_info' ) )
			$modules[] = 'extrainfo';
		if ( tbcity_get_opt( 'js_basic_quickbar' ) )
			$modules[] = 'quickbar';
		if ( tbcity_get_opt( 'js_basic_menu' ) )
			$modules[] = 'animatemenu';
		if ( tbcity_get_opt( 'js_basic_autoscroll' ) )
			$modules[] = 'scrolltopbottom';
		if ( tbcity_get_opt( 'js_basic_video_resize' ) )
			$modules[] = 'resizevideo';
		if ( tbcity_get_opt( 'js_basic_comment_tooltip' ) )
			$modules[] = 'lastcomments';

		$modules = implode(',', $modules);

		return  apply_filters( 'tbcity_filter_js_modules', $modules );

	}
}


// initialize js
function tbcity_initialize_scripts() {

	if ( is_admin() || tbcity_is_mobile() ) return;

?>
	<script type="text/javascript">
		/* <![CDATA[ */
		(function(){
			var c = document.body.className;
			c = c.replace(/tb-no-js/, 'tb-js');
			document.body.className = c;
		})();
		/* ]]> */
	</script>
<?php

}


// Add stylesheets to page
if ( !function_exists( 'tbcity_stylesheet' ) ) {
	function tbcity_stylesheet(){

		if ( is_admin() || tbcity_is_mobile() ) return;

		wp_enqueue_style( 'tbcity-general-style', get_stylesheet_uri(), array('thickbox'), tbcity_get_info( 'version' ), 'screen' );
		wp_enqueue_style( 'tbcity-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );

		//google font
		if ( tbcity_get_opt( 'google_font_family' ) )
			wp_enqueue_style( 'tbcity-google-fonts', '//fonts.googleapis.com/css?family=' . urlencode( tbcity_get_opt( 'google_font_family' ) ) );

		wp_enqueue_style( 'tbcity-print-style', get_template_directory_uri() . '/css/print.css', false, tbcity_get_info( 'version' ), 'print' );

	}
}


// add scripts
if ( !function_exists( 'tbcity_scripts' ) ) {
	function tbcity_scripts(){

		if ( is_admin() || tbcity_is_mobile() || ! tbcity_get_opt( 'jsani' ) ) return;

		$deps = array( 'jquery', 'hoverIntent' );

		wp_enqueue_script( 'tbcity-script', get_template_directory_uri() . '/js/animations.js', $deps, tbcity_get_info( 'version' ), true );

		$data = array(
			'script_modules'	=> tbcity_get_js_modules(),
		);

		wp_localize_script( 'tbcity-script', 'tbcity_l10n', $data );

	}
}


// display the post title with the featured image
if ( !function_exists( 'tbcity_featured_title' ) ) {
	function tbcity_featured_title( $args = '' ) {
		global $post;

		if ( tbcity_get_opt( 'hide_frontpage_title' ) && is_front_page() && is_page() && ! tbcity_is_allcat() ) return;

		if ( tbcity_get_opt( 'hide_pages_title' ) && is_page() ) return;

		$selected_ids = explode( ',', tbcity_get_opt( 'hide_selected_entries_title' ) );
		if ( in_array( $post->ID, $selected_ids ) ) return;

		$defaults = array(
			'alternative'	=> '',
			'fallback'		=> '',
			'href'			=> get_permalink(),
			'target'		=> '',
			'title'			=> the_title_attribute( array('echo' => 0 ) ),
			'micro'			=> false,
			'featured'		=> true,
		);
		$args = wp_parse_args( $args, $defaults );

		$post_title = $args['alternative'] ? $args['alternative'] : get_the_title();
		$post_title = $post_title ? $post_title : $args['fallback'];
		$link_target = $args['target'] ? ' target="'.$args['target'].'"' : '';
		$title_content = is_singular() ? $post_title : '<a title="' . esc_attr( $args['title'] ) . '" href="' . esc_url( $args['href'] ) . '"' . $link_target . ' rel="bookmark">' . $post_title . '</a>';
		if ( $args['featured'] && tbcity_get_opt( 'featured_title' ) == 'avatar' && ! is_page() ) $title_content = get_avatar( get_the_author_meta( 'user_email' ), 50, $default = get_option( 'avatar_default' ) ) . $title_content;
		if ( $post_title ) $post_title = '<h2 class="storytitle">' . $title_content . '</h2>';

		if ( post_password_required() )
			$post_title = '<h2 class="storytitle">' . get_the_title() . '</h2>';

		if ( $args['micro'] )
			$post_title = '<a class="micro-anchor" title="' . esc_attr( $args['title'] ) . '" href="' . esc_url( $args['href'] ) . '">' . tbcity_get_the_thumb( array( 'id' => $post->ID, 'class' => 'icon-placeholder', 'only_icon' => true ) ) . '</a>';

		$post_title = apply_filters( 'tbcity_featured_title', $post_title, $args );

?>
	<?php tbcity_hook_post_title_wrap_before(); ?>

	<div class="entrytitle_wrap">

		<?php tbcity_hook_post_title_wrap_top(); ?>

		<div class="entrytitle">

			<?php tbcity_hook_post_title_top(); ?>

			<?php echo $post_title; ?>

			<?php tbcity_hook_post_title_bottom(); ?>

		</div>

		<?php tbcity_hook_post_title_wrap_bottom(); ?>

	</div>

	<?php tbcity_hook_post_title_wrap_after(); ?>
<?php

	}
}


// print extra info for posts/pages
if ( !function_exists( 'tbcity_entry_date' ) ) {
	function tbcity_entry_date() {

		if( tbcity_is_allcat() ) return;

?>
	<div class="entrydate">

		<?php if ( ! is_page() ) { ?><span><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( printf( __( 'View all posts by %s', 'tbcity' ), get_the_author() ) ); ?>"><?php echo get_the_author(); ?></a> , <?php the_time( get_option( 'date_format' ) ); ?></span><?php } ?>

		<?php edit_post_link( '<i class="icon-pencil"></i>', '<span>', '</span>' ); ?>

		<span><a class="jump-to-top" href="#" title="top"><i class="icon-caret-up"></i></a></span>

		<span><a class="jump-to-bottom" href="#footer" title="bottom"><i class="icon-caret-down"></i></a></span>

	</div>
<?php

	}
}


// print entry thumbnail
function tbcity_entry_thumb() {

	$thumb = '';

	if ( post_password_required() )

		$thumb = '';

	elseif ( ( tbcity_get_opt( 'featured_title' ) == 'thumbnail' ) )

		$thumb = ( $src = tbcity_get_the_thumb_url( 0 , 4) ) ? '<img class="feaured-image" src="' . esc_url( $src ) . '" alt="thumbnail" />' : '';//get_the_post_thumbnail( $post->ID, array( 50, 50 ) );

	elseif ( ( tbcity_get_opt( 'featured_title' ) == 'qr_code' ) )

		$thumb = '<img class="feaured-image qrcode" src="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=' . urlencode( home_url() . '/?p=' . get_the_ID() ) . '&chld=L|0" alt="thumbnail" />';

	$thumb = apply_filters( 'tbcity_entry_thumb', $thumb );

	if ( $thumb )
		echo $thumb;

}


// print extra info for posts/pages
if ( !function_exists( 'tbcity_extrainfo' ) ) {
	function tbcity_extrainfo( $args = '' ) {

		if( tbcity_is_allcat() ) return;

		$defaults = array(
			'comms' => tbcity_get_opt( 'post_comments' ),
			'tags' => tbcity_get_opt( 'post_tag' ),
			'cats' => tbcity_get_opt( 'post_cat' ),
			'hiera' => 1,
			'list_view' => 0
		);
		$args = wp_parse_args( $args, $defaults );

?>
	<div class="extra-info">

		<?php if ( $args['hiera'] ) { ?>
			<?php tbcity_multipages(); ?>
		<?php } ?>

		<?php if ( $args['tags'] && ! is_page() ) { ?>
			<div class="metafield alternate">
				<span class="meta-trigger-wrap"><i class="icon-tags meta-trigger"></i></span>
				<div class="metafield_content">
					<?php _e( 'Tags', 'tbcity' ); ?>:
					<?php if ( !get_the_tags() ) { _e( 'No Tags', 'tbcity' ); } else { the_tags( '', ', ', '' ); } ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( $args['cats'] && ! is_page() ) { ?>
			<div class="metafield alternate">
				<span class="meta-trigger-wrap"><i class="icon-folder-close meta-trigger"></i></span>
				<div class="metafield_content">
					<?php echo __( 'Categories', 'tbcity' ); ?>:
					<?php the_category( ', ' ) ?>
				</div>
			</div>
		<?php } ?> 

		<?php $page_cd_nc = ( is_page() && !comments_open() && !have_comments() ); //true if page with comments disabled and no comments ?>
		<?php if ( $args['comms'] && !$page_cd_nc ) { ?>
			<div class="metafield alternate">
				<span class="meta-trigger-wrap"><i class="icon-comment meta-trigger"></i></span>
				<div class="metafield_content">
					<?php _e( 'Comments', 'tbcity' ); ?>:
					<?php echo tbcity_get_comments_link(); // number of comments?>
				</div>
			</div>
		<?php } ?>


	</div>
<?php

	}
}


// page hierarchy
if ( !function_exists( 'tbcity_multipages' ) ) {
	function tbcity_multipages(){
		global $post;

		$args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'numberposts' => 0,
			'no_found_rows' => true
		);
		$childrens = get_posts( $args ); // retrieve the child pages
		$the_parent_page = $post->post_parent; // retrieve the parent page
		$has_herarchy = false;

		if ( ( $childrens ) || ( $the_parent_page ) ){

?>
	<div class="metafield alternate">
		<span class="meta-trigger-wrap"><i class="icon-indent-right meta-trigger"></i></span>
		<div class="metafield_content">
			<?php
			if ( $the_parent_page ) {
				$the_parent_link = '<a href="' . esc_url( get_permalink( $the_parent_page ) ) . '" title="' . esc_attr( strip_tags( get_the_title( $the_parent_page ) ) ) . '">' . get_the_title( $the_parent_page ) . '</a>';
				echo __( 'Upper page: ', 'tbcity' ) . $the_parent_link ; // echoes the parent
			}
			if ( ( $childrens ) && ( $the_parent_page ) ) { echo ' - '; } // if parent & child, echoes the separator
			if ( $childrens ) {
				$the_child_list = '';
				foreach ( $childrens as $children ) {
					$the_child_list[] = '<a href="' . esc_url( get_permalink( $children ) ) . '" title="' . esc_attr( strip_tags( get_the_title( $children ) ) ) . '">' . get_the_title( $children ) . '</a>';
				}
				$the_child_list = implode( ', ' , $the_child_list );
				echo __( 'Lower pages: ', 'tbcity' ) . $the_child_list; // echoes the childs
			}
			?>
		</div>
	</div>
<?php

			$has_herarchy = true;
		}
		return $has_herarchy;

	}
}


// the last commenters of a post
if ( !function_exists( 'tbcity_last_comments' ) ) {
	function tbcity_last_comments( $id = 0 ) {
		global $post;

		if( is_singular() ) return;

		$num = apply_filters( 'tbcity_last_comments_number', 6 );
		if ( !$id ) $id = $post->ID;

		$comments = get_comments( 'status=approve&number=' . $num . '&type=comment&post_id=' . $id ); // valid type values (not documented) : 'pingback','trackback','comment'

		$ellipsis = '';
		if ( count( $comments ) > 5 ) {
			$ellipsis = '<li>...</li>';
			$comments = array_slice( $comments, 0, 5 );
		}

		$comments = array_reverse( $comments );

		if ( $comments ) {
?>
	<ul class="last-comments fixfloat css">
		<li><?php _e('last comments','tbcity'); ?> <i class="icon-angle-right"></i></li>
		<?php echo $ellipsis; ?>
		<?php foreach ( $comments as $comment ) { ?>
			<li class="no-grav">
				<?php echo get_avatar( $comment, 32, $default = get_option( 'avatar_default' ), $comment->comment_author );?>
				<span class="tooltip">
					<span class="comment-author"><?php echo $comment->comment_author; ?></span>
					<br />
					<?php comment_excerpt( $comment->comment_ID ); ?>
				</span>
			</li>
		<?php } ?>
	</ul>
<?php
		}

	}
}


/**
 * Displays the link to the comments popup window for the current post ID.
 *
 */
function tbcity_get_comments_link( $args = '' ) {

	$defaults = array(
		'zero'		=> false,
		'one'		=> false,
		'more'		=> false,
		'css_class'	=> '',
		'none'		=> false,
		'before'	=> '',
		'after'		=> '',
		'id'		=> false,
	);
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);

	if ( false === $zero )	$zero	= __( 'No Comments', 'tbcity' );
	if ( false === $one )	$one	= __( '1 Comment', 'tbcity' );
	if ( false === $more )	$more	= __( '% Comments', 'tbcity' );
	if ( false === $none )	$none	= __( 'Comments Off', 'tbcity' );
	$id = ( $id ) ? (int)$id : get_the_ID();
	$css_class = ( ! empty( $css_class ) ) ? ' class="' . esc_attr( $css_class ) . '"' : '';

	$number = get_comments_number( $id );

	if ( 0 == $number && !comments_open() && !pings_open() ) {

		$output = $none ? $before . '<span' . $css_class . '>' . $none . '</span>' . $after : '';

	} elseif ( post_password_required() ) {

		$output = $before . __( 'Enter your password to view comments', 'tbcity' ) . $after;

	} else {

		$label = tbcity_get_opt( 'tbcity_cust_comrep' ) ? '#comments' : '#respond';
		$href = ( 0 == $number ) ? get_permalink() . $label : get_comments_link();
		$title = esc_attr( sprintf( __( 'Comment on %s', 'tbcity'), the_title_attribute( array( 'echo' => 0 ) ) ) );

		if ( $number > 1 )
			$text = str_replace( '%', number_format_i18n( $number ), $more );
		elseif ( $number == 0 )
			$text = $zero;
		else
			$text = $one;

		$output = $before . '<a' . $css_class . ' href="' . esc_url( $href ) . '" title="' . $title . '">' . $text . '</a>' . $after;

	}

	return apply_filters( 'tbcity_get_comments_link' , $output );

}


// the header
if ( !function_exists( 'tbcity_get_header' ) ) {
	function tbcity_get_header() {

		$header = '';
		$header_title = '';

		if ( display_header_text() )
			$header_title = '<div id="head-title" class="on-top"><h1><a href="' . esc_url( home_url() ) . '/">' . get_bloginfo( 'name' ) . '</a></h1><span>' . get_bloginfo( 'description' ) . '</span></div>';

		if ( get_header_image() ) {

			if ( display_header_text() )
				$header .= '<img class="aligncenter" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( get_header_image() ) . '" />';
			else
				$header .= '<a href="' . esc_url( home_url() ) . '/"><img class="aligncenter" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( get_header_image() ) . '" /></a>';

		} else {

			$header = apply_filters( 'tbcity_filter_header', $header );

		}

		$header = $header ? '<div id="head-image-wrapper">' . $header . '</div>' : '';


		return $header_title . $header;

	}
}


// archives pages navigation
if ( !function_exists( 'tbcity_navigate_archives' ) ) {
	function tbcity_navigate_archives() {
		global $paged, $wp_query;

		if ( !$paged ) $paged = 1;

?>
	<div class="navigation-links navigate_archives">
	<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>

		<?php wp_pagenavi(); ?>

	<?php } elseif ( function_exists( 'wp_paginate' ) ) { ?>

		<?php wp_paginate(); ?>

	<?php } else { ?>

		<?php next_posts_link( '&laquo;' ); ?>
		<?php printf( '<span>' . __( 'page %1$s of %2$s','tbcity' ) . '</span>', $paged, $wp_query->max_num_pages ); ?>
		<?php previous_posts_link( '&raquo;' ); ?>

	<?php } ?>
	</div>
<?php

	}
}


// attachments navigation
if ( !function_exists( 'tbcity_navigate_attachments' ) ) {
	function tbcity_navigate_attachments() {
		global $post;

		if ( ! is_attachment() || ! wp_attachment_is_image() ) return;

		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				break;
		}
		$nextk = $k + 1;
		$prevk = $k - 1;

		if ( ! isset( $attachments[ $prevk ] ) && ! isset( $attachments[ $nextk ] ) ) return;

?>
	<div class="nav-single">

		<?php if ( isset( $attachments[ $prevk ] ) ) { ?>
			<span class="nav-previous ">
				<i class="icon-angle-left"></i>
				<a rel="prev" href="<?php echo esc_url( get_attachment_link( $attachments[ $prevk ]->ID ) ); ?>"><?php echo wp_get_attachment_image( $attachments[ $prevk ]->ID, array( 70, 70 ) ); ?></a>
			</span>
		<?php } ?>

		<?php if ( isset( $attachments[ $nextk ] ) ) { ?>
			<span class="nav-next">
				<a rel="next" href="<?php echo esc_url( get_attachment_link( $attachments[ $nextk ]->ID ) ); ?>"><?php echo wp_get_attachment_image( $attachments[ $nextk ]->ID, array( 70, 70 ) ); ?></a>
				<i class="icon-angle-right"></i>
			</span>
		<?php } ?>

	</div><!-- #nav-single -->
<?php

	}
}


// displays page-links for paginated posts
function tbcity_link_pages() {

	if ( ! is_singular() && tbcity_get_opt( 'post_formats_standard_content' ) != 'content' ) return;
?>
	<div class="fixfloat">
		<?php
			echo wp_link_pages( array(
				'before' => '<div class="navigation-links navigate_page"><span>' . __( 'Pages','tbcity' ) . ':</span>',
				'after' => '</div>',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'echo' => 0,
			) );
		?>
	</div>
<?php

}


// the widget area for single posts/pages
function tbcity_single_widgets_area() {

	if ( !is_singular() ) return;

	get_sidebar( 'single' );

}


// default widgets to be printed in primary sidebar
if ( !function_exists( 'tbcity_default_widgets' ) ) {
	function tbcity_default_widgets() {

		$default_widgets = array(
			'WP_Widget_Search'		=> 'widget_search',
			'WP_Widget_Meta'		=> 'widget_meta',
			'WP_Widget_Pages'		=> 'widget_pages',
			'WP_Widget_Categories'	=> 'widget_categories',
			'WP_Widget_Archives'	=> 'widget_archive',
		);

		foreach ( apply_filters( 'tbcity_default_widgets', $default_widgets ) as $widget => $class ) {
			the_widget( $widget, '', tbcity_get_default_widget_args( 'id=&class=' . $class ) );
		}

	}
}


//get a thumb for a post/page
if ( !function_exists( 'tbcity_get_the_thumb_url' ) ) {
	function tbcity_get_the_thumb_url( $post_id = 0, $step = 999 ){
		global $post;

		if ( !$post_id ) $post_id = $post->ID;

		// step #0  - has featured image
		if ( get_post_thumbnail_id( $post_id ) )
			return wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );

		$attachments = get_children( array(
			'post_parent'		=> $post_id,
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'numberposts'		=> 1,
		) );

		// step #1  - has attachments
		if ( ( $step > 1 ) && $attachments )
			return wp_get_attachment_thumb_url( key($attachments) );

		// step #2  - has gallery shortcode
		$pattern = get_shortcode_regex();

		if (
			( $step > 2 )
			&& preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
			&& array_key_exists( 2, $matches )
			&& in_array( 'gallery', $matches[2] )
		) {

			$key = array_search( 'gallery', $matches[2] );

			$attr = shortcode_parse_atts( $matches['3'][$key] );

			if ( ! empty( $attr['ids'] ) ) {
				if ( empty( $attr['orderby'] ) )
					$attr['orderby'] = 'post__in';
				$attr['include'] = $attr['ids'];
			}

			extract( shortcode_atts( array(
				'order'			=> 'ASC',
				'orderby'		=> 'menu_order ID',
				'include'		=> '',
			), $attr) );

			if ( 'RAND' == $order )
				$orderby = 'none';

			if ( ! empty( $include ) )
				$attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

			if ( isset( $attachments[0] ) )
				return wp_get_attachment_thumb_url( $attachments[0]->ID );
		}


		// step #3  - has an hardcoded <img>
		if ( ( $step > 3 ) && $img = tbcity_get_first_image() )
			return $img['src'];

		// step #4  - has a generated <img>
		if ( ( $step > 4 ) && $img = tbcity_get_first_image( false, true) )
			return $img['src'];

		// step #5  - get the header image
		if ( ( $step > 5 ) && $img = get_header_image() )
			return $img;

		// step #6  - nothing found
		return '';
	}

}


// Get first image in the post content
if ( !function_exists( 'tbcity_get_first_image' ) ) {
	function tbcity_get_first_image( $post_id = null, $filtered_content = false ) {

		$post = get_post( $post_id );

		$first_image = array( 'img' => '', 'title' => '', 'src' => '' );

		//search the images in post content
		preg_match_all( '/<img[^>]+>/i',$filtered_content ? apply_filters( 'the_content', $post->post_content ): $post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_image['img'] = $result[0][0];
			//get the title (if any)
			preg_match_all( '/(title)=(["|\'][^"|\']*["|\'])/i',$first_image['img'], $title );
			if ( isset( $title[2][0] ) ){
				$first_image['title'] = str_replace( '"','',$title[2][0] );
			}
			//get the path
			preg_match_all( '/(src)=(["|\'][^"|\']*["|\'])/i',$first_image['img'], $src );
			if ( isset( $src[2][0] ) ){
				$first_image['src'] = str_replace( array( '"', '\''),'',$src[2][0] );
			}
			return $first_image;
		} else {
			return false;
		}

	}
}


// Get first link of a post
if ( !function_exists( 'tbcity_get_first_link' ) ) {
	function tbcity_get_first_link( $post_id = null ) {

		$post = get_post( $post_id );

		$first_info = array( 'anchor' => '', 'title' => '', 'href' => '', 'text' => '' );
		//search the link in post content
		preg_match_all( "/<a\b[^>]*>(.*?)<\/a>/i",$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['anchor'] = $result[0][0];
			$first_info['text'] = isset( $result[1][0] ) ? $result[1][0] : '';
			//get the title (if any)
			preg_match_all( '/(title)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_title );
			$first_info['title'] = isset( $link_title[2][0] ) ? str_replace( array('"','\''),'',$link_title[2][0] ) : '';
			//get the path
			preg_match_all( '/(href)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_href );
			$first_info['href'] = isset( $link_href[2][0] ) ? str_replace( array('"','\''),'',$link_href[2][0] ) : '';
			return $first_info;
		} else {
			return false;
		}

	}
}


// Get first blockquote words
if ( !function_exists( 'tbcity_get_blockquote' ) ) {
	function tbcity_get_blockquote( $post_id = null, $trim = false ) {

		$post = get_post( $post_id );

		$first_quote = array( 'quote' => '', 'cite' => '' );
		//search the blockquote in post content
		preg_match_all( '/<blockquote\b[^>]*>([\w\W]*?)<\/blockquote>/',$post->post_content, $blockquote );
		//grab the first one
		if ( isset( $blockquote[0][0] ) ){
			$first_quote['quote'] = strip_tags( $blockquote[0][0] );
			if ( $trim ) {
				$words = explode( " ", $first_quote['quote'], 6 );
				if ( count( $words ) == 6 ) $words[5] = '...';
				$first_quote['quote'] = implode( ' ', $words );
			}
			preg_match_all( '/<cite>([\w\W]*?)<\/cite>/',$blockquote[0][0], $cite );
			$first_quote['cite'] = ( isset( $cite[1][0] ) ) ? $cite[1][0] : '';
			return $first_quote;
		} else {
			return false;
		}

	}
}


// get the post thumbnail or (if not set) the format related icon
if ( !function_exists( 'tbcity_get_the_thumb' ) ) {
	function tbcity_get_the_thumb( $args ) {

		$defaults = array(
			'id' => 0,
			'size_w' => 32,
			'size_h' => 0,
			'class' => '',
			'default' => '',
			'only_icon' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! $args['size_h'] ) $args['size_h'] = $args['size_w'];

		if ( $args['class'] ) $args['class'] = ' ' . $args['class'];

		if ( $args['id'] && has_post_thumbnail( $args['id'] ) && ! $args['only_icon'] ) {

			return get_the_post_thumbnail( $args['id'], array( $args['size_w'],$args['size_h'] ) );

		} else {

			if ( $args['id'] )
				$format = tbcity_get_post_format( $args['id'] );
			else
				$format = $args['default'];

			return apply_filters( 'tbcity_hook_get_the_thumb', '<i class="icon-' . $args['size_h'] . ' ' . $format . $args['class'] . '"></i>', $format, $args );

		}

	}
}


// get the post format string
if ( !function_exists( 'tbcity_get_post_format' ) ) {
	function tbcity_get_post_format( $id, $alt = 'standard' ) {

		if ( post_password_required() )
			$format = 'protected';
		else
			$format = get_post_format( $id ) ? get_post_format( $id ) : $alt;

		return $format;

	}
}


// set up custom colors and header image
if ( !function_exists( 'tbcity_setup' ) ) {
	function tbcity_setup() {

		// Register localization support
		load_theme_textdomain( 'tbcity', get_template_directory() . '/languages' );

		// Theme uses wp_nav_menu() in three location
		register_nav_menus( array( 'primary'	=> __( 'Main Navigation Menu', 'tbcity' ) ) );
		register_nav_menus( array( 'secondary1'	=> __( 'Secondary Navigation Menu #1', 'tbcity' ) ) );
		register_nav_menus( array( 'secondary2'	=> __( 'Secondary Navigation Menu #2', 'tbcity' ) ) );

		// Register Features Support
		add_theme_support( 'automatic-feed-links' );

		// Thumbnails support
		add_theme_support( 'post-thumbnails' );

		// Add the editor style
		if ( tbcity_get_opt( 'editor_style' ) ) add_editor_style( 'css/editor-style.css' );

		// This theme uses post formats
		add_theme_support( 'post-formats', apply_filters( 'tbcity_post_formats', array() ) );

		add_theme_support( 'custom-background', array(
			'default-color' => '222',
		) );

	}
}


//add a default gravatar
function tbcity_addgravatar( $avatar_defaults ) {

	$myavatar = get_template_directory_uri() . '/images/user.png';

	$avatar_defaults[$myavatar] = __( 'tbcity Default Gravatar', 'tbcity' );

	return $avatar_defaults;

}


//Display navigation to next/previous post when applicable
if ( !function_exists( 'tbcity_single_nav' ) ) {
	function tbcity_single_nav() {
		global $post;

		if ( ! is_single() || is_attachment() ) return;

		if ( ! tbcity_get_opt( 'browse_links' ) ) return;

		$next = get_next_post();
		$prev = get_previous_post();
		$next_title = get_the_title( $next ) ? ': ' . get_the_title( $next ) : '';
		$prev_title = get_the_title( $prev ) ? ': ' . get_the_title( $prev ) : '';
?>
	<div class="navigation-links nav-single fixfloat">
		<?php if ( $prev ) { ?>
			<a class="btn nav-previous" rel="prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>" title="<?php echo esc_attr( strip_tags( __( 'Previous Post', 'tbcity' ) . $prev_title ) ); ?>"><i class="icon-angle-left"></i></a>
		<?php } ?>
		<?php if ( $next ) { ?>
			<a class="btn nav-next" rel="next" href="<?php echo esc_url( get_permalink( $next ) ); ?>" title="<?php echo esc_attr( strip_tags( __( 'Next Post', 'tbcity' ) . $next_title ) ); ?>"><i class="icon-angle-right"></i></a>
		<?php } ?>
	</div><!-- #nav-single -->
<?php

	}
}


//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'tbcity_friendly_date' ) ) {
	function tbcity_friendly_date() {

		$postTime = get_the_time('U');
		$currentTime = time();
		$timeDifference = $currentTime - $postTime;

		$minInSecs = 60;
		$hourInSecs = 3600;
		$dayInSecs = 86400;
		$monthInSecs = $dayInSecs * 31;
		$yearInSecs = $dayInSecs * 366;

		//if over 2 years
		if ($timeDifference > ($yearInSecs * 2)) {
			$dateWithNiceTone = __( 'quite a long while ago...', 'tbcity' );

		//if over a year 
		} else if ($timeDifference > $yearInSecs) {
			$dateWithNiceTone = __( 'over a year ago', 'tbcity' );

		//if over 2 months
		} else if ($timeDifference > ($monthInSecs * 2)) {
			$num = round($timeDifference / $monthInSecs);
			$dateWithNiceTone = sprintf(__('%s months ago', 'tbcity' ),$num);
		
		//if over a month	
		} else if ($timeDifference > $monthInSecs) {
			$dateWithNiceTone = __( 'a month ago', 'tbcity' );
				 
		//if more than 2 days ago
		} else {
			$htd = human_time_diff( get_the_time('U'), current_time('timestamp') );
			$dateWithNiceTone = sprintf(__('%s ago', 'tbcity' ), $htd );
		} 
		
		return $dateWithNiceTone;
			
	}
}


// add links to admin bar
if ( !function_exists( 'tbcity_admin_bar_plus' ) ) {
	function tbcity_admin_bar_plus() {
		global $wp_admin_bar;

		if (!is_super_admin() || !is_admin_bar_showing() || !current_user_can( 'edit_theme_options' ) ) return;

		$add_menu_meta = array(
			'target'    => '_blank'
		);

		$wp_admin_bar->add_menu( array(
			'id'		=> 'tbcity_theme_options',
			'parent'	=> 'appearance',
			'title'		=> esc_attr( __( 'Theme Options','tbcity' ) ),
			'href'		=> get_admin_url() . 'themes.php?page=tbcity_functions',
			'meta'		=> $add_menu_meta
		) );

	}
}


// comments navigation
if ( !function_exists( 'tbcity_navigate_comments' ) ) {
	function tbcity_navigate_comments(){

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {

?>
	<div class="navigation-links navigate_comments">
		<?php if(function_exists('wp_paginate_comments')) {
			wp_paginate_comments();
		} else {
			paginate_comments_links();
		} ?>
		<br class="fixfloat" />
	</div>
<?php 

		}

	}
}


// comments-are-closed message when post type supports comments and we're not on a page
function tbcity_comments_closed() {
	if ( ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) {

?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'tbcity' ); ?></p>
<?php

	}
}


//enqueue the 'comment-reply' script
function tbcity_enqueue_comments_reply() {

	if( get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}


// Custom form fields for the comment form
function tbcity_comments_form_fields( $fields ) {

	$commenter	=	wp_get_current_commenter();
	$req		=	get_option( 'require_name_email' );
	$aria_req	=	( $req ? " aria-required='true'" : '' );

	$custom_fields =  array(
		'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
					'<label for="author">' . __( 'Name', 'tbcity' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
					'<label for="email">' . __( 'Email', 'tbcity' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'url'    => '<p class="comment-form-url">' . '<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" />' .
					'<label for="url">' . __( 'Website', 'tbcity' ) . '</label>' .'</p>',
	);

	return $custom_fields;

}


// filters comments_form() default arguments
function tbcity_comment_form_defaults( $defaults ) {

	$defaults['label_submit']		= __( 'Say It!','tbcity' );
	$defaults['comment_field']		= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
	$defaults['cancel_reply_link']	= '<i class="icon-remove-circle"></i>';

	return $defaults;

}


// add the avatar before the "logged in as..." message
function tbcity_add_avatar_to_logged_in( $text = '', $commenter = false, $user_identity = false ) {

	$avatar = is_user_logged_in() ? get_avatar( get_current_user_id(), 50, $default = get_option( 'avatar_default' ) ) . ' ' : '';

	$text = str_replace( '<p class="logged-in-as">', '<p class="logged-in-as no-grav">' . $avatar, $text );

	return $text;

}


// Display search form.
function tbcity_search_form() {

	$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
	<div>
		<label class="screen-reader-text" for="s">Search for:</label>
		<input type="text" value="' . get_search_query() . '" name="s" id="s" />
		<button class="btn" type="submit">
			<i class="icon-search"></i>
		</button>
		</div>
	</form>';

	return $form;

}


//order the categories list by count 
function tbcity_widget_categories_args( $cat_args ) {

	$cat_args['orderby'] = 'count';
	$cat_args['order'] = 'DESC';

	return $cat_args;

}


// add a fix for embed videos
if ( !function_exists( 'tbcity_wmode_transparent' ) ) {
	function tbcity_wmode_transparent($html, $url = null, $attr = null) {

		if ( strpos( $html, '<embed ' ) !== false ) {

			$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
			$html = str_replace('<embed ', '<embed wmode="transparent" ', $html);
			return $html;

		} elseif ( strpos ( $html, 'feature=oembed' ) !== false )

			return str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );

		else

			return $html;

	}
}


// custom image caption
function tbcity_img_caption_shortcode( $deprecated, $attr, $content = null ) {

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px"><div class="wp-caption-inside">'
	. do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div></div>';

}


//clear any floats at the end of post content
function tbcity_clear_float( $content ) {

	return $content . '<br class="fixfloat" />';

}


// Add specific CSS class by filter
function tbcity_body_classes($classes) {

	$classes[] = 'tb-no-js';

	if ( has_nav_menu( 'secondary1' ) )
		$classes[] = 'top-menu';

	if ( ! tbcity_get_opt( 'primary_sidebar' ) )
		$classes[] = 'no-sidebar';

	return $classes;

}


// Add specific CSS class by filter
function tbcity_post_classes($classes) {

	if ( ! is_singular() && tbcity_get_opt( 'post_formats_standard_title' ) == 'none' )
		$classes[] = 'micro-title';

	return $classes;

}


//add attachment description to thickbox
function tbcity_get_attachment_link( $markup = '', $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false ) {

	$id = intval( $id );
	$_post = get_post( $id );

	if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) )
		return __( 'Missing Attachment','tbcity' );

	if ( $permalink )
		$url = esc_url( get_attachment_link( $_post->ID ) );

	$post_title = esc_attr( $_post->post_excerpt ? $_post->post_excerpt : $_post->post_title );

	if ( $text )
		$link_text = $text;
	elseif ( $size && 'none' != $size )
		$link_text = wp_get_attachment_image( $id, $size, $icon );
	else
		$link_text = '';

	if ( trim( $link_text ) == '' )
		$link_text = $_post->post_title;

	return "<a href='$url' title='$post_title'>$link_text</a>";

}


//print credits
function tbcity_get_credits() {

	$credits = '&copy; ' . date( 'Y' ) . ' <strong>' . get_bloginfo( 'name' ) . '</strong>. ' . __( 'All rights reserved', 'tbcity' );

	if ( tbcity_get_opt('tbcred') )
		$credits .= '<br />' . sprintf( __( 'Powered by %s and %s', 'tbcity' ), '<a target="_blank" href="http://wordpress.org/" title="WordPress">WordPress</a>', '<a target="_blank" href="http://www.twobeers.net/" title="' . esc_attr( __( 'Visit theme authors homepage', 'tbcity' ) . ' @ twobeers.net' ) . '">' . esc_attr( __( 'tbcity theme', 'tbcity' ) ) . '</a>' );

	$credits = apply_filters( 'tbcity_credits', $credits );

	return $credits;

}


//filter wp_title
function tbcity_filter_wp_title( $title ) {

	if ( is_single() && empty( $title ) ) {
		$_post = get_queried_object();
		$title = tbcity_titles_filter( '', $_post->ID ) . ' &laquo; ';
	}

	// Get the Site Name
	$site_name = get_bloginfo( 'name' );

	// Append name
	$filtered_title = $title . $site_name;

	// If site front page, append description
	if ( is_front_page() ) {
		// Get the Site Description
		$site_description = get_bloginfo( 'description' );
		// Append Site Description to title
		$filtered_title .= ' - ' . $site_description;
	}

	// Return the modified title
	return $filtered_title;

}


//Add new contact methods to author panel
function tbcity_new_contactmethods( $contactmethods ) {

	$contactmethods['twitter'] = 'Twitter'; //add Twitter

	$contactmethods['facebook'] = 'Facebook'; //add Facebook

	$contactmethods['googleplus'] = 'Google+'; //add Google+

	return $contactmethods;

}


// add 'quoted on' before trackback/pingback comments link
function tbcity_add_quoted_on( $return ) {
	global $comment;

	$text = '';
	if ( get_comment_type() != 'comment' )
		$text = '<img class="avatar" width="32" src="' . get_template_directory_uri() . '/images/bot.png" alt="talking bot"/><span>' . __( 'quoted on', 'tbcity' ) . ' </span>';

	return $text . $return;

}


// strip tags and apply title format for blank titles
function tbcity_titles_filter( $title, $id = null ) {

	if ( is_admin() ) return $title;

	$title = strip_tags( $title, '<abbr><acronym><em><i><del><ins><bdo><strong>' );

	if ( $id == null ) return $title;

	if ( ! tbcity_get_opt( 'blank_title' ) ) return $title;

	if ( empty( $title ) ) {

		if ( ! tbcity_get_opt( 'blank_title_text' ) ) return __( '(no title)', 'tbcity' );
		$postdata = array( tbcity_get_post_format( $id, __( 'Post', 'tbcity' ) ), get_the_time( get_option( 'date_format' ), $id ), $id );
		$codes = array( '%f', '%d', '%n' );

		return str_replace( $codes, $postdata, tbcity_get_opt( 'blank_title_text' ) );

	} else

		return $title;

}


//set the excerpt length
function tbcity_excerpt_length( $length ) {

	return (int) tbcity_get_opt( 'excerpt_length' );

}


// use the "excerpt more" string as a link to the post
function tbcity_excerpt_more( $more ) {

	if ( is_admin() ) return $more;

	if ( tbcity_get_opt( 'excerpt_more_txt' ) )
		$more = tbcity_get_opt( 'excerpt_more_txt' );

	if ( tbcity_get_opt( 'excerpt_more_link' ) )
		$more = '<a href="' . esc_url( get_permalink() ) . '">' . $more . '</a>';

	return $more;

}


// custom text for the "more" tag
function tbcity_more_link( $more_link, $more_link_text ) {

	if ( tbcity_get_opt( 'more_tag' ) && !is_admin() ) {

		$text = str_replace ( '%t', get_the_title(), tbcity_get_opt( 'more_tag' ) );

		$more_link = str_replace( $more_link_text, $text, $more_link );

	}

	return '<br />' . $more_link;

}


/**
 * Add parent class to wp_page_menu top parent list items
 */
function tbcity_add_parent_class( $css_class, $page, $depth, $args ) {

	if ( ! empty( $args['has_children'] ) && $depth == 0 )
		$css_class[] = 'menu-item-parent';

	return $css_class;

}


/**
 * Add parent class to wp_nav_menu top parent list items
 */
function tbcity_add_menu_parent_class( $items ) {

	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			if ( ! $item->menu_item_parent )
				$item->classes[] = 'menu-item-parent'; 
		}
	}

	return $items;    
}


/**
 * Add 'selected' class to current page item in menus
 */
function tbcity_add_selected_class_to_page_item( $css_class ) {

	if ( in_array( 'current_page_item', $css_class ) ) $css_class[] = 'selected';

	return $css_class;    
}


/**
 * Add 'selected' class to current menu item in menus
 */
function tbcity_add_selected_class_to_menu_item( $classes, $item ) {

	if ( $item->current )
		$classes[] = 'selected';

	return $classes;    
}


// wrap the categories count with a span
function tbcity_wrap_categories_count( $output ) {
	$pattern = '/<\/a>(\s|&nbsp;)(\(\d+\))/i';
	$replacement = ' <span class="details">$2</span></a>';
	return preg_replace( $pattern, $replacement, $output );
}


// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'tbcity_allcat' ) ) {
	function tbcity_allcat () {

		if( tbcity_is_allcat() ) {

			get_template_part( 'allcat' );

			exit;

		}

	}
}


//replace the comment_reply_link text
function tbcity_comment_reply_link( $link ) {

	preg_match_all( '/<a\b[^>]*>(.*?)<\/a>/',$link, $text );

	if ( isset( $text[1][0] ) )
		$link = str_replace( '>' . $text[1][0], ' title="' . esc_attr( __( 'Reply to comment', 'tbcity' ) ) . '" >' . __( 'Reply', 'tbcity' ) . ' <i class="icon-share-alt"></i>', $link);

	return $link;

}


function tbcity_non_empty_excerpt( $excerpt ) {

	$output = trim(str_replace( array('<p>','</p>','&nbsp;'), '', $excerpt));

	if ( empty( $output ) )
		$excerpt = '';

	return $excerpt;

}


// display the second secondary menu
function tbcity_primary_menu() {

	if ( ! tbcity_get_opt( 'main_menu' ) ) return;

	wp_nav_menu( array(
		'container'			=> false,
		'menu_id'			=> 'mainmenu',
		'menu_class'		=> 'nav-menu',
		'fallback_cb'		=> 'tbcity_pages_menu',
		'theme_location'	=> 'primary',
	) );

}


// display the first secondary menu
function tbcity_1st_secondary_menu() {

	wp_nav_menu( array(
		'container'			=> false,
		'menu_id'			=> 'secondary1',
		'menu_class'		=> 'nav-menu',
		'fallback_cb'		=> false,
		'theme_location'	=> 'secondary1',
		'depth'				=> 1
	) );

}


// display the second secondary menu
function tbcity_2nd_secondary_menu() {

	wp_nav_menu( array(
		'container'			=> false,
		'menu_id'			=> 'secondary2',
		'menu_class'		=> 'nav-menu',
		'fallback_cb'		=> false,
		'theme_location'	=> 'secondary2',
		'depth'				=> 1
	) );

}


//add "Home" link
function tbcity_add_home_link( $items = '', $args = null ) {

	if ( ! $items ) return $items;

	$defaults = array(
		'theme_location' => 'undefined',
		'before' => '',
		'after' => '',
		'link_before' => '',
		'link_after' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ( $args['theme_location'] === 'primary' ) && ( 'posts' == get_option( 'show_on_front' ) ) ) {
		if ( is_front_page() || is_single() )
			$class = ' selected';
		else
			$class = '';

		$homeMenuItem =
				'<li class="navhome' . $class . '">' .
				$args['before'] .
				'<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr__( 'Home', 'tbcity' ) . '">' .
				$args['link_before'] . __( 'Home', 'tbcity' ) . $args['link_after'] .
				'</a>' .
				$args['after'] .
				'</li>';

		$items = $homeMenuItem . $items;
	}

	return $items;

}


// Pages Menu
if ( !function_exists( 'tbcity_pages_menu' ) ) {
	function tbcity_pages_menu() {

		$menu = tbcity_add_home_link( $items = wp_list_pages( 'sort_column=menu_order&title_li=&echo=0' ), $args = 'theme_location=primary' );

		if ( ! $menu ) return;

		echo '<ul id="mainmenu" class="nav-menu">' . $menu . '</ul>';

	}
}


function tbcity_get_colors( $variant, $to_string = true ) {
	static $colors;

	if ( ! isset( $colors ) ) {

		$hue = tbcity_get_opt( 'colors' );
		$colors[] = tbcity_colorHSLToRGB( $hue/360, 0.94, 0.76 );
		$colors[] = tbcity_colorHSLToRGB( $hue/360, 0.94, 0.60 );
		$colors[] = tbcity_colorHSLToRGB( $hue/360, 0.94, 0.36 );
		$colors[] = tbcity_colorHSLToRGB( $hue/360, 0.08, 0.40 );

	}

	if ( $to_string ) {
		$hex = "#";
		$hex.= str_pad(dechex($colors[$variant]['r']), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($colors[$variant]['g']), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($colors[$variant]['b']), 2, "0", STR_PAD_LEFT);
		return $hex;
	} else {
		return $colors[$variant];
	}

}


function tbcity_colorHSLToRGB( $h, $s, $l ){

	$r = $l;
	$g = $l;
	$b = $l;
	$v = ( $l <= 0.5 ) ? ( $l * ( 1.0 + $s ) ) : ( $l + $s - $l * $s );
	if ( $v > 0 ){

		$m = $l + $l - $v;
		$sv = ( $v - $m ) / $v;
		$h *= 6.0;
		$sextant = floor( $h );
		$fract = $h - $sextant;
		$vsf = $v * $sv * $fract;
		$mid1 = $m + $vsf;
		$mid2 = $v - $vsf;

		switch ( $sextant )
		{
			case 0:
				$r = $v;
				$g = $mid1;
				$b = $m;
				break;
			case 1:
				$r = $mid2;
				$g = $v;
				$b = $m;
				break;
			case 2:
				$r = $m;
				$g = $v;
				$b = $mid1;
				break;
			case 3:
				$r = $m;
				$g = $mid2;
				$b = $v;
				break;
			case 4:
				$r = $mid1;
				$g = $m;
				$b = $v;
				break;
			case 5:
				$r = $v;
				$g = $m;
				$b = $mid2;
				break;
		}
	}
	return array( 'r' => round( $r * 255.0 ), 'g' => round( $g * 255.0 ), 'b' => round( $b * 255.0 ) );
}
