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

$html = '';
foreach ($messages as $message) {
	$html .= '<i class="icon-cancel"></i>'.wp_kses_post($message).'<br />';
}
echo Time::getInstance()->shortcodeMessage(array('color' => 'orange'), $html);