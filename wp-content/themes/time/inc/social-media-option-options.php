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

$this->icon->options = apply_filters('time_social_media_icons', array(
	''                    => '',
	'behance'             => 'Behance',
	'blogger-rect'        => sprintf('Blogger (%s)', __('rect', 'time')),
	'blogger'             => 'Blogger',
	'deviantart'          => 'Deviantart',
	'dribbble-circled'    => sprintf('Dribbble (%s)', __('circled', 'time')),
	'dribbble'            => 'Dribbble',
	'dropbox'             => 'Dropbox',
	'evernote'            => 'Evernote',
	'facebook'            => 'Facebook',
	'facebook-circled'    => sprintf('Facebook (%s)', __('circled', 'time')),
	'facebook-rect'       => sprintf('Facebook (%s)', __('rect', 'time')),
	'flattr'              => 'Flattr',
	'flickr-circled'      => sprintf('Flickr (%s)', __('circled', 'time')),
	'flickr'              => 'Flickr',
	'friendfeed-rect'     => sprintf('Friendfeed (%s)', __('rect', 'time')),
	'friendfeed'          => 'Friendfeed',
	'github'              => 'Github',
	'github-circled'      => sprintf('Github (%s)', __('circled', 'time')),
	'github-text'         => sprintf('Github (%s)', __('text', 'time')),
	'google-circles'      => 'Google circles',
	'googleplus-rect'     => sprintf('Googleplus (%s)', __('rect', 'time')),
	'gplus-circled'       => sprintf('Gplus (%s)', __('circled', 'time')),
	'gplus'               => 'Gplus',
	'icq'                 => 'Icq',
	'instagram-filled'    => sprintf('Instagram (%s)', __('filled', 'time')),
	'instagram'           => 'Instagram',
	'instagram-1'         => 'Instagram 1',
	'jabber'              => 'Jabber',
	'lastfm'              => 'Lastfm',
	'lastfm-circled'      => sprintf('Lastfm (%s)', __('circled', 'time')),
	'lastfm-rect'         => sprintf('Lastfm (%s)', __('rect', 'time')),
	'linkedin'            => 'Linkedin',
	'linkedin-circled'    => sprintf('Linkedin (%s)', __('circled', 'time')),
	'linkedin-rect'       => sprintf('Linkedin (%s)', __('rect', 'time')),
	'mail'                => 'Mail',
	'mixi'                => 'Mixi',
	'odnoklassniki-rect'  => sprintf('Odnoklassniki (%s)', __('rect', 'time')),
	'odnoklassniki'       => 'Odnoklassniki',
	'paypal'              => 'Paypal',
	'picasa'              => 'Picasa',
	'pinterest-circled'   => sprintf('Pinterest (%s)', __('circled', 'time')),
	'pinterest'           => 'Pinterest',
	'qq'                  => 'QQ',
	'rdio-circled'        => sprintf('Rdio (%s)', __('circled', 'time')),
	'rdio'                => 'Rdio',
	'renren'              => 'Renren',
	'rss'                 => 'RSS',
	'sina-weibo'          => 'Sina weibo',
	'skype-circled'       => sprintf('Skype (%s)', __('circled', 'time')),
	'skype'               => 'Skype',
	'smashing'            => 'Smashing',
	'soundcloud'          => 'Soundcloud',
	'spotify'             => 'Spotify',
	'stumbleupon-circled' => sprintf('Stumbleupon (%s)', __('circled', 'time')),
	'stumbleupon'         => 'Stumbleupon',
	'tumblr'              => 'Tumblr',
	'tumblr-circled'      => sprintf('Tumblr (%s)', __('circled', 'time')),
	'tumblr-rect'         => sprintf('Tumblr (%s)', __('rect', 'time')),
	'twitter-circled'     => sprintf('Twitter (%s)', __('circled', 'time')),
	'twitter'             => 'Twitter',
	'vimeo'               => 'Vimeo',
	'vimeo-circled'       => sprintf('Vimeo (%s)', __('circled', 'time')),
	'vimeo-rect'          => sprintf('Vimeo (%s)', __('rect', 'time')),
	'vkontakte'           => 'Vkontakte',
	'wordpress'           => 'Wordpress',
	'yandex-rect'         => sprintf('Yandex (%s)', __('rect', 'time')),
	'yandex'              => 'Yandex',
	'youtube'             => 'Youtube',
	'youtube-play'        => sprintf('Youtube (%s)', __('play', 'time'))
));