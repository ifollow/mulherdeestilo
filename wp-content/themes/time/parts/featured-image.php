<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php get_template_part('parts/title'); ?>
<?php if (
	has_post_thumbnail() && !post_password_required() &&
	apply_filters('time_post_thumbnail_display', Time::to_('format_posts/image/thumbnail')->value(is_singular() ? 'single' : 'list'))
): ?>
	<figure class="full-width">
		<a href="<?php
			if (Time::to('format_posts/image/link') == 'post' && !is_singular()) {
				the_permalink();
			} else {
				list($src) = wp_get_attachment_image_src(get_post_thumbnail_id(), '');
				echo $src;
			}
		?>" <?php Time::imageAttrs('a'); ?>>
			<?php the_post_thumbnail('full-width'); ?>
		</a>
	</figure>
<?php endif; ?>