<?php
defined('ABSPATH') OR exit('No direct script access allowed');
function get_blank_image($width, $height, $color = FALSE) {
	if ( ! is_numeric($width) || ! is_numeric($height) ) {
		return FALSE;
	}
	if ( $color != FALSE ) {
		if ( ! is_array($color)  ) {
			return FALSE;
		}
		$file_name = 'empty_image'.$width.'x'.$height.'_'.$color[0].$color[1].$color[2].'.jpg';
	} else {
		$file_name = 'empty_image'.$width.'x'.$height.'.jpg';
	}
	$pluging_dir = WPMU_PLUGIN_DIR.'/empty-image';
	$link = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $pluging_dir.'/cache/'.$file_name);
	$pluging_dir = wp_normalize_path($pluging_dir);
	$requested_file_uri = $pluging_dir.'/cache/'.$file_name;
	if ( file_exists($requested_file_uri) ) {
		return $link;
	} else {
		$output_image = @imagecreatetruecolor($width, $height);
		if ( ! $output_image ) {
			return FALSE;
		}

		if ( $color == FALSE ) {
			$color = array(240, 240, 240);
		}
		$imgcolor = @imagecolorallocate($output_image, $color[0], $color[1], $color[2]);


		imagefill($output_image, 0, 0, $imgcolor);
		imagejpeg($output_image, $requested_file_uri, 100);
		return $link;
	}
}

function the_blank_image($width, $height, $color = FALSE) {
	echo get_blank_image($width, $height, $color);
}
