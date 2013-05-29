<?php
/**
 * options.php
 *
 * the options array
 *
 * @package The Black City
 * @since 2.03
 */


// Complete Options Array, with type, defaults values, description, infos and required option
function tbcity_get_coa( $option = false ) {

	$tbcity_groups = array(
							'style'			=> __( 'Style' , 'tbcity' ),
							'content'		=> __( 'Contents' , 'tbcity' ),
							'javascript'	=> __( 'Javascript' , 'tbcity' ),
							'header'		=> __( 'Header Animation' , 'tbcity' ),
							'other'			=> __( 'Other' , 'tbcity' ),
	);
	$tbcity_groups = apply_filters( 'tbcity_options_groups', $tbcity_groups );

	$tbcity_coa = array(
		'colors' => array(
							'group'			=> 'style',
							'type'			=> 'hue',
							'default'		=> '203',
							'range'			=> array( 'min' => 0, 'max' => 360 ),
							'description'	=> 'Color',
							'info'			=> __( 'hue [0-360]', 'tbcity' ),
							'req'			=> '',
		),
		'post_formats_standard' => array(
							'group'			=> 'content',
							'type'			=> '',
							'default'		=> 1,
							'description'	=> __( 'Posts' , 'tbcity' ),
							'info'			=> 'set the title and content in posts indexes (blog, archive, search)',
							'sub'			=> array( 'post_formats_standard_title', 'post_formats_standard_content' ),
							'req'			=> '',
		),
		'post_formats_standard_title' => array(
							'group'			=> 'content',
							'type'			=> 'sel',
							'default'		=> 'title',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'title', 'date', 'none' ),
							'options_l10n'	=> array( __( 'post title', 'tbcity' ), __( 'post date', 'tbcity' ), __( 'none', 'tbcity' ) ),
							'req'			=> '',
							'sub'			=> false,
		),
		'post_formats_standard_content' => array(
							'group'			=> 'content',
							'type'			=> 'sel',
							'default'		=> 'content',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'content', 'excerpt', 'none' ),
							'options_l10n'	=> array( __( 'content', 'tbcity' ), __( 'excerpt', 'tbcity' ), __( 'none', 'tbcity' ) ),
							'req'			=> '',
							'sub'			=> false,
		),
		'blank_title' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'blank titles', 'tbcity' ),
							'info'			=> __( 'set the standard text for blank titles', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array( 'blank_title_text' ),
		),
		'blank_title_text' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> '%f %d',
							'description'	=> __( 'default text', 'tbcity' ),
							'info'			=> __( '<br />you may use these codes:<br /><code>%d</code> for post date<br /><code>%f</code> for post format (if any)<br /><code>%n</code> for post id', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false,
		),
		'excerpt' => array(
							'group'			=> 'content',
							'type'			=> '',
							'default'		=> '',
							'description'	=> __( 'excerpt', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> array( 'excerpt_length', 'excerpt_more_txt', 'excerpt_more_link' ),
		),
		'excerpt_length' => array(
							'group'			=> 'content',
							'type'			=> 'int',
							'default'		=> 55,
							'description'	=> __( 'excerpt length', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'excerpt_more_txt' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> '[...]',
							'description'	=> __( '<em>excerpt more</em> string', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'excerpt_more_link' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( '<em>excerpt more</em> linked', 'tbcity' ),
							'info'			=> __( 'use the <em>excerpt more</em> string as a link to the full post', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false,
		),
		'more_tag' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> __( '(more...)', 'tbcity' ),
							'description'	=> __( '"more" tag string', 'tbcity' ),
							'info'			=> __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'tbcity' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
							'req'			=> '',
		),
		'browse_links' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'quick browsing links', 'tbcity' ),
							'info'			=> __( 'show navigation links before post content', 'tbcity' ),
							'req'			=> '',
		),
		'post_info' => array(
							'group'			=> 'content',
							'type'			=> '',
							'default'		=> '',
							'description'	=> __( 'Post details', 'tbcity' ),
							'info'			=> __( 'show post details after the title', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array( 'post_comments', 'post_cat', 'post_tag' ),
		),
		'post_comments' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'comments', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'post_cat' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'categories', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'post_tag' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'tags', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'featured_title' => array(
							'group'			=> 'content',
							'type'			=> 'sel',
							'default'		=> 'thumbnail',
							'description'	=> __( 'enhanced post title', 'tbcity' ),
							'info'			=> __( 'enhance the posts title by adding a little image before', 'tbcity' ),
							'options'		=> array( 'avatar', 'thumbnail', 'none' ),
							'options_l10n'	=> array( __( 'author avatar', 'tbcity' ), __( 'post thumbnail', 'tbcity' ), __( 'none', 'tbcity' ) ),
							'req'			=> '',
		),
		'custom_widgets' => array(
							'group'			=> 'other',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'custom widgets', 'tbcity' ),
							'info'			=> __( 'add a lot of new usefull widgets', 'tbcity' ),
							'req'			=> '',
		),
		'jsani' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'javascript features', 'tbcity' ),
							'info'			=> __( 'try disable all javascript features if you encountered problems with javascript', 'tbcity' ),
							'req'			=> '',
		),
		'js_basic' => array(
							'group'			=> 'javascript',
							'type'			=> '',
							'default'		=> 1,
							'description'	=> __( 'basic animations', 'tbcity' ),
							'info'			=> '',
							'req'			=> 'jsani',
							'sub'			=> array( 'js_basic_menu', 'js_basic_extra_info', 'js_basic_quickbar', 'js_basic_autoscroll', 'js_basic_video_resize' ),
		),
		'js_basic_menu' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'main menu', 'tbcity' ),
							'info'			=> __( 'fade in/out menu subitems', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false,
		),
		'js_basic_extra_info' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'post details', 'tbcity' ),
							'info'			=> __( 'slide up/down post details', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false,
		),
		'js_basic_quickbar' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'quickbar', 'tbcity' ),
							'info'			=> __( 'animate the quickbar', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false,
		),
		'js_basic_autoscroll' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'scroll', 'tbcity' ),
							'info'			=> __( 'smooth scroll to top/bottom when click top/bottom buttons', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false,
		),
		'js_basic_video_resize' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'video resize', 'tbcity' ),
							'info'			=> __( 'resize embeded video when window resizes', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false,
		),
		'quotethis' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'quote link', 'tbcity' ),
							'info'			=> __( 'show a link for easily add the selected text as a quote inside the comment form', 'tbcity' ),
							'req'			=> 'jsani',
		),
		'geo_location' => array(
							'group'			=> 'header',
							'type'			=> '',
							'default'		=> 1,
							'description'	=> __( "Blog's geographical location", 'tbcity' ),
							'info'			=> 'Latitude and longitude are needed to show the correct changes in light (sunrise, day, sunset, night) in the header of the blog.<br />You can use <a href="http://itouchmap.com/latlong.html" target="_blank">itouchmap.com</a> to find yours',
							'req'			=> '',
							'sub'			=> array( 'latitude', 'longitude' ),
		),
		'latitude' => array(
							'group'			=> 'header',
							'type'			=> 'int',
							'range'			=> array( 'min' => -90, 'max' => 90 ),
							'default'		=> 46,
							'description'	=> __( 'latitude', 'tbcity' ),
							'info'			=> __( 'Default to North (46), use a negative value for South. Must be between -90 to 90', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false,
		),
		'longitude' => array(
							'group'			=> 'header',
							'type'			=> 'int',
							'range'			=> array( 'min' => -90, 'max' => 90 ),
							'default'		=> 13,
							'description'	=> __( 'longitude', 'tbcity' ),
							'info'			=> __( 'Default to East (13), use a negative value for West. Must be between -180 to 180', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false,
		),
		'font_family' => array(							'group'			=> 'style',							'type'			=> 'sel',							'default'		=> 'Verdana, Geneva, sans-serif',							'description'	=> __( 'font family', 'tbcity' ),							'info'			=> '',							'options'		=> array( 'monospace', 'Arial, sans-serif', 'Helvetica, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),							'options_l10n'	=> array( 'monospace', 'Arial, sans-serif', 'Helvetica, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),
							'req'			=> '',
							'sub'			=> array( 'font_size' ),
		),
		'font_size' => array(							'group'			=> 'style',							'type'			=> 'sel',							'default'		=> '14',							'description'	=> __( 'font size', 'tbcity' ),							'info'			=> '',							'options'		=> array( '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20' ),							'options_l10n'	=> array( '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px', '19px', '20px' ),
							'req'			=> '',
							'sub'			=> false,
		),
		'google_font_family' => array(
							'group'			=> 'style',
							'type'			=> 'txt',
							'default'		=> 'Open Sans',
							'description'	=> __( 'Google web font', 'tbcity' ),
							'info'			=> __( 'Copy and paste <a href="http://www.google.com/webfonts" target="_blank"><strong>Google web font</strong></a> name here. Example: <code>Open Sans</code>', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array( 'google_font_body', 'google_font_post_title', 'google_font_post_content' ),
		),
		'google_font_body' => array(
							'group'			=> 'style',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'for whole site', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'google_font_post_title' => array(
							'group'			=> 'style',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'for posts/pages title', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'google_font_post_content' => array(
							'group'			=> 'style',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'for posts/pages content', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false,
		),
		'editor_style' => array(							'group'			=> 'other',							'type'			=> 'chk',							'default'		=> 1,							'description'	=> __( 'editor style', 'tbcity' ),							'info'			=> __( "add style to the editor in order to write the post exactly how it will appear on the site", 'tbcity' ),							'req'			=> '',
		),
		'tbcred' => array(							'group'			=> 'other',							'type'			=> 'chk',							'default'		=> 1,							'description'	=> __( 'theme credits', 'tbcity' ),							'info'			=> __( 'It is completely optional, but if you like the Theme we would appreciate it if you keep the credit link at the bottom', 'tbcity' ),							'req'			=> '',
		)
	);
	$tbcity_coa = apply_filters( 'tbcity_options_array', $tbcity_coa );

	if ( $option == 'groups' )
		return $tbcity_groups;
	elseif ( $option )
		return isset( $tbcity_coa[$option] ) ? $tbcity_coa[$option] : false;
	else
		return $tbcity_coa;
}


// retrive the required option. If the option ain't set, the default value is returned
if ( !function_exists( 'tbcity_get_opt' ) ) {
	function tbcity_get_opt( $opt ) {
		global $tbcity_opt;

		if ( isset( $tbcity_opt[$opt] ) ) return apply_filters( 'tbcity_option_override', $tbcity_opt[$opt], $opt );

		$defopt = tbcity_get_coa( $opt );

		if ( ! $defopt ) return null;

		if ( ( $defopt['req'] == '' ) || ( tbcity_get_opt( $defopt['req'] ) ) )
			return $defopt['default'];
		else
			return null;

	}
}
