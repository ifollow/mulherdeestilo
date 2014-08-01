<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// Options
if (is_singular() && !is_null(Time::po_('layout/banner/type')) && !Time::po_('layout/banner/type')->isDefault()) {
	$content = Time::po_('layout/banner');
} else {
	$content_tags = array_filter(
		array_slice(Time::to_('banner/content')->childs(), 1), // cut default group
		create_function('$c', 'return $c->value("type") != "inherit";')
	);
	$content = DroneFunc::wpContitionTagSwitch($content_tags, Time::to_('banner/content/default'));
}

?>

<?php if ($content->value('type') == 'empty' && $content->value('height')): // Empty ?>

	<div class="under-container" style="position: relative; height: <?php echo $content->value('height'); ?>px;">
		<?php get_template_part('parts/background', 'open'); ?>
	</div>

<?php elseif ($content->value('type') == 'image' && $content->value('image')): // Image ?>

	<div class="outer-container transparent">
		<?php get_template_part('parts/background', 'open'); ?>
		<div class="container">
			<figure class="full-width">
				<?php echo wp_get_attachment_image($content->value('image'), 'banner'); ?>
			</figure>
		</div>
	</div>

<?php elseif ($content->value('type') == 'thumbnail' && has_post_thumbnail()): // Featured image ?>

	<div class="outer-container transparent">
		<?php get_template_part('parts/background', 'open'); ?>
		<div class="container">
			<figure class="full-width">
				<?php the_post_thumbnail('banner'); ?>
			</figure>
		</div>
	</div>

<?php elseif ($content->value('type') == 'layerslider'): // Slider ?>

	<div class="under-container">
		<?php get_template_part('parts/background', 'open'); ?>
		<div class="container">
			<?php Time::layerSliderSlider($content->value('layerslider')); ?>
		</div>
	</div>

<?php elseif ($content->value('type') == 'custom'): // Custom ?>

	<div class="outer-container transparent">
		<?php get_template_part('parts/background', 'open'); ?>
		<div class="container">
			<?php echo DroneFunc::wpProcessContent($content->value('custom')); ?>
		</div>
	</div>

<?php endif; ?>