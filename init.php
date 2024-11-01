<?php
/*
Plugin Name: ZX_CSV Upload
Description: Its a simple CSV Upload plugin. Using it you can upload & update data from CSV to DB
Version: 1
Author: Khokan Sardar
Author URI:http://facebook.com/itzmekhokan
*/

//menu items
add_action('admin_menu','zx_csv_menu');
add_action('admin_init', 'zx_csv_table');

function zx_csv_menu() {
	//this is the main item for the menu
	add_menu_page('ZX CSV Upload', //page title
	'ZX CSV Upload', //menu title
	'manage_options', //capabilities
	'zx_csv_plugin_home', //menu slug
	'zx_csv_plugin_home' //function
	);
	//this is a submenu
	add_submenu_page('zx_csv_plugin_home', //parent slug
	'Upload CSV', //page title
	'Add New', //menu title
	'manage_options', //capability
	'upload_new', //menu slug
	'upload_new'); //function
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'zx_csv_home.php');
require_once(ROOTDIR . 'zx_csv_upload.php');

if(!function_exists('zx_csv_table')) 
{
	function zx_csv_table () 
	{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	//global $wpdb;
	//$table_name = $wpdb->prefix.'currency';	

	$sql_table = "CREATE TABLE IF NOT EXISTS $table_name (
	  'id' int(50) NOT NULL ,
	  'curr_name' text NOT NULL,
	  'curr_code' text NOT NULL,
	  'curr_rate' text NOT NULL,
	 
	  PRIMARY KEY ('id')
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;";
	dbDelta($sql_table);
	}
}

?>