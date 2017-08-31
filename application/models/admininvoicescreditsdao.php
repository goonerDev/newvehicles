<?php
class Admininvoicescreditsdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO invoices_credits(customer_id, doc_date, doc_no, doc_type, sell_id) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_user_no = 
		$this->_temp_doc_date = 
		$this->_temp_doc_no = 
		$this->_temp_doc_type=
		$this->_temp_sell_id = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'invoices_credits'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE invoices_credits RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE invoices_credits SELECT * FROM {$this->_table_name} WHERE 1=0" );
				$this->db->query( "ALTER TABLE invoices_credits ADD PRIMARY KEY(customer_id,doc_no)" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "DROP TABLE {$this->_table_name}" );
	}

	/**
	 *
	 */
	function additional_treatment() {}

	function add_user_no( $user_no ) {
		$this->_temp_user_no.= $user_no;
	}

	function add_doc_date( $date ) {
		$this->_temp_doc_date.= $date;
	}

	function add_doc_no( $no ) {
		$this->_temp_doc_no.= $no;
	}

	function add_doc_type( $type ) {
		$this->_temp_doc_type.= $type;
	}

	function add_sell_id( $sellid ) {
		$this->_temp_sell_id.= $sellid;
	}

	/**
	 * Action on users consists of inserting only
	 */
	function run_query( $type ) {
		$this->_total++;

		$temp = "('{$this->_temp_user_no}', '{$this->_temp_doc_date}', '{$this->_temp_doc_no}', '{$this->_temp_doc_type}', '{$this->_temp_sell_id}'),";


		// Bulk insert on "rebuild"
		//if( $type == 'rebuild' ) {

		$q = $this->_query.$temp;

		// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
		if( strlen( $q ) >= 4096 ) {
			$this->db->query( rtrim( $this->_query, ',' ) );

			$this->_query = $this->_insert_instr.$temp;
		}
		else
			$this->_query = $q;

		$this->_temp_user_no = 
		$this->_temp_doc_date = 
		$this->_temp_doc_no = 
		$this->_temp_doc_type =
		$this->_temp_sell_id = '';
	}
}