<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	if ( zleadscrm_allow_view_page() == false ) {
		echo '<div class="zleadscrm_not_allowed_wrap">';
		echo '<p>Sorry, you are not allowed to access this page!</p>';
		echo '</div>';
		exit;
	}

	$prospect_status  = zleadscrm_get_addon_status( 'prospect' );
	$pipeline_status  = zleadscrm_get_addon_status( 'pipeline' );
	$analytics_status = zleadscrm_get_addon_status( 'advanced_analytics' );
	$export_status    = zleadscrm_get_addon_status( 'export' );
	$import_status    = zleadscrm_get_addon_status( 'import' );
	$custom_fields    = zleadscrm_get_addon_status( 'custom_fields' );

?>
<div class="wrap">
    <hr class="wp-header-end"/>
    <h1 style="font-weight: bold; font-size: 20px;">Add-on Plugins</h1>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Prospect</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b>Add Google Places API to search for business
                    results
                    including the Business
                    Name, Business Type, Phone,
                    Website and Address</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $prospect_status == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $prospect_status == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $prospect_status == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/prospectleads"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/prospect/" target="_blank">More info</a>
            </div>
        </div>
    </div>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Pipeline</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b>Add form functionality on website pages to automatically create a lead for sales follow-up on form
                    submissions</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $pipeline_status == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $pipeline_status == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $pipeline_status == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/pipelineleads"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/pipeline/" target="_blank">More info</a>
            </div>
        </div>
    </div>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Advanced Analytics</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b> Add dashboard functionality to quickly view key business
                    statistics and filtering based upon specific periods of time</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $analytics_status == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $analytics_status == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $analytics_status == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/analyticsleads"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/analytics/" target="_blank">More info</a>
            </div>
        </div>
    </div>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Export</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b> Add the ability to Export prospect search results, bookmarks and leads business information to csv
                    format</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $export_status == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $export_status == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $export_status == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/exportleads"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/export/" target="_blank">More info</a>
            </div>
        </div>
    </div>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Import</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b> Add the ability to Import a CSV format into the Leads CRM database</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $import_status == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $import_status == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $import_status == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/importleads"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/import/" target="_blank">More info</a>
            </div>
        </div>
    </div>
    <div class="zleadscrm_box_shaddow zleadscrm_addon_box">
        <div class="zleadscrm_addon_title">
            <h2><b>Custom Fields</b></h2>
        </div>
        <div class="zleadscrm_addon_description">
            <p><b>Add the ability to add custom fields functionality to leads table and pipeline</b></p>
        </div>
        <div class="zleadscrm_col_12 zleadscrm_row zleadscrm_addon_action">
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <span style="vertical-align: middle"
                      class="<?php echo $custom_fields == 'active' ? 'zleadscrm_addon_active_label' : 'zleadscrm_addon_enable_label'; ?>"></span>
				<?php if ( $custom_fields == 'active' ): ?>
                    <span>Active</span>
				<?php else: ?>
                    <span><a href="<?php echo admin_url( 'plugins.php' ); ?>">Enable</a></span>
				<?php endif; ?>
            </div>
			<?php if ( $custom_fields == 'enable' ): ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <a href="https://www.bizswoop.com/product/customfields"
                       target="_blank" class="zleadscrm_addon_buy_button">Buy</a>
                </div>
			<?php else: ?>
                <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                    <span></span>
                </div>
			<?php endif; ?>
            <div class="zleadscrm_col_4 zleadscrm_disabled_padding">
                <a href="https://www.bizswoop.com/wp/leads/customfields/" target="_blank">More info</a>
            </div>
        </div>
    </div>
</div>
