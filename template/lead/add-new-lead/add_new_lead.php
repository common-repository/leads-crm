<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$add_new   = ( ! empty( $_GET['add_new'] ) ) ? sanitize_text_field( $_GET['add_new'] ) : 0;
$edit_lead = ( ! empty( $_GET['edit_lead'] ) ) ? sanitize_text_field( $_GET['edit_lead'] ) : 0;
$updated   = ( ! empty( $_GET['upd'] ) ) ? sanitize_text_field( $_GET['upd'] ) : 0;
$tab       = ( ! empty( $_GET['lead_tab'] ) ) ? sanitize_text_field( $_GET['lead_tab'] ) : 'general';
if ( $updated == '1' ): ?>
    <div id="lead_updated_notice" class="zleadscrm_updated_notice" style="display: block; width: 91%;">
        <p><strong>Lead Updated.</strong></p>
        <p><a href="<?php echo admin_url( 'admin.php' ) . '?page=zleadscrm_bookmarks'; ?>">← Back to Leads</a></p>
    </div>
<?php endif;
if ( $add_new == 1 ) {

	$from_bookmark        = isset( $_GET['from_bookmark'] ) ? sanitize_text_field( $_GET['from_bookmark'] ) : 0;
	$business_name        = isset( $_GET['business_name'] ) ? str_replace( '%amp', '&', str_replace( '\\', '', sanitize_text_field( $_GET['business_name'] ) ) ) : '';
	$mobile               = isset( $_GET['mobile'] ) ? sanitize_text_field( $_GET['mobile'] ) : '';
	$website              = isset( $_GET['website'] ) ? sanitize_text_field( $_GET['website'] ) : '';
	$account_manager_link = isset( $_GET['account_manager'] ) ? sanitize_text_field( $_GET['account_manager'] ) : 'select';
	$address              = isset( $_GET['address'] ) ? sanitize_text_field( $_GET['address'] ) : '';

	$billing_company         = $business_name;
	$billing_first_name      = '';
	$billing_last_name       = '';
	$billing_address_1       = '';
	$billing_address_2       = '';
	$billing_city            = '';
	$billing_state_post_code = '';
	$billing_state           = '';
	$billing_postcode        = '';
	$billing_phone           = $mobile;
	$billing_email           = '';
	$billing_country         = get_option( 'zleadscrm_default_country', 'US' );
	$billing_payment_method  = 'n/a';

	$shipping_company    = $business_name;
	$shipping_first_name = '';
	$shipping_last_name  = '';
	$shipping_address_1  = '';
	$shipping_address_2  = '';
	$shipping_city       = '';
	$shipping_state      = '';
	$shipping_postcode   = '';
	$shipping_country    = get_option( 'zleadscrm_default_country', 'US' );


	$lead_first_name      = '';
	$lead_last_name       = '';
	$lead_title           = '';
	$lead_source          = '';
	$lead_email_address   = '';
	$lead_status          = '';
	$lead_customer_status = '';
	$lead_description     = '';
	$lead_brands          = [];

	$order_monthly_volume_opportunity = '';
	$company_annual_revenue_size      = '';


	if ( $from_bookmark == 1 ) {
		$billing_address_split = explode( ',', $address );
		if ( count( $billing_address_split ) == 4 ) {
			$billing_address_1             = $billing_address_split[0];
			$billing_city                  = substr( $billing_address_split[1], 1 );
			$billing_state_post_code       = substr( $billing_address_split[2], 1 );
			$billing_state_post_code_split = explode( ' ', $billing_state_post_code );
			$billing_state                 = $billing_state_post_code_split[0];
			$billing_postcode              = $billing_state_post_code_split[1];
			$billing_country               = substr( $billing_address_split[3], 1 );
			if ( $billing_country == 'USA' ) {
				$billing_country = 'US';
			}
			$shipping_address_1 = $billing_address_1;
			$shipping_city      = $billing_city;
			$shipping_state     = $billing_state;
			$shipping_postcode  = $billing_postcode;
			$shipping_country   = $billing_country;
			if ( $shipping_country == 'USA' ) {
				$shipping_country = 'US';
			}
		}
	}
} else if ( $edit_lead != 0 ) {
	$user    = get_user_by( 'id', $edit_lead );
	$user_id = $edit_lead;

	$business_name        = get_user_meta( $user_id, 'zleadscrm_business_name', true );
	$mobile               = get_user_meta( $user_id, 'zleadscrm_phone', true );
	$website              = $user->user_url;
	$account_manager_link = get_user_meta( $user_id, 'zleadscrm_account_manager', true );
	$address              = get_user_meta( $user_id, 'zleadscrm_address', true );

	$billing_company        = get_user_meta( $user_id, 'billing_company', true );
	$billing_first_name     = get_user_meta( $user_id, 'billing_first_name', true );
	$billing_last_name      = get_user_meta( $user_id, 'billing_last_name', true );
	$billing_address_1      = get_user_meta( $user_id, 'billing_address_1', true );
	$billing_address_2      = get_user_meta( $user_id, 'billing_address_2', true );
	$billing_city           = get_user_meta( $user_id, 'billing_city', true );
	$billing_state          = get_user_meta( $user_id, 'billing_state', true );
	$billing_postcode       = get_user_meta( $user_id, 'billing_postcode', true );
	$billing_phone          = get_user_meta( $user_id, 'billing_phone', true );
	$billing_email          = get_user_meta( $user_id, 'billing_email', true );
	$billing_country        = get_user_meta( $user_id, 'billing_country', true );
	$billing_payment_method = get_user_meta( $user_id, 'zleadscrm_billing_payment_method', true );


	$shipping_company    = get_user_meta( $user_id, 'shipping_company', true );
	$shipping_first_name = get_user_meta( $user_id, 'shipping_first_name', true );
	$shipping_last_name  = get_user_meta( $user_id, 'shipping_last_name', true );
	$shipping_address_1  = get_user_meta( $user_id, 'shipping_address_1', true );
	$shipping_address_2  = get_user_meta( $user_id, 'shipping_address_2', true );
	$shipping_city       = get_user_meta( $user_id, 'shipping_city', true );
	$shipping_state      = get_user_meta( $user_id, 'shipping_state', true );
	$shipping_postcode   = get_user_meta( $user_id, 'shipping_postcode', true );
	$shipping_country    = get_user_meta( $user_id, 'shipping_country', true );


	$lead_first_name      = get_user_meta( $user_id, 'first_name', true );
	$lead_last_name       = get_user_meta( $user_id, 'last_name', true );
	$lead_title           = get_user_meta( $user_id, 'zleadscrm_title', true );
	$lead_source          = get_user_meta( $user_id, 'zleadscrm_lead_source', true );
	$lead_email_address   = $user->user_email;
	$lead_status          = get_user_meta( $user_id, 'zleadscrm_lead_status', true );
	$lead_customer_status = get_user_meta( $user_id, 'zleadscrm_lead_customer_status', true );
	$lead_description     = get_user_meta( $user_id, 'description', true );
	$lead_brands          = get_user_meta( $user_id, 'zleadscrm_brands', true );


	$order_monthly_volume_opportunity = get_user_meta( $user_id, 'zleadscrm_order_monthly_volume_oportunity', true );
	$company_annual_revenue_size      = get_user_meta( $user_id, 'zleadscrm_company_annual_revenue_size', true );
}


?>

<h2 class="nav-tab-wrapper" style="width: 100%;">
    <a class="nav-tab <?php echo $tab == 'general' ? 'nav-tab-active' : ''; ?>"
       href="<?php echo add_query_arg( 'lead_tab', 'general', remove_query_arg( 'upd' ) ); ?>">General</a>
	<?php if ( $add_new != 1 ): ?>
        <a class="nav-tab <?php echo $tab == 'interactions' ? 'nav-tab-active' : ''; ?>"
           href="<?php echo add_query_arg( 'lead_tab', 'interactions', remove_query_arg( 'upd' ) ); ?>">Interactions</a>
	<?php endif; ?>
	<?php if ( $edit_lead != 0 ): ?>
        <a class="nav-tab <?php echo $tab == 'audit' ? 'nav-tab-active' : ''; ?>"
           href="<?php echo add_query_arg( 'lead_tab', 'audit', remove_query_arg( 'upd' ) ); ?>">Audit</a>
	<?php endif; ?>
</h2>

<?php
if ( $tab == 'general' ) {
	include ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/add-new-lead/general.php';
} elseif ( $tab == 'interactions' && $add_new != 1 ) {
	include ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/add-new-lead/interactions.php';
} else {
	if ( $edit_lead != 0 && $tab == 'audit' ) {
		include ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/add-new-lead/audit.php';
	}

}
?>
<div class="zleadscrm_loading_screen">
    <img alt="loading" style="margin-top: 15rem;"
         src="<?php echo plugin_dir_url( ZLEADSCRM_BASIC_BASE_FILE ) . 'asset/wpspin_light-2x.gif' ?>"/>
</div>


