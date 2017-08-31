<?php
require_once 'home.php';
class Signup extends Home {

	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function index() {
		$user = parent::_valid_connection( false );
		$data = parent::_init_widgets( $user, false );
		$data[ 'signup_active' ] = true;

		$data[ 'meta_title' ] = file_exists( APPPATH.'meta_infos/signup-title.txt' ) ? file_get_contents( APPPATH.'meta_infos/signup-title.txt' ) : '';
		$data[ 'meta_description' ] = file_exists( APPPATH.'meta_infos/signup-description.txt' ) ? file_get_contents( APPPATH.'meta_infos/signup-description.txt' ) : '';
		$data[ 'meta_keywords' ] = file_exists( APPPATH.'meta_infos/signup-keywords.txt' ) ? file_get_contents( APPPATH.'meta_infos/signup-keywords.txt' ) : '';

		$this->load->view( 'new/sign-up', $data );
	}
	
	/**
	 * Upon registration just notify by email the admin. Saving into database is not required at this level
	 */
	function process() {
		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] && $this->_correct() && $this->_password_match() ) {
			$this->load->model( 'userdao' );
			$this->_send_mail_to_admin( $_POST );
		}
	}

	/**
	 * Check whether all requested fields are not empty
	 */
	function _correct() {
		$required_fields = array( 'first_name', 'last_name', 'email_s', 'telephone', 'address1', 'city', 'county', 'post_code', 'country', 'password_s', 'password_sc' );
		foreach( $required_fields as $req );
			if( empty( $_POST[ $req ] ) )
				die( '{"err":"'.misc::esc_json( E_EMPTY_FIELD ).'"}' );

		return true;
	}

	/**
	 * Do password and confirm password match
	 */
	function _password_match() {
		if( $_POST[ 'password_s' ] != $_POST[ 'password_sc' ] )
			die( '{"err":"'.misc::esc_json( E_INCORRECT_PASSWORD ).'"}' );

		return true;
	}

	/**
	 *
	 */
	function _send_mail_to_admin( $params ) {
		extract( $params );

		$from = 'no-reply@qpart.co.uk';

		$subject = 'New registration from Signup form - qpartco';

		$msg = '<p><strong>Company:</strong> '.$company.'</p>';
		$msg.= '<p><strong>First &amp; Last name:</strong> '.$first_name.' '.$last_name.'</p>';
		$msg.= '<p><strong>Email:</strong> '.$email_s.'</p>';
		$msg.= '<p><strong>Telephone:</strong> '.$telephone.'</p>';
		$msg.= '<p><strong>Fax:</strong> '.$fax.'</p>';
		$msg.= '<p><strong>Address1:</strong> '.$address1.'</p>';
		$msg.= '<p><strong>Town:</strong> '.$town.'</p>';
		$msg.= '<p><strong>City:</strong> '.$city.'</p>';
		$msg.= '<p><strong>County:</strong> '.$county.'</p>';
		$msg.= '<p><strong>Country:</strong> '.$country.'</p>';
		$msg.= '<p><strong>Post code:</strong> '.$post_code.'</p>';
		$msg.= '<p><strong>Password:</strong> '.$password_s.'</p>';

		
		$this->load->library( 'PHPMailer/SMTP' );
		$this->load->library( 'PHPMailer/PHPMailer', false, 'mail' );
		$this->mail->isSMTP();
		$this->mail->SMTPAuth = true;
		$this->mail->Host = 'localhost';
		$this->mail->Username = 'test_order@qpart.co.uk';
		$this->mail->Password = 'wO*#J%wIl[HE';
		$this->mail->addAddress( RECIPIENT );
		$this->mail->setFrom( $from, 'no-reply' );
		$this->mail->Subject = $subject;
		$this->mail->msgHTML( $msg );

		if ( !$this->mail->send() )
			die( '{"err":"'.misc::esc_json( $this->mail->ErrorInfo ).'"}' );

		die( '{"redirect":"'.base_url().'thank-you/signup"}' );
	}
}