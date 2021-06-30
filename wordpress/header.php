<?php defined('ABSPATH') OR exit('No direct script access allowed'); ?>
<?php language_attributes(); ?>
<?php echo esc_url(home_url('/')); ?>
<?php wp_head(); ?>
<?php do_action('body_head'); ?>
<?php wp_nav_menu(array('theme_location' => 'primary', 'items_wrap' => '%3$s', 'container' => '', 'walker' => new MainNavMenu)); ?>
