<?php

// Get Recent Comments
function get_tbcity_recentcomments() {
$comments = get_comments('status=approve&number=24&type=comment');

if ($comments) {
    foreach ($comments as $comment) {
		$post_title = get_the_title($comment->comment_post_ID);
		if (strlen($post_title)>35) {
			$post_title_short = substr($post_title,0,35) . '&hellip;';
		} else {
			$post_title_short = $post_title;
		}
		if ($post_title_short == "") {
			$post_title_short = __('(no title)');
		}
        echo '<li><span class="intr">'. $comment->comment_author . ' in </span><a href="' . get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . $post_title_short . '</a></li>';
    }
}
else{
	_e('<li>No comments yet</li>','tbcity');
}
}

// Get Recent Entries
function get_tbcity_recententries() {
 $lastposts = get_posts('numberposts=24');
if ($lastposts) {
	 foreach($lastposts as $post) :
		setup_postdata($post);
		$post_title = esc_html($post->post_title);
		if (strlen($post_title)>35) {
			$post_title_short = substr($post_title,0,35) . '&hellip;';
		} else {
			$post_title_short = $post_title;
		}
		if ($post_title_short == "") {
			$post_title_short = __('(no title)');
		}
		echo "<li><a href=\"".get_permalink($post->ID)."\" title=\"$post_title\">$post_title_short</a><span class=\"intr\"> " . __('by','tbcity') . " ".get_the_author().'</span></li>';
	endforeach;
}
else{
	_e('<li>No posts yet</li>','tbcity');
}
}


// Multi page
function tbcity_multipages(){
	global $post;	
	if(!$post->post_parent){
		$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
		$ancestors = $post->ID;
	}else{
		if($post->ancestors){
			$ancestors = end($post->ancestors);
			$children = wp_list_pages("title_li=&child_of=".$ancestors."&echo=0");
		}
	}
	if($children){
		echo "					
					<div class=\"navPages_cont\">
						<div class=\"navPages\">
							<div class=\"h_trig\">hierarchy</div>
							<ul class=\"navPages_ul\">
								<li><a href=\"".get_permalink($ancestors)."\" title=\"".get_the_title($ancestors)."\">".get_the_title($ancestors)."</a>
									<ul class=\"children\">$children</ul>
								</li>
							</ul>
						</div>
					</div>";
	}
}

// display a comment-like system message
function tbcity_bot_msg($saywhat) { ?>
					<div class="comment-body">
						<img class="avatar" src="<?php bloginfo('stylesheet_directory'); ?>/images/bot.png" width="32" height="32">
						<div class="comment-author vcard">
							<cite class="fn">
								<a href="<?php  bloginfo('url'); ?>" rel="external nofollow" class="url"><?php bloginfo('name'); ?>'s bot</a>
							</cite>
						</div>
						<div class="comment-meta commentmetadata">
							<?php _e('system message','tbcity'); ?>
						</div>
						<?php echo $saywhat; ?>
					</div>
<?php
}

// custom get_the_content. Not used but useful
function tbcity_get_the_content () {
	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

// Get portrait images
function get_tbcity_portrait_images(){

	if(get_option('tbcity_dir_portrait')) {
		$folder = get_option('tbcity_dir_portrait');
	}else{
		$folder = "portrait";
	}
	
	if(get_option('tbcity_num_portrait')) {
		$tbcity_num_portrait = get_option('tbcity_num_portrait');
	}else{
		$tbcity_num_portrait = 1;
	}
	
	$rand_portrait = rand(1,$tbcity_num_portrait);
	$rand_portrait = zeroise($rand_portrait,2);
	
	$url = bloginfo('stylesheet_directory')."/images/$folder/image$rand_portrait.jpg";
	
	echo $url;
}

// Disable flash header user side
function tbcity_disable_flash_header_user(){
	?>
	<script typw="text/javascript">
		function getCookie(c_name){
		if (document.cookie.length>0)
		  {
		  c_start=document.cookie.indexOf(c_name + "=");
		  if (c_start!=-1)
		    {
		    c_start=c_start + c_name.length+1;
		    c_end=document.cookie.indexOf(";",c_start);
		    if (c_end==-1) c_end=document.cookie.length;
		    return unescape(document.cookie.substring(c_start,c_end));
		    }
		  }
		return "";
		}
		
		function setCookie(c_name,value,expiredays){
			var exdate=new Date();
			exdate.setDate(exdate.getDate()+expiredays);
			document.cookie=c_name+ "=" +escape(value)+
			((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
		}
		
		function checkCookie(){
			disable_flash = getCookie('flash_header');
			if (disable_flash != null && disable_flash != ""){
			  enable_flash = confirm('<?php _e('Flash header will be enable and the cookie will be deleted.','tbcity'); ?>');
			  if (enable_flash == true){
				  deletecookie('flash_header');
				  alert('<?php _e('Flash header enabled!','tbcity'); ?>');
				  window.location.reload();
				}
			}else{
			  disable_flash = confirm('<?php _e('To disable flash header, a cookie will be set.','tbcity'); ?>');
			  if (disable_flash == true){
			    setCookie('flash_header',"disabled",365);
			  	alert('<?php _e('Flash header disabled!','tbcity'); ?>');
				  window.location.reload();
			  }
			}
		}
		
		function deletecookie(name) {
			var expdate = new Date();
			expdate.setTime(expdate.getTime() - 1);
			document.cookie = name += "=; expires=" + expdate.toGMTString();
		}
	</script>
	<?php
}

// Disable flash header user side text
function tbcity_disable_flash_header_text(){
	$tbcity_options = get_option('tbcity_options');
	if( $tbcity_options['tbcity_show_flash_header'] == 1 ) {
		echo "<div id=\"deflash\"><a href=\"#\" onClick=\"checkCookie(); return false;\" title=\"" . __('click to disable/enable the Flash header animation','tbcity') . "\">" . __('Disable/Enable Flash Header','tbcity') . "</a></div>";
	}
}

// Get header details
function get_tbcity_header_details(){
	$tbcity_options = get_option('tbcity_options');
	
	if ( $tbcity_options['tbcity_latitude'] ) {
		$tbcity_latitude = $tbcity_options['tbcity_latitude'];
		if($tbcity_latitude >= -90 && $tbcity_latitude <= 90){
			$tbcity_latitude = $tbcity_options['tbcity_latitude'];
		}else{
			$tbcity_latitude = 46;
		}
	}else{
		$tbcity_latitude = 46;						//latitude = Defaults to North, use a negative value for South
	}
	
	if ( $tbcity_options['tbcity_longitude'] ) {
		$tbcity_longitude = $tbcity_options['tbcity_longitude'];
		if($tbcity_longitude >= -180 && $tbcity_longitude <= 180){
			$tbcity_longitude = $tbcity_options['tbcity_longitude'];
		}else{
			$tbcity_longitude = 13;
		}
	}else{
		$tbcity_longitude = 13;						//longitude = Defaults to East, use a negative value for West
	}
	
	$tbcity_zenith = 91;				//The best Overall figure for zenith is 90+(50/60) degrees for true sunrise/sunset; Civil twilight 96 degrees; Nautical twilight 102 degrees; Astronical twilight at 108 degrees;
	$tbcity_offset = get_option('gmt_offset');			//WP offset setting
	$tbcity_timestamp = current_time('timestamp', 0);			//WP current time
	$tbcity_time = strftime("%H.%M",$tbcity_timestamp);
	$tbcity_sunrise = str_replace(":", ".", date_sunrise($tbcity_timestamp, SUNFUNCS_RET_STRING, $tbcity_latitude, $tbcity_longitude, $tbcity_zenith, $tbcity_offset));
	$tbcity_sunset = str_replace(":", ".", date_sunset($tbcity_timestamp, SUNFUNCS_RET_STRING, $tbcity_latitude, $tbcity_longitude, $tbcity_zenith, $tbcity_offset));
	$tbcity_day = strftime("%Y %m %d",$tbcity_timestamp);
	
	/************************************
		Flash variables description :
		- div #header
		nid = current time hh.mm format
		srd = sunrise time
		ssd = sunset time
		- div #imhere
		coord = any text
	************************************/
	
	if (get_option('tbcity_show_logo') == 1) {
		$tbcity_logo_path = get_bloginfo('stylesheet_directory')."/images/logo.png";
		$header_text = "";
	}else{
		$tbcity_logo_path = "";
		$header_text = "<h1>".get_bloginfo('name')."</h1><h2>".get_bloginfo('description')."</h2>";
	}
	
	//header background
	$tbcity_btitle_color = "#FFFFFF";
	if((int)$tbcity_time + 1 >= (int)$tbcity_sunrise && (int)$tbcity_time - 1 <= (int)$tbcity_sunrise){
		$tbcity_head_back = get_bloginfo('stylesheet_directory')."/images/c1_sunrise.jpg";
	}else{
		if((int)$tbcity_time + 1 >= (int)$tbcity_sunset && (int)$tbcity_time - 1 <= (int)$tbcity_sunset){
			$tbcity_head_back = get_bloginfo('stylesheet_directory')."/images/c3_sunset.jpg";
		}else{
			if( (int)$tbcity_time < (int)$tbcity_sunset && (int)$tbcity_time > (int)$tbcity_sunrise ){
				$tbcity_head_back = get_bloginfo('stylesheet_directory')."/images/c2_day.jpg";
			}else{
				$tbcity_head_back = get_bloginfo('stylesheet_directory')."/images/c4_night.jpg";
			}
		}
	}
	
	//if flash header is enabled
	global $header_flash_user;
	if( $tbcity_options['tbcity_show_flash_header'] == 1 && !isset($_COOKIE["flash_header"]) ){
		$tbcity_detail_url = get_bloginfo('stylesheet_directory')."/images/city.swf?nid=$tbcity_time&amp;srd=$tbcity_sunrise&amp;ssd=$tbcity_sunset&amp;author=twobeers.net";
		
		$header_flash ='
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1920" height="300">
					<param name="movie" value="'.$tbcity_detail_url.'" />
					<param name="loop" value="false" />
					<param name="menu" value="false" />
					<param name="quality" value="high" />
					<param name="wmode" value="opaque" />
					<embed src="'.$tbcity_detail_url.'" loop="false" menu="false" quality="high" wmode="opaque" width="1920" height="300" name="city" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			';
	}else{
		$header_flash ='';
	}
	
	//if im here is enabled
	if( $tbcity_options['tbcity_show_imhere'] == 1){
		$tbcity_imhere_txt = get_bloginfo('stylesheet_directory')."/images/globe.swf?coord=$tbcity_day%20GMT%20$tbcity_offset%20Lat:%20$tbcity_latitude%20Long:%20$tbcity_longitude";
		$im_here_flash = '
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="800" height="40" id="globe" align="middle">
					<param name="quality" value="high" />
					<param name="loop" value="false" />
					<param name="menu" value="false" />
					<param name="wmode" value="transparent" />
					<param name="movie" value="'.$tbcity_imhere_txt.'" />
					<embed src="'.$tbcity_imhere_txt.'" quality="high" wmode="transparent" width="800" height="40" name="globe" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>';
	}else{
		$im_here_flash = '';
	}
	$tbcity_head = '
		<div id="header"> <!-- start header -->
			'.$header_flash.'
			<div id="btitle">'.$header_text.'</div>
		</div> <!-- end header -->
		<div id="imhere">	<!-- start imhere -->
			'.$im_here_flash.'
		</div>	<!-- end imhere -->';
	
	$tbcity_header = array($tbcity_head,$tbcity_head_back,$tbcity_logo_path);
	
	return ($tbcity_header);
}

// standard actions
load_theme_textdomain('tbcity', TEMPLATEPATH . '/languages' );
add_theme_support( 'automatic-feed-links' );

// Theme uses wp_nav_menu() in one location. Check if function exist
if( function_exists('register_nav_menus') )
	register_nav_menus( array(
		'primary' => __( 'Main Navigation Menu', 'tbcity' ),
	) );


// register sidebars
if ( function_exists('register_sidebar') ){
	register_sidebar(array(
		'name'=>'Left Sidebar',
		'before_widget' => '<div class="side-left">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'name'=>'Right Sidebar',
		'before_widget' => '<div class="side-right">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
}

// add theme admin menu
add_action('admin_menu', 'add_theme_tbcity_option_interface');
add_action('admin_init', 'add_theme_tbcity_init');

//Init theme options
function add_theme_tbcity_init() {
	register_setting( 'tbcity_theme_options', 'tbcity_options');
}

// add theme options page
function add_theme_tbcity_option_interface() {
	add_theme_page(__('Theme Options','tbcity'), __('Theme Options','tbcity'), 'manage_options', 'functions', 'edit_tbcity_options');
}

// manage theme options
function edit_tbcity_options() {
	$tbcity_options = get_option('tbcity_options');
	
	if ( isset( $_REQUEST['updated'] ) ) echo '<div id="message" class="updated"><p><strong>'.__('Options saved.').'</strong></p></div>';
	?>
	<style type="text/css">
		.opt_cont{
			-moz-border-radius: 7px;
			-khtml-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			background-color:#FFFFFF;
			border:1px solid #AAAAAA;
			margin:10px 0;
			padding:5px;		
		}
	</style>
	<div class='wrap'>
		<div class="icon32" id="icon-themes"><br></div>
		<h2><?php _e('The Black City theme options','tbcity'); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('tbcity_theme_options'); ?>
			<?php if( $tbcity_options['tbcity_latitude'] >= -90 && $tbcity_options['tbcity_latitude'] <= 90 ) : $errLatBeg = ""; $errLatEnd = ""; else: $errLatBeg = "<font style=\"color:red;\">"; $errLatEnd = "</font>"; endif; ?>
		<div class="opt_cont">
			<p><strong><?php _e('Blog\'s geographical location','tbcity'); ?></strong></p>
			<p><small><?php _e('Latitude and longitude are needed to show the correct changes in light (sunrise, day, sunset, night), in the header of the blog.<br />You can use <a href=\'http://itouchmap.com/latlong.html\' target=\'_blank\'>itouchmap.com</a> to find yours.','tbcity'); ?></small></p>
			<?php echo $errLatBeg; ?><p><?php _e('Latitude','tbcity'); ?>: <input type="text" name="tbcity_options[tbcity_latitude]" value="<?php echo $tbcity_options['tbcity_latitude']; ?>" /></p>
			<p><small><?php _e('Default to North (46), use a negative value for South. Must be between -90 to 90','tbcity'); echo $errLatEnd; ?></small></p>
			<?php if( $tbcity_options['tbcity_longitude'] >= -180 && $tbcity_options['tbcity_longitude'] <= 180) : $errLonBeg = ""; $errLonEnd = ""; else: $errLonBeg = "<font style=\"color:red;\">"; $errLonEnd = "</font>"; endif; ?>
			<?php echo $errLonBeg; ?><p><?php _e('Longitude','tbcity'); ?>: <input type="text" name="tbcity_options[tbcity_longitude]" value="<?php echo $tbcity_options['tbcity_longitude']; ?>" /></p>
			<p><small><?php _e('Default to East (13), use a negative value for West. Must be between -180 to 180','tbcity'); echo $errLonEnd; ?></small></p>
		</div>	
		<div class="opt_cont">
			<p><strong><?php _e('System timezone','tbcity'); ?>: </strong><small><?php echo __('System timezone','tbcity') . ': UTC '; echo get_option('gmt_offset'); _e('. It is currently ','tbcity'); echo date_i18n(get_option('time_format')); ?>.</small></p>
		</div>	
		<div class="opt_cont">
			<p><strong><?php _e('Use flash header','tbcity'); ?>: </strong>
				<select name="tbcity_options[tbcity_show_flash_header]">
				<?php
					$tbcity_show_flash_header = array('0'	=> 'No','1'	=> 'Yes');
					foreach ($tbcity_show_flash_header as $tbcity_flash_header_value => $tbcity_flash_header_option) {
						$tbcity_flash_header_selected = ($tbcity_flash_header_value == $tbcity_options['tbcity_show_flash_header']) ? ' selected="selected"' : '';
						echo '<option title="' . $tbcity_flash_header_option . '" value="' . $tbcity_flash_header_value . '"' . $tbcity_flash_header_selected . '>' . $tbcity_flash_header_option . '</option>';
					}
				?>
				</select>
			</p>
			<p><small><?php _e('Activate/Deactivate the flash header animation.','tbcity'); ?></small></p>
		</div>	
			<?php if( $tbcity_options['tbcity_show_flash_header'] == 1){ ?>
		<div class="opt_cont">
			<p><strong><?php _e('Show I\'m here','tbcity'); ?>:</strong>
				<select name="tbcity_options[tbcity_show_imhere]">
				<?php
					$tbcity_show_imhere = array('0'	=> 'No','1'	=> 'Yes');
					foreach ($tbcity_show_imhere as $tbcity_imhere_value => $tbcity_imhere_option) {
						$tbcity_imhere_selected = ($tbcity_imhere_value == $tbcity_options['tbcity_show_imhere']) ? ' selected="selected"' : '';
						echo '<option title="' . $tbcity_imhere_option . '" value="' . $tbcity_imhere_value . '"' . $tbcity_imhere_selected . '>' . $tbcity_imhere_option . '</option>';
					}
				?>
				</select>
			</p>
			<p><small><?php _e('Activate/Deactivate the flash animation that show the blog\'s geographical location.','tbcity'); ?></small></p>
		</div>	
			<?php }else{ ?>
			<input type="hidden" name="tbcity_options[tbcity_show_imhere]" value="0" />
			<?php } ?>
		<div class="opt_cont">
			<p><strong><?php _e('Use a custom logo or the default blog title and description','tbcity'); ?>:</strong>
				<select name="tbcity_options[tbcity_show_logo]">
				<?php
					$tbcity_show_logo = array('0'	=> __('Title + description','tbcity'),'1'	=> __('Custom logo','tbcity'));
					foreach ($tbcity_show_logo as $tbcity_logo_value => $tbcity_logo_option) {
						$tbcity_logo_selected = ($tbcity_logo_value == $tbcity_options['tbcity_show_logo']) ? ' selected="selected"' : '';
						echo '<option title="' . $tbcity_logo_option . '" value="' . $tbcity_logo_value . '"' . $tbcity_logo_selected . '>' . $tbcity_logo_option . '</option>';
					}
				?>
				</select>
			</p>
			<p><small><?php _e('You can switch between: a personalized image to be used as a logo (must be saved as "logo.png" in images folder - ...themes\tbcity\images\ - dim. width 330px height 200px), and the default text for blog title and description.','tbcity'); ?></small></p>
		</div>	
		<div class="opt_cont">
			<p><strong><?php _e('Portrait directory name','tbcity'); ?>:</strong>
				<input type="text" name="tbcity_options[tbcity_dir_portrait]" value="<?php if( $tbcity_options['tbcity_dir_portrait'] ) : echo $tbcity_options['tbcity_dir_portrait']; else: echo "portrait"; endif; ?>" />
			<?php
			$path = get_stylesheet_directory()."/images/";

			if( $tbcity_options['tbcity_dir_portrait'] ) : $path .= $tbcity_options['tbcity_dir_portrait']; else: $path .= "portrait"; endif;

			if (is_dir($path)) :
				if ($handle = opendir($path)) :
				  while (false !== ($file = readdir($handle))) :
				    if ($file != "." && $file != "..") :
				      $file = $path.'/'.$file;
				      if(is_file($file)) @$n_file++;
				    endif;
				  endwhile;
				closedir($handle);
				endif;
				echo ' &raquo; '.($n_file. __(' image/s stored in ','tbcity') .basename($path).'');
			else:
				echo "<font style=\"color:red;\">" . __("There's no directory with that name",'tbcity') . "</font>";
			endif;
			?>
			</p>
			<p><small><?php _e('Portrait folder, must be inside images theme folder (e.g. ...themes\tbcity\images\portrait\).<br />Images must be in .jpg format and named imageNN where NN is the number of the image - max 99 - e.g. image01.jpg.<br />Every time an image is added or removed, theme options <b><u>must be manually updated</u></b>.','tbcity'); ?></small></p>
		</div>	
			<input type="hidden" name="tbcity_options[tbcity_num_portrait]" value="<?php echo $n_file; ?>">
			<p><input class="button" type="submit" name="Submit" value="<?php _e('Update Options','tbcity'); ?>" /></p>
		</form>
	</div>
	<?php
}

// custom menu
function tbcity_pages_menu() { 
?>
			<ul id="mainmenu">
				<li><a href="<?php bloginfo('url'); ?>" >Home</a></li>
				<?php wp_list_pages('title_li='); ?>
			</ul>

<?php
}

?>