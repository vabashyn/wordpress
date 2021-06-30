<?php
defined('ABSPATH') OR exit('No direct script access allowed');

function get_cf7_id($form_id, $advanced_text = '') {
	wpcf7_enqueue_scripts();
	wpcf7_enqueue_styles();

	return strtr(do_shortcode('[contact-form-7 id="'.$form_id.'" '.$advanced_text.']'), ['<br>' => '', '<br />' => '', '<br/>' => '']);
}

function the_cf7_id($form_id, $advanced_text = '') {
	echo get_cf7_id($form_id, $advanced_text);
}
