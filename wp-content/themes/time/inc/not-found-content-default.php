<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

$default = sprintf(
	"<h2>%s</h2>\n%s\n\n[search]",
	__('Are you lost?', 'time'),
	sprintf(__("This is 404 page - it seems you've encountered a dead link or missing page. You can use search form below to find what you're lookig for or go to a <a href=\"%s\">homepage</a>.", 'time'), esc_url(home_url('/')))
);