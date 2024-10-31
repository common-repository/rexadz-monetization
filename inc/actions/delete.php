<?php

defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;

// secure get data and set variables
$_GET = stripslashes_deep($_GET);
$rcp_id = intval($_GET['rcpid']);

if ($rcp_id == "" || !is_numeric($rcp_id)) {
    // when get emty or other than numbers goto error page
    $rcp_error = __('Modifying of the ID is not allowed', 'rcp');
    $rcp_error_page = "";
    $rcp_error_id = "";
    return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
} else {
// delete code
    $wpdb->delete($wpdb->prefix . 'rcp_data', array('id' => $rcp_id));
}
// when data is deleted goto options page	
wp_redirect(admin_url('options-general.php?page=rcp'));
