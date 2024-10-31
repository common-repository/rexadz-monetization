<?php
defined('ABSPATH') or die('No script kiddies please!');
// uninstall function
function rcp_uninstall(){
    global $wpdb;
    // delete tables
    $wpdb->query("DROP TABLE ".$wpdb->prefix."rcp_data");
    $wpdb->query("DROP TABLE ".$wpdb->prefix."rcp_options");
}

// multiside or single uninstall?
function rcp_net_uninstall() {
    global $wpdb;  
    // multiside uninstallation
    if (is_multisite()) {
	$blog = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogids as $blogid) {
            switch_to_blog($blogid);
            rcp_uninstall();
        }
	switch_to_blog($blog);
    } else {
    // single uninstallation	
    rcp_uninstall();
    }
}

// register uninstall hook
register_uninstall_hook ( RCP_FILE, 'rcp_net_uninstall' );
