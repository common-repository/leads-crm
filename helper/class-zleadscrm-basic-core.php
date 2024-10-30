<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ZLEADSCRM_BASIC_Core {
	private static $initiated = false;

	public static function init() {
		$query = new WP_User_Query( array(
			'role'       => 'lead',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'   => 'zleadscrm_acm_notified',
					'value' => 'no'
				),
				array(
					'key'   => 'zleadscrm_account_manager',
					'value' => get_current_user_id()
				)
			)
		) );

		$users = $query->get_results();

		if ( count( $users ) != 0 ) {
			foreach ( $users as $user ) {
				$acm_assign_date = get_user_meta( $user->ID, 'zleadscrm_acm_assign_date', true );
				if ( date( 'Y-m-d', strtotime( $acm_assign_date . "+7 days" ) ) <= date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) ) ) {
					update_user_meta( $user->ID, 'zleadscrm_acm_notified', 'yes' );
				}
			}

			$notifications = [];

			foreach ( $users as $user ) {
				if ( get_user_meta( $user->ID, 'zleadscrm_acm_notified', true ) == 'no' ) {
					$notifications[] = '<div class="notice notice-success is-dismissible acm_lead_notification" data-user="' . $user->ID . '" data-link="' . admin_url( 'admin-post.php' ) . '" data-redirect="' . add_query_arg() . '"><p>A new lead <b>' . $user->last_name . ' , ' . $user->first_name . '</b> has been assigned to you, <a data-user="' . $user->ID . '" data-redirect="' . admin_url( "admin.php" ) . '?page=zleadscrm_bookmarks&tab=leads&edit_lead=' . $user->ID . '" data-link="' . admin_url( 'admin-post.php' ) . '" class="acm_lead_notification_link"  href="#" >view lead</a></p></div>';
				}
			}

			foreach ( $notifications as $notification ) {
				add_action( 'admin_notices', function () use ( $notification ) {
					echo $notification;
				} );
			}

		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			$current_user_id            = get_current_user_id();
			$dismissed_notice_timestamp = get_option( 'zleadscrm_wc_notice_' . $current_user_id, null );
			if ( isset( $dismissed_notice_timestamp ) ) {
				$dismissed_notice_date = date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d H:i:s', $dismissed_notice_timestamp ) . '+15 days' ) );
				$today                 = date( 'Y-m-d H:i:s' );

				if ( $dismissed_notice_date <= $today ) {
					add_action( 'admin_notices', function () {
						?>
                        <div class="notice notice-error is-dismissible zleadscrm_wc_notice"
                             data-link="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                            <p>To enable more advanced features for Leads CRM WordPress WooCommerce activate the
                                WooCommerce
                                plugin</p>
                        </div>
						<?php
					} );
				}
			} else {
				add_action( 'admin_notices', function () {
					?>
                    <div class="notice notice-error is-dismissible zleadscrm_wc_notice"
                         data-link="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                        <p>To enable more advanced features for Leads CRM WordPress WooCommerce activate the
                            WooCommerce
                            plugin</p>
                    </div>
					<?php
				} );
			}
		}

		if ( ! class_exists( 'ZACCTMGR_Core' ) ) {
			$current_user_id            = get_current_user_id();
			$dismissed_notice_timestamp = get_option( 'zleadscrm_acm_notice_' . $current_user_id, null );
			if ( isset( $dismissed_notice_timestamp ) ) {
				$dismissed_notice_date = date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d H:i:s', $dismissed_notice_timestamp ) . '+15 days' ) );
				$today                 = date( 'Y-m-d H:i:s' );

				if ( $dismissed_notice_date <= $today ) {
					add_action( 'admin_notices', function () {
						?>
                        <div class="notice notice-error is-dismissible zleadscrm_zacctmgr_notice"
                             data-link="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                            <p>To enable more advanced features for Leads CRM WordPress WooCommerce activate the
                                Account
                                Manager plugin</p>
                        </div>
						<?php
					} );
				}
			} else {
				add_action( 'admin_notices', function () {
					?>
                    <div class="notice notice-error is-dismissible zleadscrm_zacctmgr_notice"
                         data-link="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                        <p>To enable more advanced features for Leads CRM WordPress WooCommerce activate the Account
                            Manager plugin</p>
                    </div>
					<?php
				} );
			}
		}


		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
	}

	public static function plugin_activation() {
		self::create_leads_bookmarks_table();
		self::create_leads_user_role();
		$statistic_date = get_option( 'zleadscrm_statistics_date', '' );
		if ( $statistic_date == '' ) {
			update_option( 'zleadscrm_statistics_date', current_time( 'mysql' ) );
		}
		self::create_leads_user_analytics_table();
		self::create_leads_edit_audit_table();
		self::initialize_lead_archived_status();
		self::create_leads_interactions_table();
	}

	public static function plugin_deactivation() {
	}

	public static function initialize_lead_archived_status() {
		$users = get_users( array(
			'role' => 'lead'
		) );

		foreach ( $users as $user ) {
			if ( get_user_meta( $user->ID, 'zleadscrm_lead_archive_status', true ) == '' ) {
				update_user_meta( $user->ID, 'zleadscrm_lead_archive_status', 'false' );
			}
		}
	}

	public static function create_leads_edit_audit_table() {
		global $wpdb;

		$table_name_order_audit = $wpdb->prefix . 'zleadscrm_leads_edit_audit';

		$charset_collate = $wpdb->get_charset_collate();

		$sql_assignments = "CREATE TABLE $table_name_order_audit(
							id mediumint( 9 ) NOT null AUTO_INCREMENT,
				timestamp datetime NOT null,
				editor_id mediumint( 9 ) NOT null,
				user_id mediumint( 9 ) NOT null,
				old_value text NOT null,
				new_value text NOT null,
				action text NOT null,
				PRIMARY KEY( id )
			)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_assignments );
	}

	public static function create_leads_interactions_table() {
		global $wpdb;

		$table_name_lead_interactions = $wpdb->prefix . 'zleadscrm_leads_interactions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql_assignments = "CREATE TABLE $table_name_lead_interactions(
				id mediumint( 9 ) NOT null AUTO_INCREMENT,
				timestamp datetime NOT null,
				author_id mediumint( 9 ) NOT null,
				lead_id mediumint( 9 ) NOT null,
				message text NOT null,
				PRIMARY KEY( id )
			)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_assignments );
	}

	public static function create_leads_results_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'zleadscrm_leads_results';
		$charset_collate = $wpdb->get_charset_collate();

		$sql_query = "CREATE TABLE $table_name (
							id mediumint( 9 ) NOT null AUTO_INCREMENT,
				timestamp datetime NOT null,
				user_id mediumint( 9 ) NOT null,
				business_name text NOT null,
				business_type text NOT null,
				phone text NOT null,
				website text NOT null,
				address text NOT null,
				url text NOT null,
				PRIMARY KEY( id )
			)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_query );
	}

	public static function create_leads_bookmarks_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'zleadscrm_leads_bookmarks';
		$charset_collate = $wpdb->get_charset_collate();

		$sql_query = "CREATE TABLE $table_name (
							id mediumint( 9 ) NOT null AUTO_INCREMENT, 
				timestamp datetime NOT null,
				business_name text NOT null,
				business_type text NOT null,
				phone text NOT null,
				website text NOT null,
				address text NOT null,
				owner_id mediumint( 9 )  NOT null,
				url text NOT null,
				PRIMARY KEY( id )
			)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_query );
	}

	public static function create_leads_user_analytics_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'zleadscrm_leads_user_analytics';
		$charset_collate = $wpdb->get_charset_collate();

		$sql_query = "CREATE TABLE $table_name (
							id mediumint( 9 ) NOT null AUTO_INCREMENT,
				timestamp datetime NOT null,
				user_id mediumint( 9 )  NOT null,
				basic_requests mediumint( 9 ) NOT null,
				basic_results mediumint( 9 ) NOT null,
				advanced_requests mediumint( 9 ) NOT null,
				advanced_results mediumint( 9 ) NOT null,
				PRIMARY KEY( id )
			)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_query );
	}

	public static function create_leads_user_role() {
		add_role( 'lead', 'Lead', array( 'read' => true, 'edit_posts' => true ) );
	}
}

?>