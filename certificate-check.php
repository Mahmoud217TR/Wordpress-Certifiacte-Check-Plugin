<?php
// /wp-content/plugins/certificate-check/certificate-check.php
/**
 * Plugin Name:       Certificate Check
 * Description:       Add Certificates to database & check it
 * Version:           1.1.0
 * Author: 			  MahmoudTR
 * Author URI: 		  https://github.com/Mahmoud217TR
 * Text Domain:       certificate-check
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'CERTIFICATE_CHECK_VERSION', '1.1.0' );

define( 'CERTIFICATE_CHECK_DIR', 'certificate-check' );

function create_the_custom_table()
{
	global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cer_ch_certificates';

    $sql = "CREATE TABLE " . $table_name . " (
	id int(11) NOT NULL AUTO_INCREMENT,
	certificate_number VARCHAR(100) NOT NULL,
	first_name tinytext NOT NULL,
	last_name tinytext NOT NULL,
	email VARCHAR(100) NOT NULL,
	mobile VARCHAR(100) NOT NULL,
	product VARCHAR(100) NOT NULL,
	result VARCHAR(100) NOT NULL,
	issue_date DATE NOT NULL,
	exam_date DATE NOT NULL,
	PRIMARY KEY  (id),
	UNIQUE (certificate_number)
    ) $charset_collate;";
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_the_custom_table');

/**
 * Helpers
 */
require plugin_dir_path( __FILE__ ) . 'includes/helpers.php';


/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-certificate-check.php';


function run_certificate_check() {

    $plugin = new CertificateCheck();
    $plugin->init();

}
run_certificate_check();

