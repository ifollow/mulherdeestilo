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
$pagination = paginate_comments_links(array(
	'prev_next' => Time::to('site/comments/pagination') == 'numbers_navigation',
	'prev_text' => '&lsaquo;',
	'next_text' => '&rsaquo;',
	'echo'      => false
));

if (!$pagination) {
	return;
}

// Post processing
$pagination = preg_replace_callback(
	'/class=[\'"](prev |next )?page-numbers( current)?[\'"]()/i',
	create_function('$m', 'return "class=\\"button".str_replace("current", "active", $m[2])."\\"";'),
	$pagination
);

// Filters
if (!apply_filters('time_pagination_display', true, 'comments')) {
	return;
}

?>

<div class="pagination"><?php echo $pagination; ?></div>