<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

$social_buttons = Time::to_(array(
	sprintf('%s/social_buttons/%s', get_post_type(), is_singular() ? 'single' : 'list'),
	sprintf('%s/social_buttons', get_post_type()),
	'page/social_buttons'
));

?>

<?php
	if (is_search()) {
		$social_buttons_display = false;
	} else if (is_singular() && !is_null(Time::po_('layout/page/social_buttons')) && !Time::po_('layout/page/social_buttons')->isDefault()) {
		$social_buttons_display = (bool)Time::po('layout/page/social_buttons');
	} else {
		$social_buttons_display = $social_buttons->value('visible');
	}
	if (apply_filters('time_social_buttons_display', $social_buttons_display)):
?>
	<?php if (is_singular()): ?><section class="section"><?php endif; ?>
		<ul class="meta social">
			<?php
				foreach ($social_buttons->value('items') as $item) {
					switch ($item) {
						case 'facebook':
							Time::postMetaFormat('<li><div class="fb-like" data-href="%link%" data-send="false" data-layout="button_count" data-show-faces="false"></div></li>');
							break;
						case 'twitter':
							Time::postMetaFormat('<li><a class="twitter-share-button" href="https://twitter.com/share" data-url="%link%" data-text="%title_esc%" data-count="horizontal">Tweet</a></li>');
							break;
						case 'googleplus':
							Time::postMetaFormat('<li><div class="g-plusone" data-href="%link%" data-size="medium" data-annotation="bubble"></div></li>');
							break;
						case 'linkedin':
							Time::postMetaFormat('<li><script class="inshare" type="IN/Share" data-url="%link%" data-counter="right" data-showzero="true"></script></li>');
							break;
						case 'pinterest':
							if (has_post_thumbnail()) {
								list($thumbnail_src) = wp_get_attachment_image_src(get_post_thumbnail_id());
							} else {
								$thumbnail_src = '';
							}
							printf('<li><a data-pin-config="beside" href="http://pinterest.com/pin/create/button/?url=%s&amp;media=%s&amp;description=%s" data-pin-do="buttonPin"><img src="http://assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></li>', urlencode(get_permalink()), urlencode($thumbnail_src), urlencode(get_the_title()));
							break;
					}
				}
			?>
		</ul>
	<?php if (is_singular()): ?></section><?php endif; ?>
<?php endif; ?>