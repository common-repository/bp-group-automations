<?php

/*
Plugin Name: BP Group Automations
Plugin URI: 
Description: Automation features for groups in BuddyPress. 
Author: Edwin Celini
Version: 1.0
Author URI: edwincelini.com
*/

// Group created automatically when user subscribes

add_action('user_register', 'BPGA_addUserToNewBpGroup');

function BPGA_addUserToNewBpGroup($userId) {
    $idGroup = BPGA_createBpGroup($userId);
    BPGA_addUserToGroup($userId, $idGroup);
}

function BPGA_createBpGroup($userId) {
    $groupArgs = array();
    $groupArgs['name'] = get_option('group_name', 'Group name');
    $groupArgs['description'] = get_option('group_description', 'Group description');
    $groupArgs['creator_id'] = 0;
    $groupArgs['status'] = 'public'; 
    $groupId = groups_create_group($groupArgs);
    return $groupId;
}

function BPGA_addUserToGroup($userId, $groupId) {
    if (!$userId)
        return false;
    groups_accept_invite($userId, $groupId);
}

// Admin initialisation.

add_action('admin_init', 'BPGA_buddypressGroupAutomationsSettings');

function BPGA_buddypressGroupAutomationsSettings() {
    register_setting( 'BPGA_buddypress_group_automations_settings_group', 'group_name' );
    register_setting( 'BPGA_buddypress_group_automations_settings_group', 'group_description' );
}

// buddypressGroupAutomationAdminMenu() will be executed when wordpress going to set the menu.

add_action( 'admin_menu', 'BPGA_buddypressGroupAutomationAdminMenu' );

function BPGA_buddypressGroupAutomationAdminMenu() {
    add_options_page(
        'BuddyPress Group Automations Settings', 
        'BuddyPress Group Automations', 
        'administrator', 
        'buddypress-group-automations-settings-page',
        'BPGA_buddypressGroupAutomationsSettingsPage'
    );
}

// Settings page form.

function BPGA_buddypressGroupAutomationsSettingsPage() {
    ?>
    <div class="wrap">
        <h1>BuddyPress Group Automations</h1>
        <h2>Create group automatically when user subscribes</h2>
        <form method="post" action="options.php">
            <?php settings_fields('BPGA_buddypress_group_automations_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Group name</th>
                    <td>
                        <input type="text" id="group-name" name="group_name" value="<?php echo get_option('group_name', 'Group name') ?>"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Group description</th>
                    <td>
                        <textarea 
                        id="group-description" 
                        name="group_description" 
                        value="<?php echo get_option('group_description', 'Group description') ?>"
                        rows="2"
                        style="resize: none;"></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php 
} 
?>
