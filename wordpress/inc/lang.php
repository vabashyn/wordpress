<?php
defined('ABSPATH') OR exit('No direct script access allowed');

if ( ! function_exists('icl_object_id') ) {
	return;
}

/**
 * Отключаем загрузку стилей для стандартного переключателя языков
 */
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);

/**
 * Получаем массив всех установленных языков
 */
add_action('init', function() {
	$lang = icl_get_languages('skip_missing=0');

	$GLOBALS['other_lang'] = array();
	$GLOBALS['all_lang'] = array();
	foreach ($lang as $key => $value) {
		$GLOBALS['all_lang'][$key] = $value;

		if ( $value['active'] == 1 ) {
			$GLOBALS['current_lang'] = $value;
			continue;
		}

		$GLOBALS['other_lang'][$key] = $value;
	}
});

/**
 * Получаем код активного языка
 */
function get_clang_code() {
	if ( ! isset($GLOBALS['current_lang']['code']) ) {
		return FALSE;
	}
	return $GLOBALS['current_lang']['code'];
}

function get_real_page_id($page_id) {
	return icl_object_id($page_id, 'post', FALSE);
}

/**
 * Подменяет актуальный URL.
 */
add_filter('wpml_alternate_hreflang', function($var1, $var2) {
	$GLOBALS['var_wpml_alternate_hreflang'][$var2] = $var1;

	if ( get_clang_code() != $var2 ) {
		$GLOBALS['other_lang'][$var2]['url'] = $var1;
		$GLOBALS['all_lang'][$var2]['url'] = $var1;
	}

	return $var1;
}, 1, 2);

function prepare_content_lang($content) {
	if ( get_clang_code() != 'ru' ) {
		return rus_to_lat($content);
	}

	return $content;
}

// add_action('init', 'change_lang_default');
// function change_lang_default() {
// 	global $redirect_lang_now;
// 	$redirect_lang_now = FALSE;

// 	if ( isset($_COOKIE["redirected_lang"]) && $_COOKIE["redirected_lang"] == 'yes' ) {
// 		return TRUE;
// 	}

// 	$lang_class = new Get_Browser_Language();

// 	$user_lang = $lang_class->get_browser_lang();

// 	global $sitepress;
// 	$lang = $sitepress->get_ls_languages( array( 'skip_missing' => true ) );

// 	if ( $user_lang != get_clang_code() && isset($lang[$user_lang]) ) {
// 		$redirect_lang_now = $user_lang;
// 	} elseif ( $user_lang != get_clang_code() ) {
// 		$redirect_lang_now = 'en';
// 	}

// 	setcookie('redirected_lang', 'yes', time()+60*60*24*365, '/');
// }

// add_action('get_footer', 'change_lang_default_redirect');
// function change_lang_default_redirect() {
// 	if ( isset($_COOKIE["redirected_lang"]) && $_COOKIE["redirected_lang"] == 'yes' ) {
// 		return TRUE;
// 	}

// 	global $redirect_lang_now, $sitepress, $other_lang;

// 	if ( $redirect_lang_now === FALSE ) {
// 		return TRUE;
// 	}

// 	if ( isset($other_lang[$redirect_lang_now]['url']) ) {
// 		// wp_localize_script('main', 'langRedirect', $other_lang[$redirect_lang_now]['url']);
// 	}
// }

class Get_Browser_Language {
	public $language;
	public $default_lang = 'en';
	public $support_lang = array(
								'ru' => array('ru', 'be', 'uk', 'ky', 'ab', 'mo', 'et', 'lv'),
								'en' => 'en',
								'de' => 'de'
							);

	public function __construct() {
		if ( ($list = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ) {
			if ( preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list) ) {
				$this->language = array_combine($list[1], $list[2]);
				foreach ($this->language as $n => $v) {
					$this->language[$n] = $v ? $v : 1;
				}
				arsort($this->language, SORT_NUMERIC);
			}
		} else {
			$this->language = array();
		}
	}

	public function get_browser_lang($default = FALSE, $langs = FALSE) {
		if ( $default == FALSE ) {
			$default = $this->default_lang;
		}
		if ( $langs == FALSE ) {
			$langs = $this->support_lang;
		}
		$languages = array();
		foreach ($langs as $lang => $alias) {
			if ( is_array($alias) ) {
				foreach ($alias as $alias_lang) {
					$languages[strtolower($alias_lang)] = strtolower($lang);
				}
			} else {
				$languages[strtolower($alias)] = strtolower($lang);
			}
		}

		foreach ($this->language as $l => $v) {
			$s = strtok($l, '-'); // убираем то что идет после тире в языках вида "en-us, ru-ru"
			if ( isset($languages[$s]) ) {
				return $languages[$s];
			}
		}
		return $default;
	}
}
