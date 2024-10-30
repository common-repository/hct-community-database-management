<?php  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
    /*
    Plugin Name: HCT Community Database Management
    Plugin URI: http://www.harishtripathi.com/community-database-management/
    Description: Plugin for entering and displaying Community Data details from Database
	Version: 1.1
    Author: Harish Tripathi    
    Author URI: http://www.harishtripathi.com
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: wporg
	Domain Path: /languages
    */	


global $hctdd_db_version;
$hctdd_db_version = '1.1';

function hctdd_commu_install () {
	global $wpdb;
	global $hctdd_db_version;
	
	$table_name = $wpdb->prefix . "community_database";
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name tinytext NOT NULL,
	  gender tinytext NOT NULL,	  
	  dob date NULL,
	  married tinytext NOT NULL,	  
	  marriage_ani date NULL,
	  occupation tinytext NOT NULL,
	  education tinytext NULL,
	  email tinytext NOT NULL,
	  mobile_no mediumint(10) NOT NULL,
	  emergency_no tinytext NULL,
	  address tinytext NOT NULL,
	  service tinytext NULL,	  
	  created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,	  
	  PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	add_option( 'hctdd_db_version', $hctdd_db_version );
}
register_activation_hook( __FILE__, 'hctdd_commu_install');

//function hctdd_deactivate () {}
//register_deactivation_hook( __FILE__, 'hctdd_deactivate');

function hctdd_commu_uninstall()
{
    global $wpdb;
	$table_name = $wpdb->prefix . "community_database";
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);
	delete_option( 'hctdd_db_version');
}
register_uninstall_hook(__FILE__, 'hctdd_commu_uninstall');

function hctdd_commu_admin(){
	include("hct_dd_list.php");
}
function hctdd_commu_admin_add(){
	include("hct_dd_add.php");
}
function hctdd_commu_import(){
	include("hct_dd_import.php");
}
function hctdd_commu_export(){
	include("hct_dd_export.php");
}

function hctdd_commu_charts(){
	include("hct_dd_charts.php");
}

add_action( 'admin_post_generate_csv', 'hctdd_export_commu_data' );
function hctdd_export_commu_data() {
	global $wpdb;
	$hct_orderby = 'id';
	$hct_asc_dsc = 'ASC';
	$table_name = $wpdb->prefix . "community_database";
	if (!empty($_POST) && check_admin_referer('hct_nonce_export','hct_nonces')) {
		$hct_orderby = sanitize_text_field($_POST['hctdd_init']);
		$hct_asc_dsc = sanitize_text_field($_POST['hctdd_asc_dsc']);
	}
	$filename = 'community_data-'.time().'.csv';
	
	$output = fopen("php://output", "w");
	fprintf( $output, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	
	fputcsv($output, array('id','name','gender','dob','married','marriage_ani','occupation','education','email','mobile_no','emergency_no','address','service','created_on'));
	
	$hctdd_qstr = "SELECT * FROM $table_name ORDER BY $hct_orderby $hct_asc_dsc"; 
	$hctdd_community_list = $wpdb->get_results($hctdd_qstr, ARRAY_N);
	$hctdd_sno = 0; $hctdd_list_arr = array();
	
	if ($hctdd_community_list) { 
		foreach($hctdd_community_list as $hctdd_dl){
			fputcsv($output, $hctdd_dl);				
		}
	}
	//fclose($output);
	return $output;

}

function hctdd_admin_commu_actions() {
	add_menu_page("Community Database","Community Database", 'manage_options', "hctdd_menu", "hctdd_commu_admin", "", 2);
	add_submenu_page( "hctdd_menu", "Add New Member", "Add New Member", "manage_options", "hctdd_add_dd", "hctdd_commu_admin_add");
	add_submenu_page( "hctdd_menu", "Import Excel Data", "Import CSV Data", "manage_options", "hctdd_import_dd", "hctdd_commu_import");
	add_submenu_page( "hctdd_menu", "Export Excel Data", "Export CSV Data", "manage_options", "hctdd_export_dd", "hctdd_commu_export");
	add_submenu_page( "hctdd_menu", "Charts", "Charts", "manage_options", "hctdd_charts_dd", "hctdd_commu_charts");
}
add_action('admin_menu','hctdd_admin_commu_actions');

function hctdd_commu_scripts() {
    wp_register_style( 'custom-commu_style', plugins_url( '/css/hctdd_style.css', __FILE__ ) );
    wp_enqueue_style( 'custom-commu_style' );
	
	wp_register_script('hct_commu_loader', plugins_url( '/js/google_charts/loader.js', __FILE__ ));
	$hctdd_translation_array = array(
		'hctdd_siteurl' => get_option('siteurl')
	);
	wp_localize_script( 'hct_commu_loader', 'hct_objname', $hctdd_translation_array );
	wp_enqueue_script('hct_commu_loader');	
}
add_action( 'admin_enqueue_scripts', 'hctdd_commu_scripts' );