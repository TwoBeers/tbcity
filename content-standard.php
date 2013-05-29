<?php
/**
 * content-standard.php
 *
 * the very content of posts
 *
 * @package The Black City
 * @since 2.00
 */
?>

<div class="storycontent">

	<?php tbcity_hook_entry_content_top(); ?>

		<?php
			if ( is_singular() || tbcity_get_opt( 'post_formats_standard_content' ) == 'content' )
				the_content();
			elseif ( tbcity_get_opt( 'post_formats_standard_content' ) == 'excerpt' )
				the_excerpt();
		?>

	<?php tbcity_hook_entry_content_bottom(); ?>

</div>
