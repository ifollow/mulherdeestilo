<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    2.0.0
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

global $product;

if (get_option('woocommerce_enable_review_rating') == 'no') {
	return;
}

?>

<?php if ($average = $product->get_average_rating()): ?>
	<span class="rating" title="<?php printf(__('Rated %s out of 5', 'woocommerce'), $average); ?>">
		<?php echo Time::getInstance()->shortcodeRating(array('tag' => null, 'rate' => $average, 'max' => 5)); ?>
	</span>
<?php else: ?>
	<span class="rating">
		<?php echo str_replace('icon-rating-empty', 'icon-rating-empty pad', Time::getInstance()->shortcodeRating(array('tag' => null, 'rate' => 0, 'max' => 5))); ?>
	</span>
<?php endif; ?>