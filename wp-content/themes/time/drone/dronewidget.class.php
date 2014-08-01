<?php
 class DroneWidget extends WP_Widget { const WIDGET_LABEL_SEPARATOR = '|'; public static $_on_setup_options = array(); public static $_on_html = array(); public static $_on_output_html = array(); private $_widget_options; private $_domain; private $_id; protected function onSetupOptions($widget_options) { } public function onOptionsCompatybility(&$data, $version) { } protected function onWidget($args, &$html) { } private function getWidgetOptions($widget_data = null) { $widget_options = new DroneWidgetOptionsGroup(str_replace('[#]', '', $this->get_field_name('#'))); $this->onSetupOptions($widget_options); if (isset(self::$_on_setup_options[$this->_id]) && is_callable(self::$_on_setup_options[$this->_id])) { call_user_func_array(self::$_on_setup_options[$this->_id], array($this, $widget_options)); } if (!is_null($widget_data)) { $widget_options->fromArray($widget_data, array($this, 'onOptionsCompatybility')); } return $widget_options; } protected function htmlOutput($args, $title, $html) { _deprecated_function('DroneWidget::htmlOutput()', '3.0', 'DroneWidget::onWidget()'); if (!empty($title)) { $title = $args['before_title'].$title.$args['after_title']; } DroneHTML::make() ->add($args['before_widget'], $title, $html, $args['after_widget']) ->ehtml(); } public function __construct($label, $description = '', $class = '', $width = null) { $this->_domain = DroneTheme::getInstance()->domain; $this->_id = DroneFunc::stringID( str_replace(array(__CLASS__, DroneTheme::getInstance()->class.'Widget', DroneTheme::getInstance()->class), '', get_class($this)) ); if (DroneTheme::getInstance()->class == 'Website') { $this->_id = str_replace('-', '', $this->_id); } parent::__construct( DroneTheme::getInstance()->theme->id.'-'.$this->_id, sprintf('%s %s %s', DroneTheme::getInstance()->theme->name, self::WIDGET_LABEL_SEPARATOR, $label), array('description' => $description, 'classname' => $class ? $class : 'widget-'.$this->_id), array('width' => $width) ); } public function __get($name) { if (in_array($name, array('_domain', '_id'))) { return $this->{$name}; } } public function getTransientName($name = '') { return DroneFunc::stringID("{$this->_domain}_{$this->_id}_{$this->number}_{$name}", '_'); } public function wo_($name, $skip_if = null) { return $this->_widget_options->findChild($name, $skip_if); } public function wo($name, $skip_if = null, $fallback = null) { $child = self::wo_($name, $skip_if); return !is_null($child) && $child->isOption() ? $child->value : $fallback; } public function setWidgetOption($name, $value) { _deprecated_function('DroneWidget::setWidgetOption', '3.0'); return $this->_widget_options->value($name, $value); } public function getWidgetOption($name, $obj = false) { _deprecated_function('DroneWidget::getWidgetOption', '3.0', 'DroneWidget::wo()'); return $obj ? $this->_widget_options->child($name) : $this->_widget_options->value($name); } public function widgetOption($name) { _deprecated_function('DroneWidget::widgetOption', '3.0', 'echo DroneWidget::wo()'); echo $this->getWidgetOption($name); } public function form($instance) { DroneHTML::make('div') ->class('drone-widget-options') ->add($this->getWidgetOptions($instance)->html()) ->ehtml(); } public function update($new_instance, $old_instance) { return array_merge( $this->getWidgetOptions()->sanitize($new_instance), array(DroneWidgetOptionsGroup::VERSION_KEY => DroneTheme::getInstance()->version) ); } public function widget($args, $instance) { $this->_widget_options = $this->getWidgetOptions($instance); $html = DroneHTML::make(); $this->onWidget($args, $html); if ($html->tag || $html->count() > 0) { if (isset(self::$_on_html[$this->_id]) && is_callable(self::$_on_html[$this->_id])) { call_user_func_array(self::$_on_html[$this->_id], array($this, &$html)); } $output_html = DroneHTML::make()->add($args['before_widget']); if ($title = $this->wo('title')) { $output_html->add($args['before_title'], $title, $args['after_title']); } $output_html->add($html, $args['after_widget']); if (isset(self::$_on_output_html[$this->_id]) && is_callable(self::$_on_output_html[$this->_id])) { call_user_func_array(self::$_on_output_html[$this->_id], array($this, &$output_html)); } $output_html->ehtml(); } } } class DroneWidgetUnwrappedText extends DroneWidget { protected function onSetupOptions($widget_options) { $widget_options->addOption('text', 'title', '', __('Title', $this->_domain)); $widget_options->addOption('code', 'text', '', __('Text', $this->_domain), '', array('on_html' => create_function( '$option, &$html', '$html->style("height: 25em;");' ))); $widget_options->addOption('boolean', 'shortcodes', false, '', '', array('caption' => __('Allow shortcodes', $this->_domain))); } public function __construct() { parent::__construct(__('Unwrapped Text', $this->_domain), __('For pure HTML code.', $this->_domain), '', 600); } protected function onWidget($args, &$html) { if ($this->wo('shortcodes')) { $html->add(do_shortcode($this->wo('text'))); } else { $html->add($this->wo('text')); } } } class DroneWidgetPage extends DroneWidget { protected function onSetupOptions($widget_options) { $widget_options->addOption('text', 'title', '', __('Title', $this->_domain)); $widget_options->addOption('list', 'page', '', __('Page', $this->_domain), '', array('options' => array('' => '')+DroneFunc::wpPostsList(array( 'numberposts' => -1, 'post_type' => 'page' )))); } public function __construct() { parent::__construct(__('Page', $this->_domain), __('Displays content of a specified page.', $this->_domain)); } protected function onWidget($args, &$html) { if ($this->wo('page') && !is_null($page = get_post($this->wo('page')))) { $html->add(DroneFunc::wpProcessContent($page->post_content)); } } } class DroneWidgetPostsList extends DroneWidget { public static $_on_post; protected function onSetupOptions($widget_options) { $widget_options->addOption('text', 'title', '', __('Title', $this->_domain)); $widget_options->addOption('list', 'category', 0, __('Category', $this->_domain), '', array('options' => array(0 => __('All', $this->_domain))+DroneFunc::wpTermsList('category') )); $widget_options->addOption('list', 'orderby', 'date', __('Sort by', $this->_domain), '', array('options' => array( 'title' => __('Title', $this->_domain), 'date' => __('Date', $this->_domain), 'modified' => __('Modified date', $this->_domain), 'comment_count' => __('Comment count', $this->_domain), 'rand' => __('Random order', $this->_domain) ))); $widget_options->addOption('list', 'order', 'desc', __('Sort order', $this->_domain), '', array('options' => array( 'asc' => __('Ascending', $this->_domain), 'desc' => __('Descending', $this->_domain) ))); $widget_options->addOption('number', 'count', 5, __('Posts count', $this->_domain), '', array('min' => 1, 'max' => 20)); $widget_options->addOption('boolean', 'author', false, '', '', array('caption' => __('Show post author', $this->_domain))); $widget_options->addOption('boolean', 'comments', false, '', '', array('caption' => __('Show comments count', $this->_domain))); } public function __construct() { parent::__construct(__('Posts List', $this->_domain), __('Displays list of posts by specific criteria (e.g.: newest posts, most commented, random posts, etc.).', $this->_domain)); } protected function onWidget($args, &$html) { $posts = get_posts(array( 'category' => $this->wo('category'), 'numberposts' => $this->wo('count'), 'orderby' => $this->wo('orderby'), 'order' => strtoupper($this->wo('order')), 'exclude' => is_single() ? get_the_ID() : '' )); $html = DroneHTML::make('ul')->add(); foreach ($posts as $post) { $li = DroneHTML::make('li')->add( DroneHTML::make('a') ->href(esc_url(apply_filters('the_permalink', get_permalink($post->ID)))) ->title(esc_attr($post->post_title)) ->add($post->post_title) ); if ($this->wo('author')) { $author = get_userdata($post->post_author); $li->add( '&nbsp;', sprintf(__('by %s', $this->_domain), DroneHTML::make('a')->href(get_author_posts_url($post->post_author))->title(esc_attr($author->display_name))->add($author->display_name)->html()) ); } if ($this->wo('comments')) { $li->add(sprintf(' (%d)', $post->comment_count)); } if (is_callable(self::$_on_post)) { call_user_func_array(self::$_on_post, array($this, $post, &$li)); } $html->add($li); } } } class DroneWidgetTwitter extends DroneWidget { const TWEETS_BACKUP_INTERVAL = 604800; public static $_on_tweet; public function usernameOnSanitize($option, $original_value, &$value) { if (preg_match('|^((https?://)?(www\.)?twitter\.com/(#!/)?)?(.+?)/?$|i', $value, $matches)) { $value = $matches[5]; } } protected function onSetupOptions($widget_options) { $widget_options->addOption('text', 'title', '', __('Title', $this->_domain)); $widget_options->addOption('codeline', 'username', '', __('Username', $this->_domain), '', array('on_sanitize' => array($this, 'usernameOnSanitize'))); $widget_options->addOption('number', 'count', 5, __('Tweets count', $this->_domain), '', array('min' => 1, 'max' => 20)); $widget_options->addOption('number', 'interval', 30, __('Update interval', $this->_domain), __('Tweets receiving interval (in minutes).', $this->_domain), array('min' => 1)); $widget_options->addOption('boolean', 'include_retweets', true, '', '', array('caption' => __('Include retweets', $this->_domain))); $widget_options->addOption('boolean', 'exclude_replies', false, '', '', array('caption' => __('Exclude replies', $this->_domain))); $oauth = $widget_options->addGroup('oauth'); $oauth->addOption('codeline', 'consumer_key', '', __('Application consumer key', $this->_domain)); $oauth->addOption('codeline', 'consumer_secret', '', __('Application consumer secret', $this->_domain)); $oauth->addOption('codeline', 'access_token', '', __('Application access token', $this->_domain)); $oauth->addOption('codeline', 'access_token_secret', '', __('Application access token secret', $this->_domain)); } public function __construct() { parent::__construct(__('Twitter', $this->_domain), __('Twitter stream.', $this->_domain)); } public function update($new_instance, $old_instance) { delete_transient($this->getTransientName('valid')); delete_transient($this->getTransientName('tweets')); return parent::update($new_instance, $old_instance); } protected function onWidget($args, &$html) { if (!$this->wo('username')) { return; } if (($tweets = get_transient($this->getTransientName('tweets'))) === false || get_transient($this->getTransientName('valid')) === false) { $new_tweets = DroneFunc::twitterGetTweets( $this->wo_('oauth')->toArray(), $this->wo('username'), $this->wo('include_retweets'), $this->wo('exclude_replies'), $this->wo('count') ); if ($new_tweets !== false) { $tweets = $new_tweets; $interval = $this->wo('interval')*60; set_transient($this->getTransientName('valid'), true, $interval); set_transient($this->getTransientName('tweets'), $tweets, $interval+self::TWEETS_BACKUP_INTERVAL); } else if ($tweets === false) { return; } } $html = DroneHTML::make('ul')->add(); foreach ($tweets as $tweet) { $li = DroneHTML::make('li')->add( $tweet['html'], DroneHTML::make('br'), DroneHTML::make('small')->add( DroneHTML::make('a') ->href($tweet['url']) ->add(sprintf(__('%s ago', $this->_domain), human_time_diff($tweet['date']))) ) ); if (is_callable(self::$_on_tweet)) { call_user_func_array(self::$_on_tweet, array($this, $tweet, &$li)); } $html->add($li); } } } class DroneWidgetFlickr extends DroneWidget { public static $_on_photo; protected function onSetupOptions($widget_options) { $widget_options->addOption('text', 'title', '', __('Title', $this->_domain)); $widget_options->addOption('codeline', 'username', '', __('Username', $this->_domain), __('Screen name from Flickr account settings.', $this->_domain)); $widget_options->addOption('number', 'count', 4, __('Photos count', $this->_domain), '', array('min' => 1, 'max' => 50)); $widget_options->addOption('number', 'interval', 10, __('Update interval', $this->_domain), __('Photos receiving interval (in minutes).', $this->_domain), array('min' => 1)); $widget_options->addOption('list', 'url', 'image', 'Action after clickng on a photo', '', array('options' => array( 'flickr' => __('Open Flickr page with the photo', $this->_domain), 'image' => __('Open bigger version of the photo', $this->_domain) ))); $widget_options->addOption('codeline', 'api_key', '', __('API Key', $this->_domain), __('Optional (use only if you want to use your key).', $this->_domain)); } public function __construct() { parent::__construct(__('Flickr', $this->_domain), __('Flickr photo stream.', $this->_domain)); } public function update($new_instance, $old_instance) { delete_transient($this->getTransientName()); return parent::update($new_instance, $old_instance); } protected function onWidget($args, &$html) { if (!$this->wo('username')) { return; } $transient = $this->getTransientName(); if (($data = get_transient($transient)) === false) { $api_key = $this->wo('api_key'); if (empty($api_key)) { $api_key = DroneFunc::FLICKR_API_KEY; } if (($data['userdata'] = DroneFunc::flickrGetUserdata($api_key, $this->wo('username'))) === false) { return; } if (($data['photos'] = DroneFunc::flickrGetPhotos($api_key, $data['userdata']['id'], $this->wo('count'))) === false) { return; } set_transient($transient, $data, $this->wo('interval')*60); } $html = DroneHTML::make('ul')->add(); foreach ($data['photos'] as $photo) { $li = DroneHTML::make('li'); $li->addNew('a') ->href($this->wo('url') == 'flickr' ? $photo['url'] : sprintf($photo['src'], 'b')) ->title($photo['title']) ->rel($this->id) ->addNew('img') ->src(sprintf($photo['src'], 's')) ->alt($photo['title']) ->width(75) ->height(75); if (is_callable(self::$_on_photo)) { call_user_func_array(self::$_on_photo, array($this, $photo, &$li)); } $html->add($li); } } }