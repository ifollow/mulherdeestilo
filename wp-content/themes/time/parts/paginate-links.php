<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// Validation
if (!(is_home() || is_archive() || is_search())) {
	return;
}

global $wp_query;

// Paginate links
$pagination = paginate_links(array(
	'base'      => str_replace('99999999', '%#%', get_pagenum_link(99999999)),
	'current'   => max(1, get_query_var('paged')),
	'total'     => $wp_query->max_num_pages,
	'prev_next' => Time::to('site/pagination') == 'numbers_navigation',
	'prev_text' => '&lsaquo;',
	'next_text' => '&rsaquo;',
	'mid_size'  => 1
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
if (!apply_filters('time_pagination_display', true, 'blog')) {
	return;
}

?>

<?php if (Time::to('site/blog/style') == 'bricks'): ?>
	<div class="pagination"><?php echo $pagination; ?></div>
<?php else: ?>
	<section class="section"><div class="pagination"><?php echo $pagination; ?></div></section>
<?php endif; ?>