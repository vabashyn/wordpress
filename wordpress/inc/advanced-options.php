<?php
defined('ABSPATH') OR exit('No direct script access allowed');

if ( ! function_exists('wordpress_setup') ) :
function wordpress_setup() {
	// load_theme_textdomain('wordpress', get_template_directory() . '/languages');

	add_theme_support('title-tag');

	add_theme_support('post-thumbnails');

	register_nav_menus([
		'primary' => 'Главное меню'
	]);

	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	]);

	add_image_size('square-320', 320, 320, ['center', 'center']);
}
endif; // wordpress_setup
add_action('after_setup_theme', 'wordpress_setup');

add_action('customize_register', 'souvel_customize_setup_theme');
function souvel_customize_setup_theme($wp_customize) {
	$wp_customize->add_section(
		'souvel-panel-analytics',
		[
			'title' => 'Аналитика сайта',
			'description' => 'Добавление Google Analytics, Яндекс.Метрики и прочих инструментов на сайт.',
			'priority' => 35,
		]
	);

	$wp_customize->add_setting('sp-google-tag-head');
	$wp_customize->add_control(
		'sp-google-tag-head',
		[
			'label' => 'Диспетчер тегов Google (head)',
			'section' => 'souvel-panel-analytics',
			'type' => 'textarea',
			'description' => 'Получить код можно зарегистрировав его <a href="https://tagmanager.google.com/" target="_blank">Google тег менеджер</a>',
		]
	);

	$wp_customize->add_setting('sp-google-tag-body');
	$wp_customize->add_control(
		'sp-google-tag-body',
		[
			'label' => 'Диспетчер тегов Google (body)',
			'section' => 'souvel-panel-analytics',
			'type' => 'textarea',
		]
	);

	$wp_customize->add_setting('sp-google-analytics');
	$wp_customize->add_control(
		'sp-google-analytics',
		[
			'label' => 'Google Analytics',
			'section' => 'souvel-panel-analytics',
			'type' => 'textarea',
			'description' => '(Устаревшее) Рекомендуется использовать Google тег менеджер.',
		]
	);

	$wp_customize->add_setting('sp-yandex-metrika');
	$wp_customize->add_control(
		'sp-yandex-metrika',
		[
			'label' => 'Яндекс.Метрика',
			'section' => 'souvel-panel-analytics',
			'type' => 'textarea',
			'description' => '(Устаревшее) Рекомендуется использовать Google тег менеджер.',
		]
	);

	$wp_customize->add_setting('sp-analytics-other');
	$wp_customize->add_control(
		'sp-analytics-other',
		[
			'label' => 'Другие коды аналитики (будут добавлены перед &#60;&#47;body&#62;)',
			'section' => 'souvel-panel-analytics',
			'type' => 'textarea',
			'description' => '(Устаревшее) Рекомендуется использовать Google тег менеджер.',
		]
	);
}

add_action('wp_head', function() {
	$google_tag = get_theme_mod('sp-google-tag-head');
	if ( $google_tag ) {
		echo $google_tag;
	}
}, 1);

add_action('body_head', 'insert_analytics_code');
function insert_analytics_code() {
	$google_tag = get_theme_mod('sp-google-tag-body');
	if ( $google_tag ) {
		echo $google_tag;
	}

	$google_analytics = get_theme_mod('sp-google-analytics');
	if ( $google_analytics ) {
		echo $google_analytics;
	}
	$yandex_metrika = get_theme_mod('sp-yandex-metrika');
	if ( $yandex_metrika ) {
		echo $yandex_metrika;
	}
}

add_action('body_head', 'insert_other_analytics_code');
function insert_other_analytics_code() {
	$other_analytics = get_theme_mod('sp-analytics-other');
	if ( $other_analytics ) {
		echo $other_analytics;
	}
}

add_action('wp_head', function() {
	echo '<meta name="generator" content="digital-agency Web-Modern" />'.PHP_EOL;
});

/**
 * Disable trash
 */
function disable_trash() {
	/**
	 * Отключаем Emoji
	 */
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');

	/**
	 * Отключаем следы WordPress
	 */
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	add_filter('xmlrpc_enabled', '__return_false');

	/**
	 * По умолчанию отключаем CF7
	 */
	add_filter('wpcf7_load_js', '__return_false');
	add_filter('wpcf7_load_css', '__return_false');

	/**
	 * Отключаем REST API
	 */
	remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	// если собираетесь выводить вставки из других сайтов на своем, то закомментируйте след. строку.
	remove_action('wp_head', 'wp_oembed_add_host_js');

	/**
	 * Отключаем DNS-prefetch
	 */
	remove_action( 'wp_head', 'wp_resource_hints', 2 );

	// Отключаем стили блоков gutenberg
	wp_dequeue_style('wp-block-library');
}
add_action('init', 'disable_trash');

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins) {
	if ( is_array( $plugins ) ) {
		return array_diff($plugins, ['wpemoji']);
	} else {
		return array();
	}
}

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 20);

##  отменим показ выбранного термина наверху в checkbox списке терминов
add_filter('wp_terms_checklist_args', 'set_checked_ontop_default', 10);
function set_checked_ontop_default($args) {
	// изменим параметр по умолчанию на false
	if( ! isset($args['checked_ontop']) )
		$args['checked_ontop'] = false;

	return $args;
}

## Удаление файлов license.txt и readme.html для защиты
if( is_admin() && ! defined('DOING_AJAX') ) {
	$license_file = ABSPATH .'/license.txt';
	$readme_file = ABSPATH .'/readme.html';

	if ( file_exists($license_file) && current_user_can('manage_options') ) {
		$deleted = unlink($license_file) && unlink($readme_file);

		if ( ! $deleted  )
			$GLOBALS['readmedel'] = 'Не удалось удалить файлы: license.txt и readme.html из папки `'. ABSPATH .'`. Удалите их вручную!';
		else
			$GLOBALS['readmedel'] = 'Файлы: license.txt и readme.html удалены из из папки `'. ABSPATH .'`.';

		add_action('admin_notices', function() {
			echo '<div class="error is-dismissible"><p>'. $GLOBALS['readmedel'] .'</p></div>';
		});
	}
}

/*
 * Modify TinyMCE editor to remove H1.
 */
add_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats');
function tiny_mce_remove_unused_formats($init) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Address=address;Pre=pre';

	return $init;
}

add_action('wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}, 99);

function remove_comments_rss( $for_comments ) {
	return;
}
add_filter('post_comments_feed_link','remove_comments_rss');
