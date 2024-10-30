<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$tab       = ( ! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'leads';
$add_new   = ( ! empty( $_GET['add_new'] ) ) ? sanitize_text_field( $_GET['add_new'] ) : 0;
$edit_lead = ( ! empty( $_GET['edit_lead'] ) ) ? sanitize_text_field( $_GET['edit_lead'] ) : 0;

if ( zleadscrm_allow_view_page() == false ) {
	echo '<div class="zleadscrm_not_allowed_wrap">';
	echo '<p>Sorry, you are not allowed to access this page!</p>';
	echo '</div>';
	exit;
}

?>
<style>
    @media all and (max-width: 737px) {
        th.column-actions, td.column-actions {
            text-align: left !important;
        }
    }
</style>
<div class="wrap">
    <hr class="wp-header-end"/>
    <div class="zleadscrm_row zleadscrm_col_12" style="align-items: baseline;">
        <h1 style="display: inline-block; margin:5px;" class="zleadscrm_mobile_grow_100">Leads</h1>
		<?php if ( $add_new == 0 && $edit_lead == 0 ): ?>
        <a href="<?php echo admin_url( 'admin.php' ) . '?page=zleadscrm_bookmarks&add_new=1'; ?>"
           class="page-title-action zleadscrm_mobile_grow_35"
           style="margin: 5px;">Add Lead</a>
		<?php if ( $tab == 'leads' ) {
			if ( ! class_exists( 'ZLEADSCRM_EXPORT_Core' ) ) {
				if ( ! class_exists( 'ZLEADSCRM_BASIC_Core_Leads_Users' ) ) {
					require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-core-leads-users.php' );
				}
			} else {
				if ( ! class_exists( 'ZLEADSCRM_EXPORT_BASIC_Core_Leads_Users' ) ) {
					require_once( ZLEADSCRM_EXPORT_PLUGIN_DIR . 'helper/class-zleadscrm-core-leads-users.php' );
				}
			}

			if ( class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
				if ( zleadscrm_user_can_access_custom_fields( get_current_user_id() ) ) {
					echo '<a style="margin: 5px;"  href="' . admin_url( 'admin.php' ) . '?page=zleadscrm_custom_fields" class="page-title-action zleadscrm_mobile_grow_35">Custom Fields</a>';
				}
			}

			if ( class_exists( 'ZLEADSCRM_EXPORT_Core' ) ) {
				$zleadscrm_lead_users = new ZLEADSCRM_EXPORT_BASIC_Core_Leads_Users();
				$zleadscrm_lead_users->prepare_items();
				if ( count( $zleadscrm_lead_users->items ) != 0 ) {
					if ( zleadscrm_user_can_export_bookmarks( get_current_user_id() ) ) {
						echo '<a href="data:text/csv;charset=utf-8,' . $zleadscrm_lead_users->get_export_data() . '" download="export_leads.csv"  class="page-title-action zleadscrm_mobile_grow_35" style="margin: 5px;">Export</a>';
					}
				}
			} else {
				$zleadscrm_lead_users = new ZLEADSCRM_BASIC_Core_Leads_Users();
				$zleadscrm_lead_users->prepare_items();
			}

			if ( class_exists( 'ZLEADSCRM_IMPORT_Core' ) ) {
				if ( zleadscrm_user_can_import_leads( get_current_user_id() ) ) {
					echo '<a style="margin: 5px;"  href="' . admin_url( 'admin.php' ) . '?page=zleadscrm_import_leads" class="page-title-action zleadscrm_mobile_grow_35">Import</a>';
				}
			}

		} ?>
    </div>
	<?php elseif ( $add_new == 1 || $edit_lead != 0 ): ?>
        <div class="zleadscrm_mobile_grow_100" style="display: inline-block;">
            <a href="<?php echo admin_url( 'admin.php' ) . '?page=zleadscrm_bookmarks&tab=leads'; ?>"
               class="zleadscrm_goback_btn"><span class="dashicons dashicons-undo"></span></a>
        </div>
	<?php endif; ?>
	<?php
	if ( $add_new == 1 || $edit_lead != 0 ) {
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/add-new-lead/add_new_lead.php' );
	} else {
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/leads.php' );
	}
	?>
</div>
