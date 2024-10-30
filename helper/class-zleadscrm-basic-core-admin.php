<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ZLEADSCRM_BASIC_Core_Admin {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_hooks' ), 1 );

	}


	public function init_hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_resources' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_resources' ) );

		/* Form Post */
		add_action( 'admin_post_zleadscrm_edit_settings', array( $this, 'edit_settings' ), 10 );
		add_action( 'admin_post_zleadscrm_edit_user_limits', array( $this, 'edit_user_limits' ) );
		add_action( 'admin_post_zleadscrm_add_new_lead', array( $this, 'add_new_lead' ) );
		add_action( 'admin_post_zleadscrm_edit_lead', array( $this, 'edit_lead' ) );
		add_action( 'admin_post_zleadscrm_add_interaction', array( $this, 'add_interaction' ) );
		add_action( 'admin_post_remove_lead_notification', array( $this, 'remove_lead_notification' ) );

		/*Ajax Post*/
		add_action( 'wp_ajax_search_admins', array( $this, 'ajax_search_admins' ) );
		add_action( 'wp_ajax_change_lead_archive_status', array( $this, 'change_lead_archive_status' ) );
		add_action( 'wp_ajax_zacctmgr_notice_dismissed', array( $this, 'zacctmgr_notice_dismissed' ) );
		add_action( 'wp_ajax_zleadscrm_wc_notice_dismissed', array( $this, 'zleadscrm_wc_notice_dismissed' ) );

	}

	public function admin_init() {
	}

	public function zacctmgr_notice_dismissed() {
		$current_user_id = get_current_user_id();
		update_option( 'zleadscrm_acm_notice_' . $current_user_id, current_time( 'timestamp' ) );
	}

	public function zleadscrm_wc_notice_dismissed() {
		$current_user_id = get_current_user_id();
		update_option( 'zleadscrm_wc_notice_' . $current_user_id, current_time( 'timestamp' ) );
	}

	public function change_lead_archive_status() {
		$lead_id        = sanitize_text_field( $_POST['lead_id'] );
		$current_status = get_user_meta( $lead_id, 'zleadscrm_lead_archive_status', true );
		if ( $current_status == 'false' || $current_status == '' || $current_status == null ) {
			update_user_meta( $lead_id, 'zleadscrm_lead_archive_status', 'true' );
			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Lead Archive Status Changed',
				'old_value' => 'Not Archived',
				'new_value' => 'Archived',
				'user_id'   => $lead_id,
				'editor_id' => get_current_user_id()
			) );
			update_user_meta( $lead_id, 'zleadscrm_last_edit', current_time( 'timestamp' ) );

			print( 'Restore' );
			exit();
		} else {
			update_user_meta( $lead_id, 'zleadscrm_lead_archive_status', 'false' );
			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Short Description Changed',
				'old_value' => 'Archived',
				'new_value' => 'Not Archived',
				'user_id'   => $lead_id,
				'editor_id' => get_current_user_id()
			) );
			update_user_meta( $lead_id, 'zleadscrm_last_edit', current_time( 'timestamp' ) );

			print( 'Archive' );
			exit();
		}
	}

	public function remove_lead_notification() {
		$lead_id = sanitize_text_field( $_POST['lead_id'] );
		update_user_meta( $lead_id, 'zleadscrm_acm_notified', 'yes' );

		print( 'a' );
		exit();
	}

	public function add_interaction() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'zleadscrm_add_interaction' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks&lead_tab=interactions' );
			exit();
		}
		$message = sanitize_textarea_field( wp_unslash( $_POST['zleadscrm_interaction_message'] ) );
		$lead_id = sanitize_textarea_field( wp_unslash( $_POST['lead_id'] ) );


		if ( ! isset( $_POST['zleadscrm_interaction_message'] ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks&lead_tab=interactions&edit_lead=' . $lead_id );
			exit();
		} else {
			zleadscrm_insert_lead_interaction( array(
				'lead_id'   => $lead_id,
				'message'   => $message,
				'author_id' => get_current_user_id()
			) );
		}

		wp_redirect( 'admin.php?page=zleadscrm_bookmarks&lead_tab=interactions&edit_lead=' . $lead_id );
		exit();
	}

	public function add_new_lead() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'zleadscrm_add_new_lead' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks&add_new=1' );
			exit();
		}
		$redirect_tab = 'general';

		if ( isset( $_POST['redirect_tab'] ) ) {
			$redirect_tab = sanitize_text_field( $_POST['redirect_tab'] );
		}

		if ( isset( $_POST['zleadscrm_business_name'] ) ) {
			$username = preg_replace( '/[^A-Za-z0-9\-]/', '', sanitize_text_field( $_POST['zleadscrm_business_name'] ) );
			$username = strtolower( $username );
			$username = str_replace( ' ', '', $username );

			$user_id                    = wp_create_user( $username, wp_generate_password(), sanitize_email( $_POST['zleadscrm_email_address'] ) );
			$send_new_user_notification = get_option( 'zleadscrm_new_user_email_notification', 'off' );
			if ( $send_new_user_notification === 'on' ) {
				wp_new_user_notification( $user_id, null, 'user' );
			}
		} else {
			wp_safe_redirect( 'admin.php?page=zleadscrm_bookmarks&add_new=1&lead_tab=' . $redirect_tab );
			exit();
		}

		if ( $user_id instanceof WP_Error ) {
			wp_safe_redirect( 'admin.php?page=zleadscrm_bookmarks&add_new=1&lead_tab=' . $redirect_tab );
			exit();
		}

		if ( isset( $_POST['zleadscrm_first_name'] ) ) {
			update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['zleadscrm_first_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_last_name'] ) ) {
			update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['zleadscrm_last_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_title'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_title', sanitize_text_field( $_POST['zleadscrm_title'] ) );
		}

		if ( isset( $_POST['zleadscrm_business_name'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_business_name', sanitize_text_field( $_POST['zleadscrm_business_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_address'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_address', sanitize_text_field( $_POST['zleadscrm_address'] ) );
		}

		if ( isset( $_POST['zleadscrm_lead_source'] ) && $_POST['zleadscrm_lead_source'] != 'select' ) {
			update_user_meta( $user_id, 'zleadscrm_lead_source', sanitize_text_field( $_POST['zleadscrm_lead_source'] ) );
		}

		if ( isset( $_POST['zleadscrm_account_manager'] ) && $_POST['zleadscrm_account_manager'] != '0' ) {
			update_user_meta( $user_id, 'zleadscrm_account_manager', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );
			zacctmgr_insert_account_manager_assignment( array(
				'timestamp'   => current_time( 'mysql' ),
				'manager_id'  => (int) sanitize_text_field( $_POST['zleadscrm_account_manager'] ),
				'customer_id' => $user_id
			) );
			update_user_meta( $user_id, 'zacctmgr_assigned', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );
			$send_account_manager_notification_email = get_option( 'zleadscrm_acm_assignment_notification_email', 'off' );

			if ( $send_account_manager_notification_email == 'on' ) {
				$account_manager = get_user_by( 'id', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );
				$user            = get_user_by( 'id', $user_id );
				$edit_url        = admin_url( 'admin.php' ) . '?page=zleadscrm_bookmarks&tab=leads&edit_lead=' . $user_id;
				$subject         = 'A new lead has been assigned to you!';
				$body            = 'A new lead ' . $user->last_name . ', ' . $user->first_name . ' has been assigned to you, <a href="' . esc_url( $edit_url ) . '">view lead</a> on ' . get_bloginfo( 'name' );


				add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
				wp_mail( $account_manager->user_email, $subject, $body );
				remove_filter( 'wp_mail_content_type', array(
					$this,
					'set_html_content_type'
				) );
			}
		}

		if ( isset( $_POST['zleadscrm_phone'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_phone', sanitize_text_field( $_POST['zleadscrm_phone'] ) );
		}

		if ( isset( $_POST['zleadscrm_website'] ) ) {
			wp_update_user( array(
				'ID'       => $user_id,
				'user_url' => sanitize_text_field( $_POST['zleadscrm_website'] )
			) );
		}

		if ( isset( $_POST['zleadscrm_email_address'] ) ) {
			wp_update_user( array(
				'ID'         => $user_id,
				'user_email' => sanitize_email( $_POST['zleadscrm_email_address'] )
			) );
		}

		if ( isset( $_POST['zleadscrm_lead_status'] ) && $_POST['zleadscrm_lead_status'] != 'select' ) {
			update_user_meta( $user_id, 'zleadscrm_lead_status', sanitize_text_field( $_POST['zleadscrm_lead_status'] ) );
		}

		if ( isset( $_POST['zleadscrm_brands'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_brands', zleadscrm_sanitize_array( $_POST['zleadscrm_brands'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_first_name'] ) ) {
			update_user_meta( $user_id, 'billing_first_name', sanitize_text_field( $_POST['zleadscrm_billing_first_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_last_name'] ) ) {
			update_user_meta( $user_id, 'billing_last_name', sanitize_text_field( $_POST['zleadscrm_billing_last_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_company'] ) ) {
			update_user_meta( $user_id, 'billing_company', sanitize_text_field( $_POST['zleadscrm_billing_company'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_address_1'] ) ) {
			update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $_POST['zleadscrm_billing_address_1'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_address_2'] ) ) {
			update_user_meta( $user_id, 'billing_address_2', sanitize_text_field( $_POST['zleadscrm_billing_address_2'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_city'] ) ) {
			update_user_meta( $user_id, 'billing_city', sanitize_text_field( $_POST['zleadscrm_billing_city'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_postcode'] ) ) {
			update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $_POST['zleadscrm_billing_postcode'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_country'] ) && $_POST['zleadscrm_billing_country'] != 'select' ) {
			update_user_meta( $user_id, 'billing_country', sanitize_text_field( $_POST['zleadscrm_billing_country'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_state'] ) ) {
			update_user_meta( $user_id, 'billing_state', sanitize_text_field( $_POST['zleadscrm_billing_state'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_email'] ) ) {
			update_user_meta( $user_id, 'billing_email', sanitize_email( $_POST['zleadscrm_billing_email'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_phone'] ) ) {
			update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['zleadscrm_billing_phone'] ) );
		}

		if ( isset( $_POST['zleadscrm_billing_payment_method'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_billing_payment_method', sanitize_text_field( $_POST['zleadscrm_billing_payment_method'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_first_name'] ) ) {
			update_user_meta( $user_id, 'shipping_first_name', sanitize_text_field( $_POST['zleadscrm_shipping_first_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_last_name'] ) ) {
			update_user_meta( $user_id, 'shipping_last_name', sanitize_text_field( $_POST['zleadscrm_shipping_last_name'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_company'] ) ) {
			update_user_meta( $user_id, 'shipping_company', sanitize_text_field( $_POST['zleadscrm_shipping_company'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_address_1'] ) ) {
			update_user_meta( $user_id, 'shipping_address_1', sanitize_text_field( $_POST['zleadscrm_shipping_address_1'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_address_2'] ) ) {
			update_user_meta( $user_id, 'shipping_address_2', sanitize_text_field( $_POST['zleadscrm_shipping_address_2'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_city'] ) ) {
			update_user_meta( $user_id, 'shipping_city', sanitize_text_field( $_POST['zleadscrm_shipping_city'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_postcode'] ) ) {
			update_user_meta( $user_id, 'shipping_postcode', sanitize_text_field( $_POST['zleadscrm_shipping_postcode'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_country'] ) && $_POST['zleadscrm_shipping_country'] != 'select' ) {
			update_user_meta( $user_id, 'shipping_country', sanitize_text_field( $_POST['zleadscrm_shipping_country'] ) );
		}

		if ( isset( $_POST['zleadscrm_shipping_state'] ) ) {
			update_user_meta( $user_id, 'shipping_state', sanitize_text_field( $_POST['zleadscrm_shipping_state'] ) );
		}

		if ( isset( $_POST['zleadscrm_lead_customer_status'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_lead_customer_status', sanitize_text_field( $_POST['zleadscrm_lead_customer_status'] ) );
		}

		if ( isset( $_POST['zleadscrm_company_annual_revenue_size'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_company_annual_revenue_size', sanitize_text_field( $_POST['zleadscrm_company_annual_revenue_size'] ) );
		}

		if ( isset( $_POST['zleadscrm_order_monthly_volume_oportunity'] ) ) {
			update_user_meta( $user_id, 'zleadscrm_order_monthly_volume_oportunity', sanitize_text_field( $_POST['zleadscrm_order_monthly_volume_oportunity'] ) );
		}

		if ( isset( $_POST['zleadscrm_short_description'] ) ) {
			update_user_meta( $user_id, 'description', sanitize_textarea_field( $_POST['zleadscrm_short_description'] ) );
		}

		update_user_meta( $user_id, 'zleadscrm_lead_archive_status', 'false' );

		update_user_meta( $user_id, 'zleadscrm_last_edit', current_time( 'timestamp' ) );

		update_user_meta( $user_id, 'zleadscrm_lead_author', get_current_user_id() );

		$user = new WP_User( $user_id );

		$user->remove_role( 'subscriber' );

		$user->add_role( 'lead' );

		zleadscrm_insert_lead_edit_audit( array(
			'action'    => 'Lead created',
			'old_value' => '-',
			'new_value' => '-',
			'user_id'   => $user_id,
			'editor_id' => get_current_user_id()
		) );

		if ( ! class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
			wp_safe_redirect( 'admin.php?page=zleadscrm_bookmarks&upd=1&edit_lead=' . $user_id . '&lead_tab=' . $redirect_tab );
			exit();
		} else {
			require_once( ZLEADSCRM_CUSTOM_FIELDS_PLUGIN_DIR . 'helper/class-zleadscrm-custom-fields-core-admin.php' );
			$adm_admin = new ZLEADSCRM_CUSTOM_FIELDS_Core_Admin();

			$adm_admin->add_new_lead_custom_fields( $user_id );
		}

	}

	public function edit_lead() {

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'zleadscrm_edit_lead' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks' );
			exit();
		}
		$redirect_tab = 'general';

		if ( isset( $_POST['redirect_tab'] ) ) {
			$redirect_tab = sanitize_text_field( $_POST['redirect_tab'] );
		}


		if ( isset( $_POST['user_id'] ) ) {
			$user_id = (int) sanitize_text_field( $_POST['user_id'] );
		} else {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks' . '&lead_tab=' . $redirect_tab );
			exit();
		}

		$user = get_user_by( 'id', $user_id );

		if ( isset( $_POST['zleadscrm_email_address'] ) ) {
			$old_value = $user->user_email;
			$new_value = sanitize_email( $_POST['zleadscrm_email_address'] );

			wp_update_user( array(
				'ID'         => $user_id,
				'user_email' => $new_value
			) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Email Address Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );

		}

		if ( isset( $_POST['zleadscrm_first_name'] ) ) {
			$old_value = $user->first_name;
			$new_value = sanitize_text_field( $_POST['zleadscrm_first_name'] );

			update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['zleadscrm_first_name'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'First Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_last_name'] ) ) {
			$old_value = $user->last_name;
			$new_value = sanitize_text_field( $_POST['zleadscrm_last_name'] );

			update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['zleadscrm_last_name'] ) );
			update_user_meta( $user_id, 'display_name', sanitize_text_field( $_POST['zleadscrm_first_name'] ) . ' ' . sanitize_text_field( $_POST['zleadscrm_last_name'] ) );


			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Last Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_title'] ) ) {
			$old_value = get_user_meta( $user_id, 'zleadscrm_title', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_title'] );

			update_user_meta( $user_id, 'zleadscrm_title', sanitize_text_field( $_POST['zleadscrm_title'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Title Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_business_name'] ) ) {
			$old_value = get_user_meta( $user_id, 'zleadscrm_business_name', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_business_name'] );

			update_user_meta( $user_id, 'zleadscrm_business_name', sanitize_text_field( $_POST['zleadscrm_business_name'] ) );


			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Business Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_address'] ) ) {
			$old_value = get_user_meta( $user_id, 'zleadscrm_address', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_address'] );

			update_user_meta( $user_id, 'zleadscrm_address', sanitize_text_field( $_POST['zleadscrm_address'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Address Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_lead_source'] ) && sanitize_text_field( $_POST['zleadscrm_lead_source'] ) != 'select' ) {
			$old_value = ucwords( str_replace( '_', ' ', get_user_meta( $user_id, 'zleadscrm_lead_source', true ) ) );
			$new_value = ucwords( str_replace( '_', ' ', sanitize_text_field( $_POST['zleadscrm_lead_source'] ) ) );

			update_user_meta( $user_id, 'zleadscrm_lead_source', sanitize_text_field( $_POST['zleadscrm_lead_source'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Lead Source Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_account_manager'] ) && sanitize_text_field( $_POST['zleadscrm_account_manager'] ) != 'select' ) {
			$old_value = get_user_by( 'id', get_user_meta( $user_id, 'zleadscrm_account_manager', true ) )->display_name;
			$new_value = get_user_by( 'id', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) )->display_name;

			if ( $new_value != $old_value ) {
				update_user_meta( $user_id, 'zleadscrm_account_manager', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );
				zacctmgr_insert_account_manager_assignment( array(
					'timestamp'   => current_time( 'mysql' ),
					'manager_id'  => sanitize_text_field( $_POST['zleadscrm_account_manager'] ),
					'customer_id' => $user_id
				) );
				update_user_meta( $user_id, 'zacctmgr_assigned', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );

				zleadscrm_insert_lead_edit_audit( array(
					'action'    => 'Account Manager Changed',
					'old_value' => $old_value,
					'new_value' => $new_value,
					'user_id'   => $user_id,
					'editor_id' => get_current_user_id()
				) );

				$send_account_manager_notification_email = get_option( 'zleadscrm_acm_assignment_notification_email', 'off' );

				if ( $send_account_manager_notification_email == 'on' && $new_value != $old_value ) {
					$account_manager = get_user_by( 'id', sanitize_text_field( $_POST['zleadscrm_account_manager'] ) );
					$user            = get_user_by( 'id', $user_id );
					$edit_url        = admin_url( 'admin.php' ) . '?page=zleadscrm_bookmarks&tab=leads&edit_lead=' . $user_id;
					$subject         = 'A new lead has been assigned to you!';
					$body            = 'A new lead ' . $user->last_name . ', ' . $user->first_name . ' has been assigned to you, <a href="' . esc_url( $edit_url ) . '">view lead</a> on ' . get_bloginfo( 'name' );

					add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
					wp_mail( $account_manager->user_email, $subject, $body );
					remove_filter( 'wp_mail_content_type', array(
						$this,
						'set_html_content_type'
					) );
				}

				$show_account_manager_banner = get_option( 'zleadscrm_show_acm_banner', 'off' );
				if ( $show_account_manager_banner == 'on' && $new_value != $old_value ) {
					update_user_meta( $user_id, 'zleadscrm_acm_notified', 'no' );
					update_user_meta( $user_id, 'zleadscrm_acm_assign_date', current_time( 'mysql' ) );
				}
			}
		}

		if ( isset( $_POST['zleadscrm_phone'] ) ) {
			$old_value = get_user_meta( $user_id, 'zleadscrm_phone', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_phone'] );

			update_user_meta( $user_id, 'zleadscrm_phone', sanitize_text_field( $_POST['zleadscrm_phone'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Phone Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_website'] ) ) {
			$old_value = $user->user_url;
			$new_value = sanitize_text_field( $_POST['zleadscrm_website'] );

			wp_update_user( array(
				'ID'       => $user_id,
				'user_url' => sanitize_text_field( $_POST['zleadscrm_website'] )
			) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Website Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_lead_status'] ) && $_POST['zleadscrm_lead_status'] != 'select' ) {
			$old_value = ucwords( str_replace( '_', ' ', get_user_meta( $user_id, 'zleadscrm_lead_status', true ) ) );
			$new_value = ucwords( str_replace( '_', ' ', sanitize_text_field( $_POST['zleadscrm_lead_status'] ) ) );

			update_user_meta( $user_id, 'zleadscrm_lead_status', sanitize_text_field( $_POST['zleadscrm_lead_status'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Lead Status Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_brands'] ) ) {
			$zleadscrm_brands_old = get_user_meta( $user_id, 'zleadscrm_brands', true );
			$zleadscrm_brands_new = zleadscrm_sanitize_array( $_POST['zleadscrm_brands'] );

			if ( count( $zleadscrm_brands_old ) > 0 ) {
				$old_value = $zleadscrm_brands_old[0] . ',';

				for ( $i = 1; $i < count( $zleadscrm_brands_old ); $i ++ ) {
					$old_value .= $zleadscrm_brands_old[ $i ] . ',';
				}
				$old_value .= $zleadscrm_brands_old[ count( $zleadscrm_brands_old ) ];
			} else {
				$old_value = '-';
			}

			if ( count( $zleadscrm_brands_new ) > 0 ) {
				$new_value = $zleadscrm_brands_new[0] . ',';

				for ( $i = 1; $i < count( $zleadscrm_brands_new ); $i ++ ) {
					$new_value .= $zleadscrm_brands_new[ $i ] . ',';
				}
				$new_value .= $zleadscrm_brands_new[ count( $zleadscrm_brands_new ) ];
			} else {
				$new_value = '-';
			}

			update_user_meta( $user_id, 'zleadscrm_brands', zleadscrm_sanitize_array( $_POST['zleadscrm_brands'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Brands Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_first_name'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_first_name', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_first_name'] );

			update_user_meta( $user_id, 'billing_first_name', sanitize_text_field( $_POST['zleadscrm_billing_first_name'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing First Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_last_name'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_last_name', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_last_name'] );

			update_user_meta( $user_id, 'billing_last_name', sanitize_text_field( $_POST['zleadscrm_billing_last_name'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Last Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_company'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_company', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_company'] );

			update_user_meta( $user_id, 'billing_company', sanitize_text_field( $_POST['zleadscrm_billing_company'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Company Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_address_1'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_address_1', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_address_1'] );

			update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $_POST['zleadscrm_billing_address_1'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Address 1 Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_address_2'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_address_2', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_address_2'] );

			update_user_meta( $user_id, 'billing_address_2', sanitize_text_field( $_POST['zleadscrm_billing_address_2'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Address 2 Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_city'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_city', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_city'] );

			update_user_meta( $user_id, 'billing_city', sanitize_text_field( $_POST['zleadscrm_billing_city'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing City Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_postcode'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_postcode', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_postcode'] );

			update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $_POST['zleadscrm_billing_postcode'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Postcode Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_country'] ) && $_POST['zleadscrm_billing_country'] != 'select' ) {
			$old_value = get_user_meta( $user_id, 'billing_country', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_country'] );

			update_user_meta( $user_id, 'billing_country', sanitize_text_field( $_POST['zleadscrm_billing_country'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Country Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_state'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_state', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_state'] );

			update_user_meta( $user_id, 'billing_state', sanitize_text_field( $_POST['zleadscrm_billing_state'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing State Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_email'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_email', true );
			$new_value = sanitize_email( $_POST['zleadscrm_billing_email'] );

			update_user_meta( $user_id, 'billing_email', sanitize_email( $_POST['zleadscrm_billing_email'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Email Address Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_phone'] ) ) {
			$old_value = get_user_meta( $user_id, 'billing_phone', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_billing_phone'] );

			update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['zleadscrm_billing_phone'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Billing Phone Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_billing_payment_method'] ) ) {
			$old_value = ucwords( str_replace( '_', ' ', get_user_meta( $user_id, 'zleadscrm_billing_payment_method', true ) ) );
			$new_value = ucwords( str_replace( '_', ' ', sanitize_text_field( $_POST['zleadscrm_billing_payment_method'] ) ) );

			update_user_meta( $user_id, 'zleadscrm_billing_payment_method', sanitize_text_field( $_POST['zleadscrm_billing_payment_method'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Payment Method Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_first_name'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_first_name', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_first_name'] );


			update_user_meta( $user_id, 'shipping_first_name', sanitize_text_field( $_POST['zleadscrm_shipping_first_name'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping First Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_last_name'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_last_name', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_last_name'] );

			update_user_meta( $user_id, 'shipping_last_name', sanitize_text_field( $_POST['zleadscrm_shipping_last_name'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Last Name Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_company'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_company', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_company'] );

			update_user_meta( $user_id, 'shipping_company', sanitize_text_field( $_POST['zleadscrm_shipping_company'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Company Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_address_1'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_address_1', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_address_1'] );

			update_user_meta( $user_id, 'shipping_address_1', sanitize_text_field( $_POST['zleadscrm_shipping_address_1'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Address 1 Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_address_2'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_address_2', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_address_2'] );

			update_user_meta( $user_id, 'shipping_address_2', sanitize_text_field( $_POST['zleadscrm_shipping_address_2'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Address 2 Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_city'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_city', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_city'] );

			update_user_meta( $user_id, 'shipping_city', sanitize_text_field( $_POST['zleadscrm_shipping_city'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping City Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_postcode'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_postcode', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_postcode'] );

			update_user_meta( $user_id, 'shipping_postcode', sanitize_text_field( $_POST['zleadscrm_shipping_postcode'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Postcode Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_country'] ) && sanitize_text_field( $_POST['zleadscrm_shipping_country'] ) != 'select' ) {
			$old_value = get_user_meta( $user_id, 'shipping_country', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_country'] );

			update_user_meta( $user_id, 'shipping_country', sanitize_text_field( $_POST['zleadscrm_shipping_country'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping Country Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_shipping_state'] ) ) {
			$old_value = get_user_meta( $user_id, 'shipping_state', true );
			$new_value = sanitize_text_field( $_POST['zleadscrm_shipping_state'] );

			update_user_meta( $user_id, 'shipping_state', sanitize_text_field( $_POST['zleadscrm_shipping_state'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Shipping State Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_lead_customer_status'] ) ) {
			$old_value = ucfirst( get_user_meta( $user_id, 'zleadscrm_lead_customer_status', true ) );
			$new_value = ucfirst( sanitize_text_field( $_POST['zleadscrm_lead_customer_status'] ) );

			update_user_meta( $user_id, 'zleadscrm_lead_customer_status', sanitize_text_field( $_POST['zleadscrm_lead_customer_status'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Lead Customer Status Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_company_annual_revenue_size'] ) ) {
			$old_value = ucfirst( get_user_meta( $user_id, 'zleadscrm_company_annual_revenue_size', true ) );
			$new_value = ucfirst( sanitize_text_field( $_POST['zleadscrm_company_annual_revenue_size'] ) );

			update_user_meta( $user_id, 'zleadscrm_company_annual_revenue_size', sanitize_text_field( $_POST['zleadscrm_company_annual_revenue_size'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Company Annual Revenue Size Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_order_monthly_volume_oportunity'] ) ) {
			$old_value = ucfirst( get_user_meta( $user_id, 'zleadscrm_order_monthly_volume_oportunity', true ) );
			$new_value = ucfirst( sanitize_text_field( $_POST['zleadscrm_order_monthly_volume_oportunity'] ) );

			update_user_meta( $user_id, 'zleadscrm_order_monthly_volume_oportunity', sanitize_text_field( $_POST['zleadscrm_order_monthly_volume_oportunity'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Order Monthly Volume Oportunity Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		if ( isset( $_POST['zleadscrm_short_description'] ) ) {
			$old_value = get_user_meta( $user_id, 'description', true );
			$new_value = sanitize_textarea_field( $_POST['zleadscrm_short_description'] );

			update_user_meta( $user_id, 'description', sanitize_textarea_field( $_POST['zleadscrm_short_description'] ) );

			zleadscrm_insert_lead_edit_audit( array(
				'action'    => 'Short Description Changed',
				'old_value' => $old_value,
				'new_value' => $new_value,
				'user_id'   => $user_id,
				'editor_id' => get_current_user_id()
			) );
		}

		update_user_meta( $user_id, 'zleadscrm_last_edit', current_time( 'timestamp' ) );

		if ( ! class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_bookmarks&upd=1&edit_lead=' . $user_id . '&lead_tab=' . $redirect_tab );
			exit();
		} else {
			require_once( ZLEADSCRM_CUSTOM_FIELDS_PLUGIN_DIR . 'helper/class-zleadscrm-custom-fields-core-admin.php' );
			$adm_admin = new ZLEADSCRM_CUSTOM_FIELDS_Core_Admin();

			$adm_admin->edit_lead_custom_fields( $user_id );
		}

	}

	public function set_html_content_type() {
		return 'text/html;  charset=utf-8';
	}

	public function edit_settings() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'zleadscrm_edit_settings' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_settings' );
			exit();
		}

		if ( isset( $_POST['zleadscrm_roles'] ) ) {
			$selected_roles = zleadscrm_sanitize_array( $_POST['zleadscrm_roles'] );
			if ( $selected_roles && is_array( $selected_roles ) && count( $selected_roles ) > 0 ) {
				update_option( 'zleadscrm_selected_roles', zleadscrm_sanitize_array( $selected_roles ) );
			}
		}

		if ( isset( $_POST['zleadscrm_currency'] ) ) {
			update_option( 'zleadscrm_currency', sanitize_text_field( $_POST['zleadscrm_currency'] ) );
		}

		if ( isset( $_POST['zleadscrm_country'] ) ) {
			update_option( 'zleadscrm_default_country', sanitize_text_field( $_POST['zleadscrm_country'] ) );
		}

		if ( isset( $_POST['zleadscrm_new_user_email_notification'] ) ) {
			update_option( 'zleadscrm_new_user_email_notification', sanitize_text_field( $_POST['zleadscrm_new_user_email_notification'] ) );
		} else {
			update_option( 'zleadscrm_new_user_email_notification', 'off' );
		}

		if ( isset( $_POST['zleadscrm_debug_mode'] ) ) {
			update_option( 'zleadscrm_debug_mode', sanitize_text_field( $_POST['zleadscrm_debug_mode'] ) );
		} else {
			update_option( 'zleadscrm_debug_mode', 'off' );
		}

		if ( isset( $_POST['zleadscrm_show_acm_banner'] ) ) {
			update_option( 'zleadscrm_show_acm_banner', sanitize_text_field( $_POST['zleadscrm_show_acm_banner'] ) );
		} else {
			update_option( 'zleadscrm_show_acm_banner', 'off' );
		}

		if ( isset( $_POST['zleadscrm_acm_assignment_notification_email'] ) ) {
			update_option( 'zleadscrm_acm_assignment_notification_email', sanitize_text_field( $_POST['zleadscrm_acm_assignment_notification_email'] ) );
		} else {
			update_option( 'zleadscrm_acm_assignment_notification_email', 'off' );
		}

		if ( isset( $_POST['zleadscrm_hide_settings_in_menu'] ) ) {
			update_option( 'zleadscrm_hide_settings_in_menu', 1 );
		} else {
			update_option( 'zleadscrm_hide_settings_in_menu', 0 );
		}

		if ( isset( $_POST['zleadscrm_user_access_settings'] ) ) {
			update_option( 'zleadscrm_user_access_settings', sanitize_text_field( $_POST['zleadscrm_user_access_settings'] ) );
		}

		if ( isset( $_POST['zleadscrm_users_allowed_access_analytics_setting'] ) ) {
			update_option( 'zleadscrm_users_allowed_access_analytics_setting', sanitize_text_field( $_POST['zleadscrm_users_allowed_access_analytics_setting'] ) );
		}

		if ( isset( $_POST['zleadscrm_allowed_access_analytics_users'] ) ) {
			update_option( 'zleadscrm_allowed_access_analytics_users', zleadscrm_sanitize_array( $_POST['zleadscrm_allowed_access_analytics_users'] ) );
		}

		if ( ! class_exists( 'ZLEADSCRM_EXPORT_Core' ) && ! class_exists( 'ZLEADSCRM_PIPELINE_Core' ) && ! class_exists( 'ZLEADSCRM_PROSPECT_Core' ) && ! class_exists( 'ZLEADSCRM_ANALYTICS_Core' ) ) {
			wp_redirect( 'admin.php?page=zleadscrm_settings&_wpnonce=' . $_POST['_wpnonce'] );
			exit();
		}

	}

	public function load_resources() {
		global $wp_scripts;

		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_register_style( 'jquery-ui', plugins_url( 'css/jquery-ui.css', ZLEADSCRM_BASIC_BASE_FILE ) );
		wp_enqueue_style( 'jquery-ui' );

		if ( class_exists( 'WooCommerce' ) ) {
			wp_register_style( 'woocommerce_admin_menu_styles', WC()->plugin_url() . '/assets/css/menu.css', array(), WC_VERSION );
			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
			wp_register_style( 'jquery-ui-style', WC()->plugin_url() . '/assets/css/jquery-ui/jquery-ui.min.css', array(), WC_VERSION );
			wp_register_style( 'woocommerce_admin_dashboard_styles', WC()->plugin_url() . '/assets/css/dashboard.css', array(), WC_VERSION );
			wp_register_style( 'woocommerce_admin_print_reports_styles', WC()->plugin_url() . '/assets/css/reports-print.css', array(), WC_VERSION, 'print' );
		}

		// Add RTL support for admin styles.
		wp_style_add_data( 'woocommerce_admin_menu_styles', 'rtl', 'replace' );
		wp_style_add_data( 'woocommerce_admin_styles', 'rtl', 'replace' );
		wp_style_add_data( 'woocommerce_admin_dashboard_styles', 'rtl', 'replace' );
		wp_style_add_data( 'woocommerce_admin_print_reports_styles', 'rtl', 'replace' );

		// Sitewide menu CSS.
		wp_enqueue_style( 'woocommerce_admin_menu_styles' );

		// @deprecated 2.3.
		if ( has_action( 'woocommerce_admin_css' ) ) {
			do_action( 'woocommerce_admin_css' );
			wc_deprecated_function( 'The woocommerce_admin_css action', '2.3', 'admin_enqueue_scripts' );
		}


		// Register admin styles.
		wp_register_style( 'select2_style', plugins_url( 'css/select2.min.css', ZLEADSCRM_BASIC_BASE_FILE ), array(), '1.0.0' );
		wp_register_style( 'zleadscrm_style', plugins_url( 'css/style.css', ZLEADSCRM_BASIC_BASE_FILE ), array(), '1.0.1' );

		wp_enqueue_style( 'select2_style' );
		wp_enqueue_style( 'zleadscrm_style' );

		$register_scripts = array(
			'select2_script'   => array(
				'src'     => plugins_url( 'js/select2.js', ZLEADSCRM_BASIC_BASE_FILE ),
				'deps'    => array( 'jquery' ),
				'version' => '1.0.0'
			),
			'zleadscrm_script' => array(
				'src'     => plugins_url( 'js/script.js', ZLEADSCRM_BASIC_BASE_FILE ),
				'deps'    => array( 'jquery' ),
				'version' => '1.0.1',
			)
		);

		foreach ( $register_scripts as $name => $props ) {
			wp_register_script( $name, $props['src'], $props['deps'], $props['version'], true );
		}
		wp_enqueue_script( 'select2_script' );
		wp_enqueue_script( 'zleadscrm_script' );

		wp_localize_script( 'ajax-script', 'ajax_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'we_value' => 1234
		) );
	}

	public function ajax_search_admins() {
		$managers = array();
		$data     = array();

		if ( isset( $_REQUEST['search'] ) ) {
			$search = sanitize_text_field( $_REQUEST['search'] );

			$query    = zleadscrm_get_admins_query_by_key( $search );
			$managers = $query->get_results();
			if ( $managers && count( $managers ) > 0 ) {
				foreach ( $managers as $manager ) {
					$temp['id']         = $manager->ID;
					$temp['first_name'] = $manager->first_name;
					$temp['last_name']  = $manager->last_name;

					$data[] = $temp;
				}
			}
		} else {
			$query    = zleadscrm_get_admins_query_by_key();
			$managers = $query->get_results();

			if ( $managers && count( $managers ) > 0 ) {
				foreach ( $managers as $manager ) {
					$temp['id']         = $manager->ID;
					$temp['first_name'] = $manager->first_name;
					$temp['last_name']  = $manager->last_name;

					$data[] = $temp;
				}
			}
		}

		exit( json_encode( $data ) );
	}

	public function admin_menu() {

		add_menu_page( 'Leads', 'Leads', 'read', 'zleadscrm_bookmarks', array(
			$this,
			'show_zleadscrm_bookmarks'
		), 'dashicons-index-card', 70 );


		add_submenu_page( 'zleadscrm_bookmarks', 'Leads', 'Leads', 'read', 'zleadscrm_bookmarks', array(
			$this,
			'show_zleadscrm_bookmarks'
		) );

		if ( zleadscrm_user_can_access_analytics( get_current_user_id() ) ) {
			add_submenu_page( 'zleadscrm_bookmarks', 'Analytics', 'Analytics', 'read', 'zleadscrm_insights', array(
				$this,
				'show_zleadscrm_insights'
			) );
		}

		add_submenu_page( 'zleadscrm_bookmarks', 'Add-on Plugins', 'Add-ons', 'read', 'zleadscrm_addons', array(
			$this,
			'show_zleadscrm_addons'
		) );


		if ( $this->zleadscrm_show_settings_menu() ) {
			add_submenu_page( 'zleadscrm_bookmarks', 'Settings', 'Settings', 'read', 'zleadscrm_settings', array(
				$this,
				'show_zleadscrm_settings'
			) );
		}

		$zleadscrm_debug_mode = get_option( 'zleadscrm_debug_mode', 'off' );

		if ( $zleadscrm_debug_mode == 'on' ) {
			add_submenu_page( 'zleadscrm_bookmarks', 'Debug Mode', 'Debug Mode', 'read', 'zleadscrm_debug_mode', array(
				$this,
				'show_zleadscrm_debug_mode'
			) );
		}

		zleadscrm_sort_zleadscrm_menu();

	}

	public function zleadscrm_show_settings_menu() {
		$zleadscrm_hide_settings_in_menu = zleadscrm_get_hide_settings_in_menu();
		$zleadscrm_user_access_settings  = zleadscrm_get_user_access_settings();
		if ( $zleadscrm_hide_settings_in_menu == 0 ) {
			return true;
		}

		if ( $zleadscrm_user_access_settings == 'administrators' ) {
			return current_user_can( 'administrator' );
		}

		if ( $zleadscrm_user_access_settings == 'manage_options' ) {
			return current_user_can( 'manage_options' );
		}

		if ( is_numeric( $zleadscrm_user_access_settings ) ) {
			return get_current_user_id() == $zleadscrm_user_access_settings;
		}
	}

	public function show_zleadscrm_settings() { // Settings Page
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/settings.php' );
	}

	public function show_zleadscrm_debug_mode() { // Settings Page
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/debug-mode.php' );
	}

	public function show_zleadscrm_addons() { // Settings Page
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/add-ons.php' );
	}

	public function show_zleadscrm_bookmarks() { // Leads Page
		if ( ! class_exists( 'ZLEADSCRM_PROSPECT_Core' ) ) {
			include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/leads.php' );
		}
	}

	public function show_zleadscrm_insights() { // Insights Page
		if ( ! class_exists( 'ZLEADSCRM_PROSPECT_Core' ) && ! class_exists( 'ZLEADSCRM_ANALYTICS_Core' ) ) {
			include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/insights.php' );
		}
	}

	public function show_zleadscrm_bookmarks_tab( $current ) {
		$tabs = array(
			'leads' => 'Leads'
		);

		$html = '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? 'nav-tab-active' : '';
			$html  .= '<a class="nav-tab ' . $class . '" href="?page=zleadscrm_bookmarks&tab=' . $tab . '">' . $name . '</a>';
		}
		$html .= '</h2>';

		echo $html;
	}

	public function show_zleadscrm_analytics_tab( $current ) {
		$tabs = array(
			'my_analytics' => 'My Analytics'
		);

		if ( zleadscrm_user_can_view_company_analytics( get_current_user_id() ) ) {
			$tabs['company_analytics'] = 'Company Analytics';
		}

		$html = '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? 'nav-tab-active' : '';
			$html  .= '<a class="nav-tab ' . $class . '" href="?page=zleadscrm_insights&tab=' . $tab . '">' . $name . '</a>';
		}
		$html .= '</h2>';

		echo $html;
	}
}

?>