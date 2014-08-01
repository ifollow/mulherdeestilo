<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// Validation
if (!is_singular()) {
	return;
}

// Pagination
$pagination = wp_link_pages(array(
	'before'           => ' ',
	'after'            => ' ',
	'next_or_number'   => rtrim(Time::to('site/page_pagination'), 's'),
	'previouspagelink' => __('&lsaquo; Previous page', 'time'),
	'nextpagelink'     => __('Next page &rsaquo;', 'time'),
	'echo'             => false
));

if (!$pagination) {
	return;
}

// Post processing
$pagination = str_replace('<a ', '<a class="button" ', $pagination);
$pagination = preg_replace('/ ([0-9]+) /',' <span class="button active">\1</span> ', $pagination);

// Filters
if (!apply_filters('time_pagination_display', true, 'page')) {
	return;
}

?>

<div class="pagination"><?php echo $pagination; ?></div>