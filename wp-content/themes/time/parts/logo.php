<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// Blog name
$name = esc_attr(get_bloginfo('name', 'display'));

// Logo
$logo = DroneHTML::make('h1')->class('logo vertical-align');

// Hyperlink
$a = $logo->addNew('a')
	->href(esc_url(home_url('/')))
	->title($name)
	->rel('home');

// Centered
if (Time::to('header/logo/center')) {
	$logo->addClass('center');
}

if (Time::to_('header/logo/image')->property('image1x')) {

	// Image logo
	$img = Time::to_('header/logo/image')->image();
	$img->alt = $name;
	$a->add($img);

} else {

	// Text logo
	$a->add($name);

}

// Logo
$logo->ehtml();