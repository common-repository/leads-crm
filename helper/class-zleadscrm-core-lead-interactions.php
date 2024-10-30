<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ZLEADSCRM_BASIC_Core_Lead_Interactions extends WP_List_Table {

	private $lead_id;

	public function __construct( $lead_id ) {
		$this->lead_id = $lead_id;
		parent::__construct(
			array(
				'singular' => 'lead_interaction',
				'plural'   => 'lead_interactions',
				'ajax'     => false,
			)
		);
	}

	public function print_overview() {
		$this->prepare_items();
		echo '<div class="zleadscrm_overview_wrap">';
		echo '<h2 class="screen-reader-text">Lead Interaction List</h2>';
		if ( count( $this->items ) == 0 ) {
			echo '<div class="zleadscrm_row" style="margin: 3rem auto; display: block; text-align: center;">';
			echo '<h2 style="font-size: 26px; color: #737373;">No interactions on this lead</h2>';
			echo '</div>';
		} else {
			$this->display();
		}
		echo '</div>';
	}

	public function column_default( $lead_interaction, $column_name ) {
		$user = get_user_by( 'id', $lead_interaction->author_id );

		switch ( $column_name ) {
			case 'username':
				return get_avatar( $user->ID, 50 ) . '<a style="margin-left:1rem;" href="' . get_edit_user_link( $user->ID ) . '">' . esc_html( $user->user_nicename ) . '</a>';
			case 'message':
				return '<span>' . esc_html( $lead_interaction->message ) . '</span>';
			case 'timestamp':
				return '<span>' . esc_html( $lead_interaction->timestamp ) . '</span>';
		}

		return '';
	}

	public function display_tablenav( $which ) {
		if ( $which == 'top' ) {
			echo '<div class="zleadscrm_align_table_mobile actions bulkactions interaction_paging" style="margin-bottom: 1rem; margin-top:1rem;">';
			$this->pagination( "top" );
			echo '</div>';
		}
	}

	public function get_columns() {
		$columns = array(
			'username'  => 'Username',
			'message'   => 'Notes',
			'timestamp' => 'Timestamp'
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

		$table_name = $wpdb->prefix . 'zleadscrm_leads_interactions';

		$current_user_id = $this->lead_id;

		$query = $wpdb->get_results( "SELECT * FROM $table_name WHERE lead_id=$current_user_id ORDER BY timestamp DESC;" );

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