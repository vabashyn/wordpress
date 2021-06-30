<?php
defined('ABSPATH') OR exit('No direct script access allowed');

add_action('init','sap_options');
function sap_options() {
	global $sap_options;

	$sap_options = [];

	$sap_options [] = [
		'name' => 'Основные настройки',
		'type' => 'heading',
	];

}
