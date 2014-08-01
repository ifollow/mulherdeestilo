<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

$meta = Time::to_(array(
	sprintf('%s/meta/%s', get_post_type(), is_singular() ? 'single' : 'list'),
	sprintf('%s/meta', get_post_type()),
	'page/meta'
));

?>

<?php
	if (is_search()) {
		$meta_display = false;
	} else if (is_singular() && !is_null(Time::po_('layout/page/meta')) && !Time::po_('layout/page/meta')->isDefault()) {
		$meta_display = (bool)Time::po('layout/page/meta');
	} else {
		$meta_display = $meta->value('visible');
	}
	if (apply_filters('time_meta_display', $meta_display)):
?>
	<?php if (is_singular()): ?><section class="section"><?php endif; ?>
		<ul class="meta alt">
			<?php
				foreach ($meta->value('items') as $item) {
					switch ($item) {
						case 'date_time':
							Time::postMetaFormat(
								'<li><a href="%date_month_link%" title="%s"><i class="icon-clock"></i>%s</a></li>',
								sprintf(__('View all posts from %s', 'time'), get_the_date('F')),
								sprintf(__('%1$s at %2$s', 'time'), Time::getPostMeta('date'), Time::getPostMeta('time'))
							);
							break;
						case 'date':
							Time::postMetaFormat('<li><a href="%date_month_link%" title="%s"><i class="icon-clock"></i>%date%</a></li>', sprintf(__('View all posts from %s', 'time'), get_the_date('F')));
							break;
						case 'mod_date':
							Time::postMetaFormat('<li><a href="%link%" title="%title_esc%"><i class="icon-clock"></i>%date_modified%</a></li>');
							break;
						case 'time_diff':
							Time::postMetaFormat('<li><a href="%link%" title="%title_esc%"><i class="icon-clock"></i>%time_diff%</a></li>');
							break;
						case 'comments':
							if (Time::$plugins['disqus']) {
								Time::postMetaFormat('<li><i class="icon-comment"></i><a href="%comments_link%">%comments_number%</a></li>');
							} else {
								Time::postMetaFormat('<li><a href="%comments_link%" title="%comments_number_esc%"><i class="icon-comment"></i>%comments_number%</a></li>');
							}
							break;
						case 'author':
							Time::postMetaFormat('<li><a href="%author_link%" title="%author_name_esc%"><i class="icon-user"></i>%author_name%</a></li>');
							break;
						case 'categories':
							if (get_post_type() == 'portfolio') {
								the_terms(get_the_ID(), 'portfolio-category', '<li><i class="icon-list"></i>', ', ', '</li>');
							} else {
								Time::postMetaFormat('[%category_list%]<li><i class="icon-list"></i>%category_list%</li>[/%category_list%]');
							}
							break;
						case 'tags':
							if (get_post_type() == 'portfolio') {
								the_terms(get_the_ID(), 'portfolio-tag', '<li><i class="icon-tag"></i>', ', ', '</li>');
							} else {
								Time::postMetaFormat('[%tags_list%]<li><i class="icon-tag"></i>%tags_list%</li>[/%tags_list%]');
							}
							break;
						case 'permalink':
							Time::postMetaFormat('<li><a href="%link%" title="%title_esc%"><i class="icon-link"></i>%s</a></li>', __('Permalink', 'time'));
							break;
						case 'edit_link':
							Time::postMetaFormat('[%link_edit%]<li><a href="%link_edit%" title="%1$s"><i class="icon-pencil"></i>%1$s</a></li>[/%link_edit%]', __('Edit', 'time'));
							break;
					}
				}
			?>
		</ul>
	<?php if (is_singular()): ?></section><?php endif; ?>
<?php endif; ?>