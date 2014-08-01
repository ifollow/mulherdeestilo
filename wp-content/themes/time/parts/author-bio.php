<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php if (apply_filters('time_author_bio_display', (bool)Time::io('layout/page/author_bio', array(get_post_type().'/author_bio', 'page/author_bio')))): ?>

	<section class="section">
		<figure class="alignleft fixed inset-border featured">
			<?php echo get_avatar(get_the_author_meta('ID'), 64); ?>
		</figure>
		<h3><?php the_author(); ?></h3>
		<p><?php echo nl2br(get_the_author_meta('description')); ?></p>
	</section>

<?php endif; ?>