<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    1.6.4
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

?>

<?php do_action('woocommerce_before_single_product'); ?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="columns">
		<?php $is = explode('_', Time::to('woocommerce/product/image_size')); ?>
		<ul>
			<li class="<?php printf('col-%d-%d', $is[0]{0}, $is[0]{1}); ?>">
				<?php do_action('woocommerce_before_single_product_summary'); ?>
			</li>
			<li class="<?php printf('col-%d-%d', $is[1]{0}, $is[1]{1}); ?>">
				<div class="summary entry-summary">
					<?php do_action('woocommerce_single_product_summary'); ?>
				</div>
			</li>
		</ul>
	</div>

	<?php do_action('woocommerce_after_single_product_summary'); ?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>