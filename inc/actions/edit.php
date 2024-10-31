<?php
defined('ABSPATH') or die('No script kiddies please!');
global $wpdb;

// when form was sent
if (isset($_POST) && isset($_POST['submit'])) {

    // secure post data and set variables
    $_POST = stripslashes_deep($_POST);
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
    $t_rcp_id = sanitize_text_field($_POST['id']);

    if ($t_rcp_id == "" || !is_numeric($t_rcp_id)) {
        // when get emty or other than numbers goto error page
        $rcp_error = __('Modifying of the ID is not allowed', 'rcp');
        $rcp_error_page = "";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    if ($t_rcp_code == "") {
        // when post emty goto error page
        $rcp_error = __('The Code must be filled in', 'rcp');
        $rcp_error_page = "&load=edit";
        $rcp_error_id = "&rcpid=$rcp_id";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    $wpdb->update($wpdb->prefix . 'rcp_data', array('code' => $t_rcp_code, 'alignment' => $t_rcp_alignment, 'status' => $t_rcp_status), array('id' => $t_rcp_id));

    // when edited goto options page
    wp_redirect(admin_url('options-general.php?page=rcp'));
    exit();
} else {
    // when nothing done
    // secure get data and set variables
    $_GET = stripslashes_deep($_GET);
    $rcp_id = $_GET['rcpid'];

    if ($rcp_id == "" || !is_numeric($rcp_id)) {
        // when get emty or other than numbers goto error page
        $rcp_error = __('Modifying of the ID is not allowed', 'rcp');
        $rcp_error_page = "";
        $rcp_error_id = "";
        return(rcp_error($rcp_error, $rcp_error_page, $rcp_error_id));
    }

    $rcp_load = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'rcp_data WHERE id= ' . $rcp_id . '');
    ?>

    <div class="wrap">
        <h2>REXADZ Monetization - <?php _e('Edit Code', 'rcp'); ?></h2>
        <br>

        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="3">    
                <tr>
                    <td><?php _e('Ad Name', 'rcp'); ?>:</td>
                </tr>
                <tr>
                    <td><input disabled="disabled" type="text" style="width: 250px; height: 50px;" name="name" align="center" value="<?php echo ($rcp_load->name); ?>">
                        <input type="hidden" name="id" align="center" value="<?php echo ($rcp_load->id); ?>">
                        <br>- <?php _e('Only Letters and Numbers are allowed', 'rcp'); ?>.
                        <br>- <?php _e('Instead of Whitesspaces use Underlines', 'rcp'); ?>.
                        <br>- <?php _e('A maximum of 30 Characters is allowed', 'rcp'); ?>.</td>
                </tr>
                <tr>
                    <td><?php _e('Enter Code : (Pull code from your REXADZ account)', 'rcp'); ?></td>
                </tr>
                <tr>
                    <td><textarea style="width: 600px; height: 150px;" name="code"><?php echo ($rcp_load->code); ?></textarea></td>
                </tr>
                <tr>
                    <td><?php _e('Alignment', 'rcp'); ?>:</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="alignment" value="0" <?php
                        if ($rcp_load->alignment == "0" OR $rcp_load->alignment == "") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('None', 'rcp'); ?>
                        <input type="radio" name="alignment" value="1" <?php
                        if ($rcp_load->alignment == "1") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('Left', 'rcp'); ?>
                        <input type="radio" name="alignment" value="2" <?php
                        if ($rcp_load->alignment == "2") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('Center', 'rcp'); ?>
                        <input type="radio" name="alignment" value="3" <?php
                        if ($rcp_load->alignment == "3") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('Right', 'rcp'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Status', 'rcp'); ?>:</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="status" value="1" <?php
                        if ($rcp_load->status == "1") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('Online', 'rcp'); ?>
                        <input type="radio" name="status" value="2" <?php
                        if ($rcp_load->status == "2") {
                            echo "checked";
                        } else {
                            echo "";
                        }
                        ?>><?php _e('Offline', 'rcp'); ?>
                    </td>
                </tr>
            </table>
            <br><input type="button" class="button-secondary" value="<?php _e('Back', 'rcp'); ?>" onClick='document.location.href = "<?php echo admin_url('options-general.php?page=rcp'); ?>"'>&nbsp;&nbsp;<input type="submit" name="submit" class="button-primary" value="<?php _e('Save', 'rcp'); ?>">
        </form>

    </div>

    <?php
}