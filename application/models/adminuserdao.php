<?php
class Adminuserdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO users(user_no, fname, lname, email, password, suspended, vrm_access) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_id = 
		$this->_temp_user_no = 
		$this->_temp_fname = 
		$this->_temp_lname = 
		$this->_temp_email = 
		$this->_temp_password = 
		$this->_temp_suspended =
		$this->_temp_vrm_access = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'users'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE users RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE users SELECT * FROM {$this->_table_name} WHERE 1=0" );
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

	function add_id( $id ) {
		$this->_temp_id.= $id;
	}

	function add_user_no( $user_no ) {
		$this->_temp_user_no.= $user_no;
	}

	function add_fname( $fname ) {
		$this->_temp_fname.= $fname;
	}

	function add_lname( $lname ) {
		$this->_temp_lname.= $lname;
	}

	function add_email( $email ) {
		$this->_temp_email.= $email;
	}

	function add_password( $password ) {
		$this->_temp_password.= $password;
	}

	function add_suspended( $suspended ) {
		$this->_temp_suspended.= $suspended;
	}

	function add_vrm_access( $vrm_access ) {
		$this->_temp_vrm_access.= $vrm_access;
	}

	/**
	 * Action on users consists of inserting only
	 */
	function run_query( $type ) {
		$this->_total++;

		$temp = "('{$this->_temp_user_no}', '{$this->_temp_fname}', '{$this->_temp_lname}', '{$this->_temp_email}', '{$this->_temp_password}', '{$this->_temp_suspended}', '{$this->_temp_vrm_access}'),";


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

		$this->_temp_id = 
		$this->_temp_user_no = 
		$this->_temp_fname = 
		$this->_temp_lname = 
		$this->_temp_email = 
		$this->_temp_password = 
		$this->_temp_suspended =
		$this->_temp_vrm_access = '';
	}
}