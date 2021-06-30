<?php
defined('ABSPATH') OR exit('No direct script access allowed');

if ( wp_doing_ajax() ) {
	require_once(get_template_directory().'/../../mu-plugins/plugins.php');
	sap_get_theme_option();
}

include_once(get_template_directory().'/inc/other.php');
include_once(get_template_directory().'/inc/menu-field.php');
include_once(get_template_directory().'/inc/advanced-options.php');
include_once(get_template_directory().'/inc/elements.php');
include_once(get_template_directory().'/inc/shortcodes.php');
include_once(get_template_directory().'/inc/acf.php');

/**
 * Enqueue scripts and styles.
 *
 * wp_register_style('style', $themes_uri . '/assets/css/style.css', false, $ver);
 * wp_enqueue_style('style');
 *
 * wp_register_script('jquery', $themes_uri . '/assets/js/production.js', false, $ver, true);
 * wp_enqueue_script('jquery');
 * wp_enqueue_script('gmaps', '//maps.googleapis.com/maps/api/js?sensor=false', false, '', true);
 *
 */
function wordpress_scripts() {
	$ver = 1;
	$themes_uri = get_template_directory_uri();

	wp_register_style('style', $themes_uri . '/assets/css/style.css', false, $ver);
	wp_enqueue_style('style');


	wp_deregister_script('jquery');
	wp_deregister_script('wp-embed');
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');

	wp_register_script('jquery', $themes_uri . '/assets/js/production.js', false, $ver, true);
	wp_enqueue_script('jquery');

	wp_add_inline_script('jquery', 'var ajaxData = {"url":'.json_encode(admin_url('admin-ajax.php')).',"protect":'.json_encode(wp_create_nonce('forCustomAjax')).'};', 'before');
}
add_action('wp_enqueue_scripts', 'wordpress_scripts');

add_action('init', 'custom_posts');
function custom_posts() {

}
