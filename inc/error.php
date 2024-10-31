<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<div class="wrap">
    <h2>Monetization by REXADZ <?php _e('Error', 'rcp'); ?></h2>
    <br>

    <table width="100%" border="0" cellspacing="0" cellpadding="6">    
        <tr>
            <td><h3><font color="#FF0000"><?php _e('Error', 'rcp'); ?>!</font></h3></td>
        </tr>
        <tr>
            <td><?php echo $rcp_error; ?>!</td>
        </tr>
    </table>
    <br><input type="button" class="button-secondary" value="<?php _e('Back', 'rcp'); ?>" onClick='document.location.href = "<?php echo admin_url("options-general.php?page=rcp$rcp_error_page$rcp_error_id"); ?>"'>

</div>