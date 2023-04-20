<?php

/**
 * helpers
 */

function certificate_check_admin_current_view()
{
    $current_step = isset($_GET['page']) ? $_GET['page'] : 'main';

    if (strpos($current_step, '_') === false) {
        return 'main';
    }

    return str_replace("certificate-check_", "", $current_step);

}

function certificate_check_admin_template_server_path($file_path, $include = true, $options = array())
{
    $my_plugin_dir = WP_PLUGIN_DIR . "/" . CERTIFICATE_CHECK_DIR . "/";
    
    if ( is_dir( $my_plugin_dir ) ) {
        $path_to_file = $my_plugin_dir . $file_path . '.php';
    }

    if ($include) {
        include $path_to_file;
    }

    return $path_to_file;
}

function certificate_check_admin_url($append = '')
{
    return plugins_url($append, __DIR__);
}

function certificate_check_admin_view_pagename($step)
{
    $view_url_part = '';
    if($step){
        $view_url_part = '_' . $step;
    }

    return admin_url('admin.php?page=certificate-check' . $view_url_part);
}

/**
 * @param $message
 * @param $msg_type
 * @return void
 * warning, info, success
 */
function certificate_check_admin_message($message, $msg_type = 'info') {
    return "<div class='row my-2'><div class='col-md-12'><div id='message' class='alert alert-$msg_type'>$message</div></div></div>";
}

function certificate_check_user_message($message, $msg_type = 'info') {
    return "<div class='row p-4'><div class='col-md-4'><div id='message' class='alert alert-$msg_type'>$message</div></div></div>";
}

function get_certificates() 
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cer_ch_certificates';
    $query = "SELECT * FROM $table_name";
    return $wpdb->get_results($query);
}

function get_certificate($id) 
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cer_ch_certificates';
    $query = "SELECT * FROM $table_name where id = $id";
    return $wpdb->get_results($query);
}

function verify_certificate($certificate_number) 
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cer_ch_certificates';
    $query = "SELECT * FROM $table_name where certificate_number = '$certificate_number'";
    return $wpdb->get_results($query);
}