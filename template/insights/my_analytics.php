<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	if ( ! class_exists( 'ZLEADSCRM_BASIC_Core_Analytics' ) ) {
		require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-basic-core-analytics.php' );
	}

	$zleadscrm_analytics = new ZLEADSCRM_BASIC_Core_Analytics( get_current_user_id() );
?>
<div style="margin-right:1rem; margin-top:2rem;">
	<?php
		$zleadscrm_analytics->print_overview();
	?>
</div>
<div class="zleadscrm_loading_screen">
    <img alt="loading" src="<?php echo plugin_dir_url( ZLEADSCRM_BASIC_BASE_FILE ) . 'asset/wpspin_light-2x.gif' ?>"/>
</div>
<?php
?>

