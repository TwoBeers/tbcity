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
							'colors'		=> __( 'Colors' , 'tbcity' ),
							'index'			=> __( 'Posts archives' , 'tbcity' ),
							'content'		=> __( 'Contents' , 'tbcity' ),
							'widgets'		=> __( 'Sidebars and Widgets' , 'tbcity' ),
							'javascript'	=> __( 'Javascript' , 'tbcity' ),
							'mobile'		=> __( 'Mobile' , 'tbcity' ),
							'header'		=> __( 'Header' , 'tbcity' ),
							'other'			=> __( 'Other' , 'tbcity' )
	);
	$tbcity_groups = apply_filters( 'tbcity_options_groups', $tbcity_groups );

	$tbcity_coa = array(
		'colors_link_wrap' => array(
							'group'			=> 'colors',
							'type'			=> '',
							'default'		=> '',
							'description'	=> __( 'links colors', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> array('colors_link', 'colors_link_hover', 'colors_link_sel')
		),
		'colors_link' => array(
							'group'			=> 'colors',
							'type'			=> 'col',
							'default'		=> '#21759b',
							'description'	=> '',
							'info'			=> __( 'links', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false
		),
		'colors_link_hover' => array(
							'group'			=> 'colors',
							'type'			=> 'col',
							'default'		=> '#404040',
							'description'	=> '',
							'info'			=> __( 'highlighted links', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'colors_link_sel' => array(
							'group'			=> 'colors',
							'type'			=> 'col',
							'default'		=> '#87CEEB',
							'description'	=> '',
							'info'			=> __( 'selected links', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'post_collapse' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'post content collapsed', 'tbcity' ),
							'info'			=> '',
							'req'			=> '' 
		),
		'post_formats' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'post formats support', 'tbcity' ),
							'info'			=> '<a href="http://codex.wordpress.org/Post_Formats" target="_blank">WordPress Codex : Post Formats</a>',
							'req'			=> '' 
		),
		'post_formats_standard' => array(
							'group'			=> 'index',
							'type'			=> 'gro',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'standard' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'standard' ) . '&quot;' ),
							'sub'			=> array('post_formats_standard_title', 'post_formats_standard_content'),
							'req'			=> '' 
		),
		'post_formats_standard_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'post title',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array('post title', 'post date', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> '',
							'sub'			=> false 
		),
		'post_formats_standard_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'content',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> '',
							'sub'			=> false 
		),
		'post_formats_aside' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'aside' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'aside' ) . '&quot;' ),
							'req'			=> 'post_formats' 
		),
		'post_formats_audio' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'audio' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'audio' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_audio_title', 'post_formats_audio_content') 
		),
		'post_formats_audio_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'first link text',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'post title', 'post date', 'first link text', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'first link text', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_audio',
							'sub'			=> false 
		),
		'post_formats_audio_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'audio player',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'audio player', 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'audio player', 'tbcity' ),__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_audio',
							'sub'			=> false 
		),
		'post_formats_chat' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'chat' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'chat' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_audio_title', 'post_formats_audio_content') 
		),
		'post_formats_gallery' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'gallery' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'gallery' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_gallery_title', 'post_formats_gallery_content') 
		),
		'post_formats_gallery_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'none',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array('post title', 'post date', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_gallery',
							'sub'			=> false 
		),
		'post_formats_gallery_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'presentation',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'presentation', 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'presentation', 'tbcity' ),__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_gallery',
							'sub'			=> false 
		),
		'post_formats_image' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'image' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'image' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_image_title', 'post_formats_image_content') 
		),
		'post_formats_image_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'first image title',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'post title', 'post date', 'first image title', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'first image title', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_image',
							'sub'			=> false 
		),
		'post_formats_image_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'first image',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'first image', 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'first image', 'tbcity' ),__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_gallery',
							'sub'			=> false 
		),
		'post_formats_link' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'link' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'link' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_link_title', 'post_formats_link_content') 
		),
		'post_formats_link_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'first link text',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'post title', 'post date', 'first link text', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'first link text', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_link',
							'sub'			=> false 
		),
		'post_formats_link_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'none',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_gallery',
							'sub'			=> false 
		),
		'post_formats_quote' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'quote' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'quote' ) . '&quot;' ),
							'req'			=> 'post_formats',
							'sub'			=> array('post_formats_quote_title', 'post_formats_quote_content') 
		),
		'post_formats_quote_title' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'short quote excerpt',
							'description'	=> __( 'title', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'post title', 'post date', 'short quote excerpt', 'none'),
							'options_l10n'	=> array(__( 'post title', 'tbcity' ),__( 'post date', 'tbcity' ),__( 'short quote excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_quote',
							'sub'			=> false 
		),
		'post_formats_quote_content' => array(
							'group'			=> 'index',
							'type'			=> 'sel',
							'default'		=> 'content',
							'description'	=> __( 'content', 'tbcity' ),
							'info'			=> '',
							'options'		=> array( 'content', 'excerpt', 'none'),
							'options_l10n'	=> array(__( 'content', 'tbcity' ),__( 'excerpt', 'tbcity' ),__( 'none', 'tbcity' )),
							'req'			=> 'post_formats_gallery',
							'sub'			=> false 
		),
		'post_formats_status' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'status' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'status' ) . '&quot;' ),
							'req'			=> 'post_formats' 
		),
		'post_formats_video' => array(
							'group'			=> 'index',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> get_post_format_string( 'video' ),
							'info'			=> sprintf( __( '%s format posts', 'tbcity' ), '&quot;' . get_post_format_string( 'video' ) . '&quot;' ),
							'req'			=> 'post_formats' 
		),
		'blank_title' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'blank titles', 'tbcity' ),
							'info'			=> __( 'set the standard text for blank titles', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array('blank_title_text') 
		),
		'blank_title_text' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> __( '(no title)', 'tbcity' ),
							'description'	=> __( 'default text', 'tbcity' ),
							'info'			=> __( '<br />you may use these codes:<br /><code>%d</code> for post date<br /><code>%f</code> for post format (if any)<br /><code>%n</code> for post id', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false
		),
		'excerpt' => array(
							'group'			=> 'content',
							'type'			=> '',
							'default'		=> '',
							'description'	=> __( 'excerpt', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> array('excerpt_length', 'excerpt_more_txt', 'excerpt_more_link') 
		),
		'excerpt_length' => array(
							'group'			=> 'content',
							'type'			=> 'int',
							'default'		=> 55,
							'description'	=> __( 'excerpt length', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false 
		),
		'excerpt_more_txt' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> '[...]',
							'description'	=> __( '<em>excerpt more</em> string', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false 
		),
		'excerpt_more_link' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( '<em>excerpt more</em> linked', 'tbcity' ),
							'info'			=> __( 'use the <em>excerpt more</em> string as a link to the full post', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'more_tag' => array(
							'group'			=> 'content',
							'type'			=> 'txt',
							'default'		=> __( '(more...)', 'tbcity' ),
							'description'	=> __( '"more" tag string', 'tbcity' ),
							'info'			=> __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'tbcity' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
							'req'			=> ''
		),
		'browse_links' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'quick browsing links', 'tbcity' ),
							'info'			=> __( 'show navigation links before post content', 'tbcity' ),
							'req'			=> '' 
		),
		'post_info' => array(
							'group'			=> 'content',
							'type'			=> '',
							'default'		=> '',
							'description'	=> __( 'Post details', 'tbcity' ),
							'info'			=> __( 'show post details in index view, right before the post content<br />in single post view you can use the <strong>Post details</strong> widget', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array( 'post_date', 'post_cat', 'post_tag' )
		),
		'post_date' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'date', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'post_cat' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'categories', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'post_tag' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'tags', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'featured_title' => array(
							'group'			=> 'content',
							'type'			=> 'sel',
							'default'		=> 'lists',
							'description'	=> __( 'enhanced post title', 'tbcity' ),
							'info'			=> __( 'use the featured image as background for the post title', 'tbcity' ),
							'options'		=> array('lists', 'single', 'both', 'none'),
							'options_l10n'	=> array(__('in lists', 'tbcity'),__('in single posts/pages', 'tbcity'),__('both', 'tbcity'),__('none', 'tbcity')),
							'req'			=> '',
							'sub'			=> array('featured_title_thumb') 
		),
		'featured_title_thumb' => array(
							'group'			=> 'content',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'thumbnail', 'tbcity' ),
							'info'			=> 'use small thumbnail instead of the full image',
							'req'			=> '',
							'sub'			=> false 
		),
		'custom_widgets' => array(
							'group'			=> 'widgets',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'custom widgets', 'tbcity' ),
							'info'			=> __( 'add a lot of new usefull widgets', 'tbcity' ),
							'req'			=> '' 
		),
		'jsani' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'javascript features', 'tbcity' ),
							'info'			=> __( 'try disable all javascript features if you encountered problems with javascript', 'tbcity' ),
							'req'			=> '' 
		),
		'js_basic' => array(
							'group'			=> 'javascript',
							'type'			=> '',
							'default'		=> 1,
							'description'	=> __( 'basic animations', 'tbcity' ),
							'info'			=> '',
							'req'			=> 'jsani',
							'sub'			=> array('js_basic_menu', 'js_basic_autoscroll', 'js_basic_video_resize') 
		),
		'js_basic_menu' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'main menu', 'tbcity' ),
							'info'			=> __( 'fade in/out menu subitems', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false 
		),
		'js_basic_autoscroll' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'scroll', 'tbcity' ),
							'info'			=> __( 'smooth scroll to top/bottom when click top/bottom buttons', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false 
		),
		'js_basic_video_resize' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'video resize', 'tbcity' ),
							'info'			=> __( 'resize embeded video when window resizes', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> false 
		),
		'js_thickbox' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'thickbox preview', 'tbcity' ),
							'info'			=> __( 'add the thickbox effect to each linked image and galleries in post content', 'tbcity' ),
							'req'			=> 'jsani',
							'sub'			=> array('js_thickbox_force') 
		),
		'js_thickbox_force' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'replace links', 'tbcity' ),
							'info'			=> __( 'force galleries to use links to image instead of links to attachment', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'js_swfplayer' => array( 
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'swf audio player', 'tbcity' ),
							'info'			=> __( 'create an audio player for linked audio files (mp3,ogg and m4a) in the audio format posts', 'tbcity' ),
							'req'			=> 'jsani' 
		),
		'quotethis' => array(
							'group'			=> 'javascript',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'quote link', 'tbcity' ),
							'info'			=> __( 'show a link for easily add the selected text as a quote inside the comment form', 'tbcity' ),
							'req'			=> 'jsani' 
		),
		'mobile_css' => array(
							'group'			=> 'mobile',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'mobile support', 'tbcity' ),
							'info'			=> __( 'use a dedicated style in mobile devices', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array('mobile_css_color')
		),
		'mobile_css_color' => array(
							'group'			=> 'mobile',
							'type'			=> 'opt',
							'default'		=> 'light',
							'options'		=> array('light', 'dark'),
							'options_l10n' => array('<img src="' . get_template_directory_uri() . '/images/mobile-light.png" alt="light" />', '<img src="' . get_template_directory_uri() . '/images/mobile-dark.png" alt="dark" />'),
							'description'	=> __( 'colors', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'geo_location' => array(
							'group'			=> 'header',
							'type'			=> '',
							'default'		=> 1,
							'description'	=> __( "Blog's geographical location", 'tbcity' ),
							'info'			=> 'Latitude and longitude are needed to show the correct changes in light (sunrise, day, sunset, night) in the header of the blog.<br />You can use <a href="http://itouchmap.com/latlong.html" target="_blank">itouchmap.com</a> to find yours',
							'req'			=> '',
							'sub'			=> array( 'latitude', 'longitude' ) 
		),
		'latitude' => array(
							'group'			=> 'header',
							'type'			=> 'int',
							'range'			=> array( 'min' => -90, 'max' => 90 ),
							'default'		=> 46,
							'description'	=> __( 'latitude', 'tbcity' ),
							'info'			=> __( 'Default to North (46), use a negative value for South. Must be between -90 to 90', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'longitude' => array(
							'group'			=> 'header',
							'type'			=> 'int',
							'range'			=> array( 'min' => -90, 'max' => 90 ),
							'default'		=> 13,
							'description'	=> __( 'longitude', 'tbcity' ),
							'info'			=> __( 'Default to East (13), use a negative value for West. Must be between -180 to 180', 'tbcity' ),
							'req'			=> '',
							'sub'			=> false 
		),
		'font_family' => array(							'group'			=> 'other',							'type'			=> 'sel',							'default'		=> 'monospace',							'description'	=> __( 'font family', 'tbcity' ),							'info'			=> '',							'options'		=> array('monospace', 'Arial, sans-serif', 'Helvetica, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif'),							'options_l10n'	=> array('monospace', 'Arial, sans-serif', 'Helvetica, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif'),
							'req'			=> '',
							'sub'			=> array('font_size') 
		),
		'font_size' => array(							'group'			=> 'other',							'type'			=> 'sel',							'default'		=> '14px',							'description'	=> __( 'font size', 'tbcity' ),							'info'			=> '',							'options'		=> array('10px', '11px', '12px', '13px', '14px', '15px', '16px'),							'options_l10n'	=> array('10px', '11px', '12px', '13px', '14px', '15px', '16px'),
							'req'			=> '',
							'sub'			=> false 
		),
		'google_font_family' => array(
							'group'			=> 'other',
							'type'			=> 'txt',
							'default'		=> '',
							'description'	=> __( 'Google web font', 'tbcity' ),
							'info'			=> __( 'Copy and paste <a href="http://www.google.com/webfonts" target="_blank"><strong>Google web font</strong></a> name here. Example: <code>Architects Daughter</code>', 'tbcity' ),
							'req'			=> '',
							'sub'			=> array( 'google_font_body', 'google_font_post_title', 'google_font_post_content' )
		),
		'google_font_body' => array(
							'group'			=> 'other',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'for whole site', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'google_font_post_title' => array(
							'group'			=> 'other',
							'type'			=> 'chk',
							'default'		=> 1,
							'description'	=> __( 'for posts/pages title', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'google_font_post_content' => array(
							'group'			=> 'other',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> __( 'for posts/pages content', 'tbcity' ),
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'plusone' => array(							'group'			=> 'other',							'type'			=> 'chk',							'default'		=> 1,							'description'	=> '<a href="https://plus.google.com/" target="_blank">Google +1</a>',							'info'			=> __( 'integrates the +1 feature for your contents', 'tbcity' ),							'req'			=> '',
							'sub'			=> array('plusone_official')
		),
		'plusone_official' => array(
							'group'			=> 'other',
							'type'			=> 'chk',
							'default'		=> 0,
							'description'	=> 'use the official button',
							'info'			=> '',
							'req'			=> '',
							'sub'			=> false
		),
		'editor_style' => array(							'group'			=> 'other',							'type'			=> 'chk',							'default'		=> 1,							'description'	=> __( 'editor style', 'tbcity' ),							'info'			=> __( "add style to the editor in order to write the post exactly how it will appear on the site", 'tbcity' ),							'req'			=> '' 
		),
		'custom_css' => array(
							'group'			=> 'other',
							'type'			=> 'txtarea',
							'default'		=> '',
							'description'	=> __( 'custom CSS code', 'tbcity' ),
							'info'			=> __( '<strong>For advanced users only</strong>: paste here your custom css code. it will be added after the defatult style', 'tbcity' ) . ' (<a href="'. get_stylesheet_uri() .'" target="_blank">style.css</a>)',
							'req'			=> ''
		),
		'tbcred' => array(							'group'			=> 'other',							'type'			=> 'chk',							'default'		=> 1,							'description'	=> __( 'theme credits', 'tbcity' ),							'info'			=> __( 'It is completely optional, but if you like the Theme we would appreciate it if you keep the credit link at the bottom', 'tbcity' ),							'req'			=> '' 
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
