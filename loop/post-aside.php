<?php
/**
 * post-aside.php
 *
 * Template part file that contains the Aside Format entry
 * 
 * @package The Black City
 * @since 1.00
 */
?>

<?php tbcity_hook_entry_before(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php tbcity_hook_entry_top(); ?>

	<div class="storycontent">
		<?php the_content(); ?>
		<div class="fixfloat details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?></div>
	</div>

	<?php tbcity_hook_entry_bottom(); ?>

</div>

<?php tbcity_hook_entry_after(); ?>

<?php tbcity_last_comments( get_the_ID() ); ?>
