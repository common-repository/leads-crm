<div class="zleadscrm_col_12 zleadscrm_row zleadscrm_disabled_padding" style="margin-top:2rem;">
    <div class="zleadscrm_col_8 zleadscrm_disabled_padding" style="align-self: start;">
        <h3 style="margin-top: 0;">Audit Log</h3>
        <div class="zleadscrm_tab_content zleadscrm_col_12 zleadscrm_disabled_padding" style="margin-top: -4em;">
			<?php
			if ( ! class_exists( 'ZLEADSCRM_BASIC_Core_Lead_Edit_Audit' ) ) {
				require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-core-lead-edit-audit.php' );
			}
			$lead_edit_audit = new ZLEADSCRM_Core_Lead_Edit_Audit( $edit_lead );
			$lead_edit_audit->print_overview();
			?>
        </div>
    </div>
    <div class="zleadscrm_col_3 zleadscrm_mobile_col zleadscrm_disabled_padding" style="align-self: start;">
        <form method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>"
              id="zleadscrm_add_new_lead_form_widget">
            <input type="hidden" name="action" value="zleadscrm_edit_lead"/>
            <input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>"/>
            <input type="hidden" name="redirect_tab" value="audit"/>
			<?php wp_nonce_field( 'zleadscrm_edit_lead' ); ?>

            <div class="zleadscrm_col_12 zleadscrm_mobile_col zleadscrm_card_box_container"
                 style="margin-left: 2rem; align-self: baseline;">

                <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_card_box"
                     style="background: #fff;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="border-bottom: solid 1px #bbb;">
                    <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                          style="display: block; margin: 10px;font-weight: bold;">Customer Actions</span>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="margin-top: 10px; margin-bottom: 10px;">
						<?php $lead_archive_status = get_user_meta( $user_id, 'zleadscrm_lead_archive_status', true ) == 'false' || get_user_meta( $user_id, 'zleadscrm_lead_archive_status', true ) == '' ? 'Archive' : 'Restore'; ?>
                        <a href="" data-link="<?php echo admin_url( 'admin-ajax.php' ); ?>"
                           data-lead="<?php echo esc_attr( $user_id ); ?>" id="zleadscrm_lead_change_archive_status"
                           class="button zleadscrm_mobile_button dashicons dashicons-archive"
                           style="margin-left:10px; margin-right:5px; flex:1 0 auto; text-align: center"></a>
                        <input type="submit" class="button button-primary zleadscrm_mobile_button"
                               style="margin-left:5px; margin-right:10px;  flex:1 0 auto;" value="Save"/>
                    </div>
                </div>

                <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_card_box"
                     style="background: #fff;  margin-top:2rem;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="border-bottom: solid 1px #bbb;">
                    <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                          style="display: block; margin: 10px;font-weight: bold;">Customer Status</span>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="margin-top: 10px; margin-bottom: 10px; margin-left:10px;">
                        <select name="zleadscrm_lead_customer_status" id="zleadscrm_lead_customer_status"
                                class="zleadscrm_widefat_input">
                            <option value="select">Select a Customer Status</option>
							<?php $lead_customer_statuses = zleadscrm_get_lead_customer_statuses();
							foreach ( $lead_customer_statuses as $key => $value ) {
								$selected = $lead_customer_status == $key ? 'selected' : '';

								echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
							}
							?>
                        </select>
                    </div>
                </div>

                <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_card_box"
                     style="background: #fff;  margin-top:2rem;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="border-bottom: solid 1px #bbb;">
                    <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                          style="display: block; margin: 10px;font-weight: bold;">Lead Status</span>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="margin-top: 10px; margin-bottom: 10px; margin-left:10px;">
                        <select name="zleadscrm_lead_status" id="zleadscrm_lead_status"
                                class="zleadscrm_widefat_input">
                            <option value="select">Select a Lead Status</option>
							<?php $lead_statuses = zleadscrm_get_lead_statuses();
							foreach ( $lead_statuses as $key => $value ) {
								$selected = $lead_status == $key ? 'selected' : '';
								echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
							}
							?>
                        </select>
                    </div>
                </div>

                <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_card_box"
                     style="background: #fff;  margin-top:2rem;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="border-bottom: solid 1px #bbb;">
                    <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                          style="display: block; margin: 10px;font-weight: bold;">Lead Source</span>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="margin-top: 10px; margin-bottom: 10px; margin-left:10px;">
                        <select name="zleadscrm_lead_source" id="zleadscrm_lead_source"
                                class="zleadscrm_widefat_input">
                            <option value="select">Select a lead</option>
							<?php $lead_sources = zleadscrm_get_lead_sources();
							foreach ( $lead_sources as $key => $value ) {
								$selected = $lead_source == $key ? 'selected' : '';
								echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
							}
							?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>