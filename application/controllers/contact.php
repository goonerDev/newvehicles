<?php
require_once 'home.php';
class Contact extends Home {
	/**
	 *
	 */
	function index() {
		$data = parent::_init_widgets();

		$data[ 'contact_active' ] = true;

		$data[ 'meta_title' ] = file_exists( APPPATH.'meta_infos/contact-title.txt' ) ? file_get_contents( APPPATH.'meta_infos/contact-title.txt' ) : '';
		$data[ 'meta_description' ] = file_exists( APPPATH.'meta_infos/contact-description.txt' ) ? file_get_contents( APPPATH.'meta_infos/contact-description.txt' ) : '';
		$data[ 'meta_keywords' ] = file_exists( APPPATH.'meta_infos/contact-keywords.txt' ) ? file_get_contents( APPPATH.'meta_infos/contact-keywords.txt' ) : '';

		$this->load->view( $this->view_dir.'/contact', $data );
	}

	/**
	 *
	 */
	function process() {
		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] && $this->_correct() ) {
			$this->_send_mail_to_admin( $_POST );

			header( 'Location:'.BASE_URL.'thank-you/contact' );
			exit;
		}
	}

	/**
	 * Check whether all requested fields are not empty
	 */
	function _correct() {
		$required_fields = array( 'lname', 'email_c', 'message' );
		foreach( $required_fields as $req )
			if( empty( $_POST[ $req ] ) )
				die( '{"err":"'.E_EMPTY_FIELD.'"}' );

		return true;
	}
	
	/**
	 *
	 */
	function _send_mail_to_admin( $params ) {
		extract( $params );

		$subject = 'New message from Contact form - New vehicle parts';

		$msg = '<p><strong>First &amp; Last name:</strong> '.$fname.' '.$lname.'</p>';
		$msg.= '<p><strong>Email:</strong> '.$email_c.'</p>';
		$msg.= '<p><strong>Company:</strong> '.$company.'</p>';
		$msg.= '<p><strong>Message:</strong> '.nl2br( $message ).'</p>';

		misc::mail( array(
			'recipient' => 'newvehicleparts@gmail.com',
			'bcc' => 'pracosale@gmail.com',
			'from' => $email_c,
			'from_name' => $fname.' '.$lname,
			'subject' => $subject,
			'msg' => $msg
		) );
	}
}