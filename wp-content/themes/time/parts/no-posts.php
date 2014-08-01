<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<section class="section">

	<?php if (is_search()): ?>

		<p><?php _e('Nothing was found. Please try again with different keywords.', 'time'); ?></p>
		<?php get_search_form(); ?>

	<?php else: ?>

		<p><?php _e("There's nothing here.", 'time'); ?></p>

	<?php endif; ?>

</section>