<?php
class AdminmiscDao extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Retrieve the max allowed amount of vrm access in a day
	 */
	function get_vrm_access_limit() {
		$res = $this->db->query( "SELECT value FROM access_limit WHERE name='vrm'" )->row_array();
		return ( int ) @$res[ 'value' ];
	}

	/**
	 * 
	 */
	function set_vrm_access_limit( $val ) {
		return $this->db->query( "REPLACE INTO access_limit SET value='$val', name='vrm'" );
	}

	/**
	 * @params array - date_from - date_to - like_email - order_by - order_dir - offset - limit
	 */
	function get_vrm_usage_report( $params ) {
		extract( $params );

		$where_date = "AND STR_TO_DATE(usage_date,'%d-%m-%Y %H:%i:%s') BETWEEN '".$date_from."' AND '".$date_to."' ";
		$where_like_email = empty( $like_email ) ? "" : "AND email_address LIKE '%".$like_email."%'";

		
		if( empty( $order_by ) )
			$order_by = "ORDER BY STR_TO_DATE(usage_date,'%d-%m-%Y %H:%i:%s')";
		else {
			$i = 0;
			foreach( array( 'usage_date', 'customer_id', 'lname', 'email_address', 'vrm' ) as $field ) {
				if( $order_by == 'field'.( $i + 1 ) ) {
					$order_by = "ORDER BY ".$field;
					break;
				}
				$i++;
			}
		}

		$offset = empty( $offset ) ? 0 : $offset;

		return $this->db->query( "SELECT usage_date, customer_id, fname, lname, email_address, vrm FROM vrm_usage INNER JOIN users ON user_no=customer_id AND email_address=email WHERE 1=1 ".$where_date." ".$where_like_email." ".$order_by." ".$order_dir." LIMIT ".$offset.",".$limit )->result_array();
	}

	/**
	 * @params array - date_from - date_to - like_email - order_by - order_dir - offset - limit
	 */
	function get_vrm_usage_report_count( $params ) {
		extract( $params );

		$where_date = "AND STR_TO_DATE(usage_date,'%d-%m-%Y %H:%i:%s') BETWEEN '".$date_from."' AND '".$date_to."' ";
		$where_like_email = empty( $like_email ) ? "" : "AND email_address LIKE '%".$like_email."%'";

		$res = $this->db->query( "SELECT COUNT(*) n FROM vrm_usage INNER JOIN users ON user_no=customer_id AND email_address=email WHERE 1=1 ".$where_date." ".$where_like_email )->row_array();

		return $res[ 'n' ];
	}

	/**
	 *
	 */
	function get_page_access_limit() {
		$res = $this->db->query( "SELECT value FROM access_limit WHERE name='page'" )->row_array();
		return ( int ) @$res[ 'value' ];
	}

	/**
	 * 
	 */
	function set_page_access_limit( $val ) {
		return $this->db->query( "REPLACE INTO access_limit SET value='$val', name='page'" );
	}
}