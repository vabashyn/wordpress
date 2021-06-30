<?php
defined('ABSPATH') OR exit('No direct script access allowed');

function social_likes() {
	return '<div class="social-share">
				<div class="plusone" title="'.esc_attr__('Share link on Google +', 'wordpress').'"><svg width="17" height="15"><use xmlns:xlink="https://www.w3.org/1999/xlink" xlink:href="#svgicon-social-gp"></use></svg></div>
				<div class="vkontakte" title="'.esc_attr__('Share link on Vkontakte', 'wordpress').'"><svg width="21" height="12"><use xmlns:xlink="https://www.w3.org/1999/xlink" xlink:href="#svgicon-social-vk"></use></svg></div>
				<div class="facebook" title="'.esc_attr__('Share link on Facebook', 'wordpress').'"><svg width="8" height="18"><use xmlns:xlink="https://www.w3.org/1999/xlink" xlink:href="#svgicon-social-fb"></use></svg></div>
				<div class="odnoklassniki" title="'.esc_attr__('Share link on Odnoklassniki', 'wordpress').'"><svg width="12" height="22"><use xmlns:xlink="https://www.w3.org/1999/xlink" xlink:href="#svgicon-social-ok"></use></svg></div>
			</div>';
}
