<?php
/*
 * Fired when the plugin is uninstalled.
*/

global $wpdb;
$tssev_table_name = 'spirit_event';

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

// Remove event table
$wpdb->query('DROP TABLE IF EXISTS ' .  $wpdb->prefix . $tssev_table_name); //Drop plugin table

//Delete db version option
delete_option('tssev_db_version');

