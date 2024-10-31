<?php
defined('ABSPATH') or die('No script kiddies please!');
// install function
function rcp_install(){
    global $wpdb;
    // create data table
    $rcp_table = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rcp_data (
        `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
        `code` longtext COLLATE utf8_unicode_ci NOT NULL,
        `alignment` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
        `shortcode` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
        `status` int NOT NULL,
        `version` varchar(10) COLLATE utf8_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
    $wpdb->query($rcp_table);
    // create options table
    $rcp_options = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."rcp_options (
        `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `option_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL UNIQUE,
        `option_value` varchar(10) COLLATE utf8_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
    $wpdb->query($rcp_options);
    // insert data
    $wpdb->insert($wpdb->prefix.'rcp_options', array('option_name' => 'version','option_value' => RCP_VERSION));
    $wpdb->insert($wpdb->prefix.'rcp_options', array('option_name' => 'perpage','option_value' => '10'));
}

// multiside or single installation?
function rcp_net_inst($networkwide) {
    global $wpdb;  
    // multiside installation
    if (is_multisite() && $networkwide) {
	$blog = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogids as $blogid) {
            switch_to_blog($blogid);
            rcp_install();
        }
	switch_to_blog($blog);
    } else {
    // single installation	
    rcp_install();
    }
}

// register install hook
register_activation_hook ( RCP_FILE, 'rcp_net_inst' );
