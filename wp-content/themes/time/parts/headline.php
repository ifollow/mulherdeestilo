<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php
	if (is_singular() && !is_null(Time::po_('layout/headline')) && !Time::po_('layout/headline')->isDefault()) {
		$headline_display = (bool)Time::po('layout/headline');
	} else {
		$headline_display = DroneFunc::wpContitionTagSwitch(Time::to_('nav/headline/visible')->values(), false);
	}
	if (apply_filters('time_headline_display', $headline_display)):
?>

	<?php Time::$headline_used = true; ?>

	<div class="outer-container transparent">

		<div class="headline">

			<div class="container">

				<div class="section">
					<?php
						$content = Time::to('nav/headline/content');
						switch ($content) {
							case 'mixed':
								$content = is_single() ? 'navigation' : 'breadcrumbs';
								break;
							case 'navigation':
								if (!is_single()) $content = '';
								break;
						}
						if ($content = apply_filters('time_headline_content', $content)) {
							get_template_part('parts/'.$content);
						}
					?>
					<h1><?php
						if (Time::$plugins['woocommerce'] && (is_shop() || is_product_taxonomy()) && !is_product()) {
							woocommerce_page_title();
						} else if (is_day()) {
							echo get_the_date();
						} else if (is_month()) {
							echo get_the_date('F Y');
						} else if (is_year()) {
							echo get_the_date('Y');
						} else if (is_category() || is_tax('portfolio-category')) {
							echo single_cat_title('', false);
						} else if (is_tag() || is_tax('portfolio-tag')) {
							echo single_tag_title('', false);
						} else if (is_search()) {
							printf(__('Search results for: %s', 'time'), get_search_query());
						} else if (is_author()) {
							if (have_posts()) {
								the_post();
								printf(__('All posts by: %s', 'time'), get_the_author());
								rewind_posts();
							}
						} else if (is_singular()) {
							the_title();
						} else {
							wp_title('');
						}
					?></h1>
				</div>

			</div>

		</div>

	</div>

<?php endif; ?>