<h3 style="margin:1rem;">Interactions</h3>
<div class="zleadscrm_tab_content zleadscrm_col_8"
     style="background-color: #fff; margin-top:1rem;">
	<?php zleadscrm_display_interaction_form(); ?>

	<?php
	if ( ! class_exists( 'ZLEADSCRM_BASIC_Core_Lead_Interactions' ) ) {
		require_once ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-core-lead-interactions.php';
	}
	$lead_interactions = new ZLEADSCRM_BASIC_Core_Lead_Interactions( $_GET['edit_lead'] );
	$lead_interactions->print_overview();
	?>
</div>
