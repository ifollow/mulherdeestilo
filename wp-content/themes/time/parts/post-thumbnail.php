<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php if (
	has_post_thumbnail() && !post_password_required() &&
	apply_filters('time_post_thumbnail_display', Time::to_(array(sprintf('format_posts/%s/thumbnail', get_post_format()), 'format_posts/standard/thumbnail'))->value(is_singular() ? 'single' : 'list'))
): ?>
	<figure class="align<?php echo Time::to('post/thumbnail/align'); ?> fixed">
		<?php if (is_singular()): ?>
			<div <?php Time::imageAttrs('div'); ?>>
				<?php the_post_thumbnail(); ?>
			</div>
		<?php else: ?>
			<a href="<?php the_permalink(); ?>" <?php Time::imageAttrs('a'); ?>>
				<?php the_post_thumbnail(); ?>
			</a>
		<?php endif; ?>
	</figure>
<?php endif; ?>