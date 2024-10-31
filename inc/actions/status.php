<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

// secure get data and set variables
$_GET = stripslashes_deep($_GET);
$rcp_id = sanitize_text_field($_GET['rcpid']);
$rcp_status = sanitize_text_field($_GET['status']);

if ($rcp_id=="" || !is_numeric($rcp_id)) {
    // when get emty or other than numbers goto error page
    $rcp_error = __('Modifying of the ID is not allowed', 'rcp');
    $rcp_error_page = "";
    $rcp_error_id = "";
    return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
} elseif ($rcp_status !=="1" && $rcp_status !=="2") {
    // when get emty or other than numbers goto error page
    $rcp_error = __('Modifying of the Status to something else than 1 or 2 is not allowed', 'rcp');
    $rcp_error_page = "";
    $rcp_error_id = "";
    return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
}

// change status	
$wpdb->update($wpdb->prefix.'rcp_data', array('status'=>$rcp_status), array('id'=>$rcp_id));

// when status changes goto options page
wp_redirect(admin_url('options-general.php?page=rcp'));
