<?php
class UserDao extends CI_Model {
	/**
	 *
	 */
	function valid_user_admin( $login, $password ) {
		return $this->db->query( "SELECT * FROM admin WHERE user='$login' AND pass=PASSWORD('$password')" )->row_array();
	}

	/**
	 * Get the user matching the provided email and password. If none is found return false
	 * This function  is called upon authentication
	 */
	function valid_user( $email, $password ) {
		return $this->db->query( "SELECT * FROM users WHERE email='$email' AND password=MD5('".$password."')" )->row_array();
	}

	/**
	 * Log the current user out 
	 */
	function logout_user( $id ) {
		return $this->db->query( "UPDATE users SET sid=NULL WHERE id='$id'" );
	}

	/**
	 * Get a user from a defined sid 
	 */
	function get_user_from_cookie( $sid ) {
		if( strlen( $sid ) < 4 )
			return false;

		$id = substr( $sid, 0, 4 );
		$qry = "SELECT *,(SELECT GROUP_CONCAT(price_group SEPARATOR '|') FROM customer_group WHERE account_no=users.user_no)customer_group FROM users WHERE id='$id' AND sid='$sid'";

		$res = $this->db->query( $qry )->row_array();
		if( $res )
			$res[ 'customer_group' ] = explode( '|', @$res[ 'customer_group' ] );

		return $res;
	}

	/**
	 *
	 */
	function get_admin_user_from_cookie( $sid ) {
		if( strlen( $sid ) < 4 )
			return false;

		$id = substr( $sid, 0, 4 );

		$res = $this->db->query( "SELECT * FROM admin WHERE id='$id' AND sid='$sid'" )->row_array();

		if( count( $res ) == 0 )
			return false;

		$res[ 'admin' ] = 1;

		return $res;
	}

	/**
	 *
	 */
	function check_user( $sid, $admin = 0 ) {
		if( $admin == 0 ) {
			$user = $this->get_user_from_cookie( $sid );
			$user[ 'admin' ] = 0;
			return $user;
		}
		else
			return $this->get_admin_user_from_cookie( $sid );
	}

	/**
	 * Set cookie for the current user
	 */
	function set_user_cookie( $user ) {
		$sid = str_pad( $user[ 'id' ], 4, '0', STR_PAD_LEFT ).crypt( rand() );

		$qry = "UPDATE users SET sid='$sid' WHERE id='{$user[ 'id' ]}'";
		$this->db->query( $qry );

		return array(
			'_s' => $sid
		);
	}

	/**
	 * Set cookie for the admin user
	 */
	function set_admin_user_cookie( $user ) {
		$sid = str_pad( $user[ 'id' ], 4, '0', STR_PAD_LEFT ).crypt( rand() );

		$qry = "UPDATE admin SET sid='$sid' WHERE id='{$user[ 'id' ]}'";
		$this->db->query( $qry );

		return array(
			'_s' => $sid
		);
	}

	/**
	 * Check whether an email address already exists in the database.
	 * This to prevent a duplicate user
	 */
	function user_email_exists( $email ) {
		$res = $this->db->query( "SELECT * FROM users WHERE email='$email'" )->row_array();
		return count( $res ) == 0 ? false : $res;
	}

	// /**
	 // * Save new user registration
	 // */
	// function sign_user_up( $params ) {
		// extract( $params );

		// $qry = "INSERT INTO users SET ";
		// $qry.= "fname='$first_name',";
		// $qry.= "lname='$last_name',";
		// $qry.= "email='$email_s',";
		// $qry.= "telephone='$telephone',";
		// $qry.= "fax='$fax',";
		// $qry.= "password='$password_s',";
		// $qry.= "suspended=1";
		// return $this->db->query( $qry );
	// }

	/**
	 * Get all registered addresses for a user, put the default at the top
	 */
	function addresses( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM address WHERE customer_id='$customer_id' AND by_default=1 ORDER BY id ASC" )->result_array();
	}

	/**
	 * Get the customer default address
	 */
	function default_address_exists( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM address WHERE customer_id='$user_id' AND by_default=1" )->row_array();
	}

	/**
	 *
	 */
	function remove_default_address( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE address SET by_default=0 WHERE customer_id='$user_id'" );
	}

	/**
	 * Get details for one address
	 * @params user_id - id
	 */
	function get_address( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM address WHERE id='$id' AND customer_id='$user_id'" )->row_array();
	}

	/**
	 *
	 */
	function save_address( $params ) {
		extract( $params );

		$qry ="INSERT INTO address SET ";
		$qry.="id='".$id."', ";
		$qry.="customer_id='".$user_id."', ";
		$qry.="recipient='".$recipient."', ";
		$qry.="address='".$address."', ";
		$qry.="town='".$town."', ";
		$qry.="postcode='".$postcode."', ";
		$qry.="county='".$county."', ";
		$qry.="telephone='".$telephone."', ";
		$qry.="by_default=0 ";
		$qry.="ON DUPLICATE KEY UPDATE ";
		$qry.="customer_id='".$user_id."', ";
		$qry.="recipient='".$recipient."', ";
		$qry.="address='".$address."', ";
		$qry.="town='".$town."', ";
		$qry.="postcode='".$postcode."', ";
		$qry.="county='".$county."',";
		$qry.="telephone='".$telephone."'";

		if( $this->db->query( $qry ) )
			return $id ? $id : $this->db->insert_id();

		return 0;
	}

	/**
	 *
	 */
	function set_password( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE users SET password=MD5('".$pass."') WHERE user_no='".$user_id."' AND email='".$email."'" );
	}

	/**
	 *
	 */
	function get_personal_info( $user_id, $email ) {
		return $this->db->query( "SELECT * FROM users WHERE user_no='".$user_id."' AND email='".$email."'" )->row_array();
	}

	/**
	 *
	 */
	function save_personal_info( $params ) {
		extract( $params );

		$qry = "INSERT INTO users SET ";
		$qry.= "id='".$id."',";
		$qry.= "user_no='".$user_no."',";
		$qry.= "fname='".$fname."',";
		$qry.= "lname='".$lname."',";
		$qry.= "email='".$email."',";
		$qry.= "telephone='".$telephone."',";
		$qry.= "fax='".$fax."' ";
		$qry.= "ON DUPLICATE KEY UPDATE ";
		$qry.= "fname='".$fname."',";
		$qry.= "lname='".$lname."',";
		$qry.= "email='".$email."',";
		$qry.= "telephone='".$telephone."',";
		$qry.= "fax='".$fax."' ";

		return $this->db->query( $qry );
	}

	/**
	 *
	 */
	function save_vrm_access( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE users SET vrm_access='".$vrm_access."' WHERE user_no='".$user_no."'" );
	}

	/**
	 *
	 */
	function save_suspended( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE users SET suspended='$suspended' WHERE user_no='$user_no'" );
	}

	/**
	 * 
	 */
	// function can_use_carweb_ws( $user_id ) {
		// $res = $this->db->query( "SELECT vrm_access FROM users WHERE user_no='$user_id'" )->row_array();
		// return $res[ 'vrm_access' ] == 0;
	// }

	/**
	 * Retrieve list of all users
	 */
	function all( $params ) {
		extract( $params );

		$qry = "SELECT * FROM users WHERE 1=1";

		if( !empty( $pattern ) )
			$qry.= " AND user_no LIKE '%$pattern%'";

		$qry.= " ORDER BY user_no LIMIT $start,$limit";

		return $this->db->query( $qry )->result_array();
	}

	/**
	 *
	 */
	function all_count( $params ) {
		extract( $params );

		$qry = "SELECT COUNT(*) nb FROM users WHERE 1=1";

		if( !empty( $pattern ) )
			$qry.= " AND user_no LIKE '%$pattern%'";

		$res = $this->db->query( $qry )->row_array();

		return $res[ 'nb' ];
	}

	/**
	 * @param array $params user_id - ip
	 */
	function save_login_page_usage( $params ) {
		extract( $params );

		return $this->db->query( "INSERT INTO login_page_usage SET user_no='".$user_id."', ip='".$ip."', usage_time=DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s')" );
	}

	/**
	 * @param array $params user_id - start - limit - type
	 */
	function invoices_creditnotes( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM invoices_credits WHERE customer_id='".$user_id."' AND doc_type='".$type."' ORDER BY STR_TO_DATE(doc_date,'%d/%m/%Y') DESC LIMIT ".$start.",".$limit )->result_array();
	}

	/**
	 *
	 */
	function invoices_creditnotes_count( $user_id, $type ) {
		$res = $this->db->query( "SELECT COUNT(*) nb FROM invoices_credits WHERE customer_id='".$user_id."' AND doc_type='".$type."'" )->row_array();

		return $res[ 'nb' ];
	}

	/**
	 * @param array $params user_id - doc_no
	 */
	function read_invoices_creditnotes( $params ) {
		extract( $params );

		return $this->db->query( "SELECT * FROM invoices_credits WHERE doc_no='".$doc_no."'" )->row_array();
	}
}