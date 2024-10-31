<?php
defined('ABSPATH') or die('No script kiddies please!');

if (isset($_GET['load']) && $_GET['load'] == 'delete') {
    // when delete load
    include ( RCP_INC_DIR . '/actions/delete.php' );
} elseif (isset($_GET['load']) && $_GET['load'] === 'edit') {
    // when edit load
    include ( RCP_INC_DIR . '/actions/edit.php' );
} elseif (isset($_GET['load']) && $_GET['load'] === 'add') {
    // when add load
    include( RCP_INC_DIR . '/actions/add.php' );
} elseif (isset($_GET['load']) && $_GET['load'] === 'status') {
    // when status load
    include ( RCP_INC_DIR . '/actions/status.php' );
} elseif (isset($_GET['load']) && $_GET['load'] === 'alignment') {
    // when alignment load
    include ( RCP_INC_DIR . '/actions/alignment.php' );
} else {
    // when nothing load options page
    ?>

    <div class="wrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="70%" align="left"><h2>REXADZ Monetization - <?php _e('Codes', 'rcp'); ?></h2></td>
                <td width="30%" align="right"><input type="button" class="button-secondary" value="<?php _e('System Information', 'rcp'); ?>" onClick='document.location.href = "<?php echo admin_url('options-general.php?page=rcp&load=system'); ?>"'></td>
            </tr>
            <tr>
                <td width="70%" align="left" colspan="2"><h3><a href="http://rexadz.com/signup" target="_blank">Step 1. <span style="font-weight: normal;">Create a REXADZ Publisher Account</span></a></h3></td>
            </tr>
            <tr>
                <td width="70%" align="left" colspan="2"><h3 style="margin-bottom: 0">Step 2. <span style="font-weight: normal;">Add REXADZ website code you pull form your account.</span></h3></td>
            </tr>
        </table>

        <?php render_rcp_table(); ?>

        <input type="button" class="button-primary" value="<?php _e('Add New Code', 'rcp'); ?>" onClick='document.location.href = "<?php echo admin_url('options-general.php?page=rcp&load=add'); ?>"'>&nbsp;&nbsp;

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="left"><h3>Step 3. <span style="font-weight: normal;">Add the Shortcode to Pages, Posts or Widgets where you want the ads to appear.</span></h3></td>
            </tr>
        </table>


    </div> 

    <?php
}