<?php
/**
 * custom-header.php
 *
 * The custom header support
 *
 * @package The Black City
 * @since 2.04
 */

class Tbcity_Comment_Style {

	function __construct() {

		add_action( 'after_setup_theme'							, array( $this, 'custom_header_support' ) );
		add_action( 'custom_header_options'						, array( $this, 'custom_header_background' ) );
		add_action( 'admin_init'								, array( $this, 'save_theme_mod' ) );
		add_action( 'admin_head-appearance_page_custom-header'	, array( $this, 'admin_scripts' ) );

	}


	function custom_header_background() {

?>
	<h3><?php _e( 'Header Background' ); ?></h3>

	<table class="form-table">
		<tbody>
			<tr valign="top" class="displaying-header-background">
				<th scope="row"><?php _e( 'Background Color' ); ?></th>
				<td>
					<p>
					
				<?php
					$header_background_color = get_theme_mod( 'header_background_color', '222222' );
					$default_color = '#222222';
					$default_color_attr = ' data-default-color="' . esc_attr( $default_color ) . '"';

					echo '<input type="text" name="header-background-color" id="background-color" value="#' . esc_attr( $header_background_color ) . '"' . $default_color_attr . ' />';

					if ( $default_color )
						echo ' <span class="description hide-if-js">' . sprintf( _x( 'Default: %s', 'color' ), $default_color ) . '</span>';
				?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

<?php

	}


		function save_theme_mod() {

			if( isset( $_POST['header-background-color'] ) ) {

				check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );

				$_POST['header-background-color'] = str_replace( '#', '', $_POST['header-background-color'] );
				$color = preg_replace('/[^0-9a-fA-F]/', '', $_POST['header-background-color']);
				if ( strlen($color) == 6 || strlen($color) == 3 )
					set_theme_mod('header_background_color', $color);

			}

		}


		function admin_scripts() {

			if ( isset( $_GET['step'] ) &&  $_GET['step'] == 2 ) return;

?>
	<script type="text/javascript">
	/* <![CDATA[ */
	(function($){

		function pickColor(color) {
			$('#headimg').css('background-color', color);
		}

		$(document).ready(function() {
			var text_color = $('#background-color');
			text_color.wpColorPicker({
				change: function( event, ui ) {
					pickColor( text_color.wpColorPicker('color') );
				},
				clear: function() {
					pickColor( '' );
				}
			});
		});

	})(jQuery);
	/* ]]> */
	</script>
<?php

	}


	// set up custom colors and header image
	function custom_header_support() {

		register_default_headers( array(
			'the_city' => array(
				'url'			=> '%s/images/headers/the_city.png',
				'thumbnail_url'	=> '%s/images/headers/the_city_thumbnail.png',
				'description'	=> 'the city'
			),
		) );

		$args = array(
			'width'						=> 1000, // Header image width (in pixels)
			'height'					=> 300, // Header image height (in pixels)
			'default-image'				=> '', // Header image default
			'header-text'				=> true, // Header text display default
			'default-text-color'		=> 'FFFFFF', // Header text color default
			'wp-head-callback'			=> array( $this, 'header_style_front' ),
			'admin-head-callback'		=> array( $this, 'header_style_admin' ),
			'flex-height'				=> true,
			'flex-width'				=> true,
			'admin-preview-callback'	=> array( $this, 'header_preview_admin' ),
		);

		$args = apply_filters( 'tbcity_custom_header_args', $args );

		add_theme_support( 'custom-header', $args );

	}


	// included in the admin head
	function header_style_admin() {

?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #<?php echo get_theme_mod( 'header_background_color', '222222' ); ?>;
		}
		#headimg h1
		{
			font-size:4em;
			line-height: 2em;
			margin: 0;
			background: rgb(51,51,51); /* Old browsers */
			background: -moz-linear-gradient(top,  rgba(51,51,51,1) 0%, rgba(85,85,85,1) 99%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(51,51,51,1)), color-stop(99%,rgba(85,85,85,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(51,51,51,1) 0%,rgba(85,85,85,1) 99%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(51,51,51,1) 0%,rgba(85,85,85,1) 99%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(51,51,51,1) 0%,rgba(85,85,85,1) 99%); /* IE10+ */
			background: linear-gradient(to bottom,  rgba(51,51,51,1) 0%,rgba(85,85,85,1) 99%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#333333', endColorstr='#555555',GradientType=0 ); /* IE6-9 */
			border-bottom: 1px solid #222222;
			text-align: center;
			text-shadow: 0 0 9px #000000;
		}
		#headimg h1 a {
			text-decoration: none;
			color: #<?php echo get_header_textcolor(); ?>;
		}
		#headimg img {
			height: auto;
			max-width: 100%;
			display: block;
			margin: 0 auto;
		}
	</style>
<?php

	}


	// included in the front head
	function header_style_front() {

?>
	<style type="text/css">
		#head a {
			color: #<?php header_textcolor(); ?>;
		}
		#head-image-wrapper {
			background: #<?php echo get_theme_mod( 'header_background_color', '222222' ); ?>;
		}
	</style>
<?php

	}


	// included in the admin head
	function header_preview_admin() {

		$image = get_header_image();

?>
	<div id="headimg">
		<?php if ( display_header_text() ) { ?><h1><a id="name" href="#"><?php bloginfo( 'name' ); ?></a></h1><?php } ?>
		<?php if ( $image ) { ?><img src="<?php echo esc_url( $image ); ?>" alt="header" /><?php } ?>
	</div>
<?php

	}


}

new Tbcity_Comment_Style;


