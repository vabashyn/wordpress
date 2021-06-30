<?php
defined('ABSPATH') OR exit('No direct script access allowed');

if ( function_exists('acf_add_options_sub_page') ) {
	acf_add_options_sub_page([
		'page_title' 	=> 'Наполнение сайта',
		'menu_title'	=> 'Наполнение',
		'parent_slug'	=> 'themes.php',
		'menu_slug'	=> 'advanced-content',
	]);
}
