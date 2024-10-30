<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	$limit_number                            = get_option( 'zleadscrm_limit_call_number' );
	$google_places_api                       = get_option( 'zleadscrm_google_places_api_key' );
	$currency                                = get_option( 'zleadscrm_currency' );
	$country                                 = get_option( 'zleadscrm_default_country', 'US' );
	$new_user_notification_email             = get_option( 'zleadscrm_new_user_email_notification', 'off' );
	$send_account_manager_notification_email = get_option( 'zleadscrm_acm_assignment_notification_email', 'off' );
	$zleadscrm_debug_mode                    = get_option( 'zleadscrm_debug_mode', 'off' );
	$show_account_manager_banner             = get_option( 'zleadscrm_show_acm_banner', 'off' );

	if ( $currency == false ) {
		$currency = 'USD';
	}


	if ( ! isset( $limit_number ) || $limit_number == '' ) {
		$limit_number = 60;
	}

	$users_query = new WP_User_Query( array(
		'role__in' => zleadscrm_get_selected_roles()
	) );

	$users          = $users_query->get_results();
	$roles          = zleadscrm_get_roles();
	$selected_roles = zleadscrm_get_selected_roles();

	$zleadscrm_hide_settings_in_menu                  = zleadscrm_get_hide_settings_in_menu();
	$zleadscrm_user_access_settings                   = zleadscrm_get_user_access_settings();
	$zleadscrm_users_allowed_access_analytics_setting = zleadscrm_get_users_allowed_access_analytics_setting();
	$allowed_access_analytics_users                   = zleadscrm_get_access_analytics_users();

	if ( zleadscrm_allow_edit_settings() == false ) {
		echo '<div class="zleadscrm_not_allowed_wrap">';
		echo '<p>Sorry, you are not allowed to access this page!</p>';
		echo '</div>';
		exit;
	}

?>


<div class="wrap">
    <hr class="wp-header-end"/>
    <h1 style="margin-bottom: 1rem; font-weight: 500;">Settings</h1>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" id="zleadscrm_edit_settings_form">

        <div id="zleadscrm_edit_settings_wrap">
            <label><b>Users with access to Leads Settings</b></label>
            <div style="margin-top: 10px; margin-bottom: 20px; margin-left:10px;">
                <div style="margin-bottom: 20px;">
                    <input type="radio" id="zleadscrm_user_access_settings_administrators"
                           name="zleadscrm_user_access_settings"
                           value="administrators" <?php echo checked( 'administrators', $zleadscrm_user_access_settings, false ); ?>>Administrators
                    <span><br><i>All Administrators can manage settings</i><br></span>
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="radio" id="zleadscrm_user_access_settings_manage_options"
                           name="zleadscrm_user_access_settings"
                           value="manage_options" <?php echo checked( 'manage_options', $zleadscrm_user_access_settings, false ); ?>>Anyone
                    with "manage_options" capability
                    <span><br><i>By default only Administrators have this capability.</i><br></span>
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="radio" id="zleadscrm_user_access_settings_current_user"
                           name="zleadscrm_user_access_settings"
                           value="<?php echo get_current_user_id(); ?>" <?php echo checked( get_current_user_id(), $zleadscrm_user_access_settings, false ); ?>>Only
                    the current user
                    <span><br><i>Login: <?php echo get_current_user(); ?>, user ID: <?php echo get_current_user_id(); ?></i><br></span>
                </div>
                <div style="margin-top: 10px; margin-bottom: 20px;">
					<?php $extra = $zleadscrm_hide_settings_in_menu == 1 ? 'checked="checked"' : ''; ?>
                    <input type="checkbox" id="zleadscrm_hide_settings_in_menu" name="zleadscrm_hide_settings_in_menu"
                           value="1" <?php echo $extra ?>/>
                    <label for="zleadscrm_hide_settings_in_menu" style="display: inline-block; margin-top: -5px;">Hide
                        the
                        "Settings" entry on the sub menu of Account Manager from other users</label>
                </div>
            </div>

            <div style="margin-top: 20px; margin-bottom: 20px;">
                <label><b>Debug Mode</b></label>
                <div style="margin-top: 10px; margin-left:10px;">
                    <input type="checkbox" name="zleadscrm_debug_mode"
                           id="zleadscrm_debug_mode" <?php echo $zleadscrm_debug_mode == 'on' ? 'checked' : ''; ?> />
                    <label for="zleadscrm_debug_mode">Enable debug mode. <span style="color: #7d7d7d;"><i>Recommended only on development or staging
                                servers</i></span></label>
                </div>
            </div>

            <div style="margin-top: 20px; margin-bottom: 20px;">
                <label><b>Notifications</b></label>
                <div style="margin-top: 10px; margin-left:10px;">
                    <input type="checkbox" name="zleadscrm_new_user_email_notification"
                           id="zleadscrm_new_user_email_notification" <?php echo $new_user_notification_email == 'on' ? 'checked' : ''; ?> />
                    <label for="zleadscrm_new_user_email_notification">Send the new lead user an email about their
                        account</label>
                </div>
                <div style="margin-top: 10px; margin-left:10px;">
                    <input type="checkbox" name="zleadscrm_show_acm_banner"
                           id="zleadscrm_show_acm_banner" <?php echo $show_account_manager_banner == 'on' ? 'checked' : ''; ?> />
                    <label for="zleadscrm_show_acm_banner">Show Account Manager banner assignment notification</label>
                </div>
                <div style="margin-top: 10px; margin-left:10px;">
                    <input type="checkbox" name="zleadscrm_acm_assignment_notification_email"
                           id="zleadscrm_acm_assignment_notification_email" <?php echo $send_account_manager_notification_email == 'on' ? 'checked' : ''; ?> />
                    <label for="zleadscrm_acm_assignment_notification_email">Send Account Manager email assignment
                        notification</label>
                </div>
            </div>

            <div style="margin-top: 20px; margin-bottom: 20px;">
                <label><b>Currency</b></label>
				<?php $currencies = zleadscrm_get_currencies(); ?>
                <select style="display: block; margin-top:10px;" name="zleadscrm_currency" id="zleadscrm_currency">
					<?php foreach ( $currencies as $key => $value ) {
						$selected = $key == $currency ? 'selected' : '';
						echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . ' (' . zleadscrm_get_currency_symbol( $key ) . ')' . '</option>';
					} ?>
                </select>
            </div>

            <div style="margin-top: 20px; margin-bottom: 20px;">
                <label><b>Default Country</b></label>
				<?php $countries = zleadscrm_get_countries(); ?>
                <select style="display: block; margin-top:10px;" name="zleadscrm_country" id="zleadscrm_country">
					<?php foreach ( $countries as $key => $value ) {
						$selected = $key == $country ? 'selected' : '';
						echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
					} ?>
                </select>
            </div>

            <label style="margin-top: 20px;"><b>Select Role Types for Leads Functionality</b></label>
            <div id="zleadscrm_roles_wrap">
				<?php foreach ( $roles as $key => $value ): ?>
					<?php $extra = in_array( $key, $selected_roles ) ? 'checked="checked"' : ''; ?>

                    <div class="zleadscrm_edit_settings_flex_wrap">
						<?php echo '<input type="checkbox" id="zleadscrm_role_' . esc_attr( $key ) . '" class="zleadscrm_roles_selection" name="zleadscrm_roles[]" value="' . esc_attr( $key ) . '" ' . $extra . '/>';
							echo '<label for="zleadscrm_role_' . esc_attr( $key ) . '">' . esc_html( $value['name'] ) . '</label>'; ?>
                    </div>
				<?php endforeach; ?>
            </div>
            <div style="margin-top: 20px;">
                <label style="margin: 10px 0 10px 0;"><b>Users Allowed to Access Analytics Functionality </b></label>
                <div style=" margin-top: 10px; margin-bottom: 20px; margin-left:10px;">
                    <div style="display:inline; margin-right: 10px;">
                        <input type="radio" id="zleadscrm_users_allowed_access_analytics_setting_all"
                               name="zleadscrm_users_allowed_access_analytics_setting" value="all" <?php checked(
							'all', $zleadscrm_users_allowed_access_analytics_setting, true ) ?>>All Users
                    </div>
                    <div style="display:inline; margin-right: 10px;">
                        <input type="radio" id="zleadscrm_users_allowed_access_analytics_setting_users"
                               name="zleadscrm_users_allowed_access_analytics_setting" value="users" <?php checked(
							'users', $zleadscrm_users_allowed_access_analytics_setting, true ) ?>>Select Users
                    </div>
                </div>

                <div id="zleadscrm_allowed_users_access_analytics_list_container">
                    <select id="zleadscrm_users_access_analytics_list"
                            name="zleadscrm_allowed_access_analytics_users[]" multiple="multiple">
						<?php if ( $users ) {
							foreach ( $users as $user ) {
								$extra = '';
								if ( in_array( $user->ID, $allowed_access_analytics_users ) ) {
									$extra = 'selected="selected"';
								}
								echo '<option value="' . esc_attr( $user->ID ) . '" ' . $extra . '>' . esc_html( $user->display_name ) . '</option>';
							}
						}
						?>
                    </select>
                </div>
            </div>


			<?php
				if ( class_exists( 'ZLEADSCRM_EXPORT_Core' ) ) {
					zleadscrm_display_export_settings();
				}

				if ( class_exists( 'ZLEADSCRM_IMPORT_Core' ) ) {
					zleadscrm_display_import_settings();
				}

				if ( class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
					zleadscrm_display_custom_fields_settings();
				}

				if ( class_exists( 'ZLEADSCRM_PIPELINE_Core' ) ) {
					zleadscrm_display_pipeline_settings();
				}

				if ( class_exists( 'ZLEADSCRM_PROSPECT_Core' ) ) {
					zleadscrm_display_prospect_settings();
				}


			?>


            <input type="hidden" name="action" value="zleadscrm_edit_settings"/>
			<?php wp_nonce_field( 'zleadscrm_edit_settings' ); ?>

            <input type="submit" style="margin-top: 1rem;" class="button button-primary" value="Update Settings">
    </form>
</div>
