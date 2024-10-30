<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class ZLEADSCRM_BASIC_Core_Leads_Users extends WP_List_Table {

		public function __construct() {
			$this->items = [];
			parent::__construct(
				array(
					'singular' => 'lead-user',
					'plural'   => 'lead-users',
					'ajax'     => false,
				)
			);
		}

		public function print_overview() {
			$this->prepare_items();
			echo '<style></style>';
			echo '<div class="zleadscrm_overview_wrap">';
			echo '<h2 class="screen-reader-text">Leads List</h2>';
			if ( count( $this->items ) != 0 ) {
				$this->display();
			} else {
				$this->display_tablenav( 'top' );
				echo '<div style="color:#000; padding:10px 15px; background: #fff; margin-top:1rem; border-left: solid 3px #ffff00;margin-right:20px;">';
				echo '<p style="margin:0;">No Leads</p>';
				echo '</div>';
			}
			echo '</div>';
		}

		public function column_default( $user, $column_name ) {
			switch ( $column_name ) {
				case 'lead':
					$o = '';
					if ( $user->last_name && $user->first_name ) {
						$o .= '<a><span><b>' . esc_html( $user->last_name ) . ',</b> ' . esc_html( $user->first_name ) . '</span></a>';
					} else if ( $user->last_name ) {
						$o .= '<a><span><b>' . esc_html( $user->last_name ) . '</b></span></a>';
					} else if ( $user->first_name ) {
						$o .= '<a><span>' . esc_html( $user->first_name ) . '</span></a>';
					} else {
						$o .= '<span>-</span>';
					}
					$o .= '<br/><span style="color:#999;">' . esc_html( get_user_meta( $user->ID, 'zleadscrm_title', true ) ) . '</span>';

					return $o;
				case 'contact':
					$o = '';

					$email   = ( $user->user_email != '' ) ? $user->user_email : '-';
					$phone   = ( get_user_meta( $user->ID, 'zleadscrm_phone', true ) != '' ) ? get_user_meta( $user->ID, 'zleadscrm_phone', true ) : '-';
					$website = ( $user->user_url != '' ) ? $user->user_url : '-';


					$o .= '<a href="mailto:' . $email . '">' . esc_html( $email ) . '</a><br/>';
					$o .= '<span>' . esc_html( $phone ) . '</span><br/>';
					$o .= '<span>' . esc_html( $website ) . '</span>';

					return $o;
				case 'business':
					$business = get_user_meta( $user->ID, 'zleadscrm_business_name', true );
					if ( $business == '' ) {
						$business = '-';
					}
					$address = '';

					if ( get_user_meta( $user->ID, 'billing_address_1', true ) != '' ) {
						$address = get_user_meta( $user->ID, 'billing_address_1', true );
					}
					if ( get_user_meta( $user->ID, 'billing_address_2', true ) != '' ) {
						$address .= ' ' . get_user_meta( $user->ID, 'billing_address_2', true );
					}
					if ( get_user_meta( $user->ID, 'billing_city', true ) != '' ) {
						$address .= ', ' . get_user_meta( $user->ID, 'billing_city', true );
					}
					if ( get_user_meta( $user->ID, 'billing_state', true ) != '' ) {
						$address .= ', ' . get_user_meta( $user->ID, 'billing_state', true );
					}
					if ( get_user_meta( $user->ID, 'billing_postcode', true ) != '' ) {
						$address .= ', ' . get_user_meta( $user->ID, 'billing_postcode', true );
					}
					if ( get_user_meta( $user->ID, 'billing_country', true ) != 'select' ) {
						$address .= ', ' . get_user_meta( $user->ID, 'billing_country', true );
					}
					if ( $address == '' ) {
						$address = '-';
					}

					$annual_revenue = get_user_meta( $user->ID, 'zleadscrm_company_annual_revenue_size', true );

					$currency = get_option( 'zleadscrm_currency' );
					if ( $currency == false ) {
						$currency = 'USD';
					}
					$symbol        = zleadscrm_get_currency_symbol( $currency );
					$currency_info = zleadscrm_get_currency_info( $currency );

					$company_revenue = '';

					if ( $currency_info['currency_pos'] == 'left' ) {
						switch ( $annual_revenue ) {
							case 'small':
								$company_revenue = 'Small < ' . $symbol . number_format( 500000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
								break;
							case 'medium':
								$company_revenue = 'Medium < ' . $symbol . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
								break;
							case 'large':
								$company_revenue = 'Large > ' . $symbol . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] );
								break;
						}

					} else {
						switch ( $annual_revenue ) {
							case 'small':
								$company_revenue = 'Small < ' . number_format( 500000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
								break;
							case 'medium':
								$company_revenue = 'Medium < ' . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
								break;
							case 'large':
								$company_revenue = 'Large > ' . number_format( 1000000, 0, $currency_info['decimal_sep'], $currency_info['thousand_sep'] ) . ' ' . $symbol;
								break;
						}
					}

					return '<span>' . esc_html( $business ) . '</span><br/><span>' . esc_html( $address ) . '</span><br/><span>' . esc_html( $company_revenue ) . '</span>';
				case 'status':
					$customer_status = get_user_meta( $user->ID, 'zleadscrm_lead_customer_status', true );

					return zleadscrm_get_customer_status_label( $customer_status );
				case 'order':
					$oppo = get_user_meta( $user->ID, 'zleadscrm_order_monthly_volume_oportunity', true );

					return $this->get_opportinity_icon( $oppo );
				case 'lead_status':
					$lead_status = get_user_meta( $user->ID, 'zleadscrm_lead_status', true );
					if ( $lead_status != 'select' ) {
						return '<span>' . ucwords( str_replace( '_', ' ', $lead_status ) ) . '</span>';
					} else {
						return '<span>No status</span>';
					}
				case 'account_manager':
					$account_manager_id = get_user_meta( $user->ID, 'zleadscrm_account_manager', true );
					if ( $account_manager_id != 'select' && $account_manager_id != '0' ) {
						$account_manager = get_user_by( 'id', $account_manager_id )->display_name;
					} else {
						$account_manager = '';
					}
					if ( $account_manager == '' ) {
						$account_manager = '-';
					}

					return '<span>' . esc_html( $account_manager ) . '</span>';
				case 'last_edit':
					$now       = current_time( 'timestamp' );
					$last_edit = get_user_meta( $user->ID, 'zleadscrm_last_edit', true );
					if ( $last_edit != '' ) {
						if ( $now - $last_edit < 86400 ) {
							$o = human_time_diff( $last_edit, current_time( 'timestamp' ) ) . ' ago';
						} else {
							$o = date( get_option( 'date_format' ), $last_edit );
						}

						return '<span>' . esc_html( $o ) . '</span>';
					} else {
						return '<span>' . esc_html( date_format( date_create( $user->user_registered ), get_option( 'date_format' ) ) ) . '</span>';
					}

				case 'actions':
					ob_start();
					?><p>
					<?php
					$actions['mail'] = array(
						'url'    => 'mailto:' . $user->user_email,
						'class'  => 'email',
						'target' => '_self'
					);

					$actions['view'] = array(
						'url'    => admin_url( 'user-edit.php' ) . '?user_id=' . $user->ID . '&wp_http_referer=%2Fwp-admin%2Fusers.php',
						'class'  => 'visibility',
						'target' => '_self'
					);

					$link = get_user_meta( $user->ID, 'zleadscrm_business_name', true ) . ' ';
					if ( get_user_meta( $user->ID, 'billing_address_1', true ) != '' ) {
						$link .= get_user_meta( $user->ID, 'billing_address_1', true );
					}
					if ( get_user_meta( $user->ID, 'billing_address_2', true ) != '' ) {
						$link .= ' ' . get_user_meta( $user->ID, 'billing_address_2', true );
					}
					if ( get_user_meta( $user->ID, 'billing_city', true ) != '' ) {
						$link .= ', ' . get_user_meta( $user->ID, 'billing_city', true );
					}
					if ( get_user_meta( $user->ID, 'billing_state', true ) != '' ) {
						$link .= ', ' . get_user_meta( $user->ID, 'billing_state', true );
					}
					if ( get_user_meta( $user->ID, 'billing_postcode', true ) != '' ) {
						$link .= ', ' . get_user_meta( $user->ID, 'billing_postcode', true );
					}
					if ( get_user_meta( $user->ID, 'billing_country', true ) != 'select' ) {
						$link .= ', ' . get_user_meta( $user->ID, 'billing_country', true );
					}

					$search_link = str_replace( ' ', '+', $link );

					$actions['search'] = array(
						'url'    => 'https://www.google.com/search?q=' . $search_link,
						'class'  => 'search',
						'target' => '_blank'
					);

					$actions['edit'] = array(
						'url'    => add_query_arg( 'edit_lead', $user->ID ),
						'class'  => 'edit',
						'target' => '_self'
					);

					foreach ( $actions as $action ) {
						printf( '<a class="button tips dashicons dashicons-%s" href="%s" target="%s" style="width: 35px;"></a>', esc_attr( $action['class'] ), esc_url( $action['url'] ), esc_attr( $action['target'] ) );
					}
					?>
                    </p>
					<?php
					$result_actions = ob_get_contents();
					ob_end_clean();

					return $result_actions;
			}

			return '';
		}

		public function display_tablenav( $which ) {
			if ( $which == 'top' ) {
				$customer_status = isset( $_GET['customer_status'] ) ? sanitize_text_field( $_GET['customer_status'] ) : 'all';

				$all_users      = $this->get_total_number_of_results( 'all' );
				$lead_users     = $this->get_total_number_of_results( 'lead' );
				$prospect_users = $this->get_total_number_of_results( 'prospect' );
				$customer_users = $this->get_total_number_of_results( 'customer' );
				$flagged_users  = $this->get_total_number_of_results( 'flagged' );
				$pipeline_users = $this->get_total_number_of_results( 'pipeline' );
				$closed_users   = $this->get_total_number_of_results( 'closed' );
				$archived_users = $this->get_total_number_of_results( 'archived' );

				$all_style      = $customer_status == 'all' ? 'color: #000; font-weight:bold;' : '';
				$lead_style     = $customer_status == 'lead' ? 'color: #000; font-weight:bold;' : '';
				$prospect_style = $customer_status == 'prospect' ? 'color: #000; font-weight:bold;' : '';
				$customer_style = $customer_status == 'customer' ? 'color: #000; font-weight:bold;' : '';
				$flagged_style  = $customer_status == 'flagged' ? 'color: #000; font-weight:bold;' : '';
				$pipeline_style = $customer_status == 'pipeline' ? 'color: #000; font-weight:bold;' : '';
				$closed_style   = $customer_status == 'closed' ? 'color: #000; font-weight:bold;' : '';
				$archived_style = $customer_status == 'archived' ? 'color: #000; font-weight:bold;' : '';


				echo '<ul class="subsubsub">';

				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'all' ) . '" style="' . esc_attr( $all_style ) . '">All <span style="color: #555;">(' . esc_html( $all_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'lead' ) . '" style="' . esc_attr( $lead_style ) . '">Lead <span style="color: #555;">(' . esc_html( $lead_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'prospect' ) . '" style="' . esc_attr( $prospect_style ) . '">Prospect <span style="color: #555;">(' . esc_html( $prospect_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'customer' ) . '" style="' . esc_attr( $customer_style ) . '">Customer <span style="color: #555;">(' . esc_html( $customer_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'flagged' ) . '" style="' . esc_attr( $flagged_style ) . '">Flagged <span style="color: #555;">(' . esc_html( $flagged_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'pipeline' ) . '" style="' . esc_attr( $pipeline_style ) . '">Pipeline <span style="color: #555;">(' . esc_html( $pipeline_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'closed' ) . '" style="' . esc_attr( $closed_style ) . '">Closed <span style="color: #555;">(' . esc_html( $closed_users ) . ')</span></a> | </li>';
				echo '<li style="margin-right:5px;"><a href="' . add_query_arg( 'customer_status', 'archived' ) . '" style="' . esc_attr( $archived_style ) . '">Archived <span style="color: #555;">(' . esc_html( $archived_users ) . ')</span></a></li>';
				echo '</ul>';
				if ( defined( 'ZACCTMGR_PLUGIN_DIR' ) ):
					$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZACCTMGR_PLUGIN_DIR, 'plugins' ) ) . 'accountmanager.php';
					if ( is_plugin_active( $plugin ) ): ?>
                        <select data-link="<?php echo add_query_arg(); ?>" name="manager-filter"
                                id="zleadscrm_account_manager_filter"
                                class="ewc-filter-cat" style="margin-top: 5px; margin-bottom: 10px;">
                            <option value="0">Filter by Account Manager...</option>
							<?php
								$users      = zacctmgr_get_em_users();
								$manager_id = isset( $_GET['manager_filter'] ) ? sanitize_text_field( $_GET['manager_filter'] ) : 0;

								if ( $users && count( $users ) > 0 ) {
									foreach ( $users as $user ) {
										$user_id    = (int) $user->ID;
										$first_name = get_user_meta( $user_id, 'first_name', true );
										$last_name  = get_user_meta( $user_id, 'last_name', true );

										$name = '-';
										if ( $first_name != '' || $last_name != '' ) {
											$name = $first_name . ' ' . $last_name;
										}

										$selected = $manager_id == $user_id ? 'selected="selected"' : '';
										$selected = $manager_id == $user_id ? 'selected="selected"' : '';
										?>
                                        <option value="<?php echo esc_attr( $user_id ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $name ); ?></option>
										<?php
									}
								}
							?>
                        </select>
					<?php endif; ?>
				<?php endif; ?>
                <select data-link="<?php echo remove_query_arg( 'sort_by' ); ?>" name="sort_by"
                        id="zleadscrm_leads_sort_by"
                        class="ewc-filter-cat" style="margin-top: 5px; margin-bottom: 10px;">
					<?php $sort_by = isset( $_GET['sort_by'] ) ? sanitize_text_field( $_GET['sort_by'] ) : 'last_name'; ?>
                    <option value="last_name" <?php echo $sort_by == 'last_name' ? 'selected' : ''; ?>>Sort by Last
                        Name
                    </option>
                    <option value="business_name" <?php echo $sort_by == 'business_name' ? 'selected' : ''; ?>>Sort by
                        Business Name
                    </option>
                </select>
				<?php

				echo '<div class="zleadscrm_align_table_mobile actions bulkactions lead_paging" style="margin-bottom: 1rem;">';
				$this->pagination( "top" );
				echo '</div>';
			} else {
				echo '<div class="zleadscrm_align_table_mobile actions bulkactions lead_paging" style="margin-top: 1rem;">';
				$this->pagination( "top" );
				echo '</div>';
			}
		}

		public function get_columns() {
			if ( defined( 'ZACCTMGR_PLUGIN_DIR' ) ) {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZACCTMGR_PLUGIN_DIR, 'plugins' ) ) . 'accountmanager.php';
				if ( is_plugin_active( $plugin ) ) {
					$columns = array(
						'lead'            => 'Lead',
						'contact'         => 'Contact',
						'business'        => 'Business',
						'status'          => 'Status',
						'order'           => 'Order',
						'lead_status'     => 'Lead Status',
						'account_manager' => 'Account Manager',
						'last_edit'       => 'Last Edit',
						'actions'         => 'Actions',
					);
				} else {
					$columns = array(
						'lead'        => 'Lead',
						'contact'     => 'Contact',
						'business'    => 'Business',
						'status'      => 'Status',
						'order'       => 'Order',
						'lead_status' => 'Lead Status',
						'last_edit'   => 'Last Edit',
						'actions'     => 'Actions',
					);
				}
			} else {
				$columns = array(
					'lead'        => 'Lead',
					'contact'     => 'Contact',
					'business'    => 'Business',
					'status'      => 'Status',
					'order'       => 'Order',
					'lead_status' => 'Lead Status',
					'last_edit'   => 'Last Edit',
					'actions'     => 'Actions',
				);
			}

			return $columns;

		}

		public function prepare_items() {
			$current_page = $this->get_pagenum();
			$per_page     = 20;
			/**
			 * Init column headers.
			 */
			$this->_column_headers = array( $this->get_columns(), array(), array() );
			$offset                = $current_page != 0 ? ( $current_page - 1 ) * $per_page : 0;

			$manager_id = isset( $_GET['manager_filter'] ) ? sanitize_text_field( $_GET['manager_filter'] ) : 0;

			$customer_status = isset( $_GET['customer_status'] ) ? sanitize_text_field( $_GET['customer_status'] ) : 'all';

			if ( $customer_status == 'all' ) {
				if ( $manager_id == 0 ) {
					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						),
					) );

					$users = $query->get_results();
				} else {
					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $manager_id
							),
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						),

					) );

					$users = $query->get_results();
				}
			} elseif ( $customer_status != 'archived' ) {
				if ( $manager_id == 0 ) {

					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_customer_status',
								'value' => $customer_status
							),
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						)
					) );

					$users = $query->get_results();

				} else {
					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_customer_status',
								'value' => $customer_status
							),
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $manager_id
							),
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						)
					) );

					$users = $query->get_results();
				}
			} else {
				if ( $manager_id == 0 ) {

					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'true'
							)
						)
					) );

					$users = $query->get_results();

				} else {
					$query = new WP_User_Query( array(
						'role'       => 'lead',
						'number'     => '-1',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'true'
							),
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $manager_id
							)
						)
					) );

					$users = $query->get_results();
				}
			}


			usort( $users, array( $this, "sort_items" ) );

			$this->items = array_splice( $users, $offset, $per_page );


			/**
			 * Pagination.
			 */

			$total_results = $this->get_total_number_of_results( $customer_status );

			$this->set_pagination_args(
				array(
					'total_items' => $total_results,
					'per_page'    => $per_page,
					'total_pages' => ceil( $total_results / $per_page ),
				)
			);
		}

		public function sort_items( $a, $b ) {
			$sort_by = isset( $_GET['sort_by'] ) ? sanitize_text_field( $_GET['sort_by'] ) : 'last_name';
			if ( $sort_by == 'last_name' ) {
				return strnatcmp( strtolower( $a->last_name ), strtolower( $b->last_name ) );
			} else {
				return strnatcmp( strtolower( get_user_meta( $a->ID, 'zleadscrm_business_name', true ) ), strtolower( get_user_meta( $b->ID, 'zleadscrm_business_name', true ) ) );
			}
		}

		public function get_total_number_of_results( $customer_status ) {
			$account_manager = isset( $_GET['manager_filter'] ) ? sanitize_text_field( $_GET['manager_filter'] ) : 0;

			if ( $account_manager == 0 ) {
				if ( $customer_status == 'all' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						)
					) );
				} elseif ( $customer_status != 'archived' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_customer_status',
								'value' => $customer_status
							),
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							)
						)
					) );
				} elseif ( $customer_status == 'archived' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_key'   => 'zleadscrm_lead_archive_status',
						'meta_value' => 'true'
					) );
				}
			} else {
				if ( $customer_status == 'all' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							),
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $account_manager
							)
						)

					) );
				} elseif ( $customer_status != 'archived' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_customer_status',
								'value' => $customer_status
							),
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $account_manager
							),
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'false'
							),
						)
					) );
				} elseif ( $customer_status == 'archived' ) {
					$users = get_users( array(
						'role'       => 'lead',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'zleadscrm_lead_archive_status',
								'value' => 'true'
							),
							array(
								'key'   => 'zleadscrm_account_manager',
								'value' => $account_manager
							)
						)
					) );
				}
			}

			return count( $users );
		}

		private function get_opportinity_icon( $opportunity ) {
			switch ( $opportunity ) {
				case 'small':
					$o = '<svg height="20" width="20" >';
					$o .= '<circle cx="10" cy="10" r="8" stroke="#555555" fill="white" stroke-width="2"/>';
					$o .= '</svg>';

					return $o;
					break;
				case 'medium':
					$o = '<svg style="margin-left: -10px;" height="20" width="20">';
					$o .= '<circle cx="20" cy="10" r="8" stroke="#555555" fill="#555555" />';
					$o .= '</svg>';
					$o .= '<svg height="20" width="20">';
					$o .= '<circle cx="0" cy="10" r="8" stroke="#555555" fill="white" stroke-width="1" />';
					$o .= '</svg>';

					return $o;
					break;
				case 'large':

					$o = '<svg height="20" width="20">';
					$o .= '<circle cx="10" cy="10" r="8" stroke="#555555" fill="#555555" />';
					$o .= ' </svg> ';

					return $o;
					break;
			}
		}

	}

?>