<?php
defined('ABSPATH') or die('No script kiddies please!');
// error reporting for dev
// error_reporting(E_ALL);
// define( 'DIEONDBERROR', true ); // multisite
// $wpdb->show_errors(); // single site
// generate options menu
function rcp_add_page() {
    add_options_page('REXADZ Code', 'REXADZ Code', 'manage_options', 'rcp', 'rcp');
}

// add options to menu
function rcp() {
    include ( RCP_INC_DIR . '/page.php' );
}

// add css to rcp page
add_action('admin_head', 'rcp_table_css');

function rcp_table_css() {
    $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;
    if ('rcp' != $page)
        return;

    echo '<style type="text/css">';
    echo '.wp-list-table .column-name { width: 31%; }';
    echo '.wp-list-table .column-shortcode { width: 45%; }';
    echo '.wp-list-table .column-action { width: 24%; }';
    echo '</style>';
}

// show error
function rcp_error($rcp_error, $rcp_error_page, $rcp_error_id) {
    include ( RCP_INC_DIR . '/error.php' );
}

// allow php code
function rcp_allow_php($text) {
    if (strpos($text, '<' . '?') !== false) {
        ob_start();
        eval('?' . '>' . $text);
        $text = ob_get_contents();
        ob_end_clean();
    }
    return $text;
}

// replace shortcode with code
add_shortcode('rcp', 'rcp_replace');

function rcp_replace($rcp_code) {
    global $wpdb, $_GET;
    $query = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "rcp_data WHERE name=%s", $rcp_code));

    if (count($query) > 0) {
        foreach ($query as $code_load) {
            if ($code_load->status === '1') {
                // when status is activ
                if ($code_load->alignment === '0' OR $code_load->alignment === '') {
                    $rcp_output = $code_load->code;
                } elseif ($code_load->alignment === '1') {
                    $rcp_output = "<p align='left'>" . $code_load->code . "</p>";
                } elseif ($code_load->alignment === '2') {
                    $rcp_output = "<p align='center'>" . $code_load->code . "</p>";
                } elseif ($code_load->alignment === '3') {
                    $rcp_output = "<p align='right'>" . $code_load->code . "</p>";
                }
                $pattern = "/{(.*?)}/";

                $rcp_output = preg_replace_callback($pattern, function($match) use ($data) {
                    return isset($_GET[$match[1]]) ? $_GET[$match[1]] : $_GET[$match[0]];
                }, $rcp_output);

                return $rcp_output;
            } else {
                // when status is deactive
                return '';
            }
        }
    } else {
        // when shortcode not found
        return '';
    }
}

// create options table
function render_rcp_table() {
    $rcp_options_table = new rcp_table();
    $rcp_options_table->prepare_items();
    $rcp_options_table->display();
}

function rcp_update() {
    // multiside update
    if (is_multisite()) {
        global $wpdb;
        $blog = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogids as $blogid) {
            switch_to_blog($blogid);
            $wpdb->query("UPDATE " . $wpdb->prefix . "rcp_data SET version='1.0'");
            $wpdb->update($wpdb->prefix . 'rcp_options', array('option_value' => '1.0'), array('option_name' => 'version'));
        }
        switch_to_blog($blog);
    } else {
        // single update
        global $wpdb;
        $wpdb->query("UPDATE " . $wpdb->prefix . "rcp_data SET version='1.0'");
        $wpdb->update($wpdb->prefix . 'rcp_options', array('option_value' => '1.0'), array('option_name' => 'version'));
    }
}

// update function
function rcp_do_update() {
    global $wpdb;
    $rcp_options_version = $wpdb->get_var("SELECT option_value FROM " . $wpdb->prefix . "rcp_options WHERE option_name = 'version'");
    if (!is_admin()) {
        return;
    } elseif ($rcp_options_version === '1.0') {
        return;
    } else {
        rcp_update();
        return;
    }
}

// add tables and data in it when a new blog is created
function rcp_new_blog($blog_id) {
    if (is_plugin_active_for_network('rexadz-code-placement/rexadz-code-placement.php')) {
        switch_to_blog($blog_id);
        rcp_install();
        restore_current_blog();
    }
}

// tell wordpress what to do when adding a new blog
add_action('wpmu_new_blog', 'rcp_new_blog', 99);

// delete tables and data when a blog is deleted
function rcp_deleted_blog($tables) {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'rcp_options';
    $tables[] = $wpdb->prefix . 'rcp_data';
    return $tables;
}

add_filter('wpmu_drop_tables', 'rcp_deleted_blog', 99);