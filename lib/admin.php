<?php
/**
 * admin.php
 *
 * All that affetcs the admin side (options page, styles, scripts, etc)
 *
 * @package The Black City
 * @since 2.04
 */


/* Custom actions - WP hooks */

add_action( 'admin_menu'					, 'tbcity_create_menu' ); // Add admin menus
add_action( 'admin_notices'					, 'tbcity_setopt_admin_notice' );
add_action( 'manage_posts_custom_column'	, 'tbcity_addthumbvalue', 10, 2 ); // column-thumbnail for posts
add_action( 'manage_pages_custom_column'	, 'tbcity_addthumbvalue', 10, 2 ); // column-thumbnail for pages
add_action( 'admin_head'					, 'tbcity_post_manage_style' ); // column-thumbnail style
add_action( 'admin_init'					, 'tbcity_default_options' ); // tell WordPress to run tbcity_default_options()

/* Custom filters - WP hooks */

add_filter( 'manage_posts_columns'			, 'tbcity_addthumbcolumn' ); // column-thumbnail for posts
add_filter( 'manage_pages_columns'			, 'tbcity_addthumbcolumn' ); // column-thumbnail for pages


// create theme option page
function tbcity_create_menu() {

	$pageopt = add_theme_page( __( 'Theme Options','tbcity' ), __( 'Theme Options','tbcity' ), 'edit_theme_options', 'tbcity_functions', 'tbcity_edit_options' ); //create new top-level menu

	add_action( 'admin_init'						, 'tbcity_register_tb_settings' ); //call register settings function
	add_action( 'admin_print_styles-' . $pageopt	, 'tbcity_theme_admin_styles' );
	add_action( 'admin_print_scripts-' . $pageopt	, 'tbcity_theme_admin_scripts' );
	add_action( 'admin_print_styles-widgets.php'	, 'tbcity_widgets_style' );
	add_action( 'admin_print_scripts-widgets.php'	, 'tbcity_widgets_scripts' );

}


//register tbcity settings
function tbcity_register_tb_settings() {

	register_setting( 'tbcity_settings_group', 'tbcity_options', 'tbcity_sanitize_options' );

}


// check and set default options 
function tbcity_default_options() {

		$the_coa = tbcity_get_coa();
		$the_opt = get_option( 'tbcity_options' );

		// if options are empty, sets the default values
		if ( empty( $the_opt ) || !isset( $the_opt ) ) {

			foreach ( $the_coa as $key => $val ) {
				$the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'tbcity_options' , $the_opt );

		} else if ( !isset( $the_opt['version'] ) || $the_opt['version'] < tbcity_get_info( 'version' ) ) {

			// check for unset values and set them to default value -> when updated to new version
			foreach ( $the_coa as $key => $val ) {
				if ( !isset( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'tbcity_options' , $the_opt );

		}

}


// print a reminder message for set the options after the theme is installed or updated
function tbcity_setopt_admin_notice() {

	if ( current_user_can( 'manage_options' ) && ( tbcity_get_opt( 'version' ) < tbcity_get_info( 'version' ) ) )
		echo '<div class="updated"><p><strong>' . sprintf( __( "%s theme says: \"Dont forget to set <a href=\"%s\">my options</a>!\"", 'tbcity' ), 'tbcity', get_admin_url() . 'themes.php?page=tbcity_functions' ) . '</strong></p></div>';

}


//add js script to the options page
function tbcity_theme_admin_scripts() {

	wp_enqueue_media();
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'tbcity-options-script', get_template_directory_uri().'/js/options.js', array( 'jquery', 'farbtastic', 'thickbox' ), tbcity_get_info( 'version' ), true ); //thebird js

	$data = array(
		'confirm_to_defaults' => __( 'Are you really sure you want to set all the options to their default values?', 'tbcity' )
	);
	wp_localize_script( 'tbcity-options-script', 'tbcity_options_l10n', $data );

}


//add custom stylesheet
function tbcity_widgets_style() {

	wp_enqueue_style( 'tbcity-widgets-style', get_template_directory_uri() . '/css/widgets.css', false, '', 'screen' );

}


//add js script to the widgets page
function tbcity_widgets_scripts() {

	wp_enqueue_script( 'tbcity-widgets-scripts', get_template_directory_uri() . '/js/widgets.js', array('jquery','jquery-ui-slider'), tbcity_get_info( 'version' ), true );

}


// the custon header page style
function tbcity_theme_admin_styles() {

	wp_enqueue_style( 'tbcity-options-style', get_template_directory_uri() . '/css/options.css', array(), '', 'screen' );

}


// sanitize options value
if ( !function_exists( 'tbcity_sanitize_options' ) ) {
	function tbcity_sanitize_options($input) {

		$the_coa = tbcity_get_coa();

		foreach ( $the_coa as $key => $val ) {
	
			if( $the_coa[$key]['type'] == 'chk' ) {								//CHK
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}

			} elseif( $the_coa[$key]['type'] == 'sel' ) {						//SEL
				if ( !in_array( $input[$key], $the_coa[$key]['options'] ) )
					$input[$key] = $the_coa[$key]['default'];

			} elseif( $the_coa[$key]['type'] == 'opt' ) {						//OPT
				if ( !in_array( $input[$key], $the_coa[$key]['options'] ) )
					$input[$key] = $the_coa[$key]['default'];

			} elseif( $the_coa[$key]['type'] == 'col' ) {						//COL
				$color = str_replace( '#' , '' , $input[$key] );
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
				$input[$key] = '#' . $color;

			} elseif( $the_coa[$key]['type'] == 'url' ) {						//URL
				$input[$key] = esc_url( trim( strip_tags( $input[$key] ) ) );

			} elseif( $the_coa[$key]['type'] == 'txt' ) {						//TXT
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}

			} elseif( ( $the_coa[$key]['type'] == 'int' ) || ( $the_coa[$key]['type'] == 'hue' ) ) {						//INT
				if( !isset( $input[$key] ) ) {
					$input[$key] = $the_coa[$key]['default'];
				} else {
					$input[$key] = (int) $input[$key] ;
					if( isset( $the_coa[$key]['range'] ) ) {
						if( ( $input[$key] < $the_coa[$key]['range']['min'] ) || ( $input[$key] > $the_coa[$key]['range']['max'] ) )
							$input[$key] = $the_coa[$key]['default'];
					}
				}

			} elseif( $the_coa[$key]['type'] == 'txtarea' ) {					//TXTAREA
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}
			}
		}

		// check for required options
		foreach ( $the_coa as $key => $val ) {
			if ( $the_coa[$key]['req'] != '' ) { if ( $input[$the_coa[$key]['req']] == ( 0 || '') ) $input[$key] = 0; }
		}

		$input['version'] = tbcity_get_info( 'version' ); // keep version number

		return $input;

	}
}


// the theme option page
if ( !function_exists( 'tbcity_edit_options' ) ) {
	function tbcity_edit_options() {

		if ( !current_user_can( 'edit_theme_options' ) ) wp_die( 'You do not have sufficient permissions to access this page.' );

		global $tbcity_opt;

		$the_coa = tbcity_get_coa();
		$the_groups = tbcity_get_coa( 'groups' );
		$the_option_name = 'tbcity_options';

		if ( isset( $_GET['erase'] ) && check_admin_referer( 'tbcity_reset_options_nonce' ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( 'erase', $_SERVER['REQUEST_URI'] );
			delete_option( $the_option_name );
			tbcity_default_options();
			$tbcity_opt = get_option( $the_option_name );
		}

		// update version value when admin visit options page
		if ( $tbcity_opt['version'] < tbcity_get_info( 'version' ) ) {
			$tbcity_opt['version'] = tbcity_get_info( 'version' );
			update_option( $the_option_name , $tbcity_opt );
		}

		$the_opt = $tbcity_opt;

		// options have been updated
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			//return options save message
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Options saved.','tbcity' ) . '</strong></p></div>';
		}

		// options to defaults done
		if ( isset( $_GET['erase'] ) ) {
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Defaults values loaded.', 'tbcity' ) . '</strong></p></div>';
		}

	?>
		<div class="wrap" id="main-wrap">
			<div class="icon32" id="theme-icon"><br /></div>
			<h2><?php echo tbcity_get_info( 'current_theme' ) . ' - ' . __( 'Theme Options','tbcity' ); ?></h2>
			<ul id="tabselector" class="hide-if-no-js">
<?php
				foreach( $the_groups as $key => $name ) {
?>
				<li id="selgroup-<?php echo $key; ?>"><a href="#" onClick="tbcityOptions.switchTab('<?php echo $key; ?>'); return false;"><?php echo $name; ?></a></li>
<?php 
				}
?>
				<li id="selgroup-info"><a href="#" onClick="tbcityOptions.switchTab('info'); return false;"><?php _e( 'Theme Info' , 'tbcity' ); ?></a></li>
			</ul>
			<ul id="selector" class="hide-if-js">
				<li id="theme-options-li"><a href="#theme-options"><?php _e( 'Options','tbcity' ); ?></a></li>
				<li id="theme-infos-li"><a href="#theme-infos"><?php _e( 'Theme Info','tbcity' ); ?></a></li>
			</ul>
			<div id="tabs-container">
				<div class="clear"></div>
				<div id="theme-options">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','tbcity' ); ?></h2>
					<form method="post" action="options.php">
						<?php settings_fields( 'tbcity_settings_group' ); ?>
						<div id="stylediv">
							<?php foreach ($the_coa as $key => $val) { ?>
								<?php if ( isset( $the_coa[$key]['sub'] ) && !$the_coa[$key]['sub'] ) continue; ?>
								<div class="tab-opt tabgroup-<?php echo $the_coa[$key]['group']; ?>">
									<span class="column-nam"><?php echo $the_coa[$key]['description']; ?></span>
								<?php if ( !isset ( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default']; ?>
								<?php if ( $the_coa[$key]['type'] == 'chk' ) { ?>
										<input class="type-chk" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="1" type="checkbox" <?php checked( 1 , $the_opt[$key] ); ?> />
								<?php } elseif ( $the_coa[$key]['type'] == 'sel' ) { ?>
										<select class="type-sel" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]">
										<?php foreach($the_coa[$key]['options'] as $optionkey => $option) { ?>
											<option value="<?php echo $option; ?>" <?php selected( $the_opt[$key], $option ); ?>><?php echo $the_coa[$key]['options_l10n'][$optionkey]; ?></option>
										<?php } ?>
										</select>
								<?php } elseif ( $the_coa[$key]['type'] == 'opt' ) { ?>
									<?php foreach( $the_coa[$key]['options'] as $optionkey => $option ) { ?>
										<label title="<?php echo esc_attr($option); ?>"><input class="type-opt" type="radio" <?php checked( $the_opt[$key], $option ); ?> value="<?php echo $option; ?>" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]"> <span><?php echo $the_coa[$key]['options_l10n'][$optionkey]; ?></span></label>
									<?php } ?>
								<?php } elseif ( $the_coa[$key]['type'] == 'url' ) { ?>
										<input class="type-url" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
								<?php } elseif ( $the_coa[$key]['type'] == 'txt' ) { ?>
										<input class="type-txt" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
								<?php } elseif ( $the_coa[$key]['type'] == 'int' ) { ?>
										<input class="type-int" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
								<?php } elseif ( $the_coa[$key]['type'] == 'hue' ) { ?>
										<input class="type-int" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
										<div class="color_slider" data-refers-to="option_field_<?php echo $key; ?>"></div>
								<?php } elseif ( $the_coa[$key]['type'] == 'txtarea' ) { ?>
										<textarea class="type-txtarea" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]"><?php echo $the_opt[$key]; ?></textarea>
								<?php }	?>
								<?php if ( $the_coa[$key]['info'] != '' ) { ?><div class="column-des"><?php echo $the_coa[$key]['info']; ?></div><?php } ?>
								<?php if ( isset( $the_coa[$key]['sub'] ) ) { ?>
										<div class="sub-opt-wrap">
									<?php foreach ($the_coa[$key]['sub'] as $subkey => $subval) { ?>
										<?php if ( $subval == '' ) { echo '<br />'; continue;} ?>
											<div class="sub-opt">
											<?php if ( !isset ($the_opt[$subval]) ) $the_opt[$subval] = $the_coa[$subval]['default']; ?>
												<?php if ( $the_coa[$subval]['description'] != '' ) { ?><span><?php echo $the_coa[$subval]['description']; ?> : </span><?php } ?>
											<?php if ( $the_coa[$subval]['type'] == 'chk' ) { ?>
													<input class="type-chk" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="1" type="checkbox" <?php checked( 1 , $the_opt[$subval] ); ?> />
													<span><?php echo $the_coa[$subval]['info']; ?></span>
											<?php } elseif ( $the_coa[$subval]['type'] == 'sel' ) { ?>
													<select class="type-sel" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]">
													<?php foreach($the_coa[$subval]['options'] as $optionkey => $option) { ?>
														<option value="<?php echo $option; ?>" <?php selected( $the_opt[$subval], $option ); ?>><?php echo $the_coa[$subval]['options_l10n'][$optionkey]; ?></option>
													<?php } ?>
													</select>
													<span><?php echo $the_coa[$subval]['info']; ?></span>
											<?php } elseif ( $the_coa[$subval]['type'] == 'opt' ) { ?>
												<?php foreach( $the_coa[$subval]['options'] as $optionkey => $option ) { ?>
													<label title="<?php echo esc_attr($option); ?>"><input class="type-opt" type="radio" <?php checked( $the_opt[$subval], $option ); ?> value="<?php echo $option; ?>" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]"> <span><?php echo $the_coa[$subval]['options_l10n'][$optionkey]; ?></span></label>
												<?php } ?>
											<?php } elseif ( $the_coa[$subval]['type'] == 'url' ) { ?>
													<input class="type-url" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
													<span><?php echo $the_coa[$subval]['info']; ?></span>
											<?php } elseif ( $the_coa[$subval]['type'] == 'txt' ) { ?>
													<input class="type-txt" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
													<span><?php echo $the_coa[$subval]['info']; ?></span>
											<?php } elseif ( $the_coa[$subval]['type'] == 'int' ) { ?>
													<input class="type-int" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
													<span><?php echo $the_coa[$subval]['info']; ?></span>
											<?php } ?>
												</div>
										<?php } ?>
											<br class="clear" />
										</div>
								<?php } ?>
									<?php if ( $the_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','tbcity') . '</u>: ' . $the_coa[$the_coa[$key]['req']]['description']; ?></div><?php } ?>
								</div>
							<?php } ?>
						</div>
						<p id="buttons">
							<input type="hidden" name="<?php echo $the_option_name; ?>[hidden_opt]" value="default" />
							<input class="button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'tbcity' ); ?>" />
							<a href="themes.php?page=tbcity_functions" target="_self"><?php _e( 'Undo Changes' , 'tbcity' ); ?></a>
							|
							<a id="to-defaults" href="themes.php?page=tbcity_functions&erase=1&_wpnonce=<?php echo $nonce = wp_create_nonce( 'tbcity_reset_options_nonce' ); ?>" target="_self"><?php _e( 'Back to defaults' , 'tbcity' ); ?></a>
						</p>
					</form>
					<p class="stylediv">
						<small>
							<?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'tbcity' ); ?><br />
							<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-tbcity' ); ?>" title="tbcity theme" target="_blank"><?php _e( 'Leave a feedback', 'tbcity' ); ?></a>
						</small>
					</p>
					<p class="stylediv">
						<small><?php //this line is intentionally untraslated ?>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/wp-themes/themes-translations-wordpress' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
					</p>
				</div>
				<div id="theme-infos">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Theme Info', 'tbcity' ); ?></h2>
					<?php locate_template( 'readme.html',true ); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php
	}
}


// Add Thumbnail Column in Manage Posts/Pages List
function tbcity_addthumbcolumn($cols) {

	$cols['thumbnail'] = ucwords( __('thumbnail', 'tbcity') );
	return $cols;

}


// Add Thumbnails in Manage Posts/Pages List
function tbcity_addthumbvalue($column_name, $post_id) {

		$width = (int) 60;
		$height = (int) 60;

		if ( 'thumbnail' == $column_name ) {
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			if ($thumbnail_id) $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
			if ( isset($thumb) && $thumb ) {
				echo $thumb;
			} else {
				echo '';
			}
		}

}


// Add Thumbnail Column style in Manage Posts/Pages List
function tbcity_post_manage_style(){

?>
	<style type="text/css">
		.fixed .column-thumbnail {
			width: 70px;
		}
	</style>
<?php

}
