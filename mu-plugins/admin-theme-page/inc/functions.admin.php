<?php

add_filter('sap_options_before_save', 'sap_filter_save_media_upload');
function sap_filter_save_media_upload($data) {

    if(!is_array($data)) return $data;

    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = str_replace(
                array(
                    site_url('', 'http'),
                    site_url('', 'https'),
                ),
                array(
                    '[site_url]',
                    '[site_url_secure]',
                ),
                $value
            );
        }
    }

    return $data;
}

add_action('admin_head', 'sap_admin_message');
function sap_admin_message() {
	?>
	<script type="text/javascript">
	jQuery(function(){
		var message = '<p>Новая тема активирована.</p><p>Текущая тема поддерживает дополнительную страницу настроек.</p>';
		jQuery('.themes-php #message2').html(message);
	});
	</script>
	<?php
}

add_action('admin_init','sap_admin_init');
function sap_admin_init() {
	global $sap_options, $options_machine, $smof_data, $smof_details;

	if (!isset($options_machine))
		$options_machine = new Options_Machine($sap_options);

	do_action('sap_admin_init_before', array(
			'sap_options'		=> $sap_options,
			'options_machine'	=> $options_machine,
			'smof_data'			=> $smof_data
		));

	if (empty($smof_data['smof_init'])) { // Let's set the values if the theme's already been active
		sap_save_options($options_machine->Defaults);
		sap_save_options(date('r'), 'smof_init');
		$smof_data = sap_get_options();
		$options_machine = new Options_Machine($sap_options);
	}

	do_action('sap_admin_init_after', array(
			'sap_options'		=> $sap_options,
			'options_machine'	=> $options_machine,
			'smof_data'			=> $smof_data
		));

}

add_action('admin_menu', 'sap_admin_menu');
function sap_admin_menu() {
	$sap_page = add_theme_page('Дополнительные настройки темы (' . SAP_THEMENAME . ')', 'Настройки темы', 'edit_theme_options', 'sapoption', 'sap_options_page');

	add_action("admin_print_scripts-$sap_page", 'sap_enq_scripts');
	add_action("admin_print_styles-$sap_page",'sap_enq_styles');
}

function sap_options_page() {
	global $options_machine;

	include_once(SAP_PATH . 'inc/options.php');
}

function sap_enq_styles() {
	wp_enqueue_style('admin-style', SAP_URI . 'static/css/admin-style.css');
	wp_enqueue_style('jquery-ui-custom-admin', SAP_URI .'static/css/jquery-ui-custom.css');

	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style( 'wp-color-picker', SAP_URI . 'static/css/color-picker.min.css' );
	}
	wp_enqueue_style( 'wp-color-picker' );
	do_action('sap_style_only_after');
}

function sap_enq_scripts() {
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-input-mask', SAP_URI .'static/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('tipsy', SAP_URI .'static/js/jquery.tipsy.js', array( 'jquery' ));
	wp_enqueue_script('cookie', SAP_URI . 'static/js/cookie.js', 'jquery');
	wp_enqueue_script('smof', SAP_URI .'static/js/smof.js', array( 'jquery' ));

	if ( !wp_script_is( 'wp-color-picker', 'registered' ) ) {
		wp_register_script( 'iris', SAP_URI .'static/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_register_script( 'wp-color-picker', SAP_URI .'static/js/color-picker.min.js', array( 'jquery', 'iris' ) );
	}
	wp_enqueue_script( 'wp-color-picker' );

	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();

	do_action('sap_load_only_after');
}

function sap_head() {
	do_action('sap_head');
}

function sap_option_setup() {
	global $sap_options, $options_machine;

	$options_machine = new Options_Machine($sap_options);

	if ( !sap_get_options() ) {
		sap_save_options($options_machine->Defaults);
	}
}

function sap_get_header_classes_array() {
	global $sap_options;


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
		"—"=>"-","«"=>"","»"=>"","…"=>"",' '=>''
		);

	foreach ($sap_options as $value) {
		if ($value['type'] == 'heading')
			$hooks[] = strtolower(strtr(str_replace(' ','',$value['name']), $iso));
	}

	return $hooks;
}

function sap_get_options($key = null, $data = null) {
	global $smof_data;

	do_action('sap_get_options_before', array(
		'key'=>$key, 'data'=>$data
	));

	if ($key != null) {
		$data = sap_get_theme_mod($key, $data);
	} else {
		$data = sap_get_theme_mods();
	}

	$data = apply_filters('sap_options_after_load', $data);

	if ($key == null) {
		$smof_data = $data;
	} else {
		$smof_data[$key] = $data;
	}

	do_action('sap_option_setup_before', array(
		'key'=>$key, 'data'=>$data
	));

	return $data;
}

function sap_save_options($data, $key = null) {
	global $smof_data;

    if (empty($data))
        return false;

    do_action('sap_save_options_before', array(
		'key'=>$key, 'data'=>$data
	));

	$data = apply_filters('sap_options_before_save', $data);

	if ($key != null) {
		if ($key == BACKUPS) {
			unset($data['smof_init']); // Don't want to change this.
		}
		sap_set_theme_mod($key, $data);
	} else {
		foreach ( $data as $k=>$v ) {
			if (!isset($smof_data[$k]) || $smof_data[$k] != $v) {
				sap_set_theme_mod($k, $v);
			} else if (is_array($v)) {
				foreach ($v as $key=>$val) {
					if ($key != $k && $v[$key] == $val) {
						sap_set_theme_mod($k, $v);
						break;
					}
				}
			}
	  	}
	}

    do_action('sap_save_options_after', array(
		'key'=>$key, 'data'=>$data
	));

	return true;
}

function sap_set_theme_mod($name, $value) {
	$mods = sap_get_theme_mods();
	$old_value = isset( $mods[ $name ] ) ? $mods[ $name ] : false;

	$mods[ $name ] = apply_filters( "pre_set_sap_mod_$name", $value, $old_value );

	update_option("sap_options_theme_mods", $mods);
}

function sap_get_theme_mods() {
	$mods = get_option("sap_options_theme_mods");
	return $mods;
}

function sap_get_theme_mod( $name, $default = false ) {
	$mods = sap_get_theme_mods();

	if ( isset( $mods[$name] ) ) {
		/**
		 * Filter the theme modification, or 'theme_mod', value.
		 *
		 * The dynamic portion of the hook name, `$name`, refers to
		 * the key name of the modification array. For example,
		 * 'header_textcolor', 'header_image', and so on depending
		 * on the theme options.
		 *
		 * @since 2.2.0
		 *
		 * @param string $current_mod The value of the current theme modification.
		 */
		return apply_filters( "theme_mod_{$name}", $mods[$name] );
	}

	if ( is_string( $default ) )
		$default = sprintf( $default, get_template_directory_uri(), get_stylesheet_directory_uri() );

	/** This filter is documented in wp-includes/theme.php */
	return apply_filters( "theme_mod_{$name}", $default );
}

function sap_ajax_callback() {
	global $options_machine, $sap_options;

	$nonce = $_POST['security'];

	if ( !wp_verify_nonce($nonce, 'sap_ajax_nonce') )
		die('-1');

	$all = sap_get_options();

	$save_type = $_POST['type'];

	if ( $save_type == 'upload' ) {
		$clickedID = $_POST['data'];
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';
		$uploaded_file = wp_handle_upload($filename,$override);

			$upload_tracking[] = $clickedID;

			$upload_image = $all;

			$upload_image[$clickedID] = $uploaded_file['url'];

			sap_save_options($upload_image);

		if ( !empty($uploaded_file['error']) ) {
			echo 'Upload Error: ' . $uploaded_file['error'];
		}
		else {
			echo $uploaded_file['url'];
		}
	} elseif ( $save_type == 'image_reset' ) {
		$id = $_POST['data'];

		$delete_image = $all;
		$delete_image[$id] = '';
		sap_save_options($delete_image);
	} elseif ( $save_type == 'backup_options' ) {
		$backup = $all;
		$backup['backup_log'] = date('r');

		sap_save_options($backup, BACKUPS) ;

		die('1');
	} elseif ( $save_type == 'restore_options' ) {
		$smof_data = sap_get_options(BACKUPS);

		sap_save_options($smof_data);

		die('1');
	} elseif ( $save_type == 'import_options' ) {
		$smof_data = unserialize(base64_decode($_POST['data']));
		sap_save_options($smof_data);

		die('1');
	} elseif ( $save_type == 'save' ) {
		wp_parse_str(stripslashes($_POST['data']), $smof_data);
		unset($smof_data['security']);
		unset($smof_data['sap_save']);
		sap_save_options($smof_data);
		flush_rewrite_rules();
		die('1');
	} elseif ($save_type == 'reset') {
		sap_save_options($options_machine->Defaults);

        die('1');
	}

  	die();
}
