<div class="zleadscrm_lead_fields">
    <form method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>" id="zleadscrm_add_new_lead_form">
		<?php if ( $add_new == 1 ): ?>
            <input type="hidden" name="action" value="zleadscrm_add_new_lead"/>
            <input type="hidden" name="redirect_tab" value="general"/>
			<?php wp_nonce_field( 'zleadscrm_add_new_lead' ); ?>
		<?php endif; ?>
		<?php if ( $edit_lead != 0 ): ?>
            <input type="hidden" name="action" value="zleadscrm_edit_lead"/>
            <input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>"/>
            <input type="hidden" name="redirect_tab" value="general"/>
			<?php wp_nonce_field( 'zleadscrm_edit_lead' ); ?>
		<?php endif; ?>
        <div class="zleadscrm_row" style="margin-top:2rem;">
            <div class="zleadscrm_col_8 zleadscrm_disabled_padding">
                <div class="zleadscrm_col_12 zleadscrm_card_box" style="background: #fff;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding">
                        <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                             style="border-bottom: solid 1px #bbb;">
                        <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                              style="display: block; margin-bottom: 10px;">
                            <b>General Details</b>
                        </span>
                        </div>
                        <div class="zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_row"
                             style="align-self: baseline; margin-top:10px;">
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">

                                <label for="zleadscrm_first_name" class="zleadscrm_form_label">First Name</label>
                                <input type="text" id="zleadscrm_first_name" name="zleadscrm_first_name"
                                       class="zleadscrm_widefat_input"
                                       value="<?php echo esc_attr( $lead_first_name ); ?>"/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_last_name" class="zleadscrm_form_label">Last Name</label>
                                <input type="text" id="zleadscrm_last_name" name="zleadscrm_last_name"
                                       class="zleadscrm_widefat_input"
                                       value="<?php echo esc_attr( $lead_last_name ); ?>"/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_title" class="zleadscrm_form_label">Title</label>
                                <input type="text" id="zleadscrm_title" name="zleadscrm_title"
                                       class="zleadscrm_widefat_input"
                                       value="<?php echo esc_attr( $lead_title ); ?>"/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_email_address" class="zleadscrm_form_label">Email Address</label>
                                <input type="email" id="zleadscrm_email_address" name="zleadscrm_email_address"
                                       class="zleadscrm_widefat_input" required
                                       value="<?php echo esc_attr( $lead_email_address ); ?>"/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_business_name" class="zleadscrm_form_label">Business Name</label>
                                <input type="text" id="zleadscrm_business_name" name="zleadscrm_business_name"
                                       class="zleadscrm_widefat_input" value="<?php echo esc_attr( $business_name ); ?>"
                                       required/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_phone" class="zleadscrm_form_label">Phone</label>
                                <input type="text" id="zleadscrm_phone" name="zleadscrm_phone"
                                       class="zleadscrm_widefat_input" value="<?php echo esc_attr( $mobile ); ?>"/>
                            </div>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
                                <label for="zleadscrm_website" class="zleadscrm_form_label">Website</label>
                                <input type="text" id="zleadscrm_website" name="zleadscrm_website"
                                       class="zleadscrm_widefat_input" value="<?php echo esc_url( $website ); ?>"/>
                            </div>
							<?php if ( defined( 'ZACCTMGR_PLUGIN_DIR' ) ): ?>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">

									<?php $plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZACCTMGR_PLUGIN_DIR, 'plugins' ) ) . 'accountmanager.php';
									if ( is_plugin_active( $plugin ) ):
										$account_managers = zacctmgr_get_em_users();
										?>
                                        <label for="zleadscrm_account_manager" class="zleadscrm_form_label">Account
                                            Manager</label>
                                        <select name="zleadscrm_account_manager" id="zleadscrm_account_manager"
                                                class="zleadscrm_widefat_input">
                                            <option value="0" <?php echo $account_manager_link == '0' ? 'selected' : ''; ?>>
                                                Select an Account Manager
                                            </option>
											<?php
											foreach ( $account_managers as $account_manager ) {
												$selected = $account_manager_link == $account_manager->ID ? 'selected' : '';
												echo '<option value="' . esc_attr( $account_manager->ID ) . '" ' . $selected . '>' . esc_html( $account_manager->display_name ) . '</option>';
											}
											?>
                                        </select>
									<?php endif; ?>
                                </div>
							<?php endif; ?>
                            <div class="zleadscrm_col_6 zleadscrm_disabled_padding" style="align-self: baseline;">
								<?php if ( class_exists( 'WooCommerce' ) ) :
									$brands = get_categories( array(
										'taxonomy'   => 'product_cat',
										'orderby'    => 'name',
										'show_count' => 0,
										'hide_empty' => 0
									) );
									?>
                                    <label for="zleadscrm_brands_select" class="zleadscrm_form_label">Brands</label>
                                    <select name="zleadscrm_brands[]" id="zleadscrm_brands_select"
                                            class="zleadscrm_widefat_input"
                                            multiple="multiple">
										<?php foreach ( $brands as $brand ) {
											if ( $brand->name != 'Uncategorized' ) {
												$selected = in_array( $brand->name, $lead_brands ) ? 'selected' : '';
												echo '<option value="' . esc_attr( $brand->name ) . '"' . $selected . '> ' . esc_html( $brand->name ) . '</option > ';
											}
										}
										?>
                                    </select>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding">
                        <div class="zleadscrm_col_6 zleadscrm_disabled_hpadding">
                        <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                              style="display: block;"><b>Billing Details</b></span>
                            <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                                 style="margin-top: 1rem;">
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_first_name" class="zleadscrm_form_label">First
                                        Name</label>
                                    <input type="text" id="zleadscrm_billing_first_name"
                                           name="zleadscrm_billing_first_name"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_first_name ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_last_name" class="zleadscrm_form_label">Last
                                        Name</label>
                                    <input type="text" id="zleadscrm_billing_last_name"
                                           name="zleadscrm_billing_last_name"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_last_name ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_12 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_company" class="zleadscrm_form_label">Company</label>
                                    <input type="text" id="zleadscrm_billing_company" name="zleadscrm_billing_company"
                                           class="zleadscrm_widefat_input"
                                           value="<?php echo esc_attr( $billing_company ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_address_1" class="zleadscrm_form_label">Address
                                        1</label>
                                    <input type="text" id="zleadscrm_billing_address_1"
                                           name="zleadscrm_billing_address_1"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_address_1 ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_address_2" class="zleadscrm_form_label">Address
                                        2</label>
                                    <input type="text" id="zleadscrm_billing_address_2"
                                           name="zleadscrm_billing_address_2"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_address_2 ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_city" class="zleadscrm_form_label">City</label>
                                    <input type="text" id="zleadscrm_billing_city" name="zleadscrm_billing_city"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_city ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_postcode"
                                           class="zleadscrm_form_label">Postcode</label>
                                    <input type="text" id="zleadscrm_billing_postcode" name="zleadscrm_billing_postcode"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_postcode ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_country" class="zleadscrm_form_label">Country</label>
                                    <select id="zleadscrm_billing_country" name="zleadscrm_billing_country"
                                            class="zleadscrm_halfwidefat_input">
										<?php $countries = zleadscrm_get_countries();
										foreach ( $countries as $key => $value ) {
											$selected = $billing_country == $key ? 'selected' : '';
											echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
										}
										?>
                                    </select>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_state"
                                           class="zleadscrm_form_label">State/County</label>
                                    <input type="text" id="zleadscrm_billing_state" name="zleadscrm_billing_state"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_state ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_email" class="zleadscrm_form_label">Email</label>
                                    <input type="email" id="zleadscrm_billing_email" name="zleadscrm_billing_email"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_email ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_phone" class="zleadscrm_form_label">Phone</label>
                                    <input type="text" id="zleadscrm_billing_phone" name="zleadscrm_billing_phone"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $billing_phone ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_12 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_billing_payment_method" class="zleadscrm_form_label">Payment
                                        Method</label>
                                    <select id="zleadscrm_billing_payment_method"
                                            name="zleadscrm_billing_payment_method"
                                            class="zleadscrm_widefat_input">
										<?php $billing_payment_methods = zleadscrm_get_lead_payment_methods();
										foreach ( $billing_payment_methods as $key => $value ) {
											$selected = $billing_payment_method == $key ? 'selected' : '';
											echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
										}
										?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="zleadscrm_col_6 zleadscrm_disabled_hpadding" style="align-self: baseline;">
                        <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                              style="display: block;"><b>Shipping Details</b></span>
                            <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                                  style="display: block; margin-top:10px;"><b>Copy from billing address</b> <a
                                        id="zleadscrm_copy_shipping_from_billing" class="button"
                                        style="vertical-align: middle; margin-left:10px;">Copy</a></span>
                            <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                                 style="margin-top: 1rem;">

                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_first_name" class="zleadscrm_form_label">First
                                        Name</label>
                                    <input type="text" id="zleadscrm_shipping_first_name"
                                           name="zleadscrm_shipping_first_name"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_first_name ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_last_name" class="zleadscrm_form_label">Last
                                        Name</label>
                                    <input type="text" id="zleadscrm_shipping_last_name"
                                           name="zleadscrm_shipping_last_name"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_last_name ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_12 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_company" class="zleadscrm_form_label">Company</label>
                                    <input type="text" id="zleadscrm_shipping_company" name="zleadscrm_shipping_company"
                                           class="zleadscrm_widefat_input"
                                           value="<?php echo esc_attr( $shipping_company ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_address_1" class="zleadscrm_form_label">Address
                                        1</label>
                                    <input type="text" id="zleadscrm_shipping_address_1"
                                           name="zleadscrm_shipping_address_1"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_address_1 ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_address_2" class="zleadscrm_form_label">Address
                                        2</label>
                                    <input type="text" id="zleadscrm_shipping_address_2"
                                           name="zleadscrm_shipping_address_2"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_address_2 ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_city" class="zleadscrm_form_label">City</label>
                                    <input type="text" id="zleadscrm_shipping_city" name="zleadscrm_shipping_city"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_city ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_postcode"
                                           class="zleadscrm_form_label">Postcode</label>
                                    <input type="text" id="zleadscrm_shipping_postcode"
                                           name="zleadscrm_shipping_postcode"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_postcode ); ?>"/>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_country" class="zleadscrm_form_label">Country</label>
                                    <select id="zleadscrm_shipping_country" name="zleadscrm_shipping_country"
                                            class="zleadscrm_halfwidefat_input">
										<?php $countries = zleadscrm_get_countries();
										foreach ( $countries as $key => $value ) {
											$selected = $shipping_country == $key ? 'selected' : '';

											echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
										}
										?>

                                    </select>
                                </div>
                                <div class="zleadscrm_col_6 zleadscrm_disabled_padding">
                                    <label for="zleadscrm_shipping_state"
                                           class="zleadscrm_form_label">State/County</label>
                                    <input type="text" id="zleadscrm_shipping_state" name="zleadscrm_shipping_state"
                                           class="zleadscrm_halfwidefat_input"
                                           value="<?php echo esc_attr( $shipping_state ); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="zleadscrm_col_12 zleadscrm_card_box" style="background: #fff; margin-top:2rem;">
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
                         style="border-bottom: solid 1px #bbb;">
                    <span class="zleadscrm_col_12 zleadscrm_disabled_padding"
                          style="display: block; margin-bottom: 10px;"><b>Additional Information</b></span>
                    </div>
					<?php
					$currency = get_option( 'zleadscrm_currency' );
					if ( $currency == false ) {
						$currency = 'USD';
					}
					$symbol        = zleadscrm_get_currency_symbol( $currency );
					$currency_info = zleadscrm_get_currency_info( $currency );


					if ( $currency_info['currency_pos'] == 'left' ) {
						$opp_value_small  = $symbol . ' ' . number_format( 1000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
						$opp_value_medium = $symbol . ' ' . number_format( 5000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
						$opp_value_large  = $symbol . ' ' . number_format( 10000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );

						$company_value_small  = $symbol . ' ' . number_format( 500000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
						$company_value_medium = $symbol . ' ' . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
						$company_value_large  = $symbol . ' ' . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );


					} else {
						$opp_value_small  = number_format( 1000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
						$opp_value_medium = number_format( 5000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
						$opp_value_large  = number_format( 10000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;

						$company_value_small  = number_format( 500000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
						$company_value_medium = number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
						$company_value_large  = number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;


					}

					?>
                    <div class="zleadscrm_col_12 zleadscrm_disabled_padding" style="margin-top:10px;">
                        <label for="zleadscrm_order_monthly_volume_oportunity" class="zleadscrm_form_label"><b>Order
                                Monthly
                                Volume Opportunity</b><br/></label>
                        <select name="zleadscrm_order_monthly_volume_oportunity"
                                id="zleadscrm_order_monthly_volume_oportunity" style="width: 50%;">
                            <option value="small" <?php echo $order_monthly_volume_opportunity == 'small' ? 'selected' : ''; ?>>
                                Small
                                < <?php echo $opp_value_small; ?></option>
                            <option value="medium" <?php echo $order_monthly_volume_opportunity == 'medium' ? 'selected' : ''; ?>>
                                Medium
                                < <?php echo $opp_value_medium; ?></option>
                            <option value="large" <?php echo $order_monthly_volume_opportunity == 'large' ? 'selected' : ''; ?>>
                                Large
                                > <?php echo $opp_value_large; ?></option>
                        </select>
                    </div>
                    <div class="zleadscrm_col_12 zleadscrm_disabled_padding">
                        <label for="zleadscrm_company_annual_revenue_size" class="zleadscrm_form_label"><b>Company
                                Annual
                                Revenue Size</b><br/></label>
                        <select name="zleadscrm_company_annual_revenue_size"
                                id="zleadscrm_company_annual_revenue_size" style="width: 50%;">
                            <option value="small" <?php echo $company_annual_revenue_size == 'small' ? 'selected' : ''; ?>>
                                Small
                                < <?php echo $company_value_small; ?></option>
                            <option value="medium" <?php echo $company_annual_revenue_size == 'medium' ? 'selected' : ''; ?>>
                                Medium
                                < <?php echo $company_value_medium; ?></option>
                            <option value="large" <?php echo $company_annual_revenue_size == 'large' ? 'selected' : ''; ?>>
                                Large
                                > <?php echo $company_value_large; ?></option>
                        </select>
                    </div>
                    <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding" style="margin-top: 10px;">
                        <label for="zleadscrm_short_description" class="zleadscrm_form_label"><b>Short
                                Description</b></label>
                        <textarea id="zleadscrm_short_description" name="zleadscrm_short_description"
                                  style="width: 100%; min-height: 100px; height: 100px;"><?php echo esc_textarea( $lead_description ); ?></textarea>
                    </div>


					<?php if ( class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
						zleadscrm_display_custom_fields_on_lead_form();
					}
					?>


                </div>
            </div>
            <div class="zleadscrm_col_3 zleadscrm_mobile_col zleadscrm_card_box_container"
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
                               style="margin-left:5px; margin-right:10px; flex:1 0 auto;" value="Save"/>
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


        </div>
    </form>
</div>