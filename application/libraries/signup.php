<?php
class _Signup extends Widget {

	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function process() {
		$_POST = Misc::sanitize( $_POST );

		if( empty( $_POST[ 'lname' ] ) )
			$this->_errors[] = E_EMPTY_LNAME;

		if( empty( $_POST[ 'password' ] ) )
			$this->_errors[] = E_EMPTY_PASSWORD;

		if( empty( $_POST[ 'email' ] ) )
			$this->_errors[] = E_EMPTY_EMAIL;
		elseif( !Misc::valid_email( $_POST[ 'email' ] ) )
			$this->_errors[] = E_INVALID_EMAIL_ADDR;
		elseif( UserDao::user_email_exists( $_POST[ 'email' ] ) )
			$this->_errors[] = E_DUPLICATE_EMAIL_ADDR;

		if( empty( $this->_errors ) ) {
			$err = UserDao::sign_user_up( $_POST );
			if( !$err )
				$this->_errors[] = E_UNKNOWN_ERROR;

			// TODO: next step after a successfull or failure signup
		}
	}
}