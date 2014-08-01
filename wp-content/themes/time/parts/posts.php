<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php if (have_posts()) : ?>

	<?php if (Time::to('site/blog/style') == 'bricks'): ?>

		<section class="section">
			<div class="bricks alt-mobile" data-bricks-columns="<?php echo Time::to('site/blog/columns'); ?>" data-bricks-filter="<?php echo DroneFunc::boolToString($filter = Time::to('site/blog/filter')); ?>">
				<?php while (have_posts()): the_post(); ?>
					<?php
						if ($filter) {
							$terms = DroneFunc::wpPostTermsList(get_the_ID(), 'category');
							if (is_category() && ($term_id = array_search(single_cat_title('', false), $terms)) !== false) {
								unset($terms[$term_id]);
							}
							$rel = implode(' ', array_map(create_function('$t', 'return str_replace(" ", "_", $t);'), $terms));
						}
					?>
					<div<?php if ($filter) echo " rel=\"{$rel}\""; ?>>
						<?php get_template_part('parts/post'); ?><hr />
					</div>
				<?php endwhile; ?>
			</div>
			<?php get_template_part('parts/paginate-links'); ?>
		</section>

	<?php else: ?>

		<?php while (have_posts()): the_post(); ?>
			<section class="section"><?php get_template_part('parts/post'); ?></section>
		<?php endwhile; ?>
		<?php get_template_part('parts/paginate-links'); ?>

	<?php endif; ?>

<?php else: ?>

	<?php get_template_part('parts/no-posts'); ?>

<?php endif; ?>