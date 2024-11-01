<?php
/*
Plugin Name: Spirit Events
Plugin URI: https://thespirit.studio/spirit-events/
Description: Simple event calendar plugin with Gutenberg block.
Version: 1.0.1
Author: TheSpirit.studio
Author URI: https://thespirit.studio/
Text Domain: spirit-events
Domain Path: /languages
License: GPL2

Spirit Events is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version

Spirit Events is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Spirit Events. If not, see {License URI}.
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define ('TSSEV_PLUGIN_PATH', plugin_dir_path( __FILE__));

/*
* Register language domain
*/
function tssev_load_plugin_textdomain() {

    $domain = 'spirit-events';
    load_plugin_textdomain(
        $domain, false, basename(dirname(__FILE__)) . '/languages/'
    );
}
add_action('init', 'tssev_load_plugin_textdomain');


include (TSSEV_PLUGIN_PATH . 'admin/admin.php');
include (TSSEV_PLUGIN_PATH . 'includes/functions.php');
include (TSSEV_PLUGIN_PATH . 'includes/class-spirit-event.php');
include (TSSEV_PLUGIN_PATH . "includes/class-calendar.php");
include (TSSEV_PLUGIN_PATH . "includes/widget.php");
include (TSSEV_PLUGIN_PATH . "includes/metabox.php");
include (TSSEV_PLUGIN_PATH . 'includes/post-type.php');

//Globals
global $tssev_db_version, $tssev_table_name;
$tssev_db_version = '1.0';
$tssev_table_name = 'spirit_event';

/*
* Plugin activation
*/
function tssev_activate() {

	$tssev_options = array(
		'ApiKey' => '',
		'ButtonColor' => '#3e6083',
		'ButtonHoverColor' => '#214a75',
		'EventItemColor' => '#e49229',
		'LiveStreamColor' => '#d72324'
	);
	//Save our default option values
	update_option( 'tssev_options', $tssev_options );

	//Install plugin table
	tssev_db_install();
	
	//Register custom spirit-event custom post type
	tssev_post_type();

    //Refresh permalinks
	flush_rewrite_rules();
	delete_option( 'rewrite_rules' );
}
register_activation_hook( __FILE__, 'tssev_activate' );

/*
* Plugin deactivation
*/
function tssev_deactivate() {

    // Deactivation code here...
}
register_deactivation_hook( __FILE__, 'tssev_deactivate' );

/*
* Include styles on frontend
*/
function tssev_enqueue_styles() {
	wp_enqueue_style('spirit-events-css', plugins_url( 'css/spirit-events.css',__FILE__ ));
	wp_enqueue_style('dashicons');

}
add_action( 'wp_enqueue_scripts', 'tssev_enqueue_styles' );

//Add dynamic styles
add_action( 'wp_enqueue_scripts', 'tssev_load_dynamic_style' );

//Add support for google maps
add_action('wp_footer', 'tssev_wp_footer' );

/*
* Register archive template
*/
function tssev_archive_template( $archive_template ) {

	global $post;
	if ( is_post_type_archive ( 'spirit-events' ) ) {
		 $archive_template = TSSEV_PLUGIN_PATH . 'templates/archive-spirit-events.php';
	}
	return $archive_template;
}
add_filter( 'archive_template', 'tssev_archive_template' ) ;

/*
* Register single post type template
*/
function tssev_single_template( $archive_template ) {

	global $post;
	if ( is_singular ( 'spirit-events' ) ) {
		$archive_template = TSSEV_PLUGIN_PATH . 'templates/single-spirit-events.php';
	}
	return $archive_template;
}
add_filter( 'single_template', 'tssev_single_template' );

?>