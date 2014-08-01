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

class TimeOptionsRetinaAttachmentOption extends DroneOptionsComplexOption
{

	// -------------------------------------------------------------------------

	protected $image1x;
	protected $image2x;

	// -------------------------------------------------------------------------

	protected function _options()
	{
		return array(
			'image1x' => 'attachment',
			'image2x' => 'attachment'
		);
	}

	// -------------------------------------------------------------------------

	protected function _html()
	{
		$html = DroneHTML::make('div')
			->class($this->getCSSClass(__CLASS__))
			->add($this->image1x->html());
		if (isset($this->image2x)) {
			$html->add(
				$this->image2x->html()
					->style('margin-top: 3px;')
					->add(' ', __('@2x', 'time'))
			);
		}
		return $html;
	}

	// -------------------------------------------------------------------------

	public function __construct($name, $default, $properties = array())
	{
		parent::__construct($name, $default, $properties);
		/* if (isset($this->image2x)) {
			$this->image2x->on_html = create_function('$o, &$html', '$html->add(" ", "'.__('@2x', 'time').'");');
		} */
	}

	// -------------------------------------------------------------------------

	public function image()
	{
		if ($this->image1x->value === 0 || ($data1x = wp_get_attachment_image_src($this->image1x->value, '')) === false) {
			return;
		}
		$img = DroneHTML::make('img')
			->src($data1x[0])
			->width($data1x[1])
			->height($data1x[2]);
		if (isset($this->image2x) && $this->image2x->value !== 0 && ($data2x = wp_get_attachment_image_src($this->image2x->value, '')) !== false) {
			$img->attr('data-2x', $data2x[0]);
		}
		return $img;
	}

}

// -----------------------------------------------------------------------------

class TimeOptionsBackgroundOption extends DroneOptionsBackgroundOption
{

	// -------------------------------------------------------------------------

	protected $image_ex;
	protected $stripes;

	// -------------------------------------------------------------------------

	protected function _options()
	{
		return parent::_options()+array(
			'image_ex' => 'retina_attachment',
			'stripes'  => 'boolean'
		);
	}

	// -------------------------------------------------------------------------

	protected function _html()
	{
		$html = parent::_html()
			->addClass($this->getCSSClass(__CLASS__));
		if (isset($this->image_ex)) {
			$html->delete(0);
			$html->insert($this->image_ex->html());
		}
		if (isset($this->stripes)) {
			$html->add('<br />', $this->stripes->html());
		}
		return $html;
	}

	// -------------------------------------------------------------------------

	public function __construct($name, $default, $properties = array())
	{
		$default['image'] = '';
		parent::__construct($name, $default, $properties);
		if (isset($this->stripes)) {
			$this->stripes->caption = __('Add stripes', 'time');
		}
	}

	// -------------------------------------------------------------------------

	public function css($selector = '')
	{
		if (isset($this->image_ex)) {
			$this->image->value = $this->image_ex->option('image1x')->uri();
		}
		$css = parent::css($selector);
		if (strpos($css, 'background-size:') === false && isset($this->image_ex) && ($size = $this->image_ex->option('image1x')->size()) !== false) {
			$css .= sprintf(' background-size: %dpx %dpx;', $size['width'], $size['height']);
		}
		return $css;
	}

	// -------------------------------------------------------------------------

	public function background()
	{
		$background = DroneHTML::make('div')
			->style($this->css())
			->add();
		if (isset($this->image_ex) && !is_null($image2x = $this->image_ex->option('image2x')) && $image2x->value > 0) {
			$background->attr('data-bg-2x', $image2x->uri());
		}
		if (isset($this->stripes) && $this->stripes->value) {
			$background->class = 'stripes';
		}
		return $background;
	}

}

// -----------------------------------------------------------------------------

class TimeOptionsCustomFontOption extends DroneOptionsFontOption
{

	// -------------------------------------------------------------------------

	protected $id;

	// -------------------------------------------------------------------------

	protected function _options()
	{
		return array('id' => 'text')+parent::_options();
	}

	// -------------------------------------------------------------------------

	protected function _html()
	{
		$html = parent::_html();
		$html->insert(array($this->id->html(), ' '));
		return $html;
	}

	// -------------------------------------------------------------------------

	public function __construct($name, $default, $properties = array())
	{
		parent::__construct($name, $default, $properties);
		$this->id->on_html = create_function('$o, &$html', '$html->style("width: 140px;");');
		$this->size->max = 1000;
	}

}

// -----------------------------------------------------------------------------

class TimeOptionsSidebarOption extends DroneOptionsOption
{

	// -------------------------------------------------------------------------

	public $options = array();

	// -------------------------------------------------------------------------

	protected function _styles()
	{
		return
<<<EOS
			.time-option-sidebar {
				margin: -5px;
				overflow: hidden;
			}
			.time-option-sidebar > div {
				border: 1px solid #dfdfdf;
				background: #fbfbfb;
				cursor: move;
				float: left;
				margin: 5px;
				padding: 10px;
				width: 120px;
				height: 46px;
			}
			.time-option-sidebar > .time-option-sidebar-placeholder {
				border-style: dashed;
				background: none;
			}
			.time-option-sidebar > div > select {
				font-weight: normal;
				min-width: auto;
				width: 100%;
			}
EOS;
	}

	// -------------------------------------------------------------------------

	protected function _scripts()
	{
		return
<<<EOS
			jQuery(document).ready(function($) {
				$('.time-option-sidebar').sortable({
					items:       '> div',
					placeholder: 'time-option-sidebar-placeholder'
				});
			});
EOS;
	}

	// -------------------------------------------------------------------------

	protected function _sanitize($value)
	{
		$value = (array)$value;
		$value = array_intersect($value, array_merge(array('#'), array_keys($this->options)));
		if (count($value) != 3 || count(array_keys($value, '#')) != 1) {
			$value = $this->default;
		}
		return $value;
	}

	// -------------------------------------------------------------------------

	protected function _html()
	{
		$html = DroneHTML::make('div')->class($this->getCSSClass(__CLASS__));
		foreach ($this->value as $value) {
			if ($value == '#') {
				$html->addNew('div')->add(
					__('Content', 'time'), DroneHTML::makeHidden($this->name.'[]', '#')
				);
			} else {
				$html->addNew('div')->add(
					__('Sidebar', 'time'), '<br />', DroneHTML::makeSelect($this->name.'[]', $value, $this->options)
				);
			}
		}
		return $html;
	}

}

// -----------------------------------------------------------------------------

class TimeOptionsSocialMediaOption extends DroneOptionsComplexOption
{

	// -------------------------------------------------------------------------

	protected $icon;
	protected $title;
	protected $url;

	// -------------------------------------------------------------------------

	protected function _options()
	{
		return array(
			'icon'  => 'list',
			'title' => 'text',
			'url'   => 'codeline'
		);
	}

	// -------------------------------------------------------------------------

	protected function _html()
	{
		$html = DroneHTML::make('div')->class($this->getCSSClass(__CLASS__));
		$html->add(
			$this->icon->html(),
			DroneHTML::make('div')->style('margin: 0 0 4px 70px;')->add(
				$this->title->html()
			),
			$this->url->html()
		);
		return $html;
	}

	// -------------------------------------------------------------------------

	public function __construct($name, $default, $properties = array())
	{
		parent::__construct($name, $default, $properties);
		require TEMPLATEPATH.'/inc/social-media-option-options.php';
		$this->icon->on_html = create_function('$o, $html', '$html->style("float: left; min-width: 0; width: 65px;");');
	}

}