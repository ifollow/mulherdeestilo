<?php
/**
 * @template name: Full screen gallery
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php get_header(); ?>

<?php if (have_posts()): the_post(); ?>

	<div class="under-container">
		<?php get_template_part('parts/background', 'open'); ?>
		<div class="container">
			<?php
				$filter = array(Time::getInstance(), 'filterTimePostGalleryHTMLFullScreenGallery');
				add_filter('time_post_gallery_html', $filter, 10, 3);
				echo get_post_gallery();
				remove_filter('time_post_gallery_html', $filter, 10);
			?>
		</div>
	</div>

<?php endif; ?>

<?php get_footer('empty'); ?>