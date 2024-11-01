<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
* Register actions only in administration
*/
if (is_admin()) {
    add_action('init', 'tssev_admin_init');
    add_action( 'admin_init', 'tssev_register_settings' );
    add_action( 'admin_enqueue_scripts', 'tssev_admin_enqueue_styles' ); //Register styles for Admin
    add_action( 'admin_enqueue_scripts', 'tssev_admin_enqueue_scripts' ); //Register scripts for Admin
}

/*
* Admin init
*/
function tssev_admin_init() {

    add_action( 'admin_menu', 'tssev_admin_menu' );
}

/*
* Include styles on backend
*/
function tssev_admin_enqueue_styles() {

    wp_enqueue_style('spirit-events-admin-css',plugins_url('../css/spirit-events-admin.css',__FILE__ ), array(),'1.0.0', 'all' );
    wp_enqueue_style('tssev-jquery.timepicker.css',plugins_url( '../css/jquery.timepicker.css',__FILE__ ));
    wp_enqueue_style('tssev-datepicker.css',plugins_url( '../css/bootstrap-datepicker.standalone.css',__FILE__ )); 
    wp_enqueue_style( 'wp-color-picker' );

}

/*
* Include scripts on backend
*/
function tssev_admin_enqueue_scripts() {
    wp_enqueue_script('spirit-events-admin-js', plugins_url('../js/spirit-events-admin.js',__FILE__ ),  array('jquery', 'wp-color-picker'),'1.0.0', 'false' );
    wp_enqueue_script('jquery.timepicker.js',plugins_url( '../js/jquery.timepicker.js',__FILE__ ));
    wp_enqueue_script('bootstrap-datepicker.js',plugins_url( '../js/bootstrap-datepicker.js',__FILE__ ));	
	wp_enqueue_script('datepair.js',plugins_url( '../js/datepair.js',__FILE__ ));
	wp_enqueue_script('jquery.datepair.js',plugins_url( '../js/jquery.datepair.js',__FILE__ ));
}

/*
* Register settings
*/
function tssev_register_settings() {
	register_setting( 'tssev_settings_group','tssev_options', 'tssev_sanitize_options');
}

/*
* Sanitize settings before saving
*/
function tssev_sanitize_options( $options ) {
    $options['ApiKey'] = ( ! empty( $options['ApiKey'] ) ) ?sanitize_text_field( $options['ApiKey'] ) : '';
    $options['ButtonColor'] = ( ! empty( $options['ButtonColor'] ) ) ?sanitize_text_field( $options['ButtonColor'] ) : '';
    $options['ButtonHoverColor'] = ( ! empty( $options['ButtonHoverColor'] ) ) ?sanitize_text_field( $options['ButtonHoverColor'] ) : '';
    $options['EventItemColor'] = ( ! empty( $options['EventItemColor'] ) ) ?sanitize_text_field( $options['EventItemColor'] ) : '';
    $options['LiveStreamColor'] = ( ! empty( $options['LiveStreamColor'] ) ) ?sanitize_text_field( $options['LiveStreamColor'] ) : '';
    
return $options;
}

/*
* Register plugin menu
*/
function tssev_admin_menu() { 
    add_submenu_page('edit.php?post_type=spirit-events', 
                    __('Settings', 'spirit-events'),
                    __('Settings', 'spirit-events'), 
                    'manage_options',
                    'tssev_settings_page',
                    'tssev_settings_page'
    );    
}

/*
* Register settings page
*/
function tssev_settings_page() { 
    global $tssev_options;
    
    $tssev_options= get_option( 'tssev_options');    

    include (TSSEV_PLUGIN_PATH . "templates/settings-page.php");    
}

