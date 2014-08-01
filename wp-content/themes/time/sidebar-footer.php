<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<div class="outer-container">

	<?php if (count($layout_classes = Time::getFooterLayoutClasses()) > 0): ?>

		<div class="container">

			<section class="section">

				<div class="columns alt-mobile">
					<ul>
						<?php foreach ($layout_classes as $i => $class): ?>
							<li class="<?php echo $class; ?>">
								<?php dynamic_sidebar(apply_filters('time_sidebar', 'footer-'.$i, 'footer')); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div><!-- // .columns -->

			</section>

		</div><!-- // .container -->

	<?php endif; ?>

</div><!-- // .outer-container -->