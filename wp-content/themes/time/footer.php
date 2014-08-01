<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

		</div>

		<div id="bottom">

			<?php get_sidebar('footer'); ?>

			<?php if (Time::to('footer/end_note/visible')): ?>
				<footer class="footer">

					<div class="container">

						<div class="section">
							<p class="small alpha"><?php echo nl2br(Time::to('footer/end_note/left')); ?></p>
							<p class="small beta"><?php echo nl2br(Time::to('footer/end_note/right')); ?></p>
						</div>

					</div>

				</footer>
			<?php endif; ?>

		</div>

		<?php wp_footer(); ?>

	</body>

</html>