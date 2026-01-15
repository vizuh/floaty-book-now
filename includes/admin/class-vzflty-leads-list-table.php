<?php
/**
 * Leads List Table.
 *
 * @package FloatyBookNowChat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table class for Leads.
 */
class VZFLTY_Leads_List_Table extends WP_List_Table {

	/**
	 * Database instance.
	 *
	 * @var VZFLTY_DB
	 */
	protected $db;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Lead', 'floaty-book-now-chat' ),
				'plural'   => __( 'Leads', 'floaty-book-now-chat' ),
				'ajax'     => false,
			)
		);

		require_once dirname( __DIR__ ) . '/class-vzflty-db.php';
		$this->db = new VZFLTY_DB();
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'         => '<input type="checkbox" />',
			'created_at' => __( 'Date', 'floaty-book-now-chat' ),
			'name'       => __( 'Name', 'floaty-book-now-chat' ),
			'contact'    => __( 'Contact', 'floaty-book-now-chat' ),
			'source'     => __( 'Source / Medium', 'floaty-book-now-chat' ),
			'campaign'   => __( 'Campaign', 'floaty-book-now-chat' ),
			'click_ids'  => __( 'Click IDs', 'floaty-book-now-chat' ),
			'landing'    => __( 'Landing Page', 'floaty-book-now-chat' ),
			'wpp_number' => __( 'WhatsApp', 'floaty-book-now-chat' ),
			'status'     => __( 'Status', 'floaty-book-now-chat' ),
		);
	}

	/**
	 * Get sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'created_at' => array( 'created_at', true ),
			'name'       => array( 'lead_name', false ),
		);
	}

	/**
	 * Render checkbox column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="lead_ids[]" value="%s" />',
			$item->id
		);
	}

	/**
	 * Render date column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_created_at( $item ) {
		return esc_html( $item->created_at );
	}

	/**
	 * Render name column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_name( $item ) {
		return '<strong>' . esc_html( $item->lead_name ) . '</strong>';
	}

	/**
	 * Render contact column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_contact( $item ) {
		$phone = $item->lead_phone ? esc_html( $item->lead_phone ) : '-';
		$email = $item->lead_email ? esc_html( $item->lead_email ) : '-';
		return sprintf( '<div>%s</div><div><small>%s</small></div>', $phone, $email );
	}

	/**
	 * Render source column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_source( $item ) {
		$s = $item->utm_source ?: '-';
		$m = $item->utm_medium ?: '-';
		return esc_html( "$s / $m" );
	}

	/**
	 * Render campaign column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_campaign( $item ) {
		return esc_html( $item->utm_campaign ?: '-' );
	}

	/**
	 * Render click IDs column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_click_ids( $item ) {
		if ( empty( $item->click_ids ) ) {
			return '-';
		}
		// Display as compact string or badges
		return '<small>' . esc_html( substr( $item->click_ids, 0, 50 ) ) . ( strlen( $item->click_ids ) > 50 ? '...' : '' ) . '</small>';
	}

	/**
	 * Render landing page column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_landing( $item ) {
		if ( empty( $item->landing_page ) ) {
			return '-';
		}
		return sprintf( '<a href="%1$s" target="_blank" title="%1$s">...%2$s</a>', esc_url( $item->landing_page ), esc_html( substr( $item->landing_page, -20 ) ) );
	}

	/**
	 * Render WPP Number column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_wpp_number( $item ) {
		return esc_html( $item->wpp_number ?: '-' );
	}

	/**
	 * Render status column.
	 *
	 * @param object $item Row item.
	 *
	 * @return string
	 */
	protected function column_status( $item ) {
		$status = $item->status ?: 'new';
		$color  = 'grey';
		if ( 'new' === $status ) {
			$color = 'green';
		}
		return sprintf( '<span style="color:%s">%s</span>', $color, esc_html( ucfirst( $status ) ) );
	}

	/**
	 * Prepare items.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$per_page = 20;
		$current_page = $this->get_pagenum();
		$offset = ( $current_page - 1 ) * $per_page;
		
		$total_items = $this->db->get_total_leads();
		$this->items = $this->db->get_leads( $per_page, $offset );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}
}
