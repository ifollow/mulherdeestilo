<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

if (is_singular() && Time::po('layout/background/enabled')) {
	Time::po_('layout/background/background')->background()->ehtml();
} else if (Time::to('general/background/custom')) {
	Time::to_('general/background/background')->background()->ehtml();
}