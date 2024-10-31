<?php

defined('ABSPATH') or die('No script kiddies please!');
/*
  Plugin Name: REXADZ Monetization
  Version: 1.0
  Plugin URI: http://www.rexadz.com/
  Author: RexDirect Inc
  Author URI: http://www.rexadz.com
  Description: A great Wordpress Plugin to place REXADZ Code ANYWHERE you want.
  License: GPL2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: rexadz-code
 */

// standards

defined('RCP_LANG_DIR') or define('RCP_LANG_DIR', plugin_dir_url(__FILE__) . '/lang');
defined('RCP_IMG_DIR') or define('RCP_IMG_DIR', plugin_dir_url(__FILE__) . '/img');
defined('RCP_INC_DIR') or define('RCP_INC_DIR', plugin_dir_path(__FILE__) . 'inc');
defined('RCP_CLASS_DIR') or define('RCP_CLASS_DIR', plugin_dir_path(__FILE__) . 'inc/classes');
defined('RCP_ACTIONS_DIR') or define('RCP_ACTIONS_DIR', plugin_dir_path(__FILE__) . 'inc/actions');
defined('RCP_PATH') or define('RCP_PATH', plugin_dir_path(__FILE__));
defined('RCP_FILE') or define('RCP_FILE', __FILE__);
defined('RCP_VERSION') or define('RCP_VERSION', '1.0');


// load functions, classes
include( RCP_PATH . '/inc/functions.php' );
include( RCP_PATH . '/inc/classes/class-rcp-tables.php' );
include( RCP_PATH . '/inc/classes/class-rcp-table.php' );

// set filters to replace shortcodes
add_filter('the_content', 'do_shortcode', 99);
add_filter('widget_text', 'do_shortcode', 99);
add_filter('the_excerpt', 'do_shortcode', 99);

// set filters to allow php code
add_filter('the_content', 'rcp_allow_php', 99);
add_filter('widget_text', 'rcp_allow_php', 99);
add_filter('the_excerpt', 'rcp_allow_php', 99);

// load languages
load_plugin_textdomain('rexadz-code', false, dirname(plugin_basename(__FILE__)) . '/lang/');

// include install and uninstall files
include( RCP_PATH . '/inc/install.php' );
include( RCP_PATH . '/inc/uninstall.php' );

// update if neccesary
rcp_do_update();

// add options menu
add_action('admin_menu', 'rcp_add_page');
