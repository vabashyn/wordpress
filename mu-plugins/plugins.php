<?php
/*
Plugin Name: Базовые надстройки
Description: Добавление базовых надстроек в WordPress. Необходимо для корректной работы текущей темы.
Version: 0.0.1
Author: Souvel
Author URI: https://web-modern.by/
License: Private
Text Domain: souvel
*/

/**
 * Преобразование ЧПУ
 */
add_action('sanitize_title', 'sanitize_title_with_translit', 0);
function sanitize_title_with_translit($title) {
	$iso = array(
		"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
		"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
		"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
		"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
		"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
		"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
		"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
		"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
		"е"=>"e","ё"=>"yo","ж"=>"zh",
		"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
		"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
		"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
		"—"=>"-","«"=>"","»"=>"","…"=>""
	);
	return strtr($title, $iso);
}

/**
 * Преобразование имени файла при загрузке.
 * Удаление запрещённых символов из названия.
 */
add_filter('sanitize_file_name', 'sanitizeFileName', 1);
function sanitizeFileName($filename) {
	$format_del = strripos($filename, '.');

	$format = substr($filename, $format_del + 1);
	$title = substr($filename, 0, $format_del);

	$title = strtolower($title);

	$iso = array(
		"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
		"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
		"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
		"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
		"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
		"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
		"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
		"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
		"е"=>"e","ё"=>"yo","ж"=>"zh",
		"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
		"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
		"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
		"—"=>"-"
		);
	$char_iso = array(
		"q","w","e","r","t","y","u",'i',"o","p","a",
		"s","d","f","g","h","j","k","l","z","x","c",
		"v","b","n","m","Q","W","E","R","T","Y","U",
		"I","O","P","A","S","D","F","G","H","j","K",
		"L","Z","X","C","V","B","N","M","_","=","-",
		"1","2","3","4","5","6","7","8","9","0","+"
		);


	$title = strtr($title, $iso);
	$title = strtolower($title);
	$title_array = str_split($title);
	$title = '';

	$count = 0;
	foreach ($title_array as $value) {
		if ( in_array($value, $char_iso) ) {
			$title .= $value;
			$count ++;
			continue;
		}
	}
	if ( $title == '' ) {
		$title .= 'image';
	}

	return $title . '.' . $format;
}

/**
 * Получение текущего URL.
 */
function get_current_url() {
	$protocol = 'http';
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
	}
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTOCOL'];
	}
	$link = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	return $link;
}

/**
 * Получение текущего URL без GET параметров.
 * При указании конкретного ключа удаляет только конкретный параметр.
 */
function get_current_url_without_get($delete_get = false) {
	$protocol = 'http';
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
	}
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTOCOL'];
	}
	$link = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'];

	if ( $delete_get === false ) {
		return $link;
	}

	$get_link = '';
	foreach ($_GET as $key => $value) {
		if ( !isset($key) || !isset($value) || $value == '' ) {
			continue;
		}

		if ( $key == $delete_get ) {
			continue;
		}

		if ( $get_link != '' ) {
			$get_link .= '&';
		}

		if ( is_array($value) ) {
			$i = 0;
			foreach ($value as $array_value) {
				if ( $i != 0 ) {
					$get_link .= '&';
				}
				$i ++;
				$get_link .= $key . '%5B%5D=' . $array_value;
			}
			continue;
		}

		$get_link .= $key . '=' . $value;
	}

	if ( $get_link != '' ) {
		$link .= '?' . $get_link;
	}

	return $link;
}

/**
 * Получение текущего адреса страницы с заменой конкретного GET параметра.
 */
function get_current_url_replace_get($get_key = NULL, $get_value = NULL) {
	$protocol = 'http';
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
	}
	if ( isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) ) {
		$protocol = $_SERVER['HTTP_X_FORWARDED_PROTOCOL'];
	}
	$link = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'];
	$status_replace = false;

	$get_link = '';
	foreach ($_GET as $key => $value) {
		if ( !isset($key) || !isset($value) || $value == '' ) {
			continue;
		}

		if ( $get_link != '' ) {
			$get_link .= '&';
		}

		if ( $key == $get_key ) {
			$get_link .= $key . '=' . $get_value;
			$status_replace = true;
			continue;
		}

		if ( is_array($value) ) {
			$i = 0;
			foreach ($value as $array_value) {
				if ( $i != 0 ) {
					$get_link .= '&';
				}
				$i ++;
				$get_link .= $key . '%5B%5D=' . $array_value;
			}
			continue;
		}

		$get_link .= $key . '=' . $value;
	}

	if ( $status_replace === false ) {
		if ( $get_link != '' ) {
			$get_link .= '&';
		}

		$get_link .= $get_key . '=' . $get_value;
	}

	if ( $get_link != '' ) {
		$link .= '?' . $get_link;
	}

	return $link;
}

function wm_write_file($path, $data, $mode = 'wb') {
	$path_dir = substr($path, 0, strripos($path, '/'));

	if ( is_dir($path_dir) == FALSE ) {
		mkdir($path_dir, 0755, TRUE);
	}

	if ( ! $fp = @fopen($path, $mode)) {
		return FALSE;
	}

	flock($fp, LOCK_EX);

	for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result) {
		if (($result = fwrite($fp, substr($data, $written))) === FALSE) {
			break;
		}
	}

	flock($fp, LOCK_UN);
	fclose($fp);

	return is_int($result);
}

function wm_save_cache($key, $ttl, $data) {
	$cache_path = WP_CONTENT_DIR.'/wm-cache';

	$contents = array(
		'time'		=> time(),
		'ttl'		=> $ttl,
		'data'		=> $data
	);

	$file_name = $cache_path.'/'.$key.'.cache';
	if ( wm_write_file($file_name, serialize($contents)) ) {
		chmod($file_name, 0640);
		return TRUE;
	}

	return FALSE;
}

function wm_get_cache($key) {
	$cache_path = WP_CONTENT_DIR.'/wm-cache';
	$file_name = $cache_path.'/'.$key.'.cache';

	if ( ! is_file($file_name)) {
		return FALSE;
	}

	$data = unserialize(file_get_contents($file_name));

	if ($data['ttl'] > 0 && time() > $data['time'] + $data['ttl']) {
		unlink($file_name);
		return FALSE;
	}

	return $data['data'];
}

add_action('admin_bar_menu', function($wp_admin_bar) {
	$wp_admin_bar->add_menu([
		'id'	=> 'wm-cache',
		'title'	=> 'Очистить кэш',
		'href'	=> '#',
		'meta' => [
			'rel' => wp_create_nonce('wm-cache-clear'),
			'html' => '<script>var cstmAjaxUrl = '.json_encode(admin_url('admin-ajax.php')).';</script>',
		]
	]);
}, 1500);

add_action('wp_enqueue_scripts', 'advanced_js_enequeue', 200);
add_action('admin_enqueue_scripts', 'advanced_js_enequeue', 200);
function advanced_js_enequeue() {
	if ( is_user_logged_in() ) {
		wp_enqueue_script('advanced-js-admin', plugins_url('js/advanced_admin.js', __FILE__ ), ['jquery'], 1, TRUE);
	}
}

function wm_clear_cache() {
	$cache_path = WP_CONTENT_DIR.'/wm-cache'.'/';
	if (file_exists($cache_path)) {
		foreach ( glob($cache_path.'*') as $file) {
			unlink($file);
		}
	}

	return TRUE;
}

add_action('wp_ajax_clear-wm-cache', 'xhr_wm_clear_cache');
function xhr_wm_clear_cache() {
	if ( ! wp_verify_nonce($_POST['nonce'], 'wm-cache-clear') ) {
		header("HTTP/1.0 404 Not Found");
		exit();
	}

	wm_clear_cache();

	echo 1;
	exit();
}

include_once(dirname(__FILE__).'/admin-theme-page/admin-theme-page.php');
include_once(dirname(__FILE__).'/empty-image/eimage.php');
