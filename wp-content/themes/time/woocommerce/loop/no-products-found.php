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

echo Time::getInstance()->shortcodeMessage(array(), '<i class="icon-info-circled"></i>'.__('No products found which match your selection.', 'woocommerce'));