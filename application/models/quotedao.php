<?php
class QuoteDao extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * @param array $params user_id - start - limit
	 */
	function all( $params ) {
		extract( $params );

		return $this->db->query( "SELECT quote_no, acc_no, quote_date, your_ref, insurance_company FROM quotes WHERE acc_no='$user_id' AND TO_DAYS(NOW())-TO_DAYS(STR_TO_DATE(quote_date,'%d/%m/%Y'))<=30 GROUP BY 1,2,3,4, 5 ORDER BY STR_TO_DATE(quote_date,'%d/%m/%Y') DESC, quote_no DESC" )->result_array();
	}

	/**
	 * Get detail about a quote
	 * @param array $params user_id - quote_no
	 */
	function read( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM quotes WHERE quote_no='$quote_no' AND acc_no='$user_id' group by 2,3,4,5,6,7,8,9,10,11,12,13,14,15 order by `part_no`" )->result_array();
	}

	/**
	 * Get a quote exactly from its line id (not quote's)
	 */
	function read_by_line_id( $id ) {
		return $this->db->query( "SELECT * FROM quotes WHERE id='$id'" )->row_array();
	}
}

