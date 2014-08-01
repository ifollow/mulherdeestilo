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

class TimeWidgetSocialMedia extends DroneWidget
{

	// -------------------------------------------------------------------------

	protected function onSetupOptions($widget_options)
	{
		$widget_options->addOption('text', 'title', '', __('Title', 'time'));
		$widget_options->addOption('memo', 'description', '', __('Description', 'time'));
		$widget_options->addOption('array', 'icons', array('icon' => '', 'title' => '', 'url' => 'http://'), __('Icons', 'time'), '', array('type' => 'social_media'));
		$widget_options->addOption('list', 'gravity', 's', __('Tooltip position', 'time'), '', array('options' => array(
			'se' => __('Northwest', 'time'),
			's'  => __('North', 'time'),
			'sw' => __('Northeast', 'time'),
			'e'  => __('West', 'time'),
			'w'  => __('East', 'time'),
			'ne' => __('Southwest', 'time'),
			'n'  => __('South', 'time'),
			'nw' => __('Southeast', 'time')
		)));
		$widget_options->addOption('boolean', 'native_colors', true, '', '', array('caption' => __('Native hover colors', 'time')));
		$widget_options->addOption('boolean', 'new_window', false, '', '', array('caption' => __('Open links in new window', 'time')));
	}

	// -------------------------------------------------------------------------

	protected function onWidget($args, &$html)
	{
		if ($this->wo('description')) {
			$html->addNew('p')->add($this->wo('description'));
		}
		$div = $html->addNew('div')->class('social-icons');
		$ul  = $div->addNew('ul')->class('alt')->add();
		if ($this->wo('native_colors')) {
			$div->addClass('native-colors');
		}
		foreach ($this->wo('icons') as $icon) {
			if (!$icon['icon'] || !$icon['url']) {
				continue;
			}
			$li = $ul->addNew('li');
			$a = $li->addNew('a')->href($icon['url']);
			$a->addNew('i')->class('icon-'.$icon['icon'])->add();
			if ($icon['title']) {
				$a->title = $icon['title'];
				$a->class = 'tipsy-tooltip';
				$a->attr('data-tipsy-tooltip-gravity', $this->wo('gravity'));
			}
			if ($this->wo('new_window')) {
				$a->target = '_blank';
			}
		}
	}

	// -------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct(__('Social media', 'time'), __('Social media icons.', 'time'));
	}

}

// -----------------------------------------------------------------------------

class TimeWidgetSocialButtons extends DroneWidget
{

	// -------------------------------------------------------------------------

	protected function onSetupOptions($widget_options)
	{
		$widget_options->addOption('text', 'title', '', __('Title', 'time'));
		$widget_options->addOption('memo', 'description', '', __('Description', 'time'));
		$widget_options->addOption('array', 'media', '', __('Media', 'time'), '', array('type' => 'list', 'options' => array(
			'facebook'   => __('Facebook', 'time'),
			'twitter'    => __('Twitter', 'time'),
			'googleplus' => __('Google+', 'time'),
			'linkedin'   => __('LinkedIn', 'time'),
			'pinterest'  => __('Pinterest', 'time')
		)));
	}

	// -------------------------------------------------------------------------

	protected function onWidget($args, &$html)
	{
		if (!is_singular()) {
			return;
		}
		if ($this->wo('description')) {
			$html->addNew('p')->add($this->wo('description'));
		}
		$sb = Time::getInstance()->shortcodeSocialButtons(array(
			'style' => 'big',
			'media' => implode(', ', $this->wo('media'))
		));
		$html->add($sb);
	}

	// -------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct(__('Social buttons', 'time'), __('Social media buttons.', 'time'));
	}

}

// -----------------------------------------------------------------------------

class TimeWidgetContact extends DroneWidget
{

	// -------------------------------------------------------------------------

	protected function onSetupOptions($widget_options)
	{
		$widget_options->addOption('text', 'title', '', __('Title', 'time'));
		$widget_options->addOption('memo', 'description', '', __('Description', 'time'));
	}

	// -------------------------------------------------------------------------

	protected function onWidget($args, &$html)
	{
		if ($this->wo('description')) {
			$html->addNew('p')->add($this->wo('description'));
		}
		$cf = Time::getInstance()->shortcodeContact(array());
		$cf = str_replace('<textarea ', '<textarea class="full-width" ', $cf);
		$html->add($cf);
	}

	// -------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct(__('Contact form', 'time'), __('Displays contact form, which can be configured in Theme Options.', 'time'));
	}

}

// -----------------------------------------------------------------------------

class TimeWidgetFacebookLikeBox extends DroneWidget
{

	// -------------------------------------------------------------------------

	protected function onSetupOptions($widget_options)
	{
		$widget_options->addOption('codeline', 'href', '', __('Facebook page URL', 'time'), sprintf(__('E.g. %s', 'time'), '<code>http://www.facebook.com/platform</code>'));
		$widget_options->addOption('number', 'height', 320, __('Height', 'time'), '', array('unit' => 'px', 'min' => 50, 'max' => 1000));
		$widget_options->addOption('boolean', 'header', true, '', '', array('caption' => __('Show header', 'time')));
		$widget_options->addOption('boolean', 'stream', false, '', '', array('caption' => __('Show stream', 'time')));
		$widget_options->addOption('boolean', 'show_faces', true, '', '', array('caption' => __('Show faces', 'time')));
		$widget_options->addOption('boolean', 'border', true, '', '', array('caption' => __('Show border', 'time')));
	}

	// -------------------------------------------------------------------------

	protected function onWidget($args, &$html)
	{
		$footer = strpos($args['id'], 'footer-') === 0;
		$scheme = Time::to('general/scheme');
		if ($footer) {
			$scheme = $scheme == 'bright' ? 'dark' : 'bright';
		}
		$html = DroneHTML::make('div')
			->style(sprintf('height: %dpx;', $this->wo('height')))
			->add(
				$html = DroneHTML::make('div')
					->class('fb-like-box')
					->attr('data-href', $this->wo('href'))
					->attr('data-width', $footer ? 220 : Time::to('sidebar/width/'.$args['id'])-40)
					->attr('data-height', $this->wo('height'))
					->attr('data-header', DroneFunc::boolToString($this->wo('header')))
					->attr('data-stream', DroneFunc::boolToString($this->wo('stream')))
					->attr('data-show-faces', DroneFunc::boolToString($this->wo('show_faces')))
					->attr('data-show-border', DroneFunc::boolToString($this->wo('border')))
					->attr('data-colorscheme', $scheme == 'bright' ? 'light' : 'dark')
					->add()
			);
	}

	// -------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct(__('Facebook Like Box', 'time'), __('Configurable Facebook widget.', 'time'));
	}

}