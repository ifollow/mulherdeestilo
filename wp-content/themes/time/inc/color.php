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

$color = <<<EOT
	a,
	a.alt:hover,
	.alt a:hover,
	#bottom a.alt:hover,
	#bottom .alt a:hover,
	h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover,
	input[type="button"].active, button.active, .button.active,
	.color,
	.super-tabs > div > .nav h2 span,
	.toggles > div > h3:hover > i,
	.logo,
	nav a:hover,
	#bottom nav a:hover,
	nav .current > a, nav .current > a:hover
	{
		color: %1\$s;
	}

	mark,
	.slider .control-nav li a:hover,
	.slider .control-nav li a.active,
	#top:before,
	#top > .before,
	.background-color,
	nav.mobile a:hover,
	nav.mobile .current > a,
	.mejs-controls .mejs-time-rail .mejs-time-loaded,
	.mejs-controls .mejs-time-rail .mejs-time-current
	{
		background-color: %1\$s;
	}

	.zoom-hover > .zoom-hover-overlay
	{
		background-color: rgba(%2\$s, 0.75);
	}

	blockquote.bar,
	.sticky:before,
	#bottom .outer-container
	{
		border-color: %1\$s;
	}
EOT;
