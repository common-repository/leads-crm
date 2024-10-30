<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
	$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'my_analytics';

	$current_user_id = get_current_user_id();

	if ( zleadscrm_user_can_access_analytics( $current_user_id ) == false ) {
		echo '<div class="zleadscrm_not_allowed_wrap">';
		echo '<p>Sorry, you are not allowed to access this page!</p>';
		echo '</div>';
		exit;
	}

?>

<?php

	echo '<div class="wrap">';
	echo '<hr class="wp-header-end"/>';
	echo '<h1 style="display: inline-block">Analytics</h1>';
	echo '</div>';
	echo '<div style="margin-top:2rem; margin-right:1rem;">';

	$this->show_zleadscrm_analytics_tab( $tab );
	if ( $tab == 'my_analytics' ) {
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/insights/my_analytics.php' );
	} else if ( $tab == 'company_analytics' ) {
		include_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/insights/company_analytics.php' );
	}

	echo '</div>';