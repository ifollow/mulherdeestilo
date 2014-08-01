<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.3
 * @version    1.6.4
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

if (!$messages) {
	return;
}

foreach ($messages as $message) {
	echo Time::getInstance()->shortcodeMessage(array('color' => 'green'), '<i class="icon-check"></i>'.wp_kses_post($message));
}