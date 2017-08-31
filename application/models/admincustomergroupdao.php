<?php
class Admincustomergroupdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO customer_group(account_no, customer_name, price_group) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_account_no = 
		$this->_temp_customer_name =
		$this->_temp_price_group ='';
	}

	/**
	 *
	 */
	function backup( $type, $bot = false ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'customer_group'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE customer_group RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE customer_group SELECT * FROM {$this->_table_name} WHERE 1=0" ) &&
				$this->db->query( "ALTER TABLE customer_group ADD PRIMARY KEY(account_no, price_group)" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		return $this->db->query( "DROP TABLE {$this->_table_name}" );
	}

	/**
	 *
	 */
	function additional_treatment() {}

	function add_account_no( $acc_no ) {
		$this->_temp_account_no.= $acc_no;
	}

	function add_customer_name( $cust_name ) {
		$this->_temp_customer_name.= $cust_name;
	}

	function add_price_group( $price_group ) {
		$this->_temp_price_group.= $price_group;
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE customer_group FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\r\n'(account_no, customer_name, price_group)" );
	}

	/**
	 * 
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_account_no}', '{$this->_temp_customer_name}', '{$this->_temp_price_group}'),";


		// Bulk insert on "rebuild"
		$q = $this->_query.$temp;

		// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
		if( strlen( $q ) >= 4096 ) {
			$this->db->query( rtrim( $this->_query, ',' ) );

			$this->_query = $this->_insert_instr.$temp;
		}
		else
			$this->_query = $q;

		$this->_temp_account_no = 
		$this->_temp_customer_name =
		$this->_temp_price_group ='';
	}

	/**
	 *
	 */
	function run_the_remain_query( $type = '' ) {
		if( !empty( $this->_query ) && $this->_query != $this->_insert_instr )
			return $this->db->query( rtrim( $this->_query, ',' ) );

		return true;
	}
}