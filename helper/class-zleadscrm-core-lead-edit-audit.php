<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ZLEADSCRM_Core_Lead_Edit_Audit extends WP_List_Table {

	private $user_id;

	public function __construct( $user_id ) {
		$this->user_id = $user_id;
		parent::__construct(
			array(
				'singular' => 'audit_lead_edit',
				'plural'   => 'audit_leads_edit',
				'ajax'     => false,
			)
		);
	}

	public function print_overview() {
		$this->prepare_items();
		echo '<div class="zleadscrm_overview_wrap">';
		echo '<h2 class="screen-reader-text">Audit Lead Edit List</h2>';
		if ( count( $this->items ) == 0 ) {
			echo '<div class="zleadscrm_row" style="margin: 3rem auto; display: block; text-align: center;">';
			echo '<h2 style="font-size: 26px; color: #737373;">No changes were made on this lead</h2>';
			echo '</div>';
		} else {
			$this->display();
		}
		echo '</div>';
	}

	public function column_default( $lead_audit, $column_name ) {
		$user = get_user_by( 'id', $lead_audit->editor_id );

		switch ( $column_name ) {
			case 'username':
				return get_avatar( $user->ID, 50 ) . '<a style="margin-left:1rem;" href="' . get_edit_user_link( $user->ID ) . '">' . esc_html( $user->user_nicename ) . '</a>';
			case 'name':
				return '<span>' . esc_html( $user->display_name ) . '</span>';
			case 'action':
				return '<span>' . esc_html( $lead_audit->action ) . '</span>';
			case 'old_value':
				return '<span>' . esc_html( $lead_audit->old_value ) . '</span>';
			case 'new_value':
				return '<span>' . esc_html( $lead_audit->new_value ) . '</span>';
			case 'timestamp':
				return '<span>' . esc_html( $lead_audit->timestamp ) . '</span>';
		}

		return '';
	}

	public function display_tablenav( $which ) {
		if ( $which == 'top' ) {
			echo '<div class="zleadscrm_align_table_mobile actions bulkactions audit_paging" style="margin-bottom:2em; margin-top:1rem;">';
			$this->pagination( "top" );
			echo '</div>';
		}

		if ( $which == 'bottom' ) {
			echo '<div class="zleadscrm_align_table_mobile actions bulkactions audit_paging" style="margin-bottom:0; margin-top:2em;">';
			$this->pagination( "top" );
			echo '</div>';
		}
	}

	public function get_columns() {
		$columns = array(
			'username'  => 'Username',
			'name'      => 'Name',
			'action'    => 'Action',
			'old_value' => 'Old Value',
			'new_value' => 'New Value',
			'timestamp' => 'Timestamp',
		);

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

		global $wpdb;

		$table_name = $wpdb->prefix . 'zleadscrm_leads_edit_audit';

		$current_user_id = $this->user_id;

		$query = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id=$current_user_id ORDER BY timestamp DESC;" );

		$total_results = count( $query );
		usort( $query, array( $this, "sort_items" ) );

		$this->items = array_splice( $query, $offset, $per_page );


		$this->set_pagination_args(
			array(
				'total_items' => $total_results,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_results / $per_page ),
			)
		);
	}

	public function sort_items( $a, $b ) {
		if ( $a->timestamp > $b->timestamp ) {
			return - 1;
		} else {
			return 1;
		}
	}

}

?>