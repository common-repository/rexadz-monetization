<?php

defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;

// secure get data and set variables
$_GET = stripslashes_deep($_GET);
$rcp_id = sanitize_text_field($_GET['rcpid']);
$rcp_alignment = sanitize_text_field($_GET['alignment']);

if ($rcp_id == "" || !is_numeric($rcp_id)) {
    // when get emty or other than numbers goto error page
    $rcp_error = __('Modifying of the ID is not allowed', 'rcp');
    $rcp_error_page = "";
    $rcp_error_id = "";
    return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
} elseif ($rcp_alignment !== "0" && $rcp_alignment !== "1" && $rcp_alignment !== "2" && $rcp_alignment !== "3") {
    // when get emty or other than numbers goto error page
    $rcp_error = __('Modifying the Alignment to something else than 0, 1, 2 or 3 is not allowed', 'rcp');
    $rcp_error_page = "";
    $rcp_error_id = "";
    return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
}

// change status	
$wpdb->update($wpdb->prefix . 'rcp_data', array('alignment' => $rcp_alignment), array('id' => $rcp_id));

// when status changes goto options page
wp_redirect(admin_url('options-general.php?page=rcp'));
