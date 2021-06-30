<?php
defined('ABSPATH') OR exit('No direct script access allowed');

function souvel_pagination($type = 'page', $main_query = NULL) {
	global $query;
	if ( $query == NULL ) {
		$query = $main_query;
	}
	if ( $main_query == NULL ) {
		global $wp_query;
		$query = $wp_query;
	}
	$num_pages = 10;
	$max_page = $query->max_num_pages;
	$posts_per_page = $query->query_vars['posts_per_page'];
	$paged = (int) $query->query_vars['paged'];

	if ( isset($_GET['page']) && is_numeric($_GET['page']) ) {
		$paged = $_GET['page'];
	}

	if( $max_page <= 1 )
		return false;
	if( empty($paged) || $paged == 0 )
		$paged = 1;

	$pages_to_show = intval( $num_pages );
	$pages_to_show_minus_1 = $pages_to_show-1;

	$half_page_start = floor( $pages_to_show_minus_1/2 );
	$half_page_end = ceil( $pages_to_show_minus_1/2 );

	$start_page = $paged - $half_page_start;
	$end_page = $paged + $half_page_end;

	if( $start_page <= 0 )
		$start_page = 1;
	if( ($end_page - $start_page) != $pages_to_show_minus_1 )
		$end_page = $start_page + $pages_to_show_minus_1;
	if( $end_page > $max_page ) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = (int) $max_page;
	}

	if( $start_page <= 0 )
		$start_page = 1;

	if ( $type == 'get' ) {
		$current_link = esc_url(home_url()) . $_SERVER['REDIRECT_URL'];
		if ( isset($_GET) && $_GET ) {
			$current_link = $current_link . '?';
			$count = 1;
			foreach ($_GET as $key => $value) {
				if ( $key != 'page' ) {
					if ( $count == 1 ) {
						$current_link .= $key . '=' . $value;
						$count ++;
					} else {
						$current_link .= '&' . $key . '=' . $value;
					}
				}
			}
			if ( $count == 1 ) {
				$current_link .= 'page=';
			} else {
				$current_link .= '&page=';
			}
		} else {
			$current_link .= '?page=';
		}
	} else {
		$link_base = get_pagenum_link(99999999); // 99999999 будет заменено
		$link_base = str_replace(99999999, '___', $link_base);
		$first_url = get_pagenum_link(1);
	}

	$out = '<section class="pagination"><ul>';

	if ( $paged != 1 ) {
		$prev_i = $paged-1;
		$out .= '<li class="arrow"><a href="' . ( ( $type == 'get' ) ? $current_link . $prev_i : str_replace( '___', ($paged-1), $link_base ) ) . '"><svg xmlns="https://www.w3.org/2000/svg" version="1.1" xmlns:xlink="https://www.w3.org/1999/xlink" xmlns:svgjs="https://svgjs.com/svgjs" width="9" height="15"><path d="M840.297 1484L842 1485.75L836.405 1491.5L842 1497.25L840.297 1499L833 1491.5Z " transform="matrix(1,0,0,1,-833,-1484)"></path></svg></a></li>';
	}

	for( $i = $start_page; $i <= $end_page; $i++ ) {
		if( $i == $paged )
			$out .= '<li><span>'.$i.'</span></li>';
		elseif( $i == 1 )
			$out .= '<li><a href="' . ( ( $type == 'get' ) ? $current_link . $i : $first_url ) . '">1</a></li>';
		else
			$out .= '<li><a href="' . ( ( $type == 'get' ) ? $current_link . $i : str_replace( '___', $i, $link_base ) ) . '">'. $i .'</a></li>';
	}

	if ( $paged != $end_page ) {
		$next_i = $paged+1;
		$out.= '<li class="arrow"><a href="' . ( ( $type == 'get' ) ? $current_link . $next_i : str_replace('___', ($paged+1), $link_base)) . '"><svg xmlns="https://www.w3.org/2000/svg" version="1.1" xmlns:xlink="https://www.w3.org/1999/xlink" xmlns:svgjs="https://svgjs.com/svgjs" width="9" height="15"><path d="M1060.7 1484L1059 1485.75L1064.59 1491.5L1059 1497.25L1060.7 1499L1068 1491.5Z " transform="matrix(1,0,0,1,-1059,-1484)"></path></svg></a></li>';
	}

	$out .= '</ul></section>';

	echo $out;
}

function souvel_paged() {
	$paged = (int) get_query_var('paged');

	if ( isset($_GET['page']) && $_GET['page'] != NULL && is_numeric($_GET['page']) ) {
		$paged = (int) $_GET['page'];
	}

	return $paged;
}

function get_the_date_advanced($separator = '.') {
	global $post;

	if ( !isset($post->post_date_gmt) )
		return NULL;

	$post_date = array();
	$post_date_old_format = $post->post_date_gmt;
	$post_date_old_format = strtr($post_date_old_format, array('-' => '|', ' ' => '|', ':' => '|'));
	$post_date_old_format = explode('|', $post_date_old_format);
	$post_date['seconds'] = $post_date_old_format[5];
	$post_date['minute'] = $post_date_old_format[4];
	$post_date['hour'] = $post_date_old_format[3];
	$post_date['day'] = $post_date_old_format[2];
	$post_date['month'] = $post_date_old_format[1];
	$post_date['year'] = $post_date_old_format[0];
	$post_date_time = gmmktime($post_date['hour'], $post_date['minute'], $post_date['seconds'], $post_date['month'], $post_date['day'], $post_date['year']);
	$post_date_diff = time() - $post_date_time;

	if ( $post_date_diff > 86400 ) {
		return get_the_date("d.m.Y");
	} elseif ( $post_date_diff > 3600 ) {
		$date_diff = $post_date_diff / 3600;
		$date_diff_val = intval($date_diff);
		if ( in_array($date_diff_val, array(1, 21)) ) {
			return $date_diff_val . ' час назад';
		} elseif ( in_array($date_diff_val, array(2, 3, 4, 22, 23, 24)) ) {
			return $date_diff_val . ' часа назад';
		} else {
			return $date_diff_val . ' часов назад';
		}
	} elseif ( $post_date_diff > 60 ) {
		$date_diff = $post_date_diff / 60;
		$date_diff_val = intval($date_diff);
		if ( in_array($date_diff_val, array(1, 21, 31, 41, 51)) ) {
			return $date_diff_val . ' минута назад';
		} elseif ( in_array($date_diff_val, array(2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54)) ) {
			return $date_diff_val . ' минуты назад';
		} else {
			return $date_diff_val . ' минут назад';
		}
	} else {
		$date_diff_val = $post_date_diff;
		if ( in_array($date_diff_val, array(1, 21, 31, 41, 51)) ) {
			return $date_diff_val . ' секунда назад';
		} elseif ( in_array($date_diff_val, array(2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54)) ) {
			return $date_diff_val . ' секунды назад';
		} else {
			return $date_diff_val . ' секунд назад';
		}
	}
}

/**
 * Получаем родственные посту записи
 */
function get_adjacent_post_custom($previous = true, $in_same_term = false, $taxonomy = 'category') {
	global $wpdb, $post;

	$current_post_date = $post->post_date;

	$join = '';
	$where .= " AND p.post_status = 'publish' ";

	if ( $in_same_term ) {
		$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		$where .= $wpdb->prepare( "AND tt.taxonomy = %s", $taxonomy );

		if ( ! is_object_in_taxonomy( $post->post_type, $taxonomy ) )
			return '';
		$term_array = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );

		// Remove any exclusions from the term array to include.
		$term_array = array_diff( $term_array, (array) $excluded_terms );
		$term_array = array_map( 'intval', $term_array );

		if ( ! $term_array || is_wp_error( $term_array ) )
			return '';

		$where .= " AND tt.term_id IN (" . implode( ',', $term_array ) . ")";
	}

	$adjacent = $previous ? 'previous' : 'next';
	$op = $previous ? '<' : '>';
	$order = $previous ? 'DESC' : 'ASC';

	$excluded_terms = apply_filters( "get_{$adjacent}_post_excluded_terms", $excluded_terms );

	$join = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_term, $excluded_terms, $taxonomy, $post );

	$where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare( "WHERE p.post_date $op %s AND p.post_type = %s $where", $current_post_date, $post->post_type ), $in_same_term, $excluded_terms, $taxonomy, $post );

	$sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 3", $post );

	$query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";

	$query_key = 'adjacent_post_custom_' . md5( $query );
	$result = wp_cache_get( $query_key, 'souvel' );
	if ( false !== $result ) {
		return $result;
	}

	$result = $wpdb->get_col( $query );
	if ( null === $result )
		$result = '';

	wp_cache_set( $query_key, $result, 'souvel' );

	// if ( $result )
	// 	$result = get_post( $result );

	return $result;
}

/**
 * Получим в сумме 3 записи
 */
function get_adjacent_id() {
	$adjacent_posts = array();
	$prev_posts = array();
	$next_posts = array();
	$count = 0;

	if ( $GLOBALS['post']->post_type == 'project' ) {
		$prev_posts = get_adjacent_post_custom(true, true, 'projcat');
		$next_posts = get_adjacent_post_custom(false, true, 'projcat');
	} else {
		$prev_posts = get_adjacent_post_custom(true);
		$next_posts = get_adjacent_post_custom(false);
	}

	$i = array_shift($next_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
	}

	$i = array_shift($prev_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
	}

	$i = array_shift($next_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
		if ( $count == 3 )
			return $adjacent_posts;
	}

	$i = array_shift($prev_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
		if ( $count == 3 )
			return $adjacent_posts;
	}

	$i = array_shift($next_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
		if ( $count == 3 )
			return $adjacent_posts;
	}

	$i = array_shift($prev_posts);
	if ( $i !== NULL && is_numeric($i) ) {
		$adjacent_posts []= $i;
		$count++;
		if ( $count == 3 )
			return $adjacent_posts;
	}

	return $adjacent_posts;
}

/**
 * Выделение поискового запроса в тексте
 */
function escape_search_word($text, $word) {
#	return strtr($text, array($word => '<mark>' . $word . '</mark>'));
	return str_ireplace($word, '<mark>' . $word . '</mark>', $text);
}

function prepare_search_content($content, $word) {
	$output = '';
	$strip_range = 63;
	$count = 3;

	$content = trim(strip_shortcodes(wp_strip_all_tags(trim($content), ' '))) . ' ';
	$word_length = mb_strlen($word);

	$i = 0;
	while ($i < $count ) {
		$i ++;

		if ( $i !== 0 ) {
			$offset = $offset + $substr_start + $substr_end;
			$prepare_content = substr($content, $offset);
		} else {
			$offset = 0;
			$prepare_content = $content;
		}

		$entry = stripos($prepare_content, $word);
		if ( $entry !== false ) {
			$output .= '<p>';

			$substr_start = $entry - $strip_range + $offset;
			if ( $substr_start < 0 ) {
				$substr_start = 0;
			}
			$substr_end = $word_length + $strip_range * 2;

			$string_part = substr($content, $substr_start, $substr_end);

			if ( $substr_start != 0 ) {
				$first_word = strpos($string_part, ' ');
				$string_part = substr($string_part, $first_word);
				$output .= '...';
			}

			$last_word = strrpos($string_part, ' ');
			$string_part = substr($string_part, 0, $last_word);

			$output .= escape_search_word(trim($string_part), $word);
			$output .= '...';
			$output .= '</p>';
		} else {
			return $output;
		}
	}

	return $output;
}

function prepare_phone_link($phone = NULL) {
	return esc_attr('tel:' . strtr($phone, array('(' => '', ')' => '', ' ' => '', '-' => '')));
}

function prepare_email_link($email = NULL) {
	return esc_attr('mailto:' . strtolower(trim($email)));
}

function prepare_skype_link($skype = NULL) {
	return esc_attr('skype:' . strtolower(trim($skype)) . '?chat');
}

function number_separator($cost = 0) {
	if ( $cost == 0 ) {
		return 0;
	}

	$separator = ' ';
	$descimal = '.';

	$cost = (float) str_replace(',', '.', $cost);
	$full_cost = explode('.', $cost);

	$cost_array = str_split($full_cost[0]);
	$cost_array = array_reverse($cost_array);
	$new_cost_array = array();

	$i = 0;
	$count = count($cost_array);
	foreach ($cost_array as $value) {
		$i ++;
		if ( $count == $i ) {
			$new_cost_array []= $value;
		} else {
			if ( $i == 3 || $i == 6 || $i == 9 ) {
			$new_cost_array []= $separator . $value;
			} else {
				$new_cost_array []= $value;
			}
		}
	}

	$new_cost = '';
	$new_cost_array = array_reverse($new_cost_array);
	foreach ($new_cost_array as $value) {
		$new_cost .= $value;
	}

	if ( isset($full_cost[1]) && is_numeric($full_cost[1]) ) {
		$new_cost .= $descimal.rtrim($full_cost[1], 0);
	}

	return $new_cost;
}

function breadcrumbs($bread = NULL) {
	if ( $bread == NULL || !is_array($bread) )
		return NULL;

	$output = '<ol class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url(home_url('/')) . '"><span itemprop="name">Главная</span></a><meta itemprop="position" content="1" /></li>';

	$i = 1;
	foreach ($bread as $value) {
		$title = $value[0];
		if ( $value[1] ) {
			$link = $value[1];
		} else {
			$link = '#';
		}

		$i ++;

		$output .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $link . '"><span itemprop="name">' . $title . '</span></a><meta itemprop="position" content="' . $i . '" /></li>';
	}

	$output .= '</ol>';

	echo $output;
}
