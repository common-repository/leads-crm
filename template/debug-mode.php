<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
?>


<div class="wrap">
    <hr class="wp-header-end"/>
    <h1 style="margin-bottom: 1rem; font-weight: 500;">Debug Mode</h1>
    <div class="zleadsc rm_col_12"
         style="background: #fff; height: 85vh; max-height: 85vh; overflow: scroll; padding:10px;">
        <h4>If there are any errors with our plugin, you will see them here.</h4>
		<?php if ( file_exists( ZLEADSCRM_BASIC_PLUGIN_DIR . 'debug.log' ) ) {
			$logs = file_get_contents( ZLEADSCRM_BASIC_PLUGIN_DIR . 'debug.log' );
			if ( $logs != false ) {
				echo $logs;
			}
		} else {
			echo '<p>There are no errors in your logs.</p>';
		}
		?>
    </div>
</div>
