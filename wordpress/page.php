<?php
defined('ABSPATH') OR exit('No direct script access allowed');
get_header();
the_post();
$page_id =  get_the_ID();
?>

<?php get_footer();
