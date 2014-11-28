<?php
function scripts_and_styles_method() {

    $templateuri = get_template_directory_uri() . '/js/';

  /* lib.js is to bundle plugins. my.js is your scripts. enqueue more files as needed */

    $jslib = $templateuri."lib.js";
    wp_enqueue_script( 'jslib', $jslib,'','',true);
    $myscripts = $templateuri."my.min.js";
    wp_enqueue_script( 'myscripts', $myscripts,'','',true);

  /* enqueue stylesheet here. file does not exist until stylus file is processed */
    wp_enqueue_style( 'site', get_stylesheet_directory_uri() . '/site.css' );


}
add_action('wp_enqueue_scripts', 'scripts_and_styles_method');

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}
if ( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'opengraph', 400, 300, true );
  add_image_size( 'name', 199, 299, true );
}

/*
register_nav_menus( array(
	'menu_location' => 'Location Name',
) );
*/

/*
override wp default gallery
get_template_part( 'lib/gallery' );
*/

get_template_part( 'lib/post-types' );
get_template_part( 'lib/meta-boxes' );

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
function cmb_initialize_cmb_meta_boxes() {

  if ( ! class_exists( 'cmb_Meta_Box' ) )
    require_once 'lib/metabox/init.php';

}

/* disable that freaking admin bar */
add_filter('show_admin_bar', '__return_false');
/* turn off version in meta */
function no_generator() { return ''; }
add_filter( 'the_generator', 'no_generator' );
/* show thumbnails in admin lists */
add_filter('manage_posts_columns', 'new_add_post_thumbnail_column');
function new_add_post_thumbnail_column($cols){
  $cols['new_post_thumb'] = __('Thumbnail');
  return $cols;
}
add_action('manage_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);
function new_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'new_post_thumb':
    if( function_exists('the_post_thumbnail') ) {
      echo the_post_thumbnail( 'admin-thumb' );
      }
    else
    echo 'Not supported in theme';
    break;
  }
}

// Utility Functions //
// get ID of page by slug 
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

// remove <a> links from images in blog 
function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);
?>