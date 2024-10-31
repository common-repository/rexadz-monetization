<?php
defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;

// when form was sent

if (isset($_POST) && isset($_POST['submit'])) {
    // secure post data and set variables
    $_POST = stripslashes_deep($_POST);
    $t_rcp_name = sanitize_text_field($_POST['name']);
    $allowedhtml = array(
        'iframe' => array(
            'src' => array(),
            'width' => array(),
            'scrolling' => array(),
            'onload' => array(),
            'frameborder' => array()
        ),
        'width' => array(
        ),
        'script' => array(
            'type' => array(),
            'src' => array()
        ),
    );
    $t_rcp_code = wp_kses($_POST['code'], $allowedhtml);
    $t_rcp_alignment = sanitize_text_field($_POST['alignment']);
    $t_rcp_status = sanitize_text_field($_POST['status']);

    if (strlen($t_rcp_name) > 30) {
        // when name is longer than 30 chars
        $rcp_error = __('A maximum of 30 Characters is allowed', 'rcp');
        $rcp_error_page = "&load=add";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    if (preg_match("/[^a-zA-Z0-9\_-]/i", $t_rcp_name)) {
        // when name contains spechial chars
        $rcp_error = __('Special Characters are not allowed in the Code Name', 'rcp');
        $rcp_error_page = "&load=add";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    if ($t_rcp_name == "" || $t_rcp_code == "") {
        // when post emty goto error page
        $rcp_error = __('The Code Name and / or the Code must be filled in', 'rcp');
        $rcp_error_page = "&load=add";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    $rcp_count = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "rcp_data WHERE name = '$t_rcp_name'");
    if ($wpdb->num_rows) {
        // when name in database goto error page
        $rcp_error = __('The Code Name already exist - It must be uniqe', 'rcp');
        $rcp_error_page = "&load=add";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    $wpdb->insert($wpdb->prefix . 'rcp_data', array('name' => $t_rcp_name, 'code' => $t_rcp_code, 'alignment' => $t_rcp_alignment, 'shortcode' => $t_rcp_name, 'status' => $t_rcp_status, 'version' => RCP_VERSION));

    // when added to database goto options page
    wp_redirect(admin_url('options-general.php?page=rcp'));
    exit();
} else {
    // when nothing done
    ?>

    <div class="wrap">
        <h2>REXADZ Monetization - <?php _e('New Code', 'rcp'); ?></h2>
        <br>

        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="3">    
                <tr>
                    <td><?php _e('Ad Name :', 'rcp'); ?></td>
                </tr>
                <tr>
                    <td><input type="text" style="width: 250px; height: 50px;" name="name" align="center">
                        <br>- <?php _e('Only Letters and Numbers are allowed', 'rcp'); ?>.
                        <br>- <?php _e('Instead of Whitesspaces use Underlines', 'rcp'); ?>.
                        <br>- <?php _e('A maximum of 30 Characters is allowed', 'rcp'); ?>.</td>
                </tr>
                <tr>
                    <td><?php _e('Enter Code : (Pull code from your REXADZ account)', 'rcp'); ?></td>
                </tr>
                <tr>
                    <td><textarea style="width: 600px; height: 150px;" name="code"></textarea></td>
                </tr>
                <tr>
                    <td><?php _e('Alignment', 'rcp'); ?>:</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="alignment" value="0" checked><?php _e('None', 'rcp'); ?>
                        <input type="radio" name="alignment" value="1"><?php _e('Left', 'rcp'); ?>
                        <input type="radio" name="alignment" value="2"><?php _e('Center', 'rcp'); ?>
                        <input type="radio" name="alignment" value="3"><?php _e('Right', 'rcp'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Status', 'rcp'); ?>:</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="status" value="1" checked><?php _e('Online', 'rcp'); ?>
                        <input type="radio" name="status" value="2"><?php _e('Offline', 'rcp'); ?>
                    </td>
                </tr>
            </table>
            <br><input type="button" class="button-secondary" value="<?php _e('Back', 'rcp'); ?>" onClick='document.location.href = "<?php echo admin_url('options-general.php?page=rcp'); ?>"'>&nbsp;&nbsp;<input type="submit" name="submit" class="button-primary" value="<?php _e('Add', 'rcp'); ?>">
        </form>

    </div> 

    <?php
}
?>