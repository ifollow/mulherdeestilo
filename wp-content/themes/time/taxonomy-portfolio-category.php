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

		<?php if (have_posts()) : ?>

			<section class="section">
				<div class="bricks" data-bricks-columns="<?php echo Time::to('portfolio/archive/columns'); ?>">
				<?php while (have_posts()): the_post(); ?>
					<div>
						<?php if (has_post_thumbnail()): ?>
							<figure class="featured full-width">
								<a href="<?php the_permalink(); ?>" <?php Time::imageAttrs('a'); ?>>
									<?php the_post_thumbnail(Time::getImageSize(Time::to('portfolio/archive/columns'))); ?>
								</a>
							</figure>
						<?php endif; ?>
						<?php if (Time::to('portfolio/archive/title')): ?>
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
						<?php endif; ?>
						<?php if (Time::to('portfolio/archive/excerpt') && has_excerpt()): ?>
							<p><?php echo do_shortcode(get_the_excerpt()); ?></p>
						<?php endif; ?>
						<?php if (Time::to('portfolio/archive/taxonomy/visible')): ?>
							<?php the_terms(get_the_ID(), 'portfolio-'.Time::to('portfolio/archive/taxonomy/taxonomy'), '<p class="small alt">', ', ', '</p>'); ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
				</div>
				<?php get_template_part('parts/paginate-links', 'portfolio'); ?>
			</section>

		<?php else: ?>

			<?php get_template_part('parts/no-posts'); ?>

		<?php endif; ?>

	<?php Time::closeContent(); ?>

</div>

<?php get_footer(); ?>