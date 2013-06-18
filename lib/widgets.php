<?php
/**
 * widgets.php
 *
 * This file defines the Widget functionality and 
 * custom widgets.
 *
 * Custom widgets:
 * - Popular Posts
 * - Latest activity
 * - Latest comment authors
 * - Popular Categories
 * - Follow Me
 * - besides...
 * - Recent Posts in Category
 * - Navigation buttons
 * - Post details
 * - Post Formats
 * - Image EXIF details
 * - User quick links
 * - Share this
 * - Clean Archives
 * - Font Resize
 *
 * @package The Black City
 * @since 1.00
 */


/* Custom actions - WP hooks */

add_action( 'widgets_init'	, 'tbcity_widget_area_init' );
add_action( 'widgets_init'	, 'tbcity_widgets_init' );


/**
 * Define default Widget arguments
 */
function tbcity_get_default_widget_args( $args = '' ) {

	$defaults = array(
		'before'	=> '',
		'after'		=> '',
		'id'		=> '%1$s',
		'class'		=> '%2$s',
	);

	$args = wp_parse_args( $args, $defaults );

	$args['id'] = $args['id'] ? ' id="' . $args['id'] . '"' : '';

	$widget_args = array(
		// Widget container opening tag, with classes
		'before_widget' => $args['before']  . '<div' . $args['id'] . ' class="widget ' . $args['class'] . '">',
		// Widget container closing tag
		'after_widget' => '</div>' . $args['after'],
		// Widget Title container opening tag, with classes
		'before_title' => '<div class="w_title">',
		// Widget Title container closing tag
		'after_title' => '</div>'
	);

	return $widget_args;

}


/**
 * Register all widget areas (sidebars)
 */
function tbcity_widget_area_init() {

	// Area 1, in the left sidebar.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Primary Sidebar', 'tbcity' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The primary sidebar widget area', 'tbcity' )
		),
		tbcity_get_default_widget_args()
	) );

	// Area 2, located after the post body.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Post Widget Area', 'tbcity' ),
			'id' => 'single-widgets-area',
			'description' => __( 'a widget area located after the post body', 'tbcity' ),
		),
		tbcity_get_default_widget_args()
	) );

	// Area 2, located in the footer.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Footer Widget Area', 'tbcity' ),
			'id' => 'footer-widgets-area',
			'description' => __( 'a widget area located in the footer', 'tbcity' ),
		),
		tbcity_get_default_widget_args()
	) );

	// Area 3, located in page 404.
	register_sidebar( array_merge( 
		array(
			'name' => __( 'Page 404', 'tbcity' ),
			'id' => 'error404-widgets-area',
			'description' => __( 'Enrich the page 404 with some useful widgets', 'tbcity' )
		),
		tbcity_get_default_widget_args()
	) );

}


/**
 * Popular_Posts widget class
 */
class Tbcity_Widget_Popular_Posts extends WP_Widget {

	function Tbcity_Widget_Popular_Posts() {

		$widget_ops = array( 'classname' => 'tb_popular_posts', 'description' => __( 'The most commented posts on your site', 'tbcity' ) );
		$this->WP_Widget( 'tb-popular-posts', __( 'Popular Posts', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_popular_posts';

		add_action( 'save_post'		,array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	,array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	,array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Popular Posts', 'tbcity' ),
			'number' => 5,
			'thumb' => 0
		);

		$this->alert = array();

	}


	function widget($args, $instance) {
		$cache = wp_cache_get( 'tb_popular_posts', 'widget' );

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		extract($args);

		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$number = (int) $instance['number'];

		$ul_class = $instance['thumb'] ? ' class="with-thumbs"' : '';

		$r = new WP_Query( array(
			'showposts' => $number,
			'nopaging' => 0,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => 'comment_count'
		) );

		$output = '';

		if ( $r->have_posts() ) {

			while ( $r->have_posts() ) {
				$r->the_post();

				$thumb = $instance['thumb'] ? tbcity_get_the_thumb( array( 'id' => get_the_ID(), 'size_w' => 32, 'class' => 'tb-thumb-format' ) ) . ' ' : '';
				$post_title = get_the_title() ? get_the_title() : get_the_ID();

				$output .= '<li><a href="' . get_permalink() . '" title="' . esc_attr( $post_title ) . '">' . $thumb . $post_title . ' <span class="details">(' . get_comments_number() . ')</span></a></li>';

			}

			$output = $before_widget . $title . '<ul' . $ul_class . '>' . $output . '</ul>' . $after_widget;
		}

		wp_reset_postdata();

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_popular_posts', $cache, 'widget' );

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['number'] = (int) $new_instance['number'];
		if ( ( $instance['number'] > 15 ) || ( $instance['number'] < 1 ) ) {
			$instance['number'] = $this->defaults['number'];
			$this->alert[] = 'number';
		}

		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_popular_posts']) )
			delete_option( 'tb_popular_posts' );

		return $instance;

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_popular_posts', 'widget' );

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = esc_attr( $instance['title'] );
		$number = (int) $instance['number'];
		$thumb = (int) $instance['thumb'];

?>
	<?php if ( ! empty( $this->alert ) ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p<?php $this->field_class( 'number' ); ?>>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'tbcity' ); ?> [1-15]:</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
		<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails', 'tbcity' ); ?></label>
	</p>
<?php

	}

}


/**
 * latest_Commented_Posts widget class
 *
 */
class Tbcity_Widget_Latest_Commented_Posts extends WP_Widget {

	function Tbcity_Widget_Latest_Commented_Posts() {
		$widget_ops = array( 'classname' => 'tb_latest_commented_posts', 'description' => __( 'The latest commented posts/pages of your site', 'tbcity' ) );
		$this->WP_Widget( 'tb-recent-comments', __( 'Latest activity', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_latest_commented_posts';

		add_action( 'comment_post'				,array( &$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status'	,array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Latest activity', 'tbcity' ),
			'number' => 5,
			'thumb' => 0
		);

		$this->alert = array();

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_latest_commented_posts', 'widget' );

	}


	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'tb_latest_commented_posts', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);

		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$number = (int) $instance['number'];

		$ul_class = $instance['thumb'] ? ' class="with-thumbs"' : '';

		$output = '';

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment', 'number' => 200 ) );

		if ( $comments ) {

			$post_array = array();
			$counter = 0;
			foreach ( (array) $comments as $comment) {

				if ( ! in_array( $comment->comment_post_ID, $post_array ) ) {

					$post = get_post( $comment->comment_post_ID );
					setup_postdata( $post );

					$the_thumb = $instance['thumb'] ? tbcity_get_the_thumb( array( 'id' => $post->ID, 'size_w' => 32, 'class' => 'tb-thumb-format' ) ) . ' ' : '';

					$output .=  '<li>' . ' <a href="' . get_permalink( $post->ID ) . '" title="' .  esc_attr( get_the_title( $post->ID ) ) . '">' . $the_thumb . get_the_title( $post->ID ) . '</a></li>';

					$post_array[] = $comment->comment_post_ID;

					if ( ++$counter >= $number ) break;

				}

			}

		} else {

			$output .= '<li>' . __( 'no comments yet', 'tbcity' ) . '</li>';

		}

		$output = $before_widget . $title . '<ul' . $ul_class . '>' . $output . '</ul>' . $after_widget;

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_latest_commented_posts', $cache, 'widget' );
	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['number'] = (int) $new_instance['number'];
		if ( ( $instance['number'] > 15 ) || ( $instance['number'] < 1 ) ) {
			$instance['number'] = $this->defaults['number'];
			$this->alert[] = 'number';
		}

		$instance['thumb'] = (int) $new_instance['thumb'] ? 1 : 0;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_latest_commented_posts']) )
			delete_option( 'tb_latest_commented_posts' );

		return $instance;

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

?>
	<?php if ( ! empty( $this->alert ) ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
	</p>

	<p<?php $this->field_class( 'number' ); ?>>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'tbcity' ); ?> [1-15]:</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $instance['number']; ?>" size="3" />
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $instance['thumb'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails', 'tbcity' ); ?></label>
	</p>
<?php

	}
}


/**
 * latest_Comment_Authors widget class
 *
 */
class Tbcity_Widget_Latest_Commentators extends WP_Widget {

	function Tbcity_Widget_Latest_Commentators() {

		$widget_ops = array( 'classname' => 'tb_latest_commentators', 'description' => __( 'The latest comment authors', 'tbcity' ) );
		$this->WP_Widget( 'tb-recent-commentators', __( 'Latest comment authors', 'tbcity' ), $widget_ops);
		$this->alt_option_name = 'tb_latest_commentators';

		add_action( 'comment_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Latest comment authors', 'tbcity' ),
			'number' => 5,
			'icon_size' => 32
		);

		$this->alert = array();

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_latest_commentators', 'widget' );

	}


	function widget( $args, $instance ) {

		if ( get_option( 'require_name_email' ) != '1' ) return; //commentors must be identifiable

		$cache = wp_cache_get( 'tb_latest_commentators', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);

		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$icon_size = (int) $instance['icon_size'];

		$number = (int) $instance['number'];

		$output = '';

		$comments = get_comments( array( 'status' => 'approve', 'type' => 'comment', 'number' => 200 ) );

		if ( $comments ) {

			$post_array = array();
			$counter = 0;
			foreach ( (array) $comments as $comment) {

				if ( !in_array( $comment->comment_author_email, $post_array ) ) {

					if ( $comment->comment_author_url == '' )
						$avatar =  get_avatar( $comment, $icon_size, $default = get_option( 'avatar_default' ) ) . ' ' . $comment->comment_author;
					else
						$avatar =  get_avatar( $comment, $icon_size, $default = get_option( 'avatar_default' ) ) . '<a target="_blank" href="' . $comment->comment_author_url . '">' . ' ' . $comment->comment_author . '</a>';

					$output .=  '<li title="' .  esc_attr( $comment->comment_author ) . '">' . $avatar . '</li>';

					$post_array[] = $comment->comment_author_email;

					if ( ++$counter >= $number ) break;

				}

			}

 		} else {

			$output .= '<li>' . __( 'no comments yet', 'tbcity' ) . '</li>';

		}

		$output = $before_widget . $title . '<ul>' . $output . '</ul><br class="fixfloat" />' . $after_widget;

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_latest_commentators', $cache, 'widget' );

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['icon_size'] = $new_instance['icon_size'];
		if ( ! in_array( $instance['icon_size'], array ( '16', '24', '32', '48', '64' ) ) ) {
			$instance['icon_size'] = $this->defaults['icon_size'];
			$this->alert[] = 'icon_size';
		}

		$instance['number'] = (int) $new_instance['number'];
		if ( ( $instance['number'] > 10 ) || ( $instance['number'] < 1 ) ) {
			$instance['number'] = $this->defaults['number'];
			$this->alert[] = 'number';
		}

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_latest_commentators']) )
			delete_option( 'tb_latest_commentators' );

		return $instance;

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = $instance['title'];
		$number = $instance['number'];
		$icon_size = $instance['icon_size'];

		if ( get_option( 'require_name_email' ) != '1' ) {
			printf ( __( 'Comment authors <strong>must</strong> use a name and a valid e-mail in order to use this widget. Check the <a href="%1$s">Discussion settings</a>', 'tbcity' ), esc_url( admin_url( 'options-discussion.php' ) ) );
			return;
		}

?>
	<?php if ( $this->alert ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p<?php $this->field_class( 'number' ); ?>>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of users to show', 'tbcity' ); ?> [1-10]:</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<p<?php $this->field_class( 'icon_size' ); ?>>
		<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select your icon size', 'tbcity' ); ?>:</label><br />
		<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
			<?php
				$size_array = array ( '16', '24', '32', '48', '64' );
				foreach($size_array as $size) {

					?><option value="<?php echo $size; ?>" <?php selected( $icon_size, $size ); ?>><?php echo $size; ?>px</option><?php
				}
			?>
		</select>
	</p>
<?php

	}

}


/**
 * Popular Categories widget class
 *
 */
class Tbcity_Widget_Pop_Categories extends WP_Widget {

	function Tbcity_Widget_Pop_Categories() {

		$widget_ops = array( 'classname' => 'tb_categories', 'description' => __( 'A list of popular categories', 'tbcity' ) );

		$this->WP_Widget( 'tb-categories', __( 'Popular Categories', 'tbcity' ), $widget_ops);

		$this->defaults = array(
			'title' => __( 'Popular Categories', 'tbcity' ),
			'number' => 5,
			'id' => ''
		);

		$this->alert = array();

	}


	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$number = (int) $instance['number'];

		$cat_args = array(
			'orderby' => 'count',
			'show_count' => 1,
			'hierarchical' => 0,
			'order' => 'DESC',
			'title_li' => '',
			'number' => $number
		);
		$cat_args = apply_filters( 'tbcity_widget_pop_categories_args', $cat_args);

		$view_all_url = ( $instance['id'] && get_permalink( $instance['id'] ) ) ? get_permalink( $instance['id'] ) : add_query_arg( 'allcat', 'y', home_url() );

?>
	<?php echo $before_widget; ?>

		<?php echo $title; ?>

		<ul>
			<?php wp_list_categories( $cat_args ); ?>
			<li class="allcat"><a rel="nofollow" title="<?php esc_attr_e( 'View all categories', 'tbcity' ); ?>" href="<?php echo $view_all_url; ?>"><?php _e( 'View all', 'tbcity' ); ?></a></li>
		</ul>

	<?php echo $after_widget; ?>

<?php

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['number'] = (int) $new_instance['number'];
		if ( ( $instance['number'] > 15 ) || ( $instance['number'] < 1 ) ) {
			$instance['number'] = $this->defaults['number'];
			$this->alert[] = 'number';
		}

		$instance['id'] = $new_instance['id'] ? (int) $new_instance['id'] : '';
		if ( $instance['id'] && ! get_post( $instance['id'] ) ) {
			$instance['id'] = $this->defaults['id'];
			$this->alert[] = 'id';
		}

		return $instance;

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = esc_attr( $instance['title'] );
		$id = $instance['id'] ? (int) $instance['id'] : '';
		$number = (int) $instance['number'];

?>
	<?php if ( $this->alert ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p<?php $this->field_class( 'number' ); ?>>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of categories to show', 'tbcity' ); ?> [1-15]:</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<p<?php $this->field_class( 'id' ); ?>>
		<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'ID of the page created using the "List of Categories" template (if any)', 'tbcity' ); ?>:</label>
		<input id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo $id; ?>" size="3" />
	</p>
<?php

	}

}


/**
 * Social network widget class.
 * Social media services supported: Facebook, Twitter, Myspace, Youtube, LinkedIn, Del.icio.us, Digg, Flickr, Reddit, StumbleUpon, Technorati and Github.
 * Optional: RSS icon. 
 *
 */
class Tbcity_Widget_Social extends WP_Widget {

	function Tbcity_Widget_Social() {

		$widget_ops = array(
			'classname'		=> 'tb_social',
			'description'	=> __( 'This widget lets visitors of your blog to subscribe to it and follow you on popular social networks like Twitter, FaceBook etc.' , 'tbcity' )
		);
		$control_ops = array( 'width' => 650 );

		$this->WP_Widget( 'tb-social', __( 'Follow Me', 'tbcity' ), $widget_ops, $control_ops );

		$this->follow_urls = array(
			// SLUG => NAME
			'blogger'		=> 'Blogger',
			'blurb'			=> 'Blurb',
			'delicious'		=> 'Delicious',
			'deviantart'	=> 'deviantART',
			'digg'			=> 'Digg',
			'dropbox'		=> 'Dropbox',
			'facebook'		=> 'Facebook',
			'flickr'		=> 'Flickr',
			'github'		=> 'GitHub',
			'googleplus'	=> 'Google+',
			'hi5'			=> 'Hi5',
			'linkedin'		=> 'LinkedIn',
			'livejournal'	=> 'LiveJournal',
			'myspace'		=> 'Myspace',
			'odnoklassniki'	=> 'Odnoklassniki',
			'orkut'			=> 'Orkut',
			'pengyou'		=> 'Pengyou',
			'picasa'		=> 'Picasa',
			'pinterest'		=> 'Pinterest',
			'qzone'			=> 'Qzone',
			'reddit'		=> 'Reddit',
			'renren'		=> 'Renren',
			'scribd'		=> 'Scribd',
			'slideshare'	=> 'SlideShare',
			'stumbleupon'	=> 'StumbleUpon',
			'soundcloud'	=> 'SoundCloud',
			'technorati'	=> 'Technorati',
			'tencent'		=> 'Tencent',
			'twitter'		=> 'Twitter',
			'tumblr'		=> 'Tumblr',
			'ubuntuone'		=> 'Ubuntu One',
			'vimeo'			=> 'Vimeo',
			'vkontakte'		=> 'VKontakte',
			'weibo'			=> 'Weibo',
			'windowslive'	=> 'Windows Live',
			'xing'			=> 'Xing',
			'yfrog'			=> 'YFrog',
			'youtube'		=> 'Youtube',
			'mail'			=> 'mail',
			'rss'			=> 'RSS'
		);

		$this->defaults = array(
			'title'		=> __( 'Follow Me', 'tbcity' ),
			'icon_size'	=> 48,
		);
		foreach ( $this->follow_urls as $follow_service => $service_name ) {
			$this->defaults[$follow_service.'_account'] = '';
			$this->defaults['show_'.$follow_service] = false;
		}

		$this->alert = array();

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_social', 'widget' );

	}


	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'tb_social', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		extract($args);

		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$icon_size = $instance['icon_size'];

		$output = '';

		foreach ($this->follow_urls as $follow_service => $service_name ) {

			$show = $instance['show_'.$follow_service];
			$account = $instance[$follow_service.'_account'];
			$prefix = __( 'Follow us on %s', 'tbcity' );
			$onclick = '';
			$class = '';
			$target = '_blank';
			if ( $follow_service == 'rss' ) {
				$account = $account? $account : get_bloginfo( 'rss2_url' );
				$prefix = __( 'Keep updated with our RSS feed', 'tbcity' );
			}
			if ( $follow_service == 'mail' ) {
				$account = preg_replace( '/(.)(.)/', '$2$1', 'mailto:'.$account );
				$prefix = __( 'Contact us', 'tbcity' );
				$class= ' hide-if-no-js';
				$onclick = ' onclick="this.href=\'' . $account . '\'.replace(/(.)(.)/g, \'$2$1\');"';
				$account = '#';
				$target = '_self';
			}

			if ( $show && ! empty( $account ) ) {
				$icon = '<img src="' . get_template_directory_uri() . '/images/follow/' . strtolower( $follow_service ) . '.png" alt="' . $follow_service . '" style="width: ' . $icon_size . 'px; height: ' . $icon_size . 'px;" />';
				$output .= '<a target="' . $target . '" href="' . $account . '"' . $onclick . ' class="tb-social-icon' . $class . '" title="' . esc_attr( sprintf( $prefix, $service_name ) ) . '">' . $icon . '</a> ';
			}

		}

		$output = $before_widget . $title . $output . $after_widget;

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_social', $cache, 'widget' );

	}


	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance["title"] = strip_tags($new_instance["title"]);

		$instance['icon_size'] = $new_instance['icon_size'];
		if ( ! in_array( $instance['icon_size'], array ( '16', '24', '32', '48', '64' ) ) ) {
			$instance['icon_size'] = $this->defaults['icon_size'];
			$this->alert[] = 'icon_size';
		}

		$url_pattern = "/^(http|https):\/\//";
		$email_pattern = "/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/";
		foreach ($this->follow_urls as $follow_service => $service_name ) {

			$instance['show_'.$follow_service] = $new_instance['show_'.$follow_service];
			$instance[$follow_service.'_account'] = $new_instance[$follow_service.'_account'];

			if ( $instance[$follow_service.'_account'] ) {

				if( $follow_service == 'mail' )
					preg_match($email_pattern, strtoupper( $instance[$follow_service.'_account'] ), $is_valid_url);
				else
					preg_match($url_pattern, $instance[$follow_service.'_account'], $is_valid_url);

				if ( ! $is_valid_url ) {
					$instance['show_'.$follow_service] = false;
					$instance[$follow_service.'_account'] = '';
					$this->alert[] = $follow_service;
				}

			}

		}

		$this->flush_widget_cache();

		return $instance;

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array)$instance, $this->defaults );

?>
	<?php if ( $this->alert ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title']); ?>" />
	</p>

	<div class="services-wrap" style="padding: 10px 0; border-top: 1px solid #DFDFDF;">

		<p><?php echo __( 'NOTE: Enter the <strong>full</strong> addresses ( with <em>http://</em> )', 'tbcity' ); ?></p>

		<?php foreach( $this->follow_urls as $follow_service => $service_name ) { ?>

		<div class="service" style="float: left; width: 40%; margin: 0pt 5%;">

			<h2>
				<input id="<?php echo $this->get_field_id( 'show_'.$follow_service ); ?>" name="<?php echo $this->get_field_name( 'show_'.$follow_service ); ?>" type="checkbox" <?php checked( $instance['show_'.$follow_service], 'on' ); ?>  class="checkbox" />
				<img style="vertical-align:middle; width:32px; height:32px;" src="<?php echo get_template_directory_uri(); ?>/images/follow/<?php echo strtolower( $follow_service ); ?>.png" alt="<?php echo $follow_service; ?>" />
				<?php echo $service_name; ?>
			</h2>

			<?php
				if ( ( $follow_service != 'RSS' ) && ( $follow_service != 'Mail' ) )
					$text = __( 'Enter your %1$s account link', 'tbcity' );
				elseif ( $follow_service == 'Mail' )
					$text = __( 'Enter email address', 'tbcity' );
				elseif ( $follow_service == 'RSS' )
					$text = __( 'Enter your feed service address. Leave it blank for using the default WordPress feed', 'tbcity' );
			?>
			<p<?php $this->field_class( $follow_service ); ?>>
				<label for="<?php echo $this->get_field_id( $follow_service.'_account' ); ?>"><?php printf( $text, $service_name ) ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( $follow_service.'_account' ); ?>" name="<?php echo $this->get_field_name( $follow_service.'_account' ); ?>" value="<?php if ( isset( $instance[$follow_service.'_account'] ) ) echo $instance[$follow_service.'_account']; ?>" class="widefat" />
			</p>

		</div>

		<?php } ?>

		<div class="clear" style="padding: 10px 0; border-top: 1px solid #DFDFDF; text-align: right;">

			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select your icon size', 'tbcity' ); ?>:</label><br />
			<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
				<?php
					$size_array = array ( '16', '24', '32', '48', '64' );
					foreach($size_array as $size) {
				?>
					<option value="<?php echo $size; ?>" <?php selected( $instance['icon_size'], $size ); ?>><?php echo $size; ?>px</option>
				<?php
					}
				?>
			</select>

		</div>

	</div>

	<?php if ( $this->alert ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>
<?php

	}

}


/**
 * Recent Posts in Category widget class
 *
 */
class Tbcity_Widget_Recent_Posts extends WP_Widget {

	function Tbcity_Widget_Recent_Posts() {

		$widget_ops = array( 'classname' => 'tb_recent_entries', 'description' => __( 'The most recent posts in a single category', 'tbcity' ) );
		$this->WP_Widget( 'tb-recent-posts', __( 'Recent Posts in Category', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_recent_entries';

		add_action( 'save_post'		,array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	,array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	,array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Recent Posts in "%s"', 'tbcity' ),
			'number' => 5,
			'category' => '',
			'thumb' => 1,
			'description' => 1,
		);

		$this->alert = array();

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_recent_posts', 'widget' );

	}


	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'tb_recent_posts', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		extract( $args );
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$category = $instance['category'];
		if ( $category === -1 ) {
			if ( !is_single() || is_attachment() ) return;
			global $post;
			$category = get_the_category( $post->ID );
			$category = ( $category ) ? $category[0]->cat_ID : '';
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$title = sprintf( $title, '<a href="' . get_category_link( $category ) . '">' . get_cat_name( $category ) . '</a>' );
		$title = $title ? $before_title . $title . $after_title : '';

		$number = (int) $instance['number'];

		$ul_class = $instance['thumb'] ? ' class="with-thumbs"' : '';

		$description = ( $instance['description'] && category_description( $category ) ) ? '<div class="cat-descr">' . category_description( $category ) . '</div>' : '';

		$r = new WP_Query( array(
			'cat' => $category,
			'posts_per_page' => $number,
			'nopaging' => 0,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true
		) );

		$output = '';

		if ($r->have_posts()) {

			while ( $r->have_posts() ) {
				$r->the_post();

				$thumb = $instance['thumb'] ? tbcity_get_the_thumb( array( 'id' => get_the_ID(), 'size_w' => 32, 'class' => 'tb-thumb-format' ) ) . ' ' : '';
				$post_title = get_the_title() ? get_the_title() : get_the_ID();

				$output .= '<li><a href="' . get_permalink() . '" title="' . esc_attr( $post_title ) . '">' . $thumb . $post_title . '</a></li>';

			}

		$output = $before_widget . $title . $description . '<ul' . $ul_class . '>' . $output . '</ul>' . $after_widget;

		}

		echo $output;

		wp_reset_postdata();

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_recent_posts', $cache, 'widget' );

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']			= strip_tags( $new_instance['title'] );

		$instance['number']			= (int) $new_instance['number'];
		if ( ( $instance['number'] > 15 ) || ( $instance['number'] < 1 ) ) {
			$instance['number'] = $this->defaults['number'];
			$this->alert[] = 'number';
		}

		$instance['category']		= (int) $new_instance['category'];

		$instance['thumb']			= (int) $new_instance['thumb'] ? 1 : 0;

		$instance['description']	= (int) $new_instance['description'] ? 1 : 0;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_recent_entries']) )
			delete_option( 'tb_recent_entries' );

		return $instance;

	}


	function field_class( $field ) {

		if ( in_array( $field , $this->alert ) ) echo ' class="invalid"';

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = esc_attr( $instance['title'] );
		$number = $instance['number'];
		$category = $instance['category'];
		$thumb = $instance['thumb'];
		$description = $instance['description'];

?>
	<?php if ( $this->alert ) echo '<div class="error">' . __( 'Invalid value', 'tbcity' ) . '</div>'?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category', 'tbcity' ); ?>:</label>
		<?php 
			$dropdown_categories = wp_dropdown_categories( Array(
				'orderby'		=> 'ID', 
				'order'			=> 'ASC',
				'show_count'	=> 1,
				'hide_empty'	=> 0,
				'hide_if_empty'	=> true,
				'echo'			=> 0,
				'selected'		=> $category,
				'hierarchical'	=> 1, 
				'name'			=> $this->get_field_name( 'category' ),
				'id'			=> $this->get_field_id( 'category' ),
				'class'			=> 'widefat',
				'taxonomy'		=> 'category',
			) );
		?>

		<?php echo str_replace( '</select>', '<option ' . selected( $category , -1 , 0 ) . 'value="-1" class="level-0">' . __( '(current post category)', 'tbcity' ) . '</option></select>', $dropdown_categories ); ?>
		<small><?php echo __( 'by selecting "(current post category)", the widget will be visible ONLY in single posts', 'tbcity' ); ?></small>
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" value="1" type="checkbox" <?php checked( 1 , $description ); ?> />
		<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Show category description', 'tbcity' ); ?></label>
	</p>

	<p<?php $this->field_class( 'number' ); ?>>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'tbcity' ); ?> [1-15]:</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
		<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show post thumbnails', 'tbcity' ); ?></label>
	</p>
<?php

	}

}


/**
 * Post details widget class
 */
class Tbcity_Widget_Post_Details extends WP_Widget {

	function Tbcity_Widget_Post_Details() {
		$widget_ops = array( 'classname' => 'tb_post_details', 'description' => __( "Show some details and links related to the current post. It's visible ONLY in single posts", 'tbcity' ) );
		$this->WP_Widget( 'tb-post-details', __( 'Post details', 'tbcity' ), $widget_ops);
		$this->alt_option_name = 'tb_post_details';

		$this->defaults = array(
			'title'			=> __( 'Post details', 'tbcity' ),
			'featured'		=> 1,
			'author'		=> 1,
			'avatar_size'	=> 1,
			'date'			=> 1,
			'tags'			=> 1,
			'categories'	=> 1,
			'fixed'			=> 1,
		);

	}


	function post_details( $args = '' ) {
		global $post;

		$defaults = array(
			'author' => 1,
			'date' => 1,
			'tags' => 1,
			'categories' => 1,
			'avatar_size' => 48,
			'featured' => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$tax_separator = apply_filters( 'tbcity_filter_taxomony_separator', ', ' );

		$output = '<ul class="post-details">';

		if ( $args['featured'] &&  has_post_thumbnail( $post->ID ) )
			$output .= '<li class="post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</li>';

		if ( $args['author'] )
			$output .= '<li>' . $this->author_badge( $post->post_author, $args['avatar_size'] ) . '</li>';

		if ( $args['categories'] )
			$output .= '<li class="post-details-cats"><i class="icon-folder-close"></i> <span>' . __( 'Categories', 'tbcity' ) . ': </span>' . get_the_category_list( $tax_separator ) . '</li>';

		if ( $args['tags'] )
			$tags = get_the_tags() ? get_the_tag_list( '</span>', $tax_separator, '' ) : __( 'No Tags', 'tbcity' ) . '</span>';
			$output .= '<li class="post-details-tags"><i class="icon-tags"></i> <span>' . __( 'Tags', 'tbcity' ) . ': ' . $tags . '</li>';

		if ( $args['date'] )
			$output .= '<li class="post-details-date"><i class="icon-time"></i> <span>' . __( 'Published', 'tbcity' ) . ': </span><a href="' . get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a></li>';

		$output .= '</ul>';

		return $output;

	}


	function author_badge( $author = '', $size ) {

		if ( ! $author ) return;

		$name = get_the_author_meta( 'nickname', $author ); // nickname

		$avatar = get_avatar( $author, $size, 'Gravatar Logo', get_the_author_meta( 'user_nicename', $author ) . '-photo' ); // gravatar

		$description = get_the_author_meta( 'description', $author ); // bio

		$author_link = get_author_posts_url($author); // link to author posts

		$author_net = ''; // author social networks
		foreach ( array( 'twitter' => 'Twitter', 'facebook' => 'Facebook', 'googleplus' => 'Google+' ) as $s_key => $s_name ) {
			if ( get_the_author_meta( $s_key, $author ) ) $author_net .= '<a target="_blank" class="url" title="' . esc_attr( sprintf( __('Follow %s on %s', 'tbcity'), $name, $s_name ) ) . '" href="'.get_the_author_meta( $s_key, $author ).'"><img alt="' . $s_key . '" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/' . $s_key . '.png" /></a> ';
		}

		$output = '<li class="author-avatar">' . $avatar . ' <a class="fn author-name" href="' . $author_link . '" >' . $name . '</a></li>';
		$output .= $description ? '<li class="author-description note">' . $description . '</li>' : '';
		$output .= $author_net ? '<li class="author-social">' . $author_net . '</li>' : '';

		$output = '<div class="tb-post-details tb-author-bio vcard"><ul>' . $output . '</ul></div>';

		return apply_filters( 'tbcity_filter_author_badge', $output );

	}


	function widget($args, $instance) {

		if ( !is_single() || is_attachment() ) return;

		extract($args);
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$avatar_size = $instance['avatar_size'];

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$title = $title ? $before_title . $title . $after_title : '';

		echo $before_widget;
		echo $title;
		echo $this->post_details( array( 'author' => $instance['author'], 'date' => $instance['date'], 'tags' => $instance['tags'], 'categories' => $instance['categories'], 'avatar_size' => $avatar_size, 'featured' => $instance['featured'] ) );
		echo $after_widget;

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']			= strip_tags($new_instance['title']);
		$instance['featured']		= (int) $new_instance['featured'] ? 1 : 0;
		$instance['author']			= (int) $new_instance['author'] ? 1 : 0;
		$instance['avatar_size']	= in_array( $new_instance['avatar_size'], array ( '32', '48', '64', '96', '128' ) ) ? $new_instance['avatar_size'] : $this->defaults['icon_size'];
		$instance['date']			= (int) $new_instance['date'] ? 1 : 0;
		$instance['tags']			= (int) $new_instance['tags'] ? 1 : 0;
		$instance['categories']		= (int) $new_instance['categories'] ? 1 : 0;

		return $instance;

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		extract($instance);

?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'featured' ); ?>" name="<?php echo $this->get_field_name( 'featured' ); ?>" value="1" type="checkbox" <?php checked( 1 , $featured ); ?> />
			<label for="<?php echo $this->get_field_id( 'featured' ); ?>"><?php _e( 'thumbnail', 'tbcity' ); ?></label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" value="1" type="checkbox" <?php checked( 1 , $author ); ?> />
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author', 'tbcity' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Select avatar size', 'tbcity' ); ?>:</label>
			<select name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" >
				<?php
					$size_array = array ( '32', '48', '64', '96', '128' );
					foreach($size_array as $size) {
				?>
					<option value="<?php echo $size; ?>" <?php selected( $avatar_size, $size ); ?>><?php echo $size; ?>px</option>
				<?php
					}
				?>
			</select>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="1" type="checkbox" <?php checked( 1 , $date ); ?> />
			<label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Date', 'tbcity' ); ?></label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="1" type="checkbox" <?php checked( 1 , $tags ); ?> />
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags', 'tbcity' ); ?></label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" value="1" type="checkbox" <?php checked( 1 , $categories ); ?> />
			<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories', 'tbcity' ); ?></label>
		</p>
<?php

	}

}


/**
 * Post Format list
 */
class Tbcity_Widget_Post_Formats extends WP_Widget {

	function Tbcity_Widget_Post_Formats() {

		$widget_ops = array( 'classname' => 'tb_post_formats', 'description' => __( 'A list of Post Formats', 'tbcity' ) );
		$this->WP_Widget( 'tb-widget-post-formats', __( 'Post Formats', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_post_formats';

		add_action( 'save_post'		, array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( &$this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Post Formats', 'tbcity' ),
			'count' => 0,
			'icon' => 3
		);

	}


	function flush_widget_cache() {

		wp_cache_delete( 'tb_post_formats', 'widget' );

	}


	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'tb_post_formats', 'widget' );

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		extract( $args );
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$c = $instance['count'];

		$i = $instance['icon'];

		$output = '';

		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$text = ( $i != '2' ) ? $string : '';
					$icon = ( $i != '1' ) ? tbcity_get_the_thumb( array( 'default' => $slug, 'size_w' => 32, 'class' => 'tb-thumb-format' ) ) : '';
					$class = ( $i == '2' ) ? ' class="compact"' : '';
					$sep = ( $text && $icon ) ? ' ' : '';
					$output .= '<li class="post-format-item"><a title="' . $string . '" href="' . get_post_format_link($slug) . '">' . $icon . $sep . $text . '</a>' . $count . '</li>';
				}
			}
		}

		$output = $before_widget . $title . '<ul' . $class . '>' . $output . '</ul><br class="fixfloat" />' . $after_widget;

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_post_formats', $cache, 'widget' );

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']	= strip_tags($new_instance['title']);
		$instance['icon']	= in_array( $new_instance['icon'], array ( '1', '2', '3' ) ) ? $new_instance['icon'] : $this->defaults['icon_size'];
		$instance['count']	= ( ( (int) $new_instance['count'] ) && ( $instance['icon'] != '2' ) ) ? 1 : 0;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_post_formats']) )
			delete_option( 'tb_post_formats' );

		return $instance;

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		extract($instance);

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Show', 'tbcity' ); ?>:</label><br />
		<select name="<?php echo $this->get_field_name( 'icon' ); ?>" id="<?php echo $this->get_field_id( 'icon' ); ?>" >
			<option value="3" <?php selected( '3', $icon ); ?>><?php echo __( 'icons & text', 'tbcity' ); ?></option>
			<option value="2" <?php selected( '2', $icon ); ?>><?php echo __( 'icons', 'tbcity' ); ?></option>
			<option value="1" <?php selected( '1', $icon ); ?>><?php echo __( 'text', 'tbcity' ); ?></option>
		</select>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" value="1" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show posts count', 'tbcity' ); ?></label><br />
	</p>
<?php

	}

}


/**
 * Image EXIF widget class
 */
class Tbcity_Widget_Image_Exif extends WP_Widget {

	function Tbcity_Widget_Image_Exif() {

		$widget_ops = array( 'classname' => 'tb_exif_details', 'description' => __( "Display image EXIF details. It's visible ONLY in single attachments", "tbcity" ) );
		$this->WP_Widget( 'tb-exif-details', __( 'Image EXIF details', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_exif_details';

		$this->defaults = array(
			'title' => __( 'Image EXIF details', 'tbcity' ),
		);

	}


	function exif_details(){

		$imgmeta = wp_get_attachment_metadata();

		// convert the shutter speed retrieve from database to fraction
		if ( $imgmeta['image_meta']['shutter_speed'] && (1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
			if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
			or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
			or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
			or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5){
				$imgmeta['image_meta']['shutter_speed'] = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '');
			} else {
				$imgmeta['image_meta']['shutter_speed'] = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '');
			}
		}

		$output = '';
		// get other EXIF and IPTC data of digital photograph
		$output														.= __( "Width", "tbcity" ) . ": " . $imgmeta['width']."px<br />";
		$output														.= __( "Height", "tbcity" ) . ": " . $imgmeta['height']."px<br />";
		if ( $imgmeta['image_meta']['created_timestamp'] ) $output	.= __( "Date Taken", "tbcity" ) . ": " . date("d-M-Y H:i:s", $imgmeta['image_meta']['created_timestamp'])."<br />";
		if ( $imgmeta['image_meta']['copyright'] ) $output			.= __( "Copyright", "tbcity" ) . ": " . $imgmeta['image_meta']['copyright']."<br />";
		if ( $imgmeta['image_meta']['credit'] ) $output				.= __( "Credit", "tbcity" ) . ": " . $imgmeta['image_meta']['credit']."<br />";
		if ( $imgmeta['image_meta']['title'] ) $output				.= __( "Title", "tbcity" ) . ": " . $imgmeta['image_meta']['title']."<br />";
		if ( $imgmeta['image_meta']['caption'] ) $output			.= __( "Caption", "tbcity" ) . ": " . $imgmeta['image_meta']['caption']."<br />";
		if ( $imgmeta['image_meta']['camera'] ) $output				.= __( "Camera", "tbcity" ) . ": " . $imgmeta['image_meta']['camera']."<br />";
		if ( $imgmeta['image_meta']['focal_length'] ) $output		.= __( "Focal Length", "tbcity" ) . ": " . $imgmeta['image_meta']['focal_length']."mm<br />";
		if ( $imgmeta['image_meta']['aperture'] ) $output			.= __( "Aperture", "tbcity" ) . ": f/" . $imgmeta['image_meta']['aperture']."<br />";
		if ( $imgmeta['image_meta']['iso'] ) $output				.= __( "ISO", "tbcity" ) . ": " . $imgmeta['image_meta']['iso']."<br />";
		if ( $imgmeta['image_meta']['shutter_speed'] ) $output		.= __( "Shutter Speed", "tbcity" ) . ": " . sprintf( __( "%s seconds", "tbcity" ), $imgmeta['image_meta']['shutter_speed']) . "<br />";

		$output = '<div class="exif-attachment-info">' . $output . '</div>';

		return $output;

	}

	function widget($args, $instance) {

		if ( !is_attachment() ) return;

		extract($args);
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		echo $before_widget . $title . $this->exif_details() . $after_widget;

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = esc_attr( $instance['title'] );

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
<?php

	}
}


/**
 * User_quick_links widget class
 *
 */
class Tbcity_Widget_User_Quick_Links extends WP_Widget {

	function Tbcity_Widget_User_Quick_Links() {

		$widget_ops = array( 'classname' => 'tb_user_quick_links', 'description' => __( "Some useful links for users. It's a kind of enhanced meta widget", "tbcity" ) );
		$this->WP_Widget( 'tb-user-quick-links', __( 'User quick links', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_user_quick_links';

		$this->defaults = array(
			'title' => __( 'Welcome %s', 'tbcity' ),
			'thumb' => 1,
			'nick' => 0
		);

	}


	function random_nick (  ) {

		$prefix = array(
			'ATX-',
			'Adorable ',
			'Adventurous ',
			'Alien ',
			'Angry ',
			'Annoyed ',
			'Anxious ',
			'Atrocious ',
			'Attractive ',
			'Bad ',
			'Bad ',
			'Barbarious ',
			'Bavarian ',
			'Beautiful ',
			'Bewildered ',
			'Bitter ',
			'Black ',
			'Blond ',
			'Blue ',
			'Blue-Eyed  ',
			'Bored ',
			'Breezy ',
			'Bright ',
			'Brown ',
			'Cloudy ',
			'Clumsy ',
			'Colorful ',
			'Combative ',
			'Condemned ',
			'Confused ',
			'Cool ',
			'Crazy ',
			'Creepy ',
			'Cruel ',
			'Cubic ',
			'Curly ',
			'Cute ',
			'Dance ',
			'Dangerous ',
			'Dark ',
			'Death ',
			'Delicious ',
			'Dinky ',
			'Distinct ',
			'Disturbed ',
			'Dizzy ',
			'Drunk ',
			'Drunken ',
			'Dull ',
			'Dumb ',
			'E-',
			'Electro ',
			'Elegant ',
			'Elite ',
			'Embarrassed ',
			'Envious ',
			'Evil ',
			'Fancy ',
			'Fast ',
			'Fat ',
			'Fierce ',
			'Flipped-out ',
			'Flying ',
			'Fourios ',
			'Frantic ',
			'Fresh ',
			'Frustraded ',
			'Funny ',
			'Furious ',
			'Fuzzy ',
			'Gameboy ',
			'Giant ',
			'Giga ',
			'Green ',
			'Handsome ',
			'Hard ',
			'Harsh ',
			'Hazardous ',
			'Hiphop ',
			'Hi-res ',
			'Holy ',
			'Horny ',
			'Hot ',
			'House ',
			'i-',
			'Icy ',
			'Infested ',
			'Insane ',
			'Joyous ',
			'Kentucky Fried ',
			'Lame ',
			'Leaking ',
			'Lone ',
			'Lovely ',
			'Lucky ',
			'Mc',
			'Melodic ',
			'Micro ',
			'Mighty ',
			'Mini ',
			'Mutated ',
			'Nasty ',
			'Nice ',
			'Orange ',
			'PS/2-',
			'Pretty ',
			'Purple ',
			'Purring ',
			'Quiet ',
			'Radioactive ',
			'Red ',
			'Resonant ',
			'Salty ',
			'Sexy ',
			'Slow ',
			'Smooth ',
			'Stinky ',
			'Strong ',
			'Supa-Dupa-',
			'Super ',
			'USB-',
			'Ugly ',
			'Unholy ',
			'Vivacious ',
			'Whispering ',
			'White ',
			'Wild ',
			'X',
			'XBox ',
			'Yellow '
		);

		$suffix = array(
			'16',
			'3',
			'6',
			'7',
			'Abe',
			'Bee',
			'Bird',
			'Boy',
			'Cat',
			'Cow',
			'Crow',
			'Cypher',
			'DJ',
			'Dad',
			'Deer',
			'Dog',
			'Donkey',
			'Duck',
			'Eagle',
			'Elephant',
			'Fly',
			'Fox',
			'Frog',
			'Girl',
			'Girlie',
			'Guinea Pig',
			'Hasi',
			'Hawk',
			'Jackal',
			'Lizard',
			'MC',
			'Men',
			'Mom',
			'Morpheus',
			'Mouse',
			'Mule',
			'Neo',
			'Pig',
			'Rabbit',
			'Rat',
			'Rhino',
			'Smurf',
			'Snail',
			'Snake',
			'Star',
			'Tank',
			'Tiger',
			'Wolf',
			'Butterfly',
			'Elk',
			'Godzilla',
			'Horse',
			'Penguin',
			'Pony',
			'Reindeer',
			'Sheep',
			'Sock-Puppet',
			'Worm',
			'Bermuda'
		);

		return $prefix[array_rand($prefix)] . $suffix[array_rand($suffix)];

	}


	function widget( $args, $instance ) {
		global $current_user;

		extract($args, EXTR_SKIP);
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$nick = $instance['nick'] ? $this->random_nick() : __( 'guest', 'tbcity' );
		$name = is_user_logged_in() ? $current_user->display_name : $nick;
		$title = sprintf ( $title, $name );
		if ( $instance['thumb'] ) {
			if ( is_user_logged_in() ) { //fix for notice when user not log-in
				$email = $current_user->user_email;
				$title = get_avatar( $email, 32, $default = get_template_directory_uri() . '/images/user.png', 'user-avatar' ) . ' ' . $title;
			} else {
				$title = get_avatar( 'dummyemail', 32, $default = get_option( 'avatar_default' ) ) . ' ' . $title;
			}
		}
		$title = $title ? $before_title . $title . $after_title : '';

?>
	<?php echo $before_widget; ?>
	<?php echo $title; ?>
	<ul>
		<?php if ( ! is_user_logged_in() || current_user_can( 'read' ) ) { wp_register(); }?>
		<?php if ( is_user_logged_in() ) { ?>
			<?php if ( current_user_can( 'read' ) ) { ?>
				<li><a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Your Profile', 'tbcity' ); ?></a></li>
				<?php if ( current_user_can( 'publish_posts' ) ) { ?>
					<li><a title="<?php esc_attr_e( 'Add New Post', 'tbcity' ); ?>" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php _e( 'Add New Post', 'tbcity' ); ?></a></li>
				<?php } ?>
				<?php if ( current_user_can( 'moderate_comments' ) ) {
					$awaiting_mod = wp_count_comments();
					$awaiting_mod = $awaiting_mod->moderated;
					$awaiting_mod = $awaiting_mod ? ' <span class="details">(' . number_format_i18n( $awaiting_mod ) . ')</span>' : '';
				?>
					<li><a title="<?php esc_attr_e( 'Comments', 'tbcity' ); ?>" href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php _e( 'Comments', 'tbcity' ); ?><?php echo $awaiting_mod; ?></a></li>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<li><?php wp_loginout(); ?></li>
	</ul>
	<?php echo $after_widget; ?>
<?php

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']	= strip_tags( $new_instance['title'] );
		$instance['thumb']	= (int) $new_instance['thumb'] ? 1 : 0;
		$instance['nick']	= (int) $new_instance['nick'] ? 1 : 0;

		return $instance;

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		extract($instance);

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		<small><?php _e( 'default: "Welcome %s" , where %s is the user name', 'tbcity' );?></small>
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'nick' ); ?>" name="<?php echo $this->get_field_name( 'nick' ); ?>" value="1" type="checkbox" <?php checked( 1 , $nick ); ?> />
		<label for="<?php echo $this->get_field_id( 'nick' ); ?>"><?php _e( 'Create a random nick for not-logged users', 'tbcity' ); ?></label>
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="1" type="checkbox" <?php checked( 1 , $thumb ); ?> />
		<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show user gravatar', 'tbcity' ); ?></label>
	</p>
<?php

	}
}


/**
 * Post share links
 */
class Tbcity_Widget_Share_This extends WP_Widget {

	var $default_services = array(
		//'ID' => array( 'NAME', 'LINK' ),
		// LINK -> %1$s: title, %2$s: url, %3$s: image/thumbnail, %4$s: excerpt, %5$s: source, %6$s: permalink
		'mail'			=> array( 'e-mail'		, 'mailto:?subject=Check it out!&body=%1$s - %6$s%0D%0A%4$s' ),
		'twitter'		=> array( 'Twitter'		, 'http://twitter.com/home?status=%1$s - %2$s' ),
		'facebook'		=> array( 'Facebook'	, 'http://www.facebook.com/sharer.php?u=%2$s&t=%1$s' ),
		'weibo'			=> array( 'Weibo'		, 'http://v.t.sina.com.cn/share/share.php?url=%2$s' ),
		'tencent'		=> array( 'Tencent'		, 'http://v.t.qq.com/share/share.php?url=%2$s&title=%1$s&pic=%3$s' ),
		'qzone'			=> array( 'Qzone'		, 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=%2$s' ),
		'reddit'		=> array( 'Reddit'		, 'http://reddit.com/submit?url=%2$s&title=%1$s' ),
		'stumbleupon'	=> array( 'StumbleUpon'	, 'http://www.stumbleupon.com/submit?url=%2$s&title=%1$s' ),
		'digg'			=> array( 'Digg'		, 'http://digg.com/submit?url=%2$s' ),
		'orkut'			=> array( 'Orkut'		, 'http://promote.orkut.com/preview?nt=orkut.com&tt=%1$s&du=%2$s&tn=%3$s' ),
		'bookmarks'		=> array( 'Bookmarks'	, 'https://www.google.com/bookmarks/mark?op=edit&bkmk=%2$s&title=%1$s&annotation=%4$s' ),
		'blogger'		=> array( 'Blogger'		, 'http://www.blogger.com/blog_this.pyra?t&u=%2$s&n=%1$s&pli=1' ),
		'delicious'		=> array( 'Delicious'	, 'http://delicious.com/save?v=5&noui&jump=close&url=%2$s&title=%1$s' ),
		'linkedin'		=> array( 'LinkedIn'	, 'http://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%1$s&source=%5$s&summary=%4$s' ),
		'tumblr'		=> array( 'Tumblr'		, 'http://www.tumblr.com/share?v=3&u=%2$s&t=%1$s&s=%4$s' ),
	);


	var $default_icon_size = array ( '16', '24', '32', '48', '64' );


	function Tbcity_Widget_Share_This() {

		$widget_ops = array( 'classname' => 'tb_share_this', 'description' => __( "Show some popular sharing services links. It's visible ONLY in single posts, pages and attachments", 'tbcity' ) );
		$this->WP_Widget( 'tb-share-this', __( 'Share this', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_share_this';

	}


	function widget( $args, $instance ) {
		global $post;

		if ( !is_singular() ) return;

		extract( $args );

		$icon_size = !empty( $instance['icon_size'] ) ? absint( $instance['icon_size'] ) : '24';

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$enc_title		= rawurlencode( $post->post_title );
		$enc_href		= rawurlencode( home_url() . '/?p=' . get_the_ID() ); //shorturl
		$enc_pict		= rawurlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );
		$enc_source		= rawurlencode( get_bloginfo( 'name' ) );
		$enc_long_url	= rawurlencode( get_permalink( $post->ID ) );
		if ( !empty( $post->post_password ) )
			$enc_xerpt	= '';
		elseif ( has_excerpt() )
			$enc_xerpt	= get_the_excerpt();
		else
			$enc_xerpt	= wp_trim_words( $post->post_content, apply_filters('excerpt_length', 55), '[...]' );
		$enc_xerpt		= rawurlencode( $enc_xerpt );


		$services = $this->default_services;

		$outer = '';
		foreach( $services as $key => $service ) {
			$href = sprintf( $service[1], $enc_title, $enc_href, $enc_pict, $enc_xerpt, $enc_source, $enc_long_url );
			if ( $instance[$key] ) $outer .= '<a class="share-item" rel="nofollow" target="_blank" id="tb-share-with-' . esc_attr( $key ) . '" href="' . $href . '"><img src="' . esc_url( get_template_directory_uri() . '/images/follow/' . $key . '.png' ) . '" width="' . $icon_size . '" height="' . $icon_size . '" alt="' . esc_attr( $service[0] ) . ' Button"  title="' . esc_attr( sprintf( __( 'Share with %s', 'tbcity' ), $service[0] ) ) . '" /></a> ';
		}

?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php echo $outer; ?>
		<?php echo $after_widget; ?>
<?php

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance["icon_size"] = in_array( $new_instance["icon_size"], $this->default_icon_size ) ? $new_instance["icon_size"] : '16' ;
	
		$services = $this->default_services;
		foreach( $services as $key => $service ) {
			$instance[$key] = (int) $new_instance[$key] ? 1 : 0;
		}

		return $instance;

	}


	function form( $instance ) {

		$size_array = $this->default_icon_size;
		$services = $this->default_services;

		foreach( $services as $key => $service ) {
			$def_instance[$key] = 1;
		}
		$def_instance['title'] = __( 'Share this', 'tbcity' );
		$def_instance['icon_size'] = '24';

		//Defaults
		$instance = wp_parse_args( (array) $instance, $def_instance );
		$title = esc_attr( $instance['title'] );
		$icon_size = absint( $instance['icon_size'] );

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Select icon size', 'tbcity' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'icon_size' ); ?>" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" >
		<?php foreach($size_array as $size) { ?>
			<option value="<?php echo $size; ?>" <?php selected( $icon_size, $size ); ?>><?php echo $size; ?>px</option>
		<?php } ?>
		</select>
	</p>
	<p>
	<?php foreach( $services as $key => $service ) { ?>
		<input id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" value="1" type="checkbox" <?php checked( 1 , $instance[$key] ); ?> />
		<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $service[0]; ?></label><br />
	<?php } ?>
	</p>
<?php

	}
}


/**
 * Clean Archives Widget
 */
class Tbcity_Widget_Clean_Archives extends WP_Widget {

	function Tbcity_Widget_Clean_Archives() {

		$widget_ops = array( 'classname' => 'tb_clean_archives', 'description' => __( 'Show archives in a cleaner way', 'tbcity' ) );
		$this->WP_Widget( 'tb-clean-archives', __( 'Clean Archives', 'tbcity' ), $widget_ops );
		$this->alt_option_name = 'tb_clean_archives';

		add_action( 'save_post'		, array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post'	, array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme'	, array( $this, 'flush_widget_cache' ) );

		$this->defaults = array(
			'title' => __( 'Archives', 'tbcity' ),
			'month_style' => 'number',
		);

	}


	function flush_widget_cache() {

		wp_cache_delete( 'widget_recent_posts', 'widget' );

	}


	function widget($args, $instance) {
		$cache = wp_cache_get( 'tb_clean_archives', 'widget' );

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		extract( $args );
		$instance = wp_parse_args( (array)$instance, $this->defaults );

		global $wpdb; // Wordpress Database

		$years = $wpdb->get_results( "SELECT distinct year(post_date) AS year, count(ID) as posts FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' GROUP BY year(post_date) ORDER BY post_date DESC" );

		if ( empty( $years ) ) {
			return; // empty archive
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		$title = $title ? $before_title . $title . $after_title : '';

		$month_style = $instance['month_style'];

		$output = '';

		if ( $month_style == 'acronym' )
			$months_short = array( '', __( 'jan', 'tbcity' ), __( 'feb', 'tbcity' ), __( 'mar', 'tbcity' ), __( 'apr', 'tbcity' ), __( 'may', 'tbcity' ), __( 'jun', 'tbcity' ), __( 'jul', 'tbcity' ), __( 'aug', 'tbcity' ), __( 'sep', 'tbcity' ), __( 'oct', 'tbcity' ), __( 'nov', 'tbcity' ), __( 'dec', 'tbcity' ) );
		else
			$months_short = array( '', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );

		foreach ( $years as $year ) {

			$output .= '<li><a class="year-link" href="' . get_year_link( $year->year ) . '">' . $year->year . '</a>';

			for ( $month = 1; $month <= 12; $month++ ) {

				if ( (int) $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND year(post_date) = '$year->year' AND month(post_date) = '$month'" ) > 0 ) {
					$output .= ' <a class="month-link" href="' . get_month_link( $year->year, $month ) . '">' . $months_short[$month] . '</a>';
				}

			}

			$output .= '</li>';

		}

		$output = $before_widget . $title . '<ul class="tb-clean-archives">' . $output . '</ul>' . $after_widget;

		echo $output;

		$cache[$args['widget_id']] = $output;
		wp_cache_set( 'tb_clean_archives', $cache, 'widget' );

	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']			= strip_tags( $new_instance['title'] );
		$instance['month_style']	= in_array( $new_instance['month_style'], array ( 'number', 'acronym' ) ) ? $new_instance['month_style'] : $this->defaults['month_style'];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tb_clean_archives']) )
			delete_option( 'tb_clean_archives' );

		return $instance;

	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		extract($instance);

?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'tbcity' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'month_style' ); ?>"><?php _e( 'Select month style', 'tbcity' ); ?>:</label>
		<select name="<?php echo $this->get_field_name( 'month_style' ); ?>" id="<?php echo $this->get_field_id( 'month_style' ); ?>" >
			<option value="number" <?php selected( $month_style, 'number' ); ?>><?php _e( 'number', 'tbcity' ); ?></option>
			<option value="acronym" <?php selected( $month_style, 'acronym' ); ?>><?php _e( 'acronym', 'tbcity' ); ?></option>
		</select>
	</p>
<?php

	}

}


/**
 * simple font resize widget
 */
function Tbcity_Widget_font_resize($args) {

	extract($args);

	echo $before_widget;
	echo '<a class="fontresizer_minus" href="javascript:void(0)" title="' . esc_attr( __( 'Decrease font size', 'tbcity' ) ) . '">A</a>';
	echo ' <i class="icon-angle-right"></i> ';
	echo '<a class="fontresizer_reset" href="javascript:void(0)" title="' . esc_attr( __( 'Reset font size', 'tbcity' ) ) . '">A</a>';
	echo ' <i class="icon-angle-right"></i> ';
	echo '<a class="fontresizer_plus" href="javascript:void(0)" title="' . esc_attr( __( 'Increase font size', 'tbcity' ) ) . '">A</a>';
	echo $after_widget;

	wp_enqueue_script( 'tbcity-fontresize', get_template_directory_uri() . '/js/font-resize.min.js', array( 'jquery' ), '', true  );

}


/**
 * Register all of the default WordPress widgets on startup.
 */
function tbcity_widgets_init() {

	if ( !is_blog_installed() )
		return;

	if ( ! tbcity_get_opt( 'custom_widgets' ) )
		return;

	register_widget( 'Tbcity_Widget_Popular_Posts' );

	register_widget( 'Tbcity_Widget_Latest_Commented_Posts' );

	register_widget( 'Tbcity_Widget_Latest_Commentators' );

	register_widget( 'Tbcity_Widget_Pop_Categories' );

	register_widget( 'Tbcity_Widget_Social' );

	register_widget( 'Tbcity_Widget_Recent_Posts' );

	register_widget( 'Tbcity_Widget_User_Quick_Links' );

	register_widget( 'Tbcity_Widget_Post_Details' );

	register_widget( 'Tbcity_Widget_Post_Formats' );

	register_widget( 'Tbcity_Widget_Image_Exif' );

	register_widget( 'Tbcity_Widget_Share_This' );

	register_widget( 'Tbcity_Widget_Clean_Archives' );

	if ( tbcity_is_mobile() )
		return;

	wp_register_sidebar_widget( 'tb-font-resize', 'Font Resize', 'Tbcity_Widget_font_resize', array( 'classname' => 'tb_font_resize', 'description' => __( 'Simple javascript-based font resizer', 'tbcity' ) ) );

}

