<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    2.1.0
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

$count   = $product->get_rating_count();
$average = $product->get_average_rating();

?>

<?php if ($count > 0): ?>
	<div title="<?php printf(__('Rated %s out of 5', 'woocommerce'), $average); ?>" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<?php echo Time::getInstance()->shortcodeRating(array('tag' => 'span', 'rate' => $average, 'max' => 5)); ?>
		<a href="#reviews" rel="nofollow">(<?php printf(_n('%s customer review', '%s customer reviews', $count, 'woocommerce'), '<span itemprop="ratingCount" class="count">'.$count.'</span>'); ?>)</a>
	</div>
<?php endif; ?>