<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
	if ( ! class_exists( 'ZLEADSCRM_BASIC_Core_Leads_Users' ) ) {
		require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-core-leads-users.php' );
	}
	$zleadscrm_lead_users = new ZLEADSCRM_BASIC_Core_Leads_Users();
?>
<style>
    .widefat td {
        vertical-align: middle !important;
    }
</style>
<div style=" margin-top:2rem; margin-right:1rem;">
	<?php $zleadscrm_lead_users->print_overview();
	?>
</div>
<div class="zleadscrm_loading_screen">
    <img alt="loading" src="<?php echo plugin_dir_url( ZLEADSCRM_BASIC_BASE_FILE ) . 'asset/wpspin_light-2x.gif' ?>"/>
</div>
<?php
?>

