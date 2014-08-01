<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php get_header(); ?>

<div class="outer-container">

	<?php get_template_part('parts/nav-secondary', 'lower'); ?>

	<?php Time::openContent(); ?>

		<?php if (have_posts()): the_post(); ?>

			<section class="section">
				<?php get_template_part('parts/title'); ?>
				<figure class="full-width">
					<div <?php Time::imageAttrs('div'); ?>>
						<?php echo wp_get_attachment_image(get_the_ID(), 'full-width'); ?>
					</div>
					<?php if (has_excerpt()): ?>
						<figcaption><?php the_excerpt(); ?></figcaption>
					<?php endif; ?>
				</figure>
			</section>

			<?php get_template_part('parts/author-bio'); ?>
			<?php get_template_part('parts/social-buttons'); ?>
			<?php get_template_part('parts/meta'); ?>
			<?php comments_template(); ?>

		<?php endif; ?>

	<?php Time::closeContent(); ?>

</div>

<?php get_footer(); ?>