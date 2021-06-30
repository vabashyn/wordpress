<?php
/*
Plugin Name: Admin Theme Page (SAP)
Description: Простое администрирование сайта на основе SMOF системы.
Version: 0.0.1
Author: Souvel
Author URI: http://souvel.ru/
License: Private
Text Domain: souvel
*/
define('SAP_VERSION', '0.0.1');
define('SMOF_VERSION', '1.5.2');

if( !defined('SAP_PATH') )
	define('SAP_PATH', plugin_dir_path(__FILE__));
if( !defined('SAP_URI') )
	define('SAP_URI', plugin_dir_url(__FILE__ ));

$template_path = get_template_directory();

if ( file_exists($template_path . '/inc/optionpage.php') ) {
	$theme_data = wp_get_theme();
	$theme_version = $theme_data->get('Version');
	$theme_name = $theme_data->get('Name');

	if( !defined('SAP_THEMENAME') )
		define('SAP_THEMENAME', $theme_name);
	if( !defined('SAP_THEMEVER') )
		define('SAP_THEMEVER', $theme_version);

	if ( is_admin() ) {
		require_once($template_path . '/inc/optionpage.php');
		require_once(SAP_PATH . 'inc/class.options_machine.php');
		require_once(SAP_PATH . 'inc/functions.admin.php');

		add_action('wp_ajax_sap_ajax_post_action', 'sap_ajax_callback');
	}

	add_action('setup_theme', 'sap_get_theme_option');
}

function sap_get_theme_option() {
	global $sap_option;

	$sap_option = get_option("sap_options_theme_mods");

	$sap_option = apply_filters('sap_options_after_load', $sap_option);
}

function get_opt($key = NULL) {
	if ( $key == NULL )
		return NULL;

	global $sap_option;

	if ( isset($sap_option[$key]) )
		return $sap_option[$key];
	else
		return NULL;
}

function the_opt($key = NULL) {
	echo get_opt($key);
}

add_shortcode('opt', 'opt_shortcode');
function opt_shortcode($attr, $content = NULL) {
	extract( shortcode_atts( array(
		'k' => NULL
	 ), $attr ) );

	return get_opt($k);
}

add_filter('sap_options_after_load', 'sap_filter_load_media_upload');
function sap_filter_load_media_upload($data) {

	if(!is_array($data)) return $data;

	foreach ($data as $key => $value) {
		if (is_string($value)) {
			$data[$key] = str_replace(
				array(
					'[site_url]',
					'[site_url_secure]',
				),
				array(
					site_url('', 'http'),
					site_url('', 'https'),
				),
				$value
			);
		}
	}

	return $data;
}
