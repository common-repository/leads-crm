<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class ZLEADSCRM_BASIC_Core_Analytics extends WP_List_Table {
		public $analytics_data = null;

		public $user_id = 0;

		public function __construct( $user_id ) {
			parent::__construct(
				array(
					'singular' => 'analytic',
					'plural'   => 'analytics',
					'ajax'     => false,
				)
			);
			$this->user_id = $user_id;

			$this->analytics_data = zleadscrm_get_analytics_data( $this->user_id );
		}

		public function print_overview() {
			$this->prepare_items();
			echo '<div class="zleadscrm_overview_wrap">';
			echo '<h2 class="screen-reader-text">Analytics</h2>';
			?>
			<?php
			$this->display();
			echo '</div>';
		}

		public function column_default( $user, $column_name ) {
			if ( $this->user_id == 0 ) {
				if ( class_exists( 'ZACCTMGR_Core' ) ) {
					switch ( $column_name ) {
						case 'name':
							return '<span>' . esc_html( $user['name'] ) . '</span>';
						case 'username':
							return '<span>' . esc_html( $user['username'] ) . '</span>';
						case 'leads':
							return '<span>' . esc_html( $user['leads'] ) . '</span>';
					}
				} else {
					switch ( $column_name ) {
						case 'name':
							return '<span>' . esc_html( $user['name'] ) . '</span>';
						case 'username':
							return '<span>' . esc_html( $user['username'] ) . '</span>';
						case 'leads_created':
							return '<span>' . esc_html( $user['leads_created'] ) . '</span>';
					}
				}
			} else if ( class_exists( 'ZACCTMGR_Core' ) ) {
				switch ( $column_name ) {
					case 'name':
						return '<span>' . esc_html( $user['name'] ) . '</span>';
					case 'username':
						return '<span>' . esc_html( $user['username'] ) . '</span>';
					case 'leads':
						return '<span>' . esc_html( $user['leads'] ) . '</span>';
				}
			} else {
				switch ( $column_name ) {
					case 'name':
						return '<span>' . esc_html( $user['name'] ) . '</span>';
					case 'username':
						return '<span>' . esc_html( $user['username'] ) . '</span>';
					case 'leads_created':
						return '<span>' . esc_html( $user['leads_created'] ) . '</span>';
				}
			}

			return '';
		}

		public function display_tablenav( $which ) {
			if ( $this->user_id == 0 ) {
				if ( $which == 'top' ) {
					$all_users = zleadscrm_get_all_eligible_users();
					$all       = 'style="color:#000; font-weight: 700;"';


					echo '<ul class="subsubsub">';
					echo '<li style="margin-right:5px;"><a ' . $all . ' href="' . add_query_arg( 'status', 'all' ) . '">All <span style="color: #555;">(' . count( $all_users ) . ')</span></a></li>';
					echo '</ul>';
				}
			}
		}

		public function get_columns() {
			if ( $this->user_id == 0 ) {
				if ( class_exists( 'ZACCTMGR_Core' ) ) {
					$columns = array(
						'name'     => 'Name',
						'username' => 'Username',
						'leads'    => 'Leads'
					);
				} else {
					$columns = array(
						'name'          => 'Name',
						'username'      => 'Username',
						'leads_created' => 'Leads Created'
					);

				}
			} else if ( class_exists( 'ZACCTMGR_Core' ) ) {
				$columns = array(
					'name'     => 'Name',
					'username' => 'Username',
					'leads'    => 'Leads',
				);
			} else {
				$columns = array(
					'name'          => 'Name',
					'username'      => 'Username',
					'leads_created' => 'Leads Created'
				);
			}

			return $columns;

		}

		public function prepare_items() {
			if ( $this->user_id == 0 ) {
				$users = [];

				$selected_roles = zleadscrm_get_selected_roles();

				$query = new WP_User_Query( array(
					'role__in' => $selected_roles
				) );

				$all_users = $query->get_results();

				foreach ( $all_users as $user ) {
					if ( ! $this->exist_in_array( $users, $user ) ) {
						if ( class_exists( 'ZACCTMGR_Core' ) ) {
							$leads         = zleadscrm_get_number_of_leads( $user->ID );
							$leads_created = 0;
						} else {
							$leads_created = zleadscrm_get_number_of_leads_created( $user->ID );
							$leads         = 0;
						}
						array_push( $users, array(
							'name'          => $user->last_name . ', ' . $user->first_name,
							'username'      => $user->user_login,
							'leads'         => $leads,
							'leads_created' => $leads_created
						) );
					}
				}

				$this->items = $users;
			} else {
				$user = get_user_by( 'id', $this->user_id );
				if ( class_exists( 'ZACCTMGR_Core' ) ) {
					$leads         = zleadscrm_get_number_of_leads( $user->ID );
					$leads_created = 0;
				} else {
					$leads         = 0;
					$leads_created = zleadscrm_get_number_of_leads_created( $user->ID );
				}

				$this->items = array(
					array(
						'name'          => $user->last_name . ', ' . $user->first_name,
						'username'      => $user->user_login,
						'leads'         => $leads,
						'leads_created' => $leads_created
					)
				);
			}
			/**
			 * Init column headers.
			 */
			$this->_column_headers = array( $this->get_columns(), array(), array() );
		}

		private function exist_in_array( $array, $user ) {
			foreach ( $array as $arr ) {
				if ( ( $arr['username'] == $user->user_login ) ) {
					return true;
				}
			}

			return false;
		}
	}

?>