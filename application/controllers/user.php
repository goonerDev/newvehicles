<?php
require_once 'home.php';
class User extends Home {
	function __construct() {
		parent::__construct();
	}

	/**
	 * url format /user/all/pattern/offset
	 */
	function all() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		if( $user[ 'admin' ] != 1 ) {
			header( 'Location:'.BASE_URL );
			exit;
		}

		$init_pattern = $this->uri->segment( 3 );
		$pattern = $init_pattern == '-' ? '' : misc::sanitize( $init_pattern );
		$start = ( int ) $this->uri->segment( 4 );
		$params = array(
			'pattern' => $pattern,
			'start' => $start,
			'limit' => 25
		);
		$data[ 'users' ] = $this->userdao->all( $params );
		$data[ 'pattern' ] = $init_pattern;

		$this->load->library( 'Pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			BASE_URL.'user/all/'.$init_pattern.'/',
			$start,
			25,
			$this->userdao->all_count( $params )
		);

		$this->load->view( 'new/users-list', $data );
	}

	/**
	 *
	 */
	function addresses() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		$params = array(
			'customer_id' => $user[ 'user_no' ]
		);
		$data[ 'addresses' ] = $this->userdao->addresses( $params );

		// $data[ 'login' ] points to _login widget
		$data[ 'login' ]->_data[ 'user_active' ] = true;

		$this->load->view( 'new/addresses-list', $data );
	}

	/**
	 *
	 */
	function add_address() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
			if( true === ( $data[ 'error' ] = $this->_correct_address() ) ) {
				$_POST[ 'user_id' ] = $user[ 'user_no' ];
				$id = $this->userdao->save_address( misc::sanitize( $_POST ) );

				if( stristr( $_POST[ 'referer' ], 'delivery-address' ) ) {
					// It's better to use modular hmvc here
					$_POST[ 'deliv_addr' ] = $id;
					$this->load->library( '../controllers/cart' );
					$this->cart->confirm_order();
				}
				else
					header( 'Location:'.$_POST[ 'referer' ] );
				exit;
			}

			$data[ 'addr' ] = $_POST;
			$data[ 'referer' ] = $_POST[ 'referer' ];
		}
		else {
			$id = ( int ) $this->uri->segment( 2 );
			$params = array(
				'id' => $id,
				'user_id' => $user[ 'user_no' ]
			);

			$data[ 'addr' ] = $this->userdao->get_address( $params );
			$data[ 'referer' ] = $_SERVER[ 'HTTP_REFERER' ];
		}

		// $data[ 'login' ] points to _login widget
		$data[ 'login' ]->_data[ 'user_active' ] = true;

		$this->load->view( 'new/address-detail', $data );
	}

	/**
	 *
	 */
	function forgot_password() {
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$email = misc::sanitize( $_POST[ 'email' ] );
			if( empty( $email ) )
				die( E_NOT_EXISTING_EMAIL_ADDR );

			if( ( $user = $this->userdao->user_email_exists( $email ) ) == false )
				die( E_NOT_EXISTING_EMAIL_ADDR );

			$sid = $this->userdao->set_user_cookie( $user );

			$link = BASE_URL.'forgot-password/'.base64_encode( $sid[ '_s' ] );
			$msg = 'Click on the link to reset your password. <a href="'.$link.'">'.$link.'</a>';

			parent::mail( array(
				'recipient' => $user[ 'email' ],
				'subject' => 'Password recovery - www.prasco.co.uk',
				'from' => 'no-reply@prasco.co.uk',
				'msg' => $msg,
				'error_label' => 'Sending password recovery mail has failed: '
			) );

			die( E_MAIL_SENT );
		}
		else {
			$hsid = misc::sanitize( $this->uri->segment( 2 ) );
			$sid = base64_decode( misc::sanitize( $this->uri->segment( 2 ) ) );
			$user = $this->userdao->check_user( $sid );

			if( empty( $user[ 'id' ] ) )
				header( 'Location:'.BASE_URL );
			else
				header( 'Location:'.BASE_URL.'change-password/'.$hsid );

			exit;
		}
	}

	/**
	 *
	 */
	function change_password_from_logged_in() {
		$hsid = $this->uri->segment( 2 );
		if( !empty( $hsid ) ) {
			$sid = base64_decode( misc::sanitize( $hsid ) );
			$user = $this->userdao->check_user( $sid );
			setcookie( '_s', $sid, 0, '/' );
		}
		else
			$user = parent::_valid_connection();

		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
			if( !empty( $_POST[ 'pass1' ] ) ) {
				$params = array(
					'user_id' => misc::sanitize( $user[ 'user_no' ] ),
					'email' => misc::sanitize( $user[ 'email' ] ),
					'pass' => misc::sanitize( $_POST[ 'pass1' ] )
				);
				$this->userdao->set_password( $params );

				header( 'Location:'.BASE_URL.'account' );
				exit;
			}

			$data[ 'error' ] = $this->_get_alert_message( E_INCORRECT_PASSWORD );
		}

		// $data[ 'login' ] points to _login widget
		$data[ 'login' ]->_data[ 'user_active' ] = true;

		$this->load->view( 'new/password-reset', $data );
	}

	/**
	 *
	 */
	function add_account() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		if( $user[ 'admin' ] != 1 ) {
			header( 'Location:'.BASE_URL );
			exit;
		}

		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
			$data[ 'user' ] = @$_POST[ 'user' ];
			$data[ 'addr' ] = @$_POST[ 'addr' ];

			if( true === ( $data[ 'error' ] = $this->_correct_personal_info() ) ) {
				$params = misc::sanitize( $_POST[ 'user' ] );
				$this->userdao->save_personal_info( $params );

				if( !empty( $_POST[ 'pass1' ] ) ) {
					$params = array(
						'user_id' => $params[ 'user_no' ],
						'email' => misc::sanitize( $data[ 'user' ][ 'email' ] ),
						'pass' => misc::sanitize( $_POST[ 'pass1' ] )
					);
					$this->userdao->set_password( $params );
				}

				// VRM access is activated for the user if it is 0 in the database but in the IU the checkbox is set to 1
				$params[ 'vrm_access' ] = $params[ 'vrm_access' ] == 1 ? 0 : 1;
				$this->userdao->save_vrm_access( $params );

				$this->userdao->save_suspended( $params );

				header( 'Location:'.BASE_URL.'account' );
				exit;
			}

			$data[ 'error' ] = $this->_get_alert_message( $data[ 'error' ] );
		}

		// $data[ 'login' ] points to _login widget
		$data[ 'login' ]->_data[ 'user_active' ] = true;
		$data[ 'isadmin' ] = 1;

		$this->load->view( 'new/user-edit', $data );
	}

	/**
	 * Getting the account that needs to be changed has 2 ways:
	 * - user_no is provided by the url (www.domain.co.uk/account/user_no/encoded_email), this means the we are getting here from
	 * list of users. Only admin can have this access
	 * - user_no is not provided (www.domain.co.uk/account). It means user id is read from cookies, the owner of the account can do this
	 */
	function edit_account() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		$user_no = $this->uri->segment( 2 );

		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
			$data[ 'user' ] = @$_POST[ 'user' ];
			if( true === ( $data[ 'error' ] = $this->_correct_personal_info() ) ) {
				$params = misc::sanitize( $_POST[ 'user' ] );
				$this->userdao->save_personal_info( $params );

				if( !empty( $_POST[ 'pass1' ] ) ) {
					$params = array(
						'user_id' => $params[ 'user_no' ],
						'email' => misc::sanitize( $data[ 'user' ][ 'email' ] ),
						'pass' => misc::sanitize( $_POST[ 'pass1' ] )
					);
					$this->userdao->set_password( $params );
				}

				if( $user[ 'admin' ] == 1 ) {
					// VRM access is activated for the user if it is 0 in the database but in the IU the checkbox is set to 1
					$params[ 'vrm_access' ] = $params[ 'vrm_access' ] == 1 ? 0 : 1;
					$this->userdao->save_vrm_access( $params );

					$this->userdao->save_suspended( $params );
				}

				if( $user[ 'admin' ] == 1 )
					header( 'Location:'.BASE_URL.'user/all/-/0' );
				elseif( $_POST[ 'action' ] == 'save' )
					header( 'Location:'.BASE_URL.'account' );
				elseif( $_POST[ 'action' ] == 'save_and_add' )
					header( 'Location:'.BASE_URL.'account' );

				exit;
			}

			$data[ 'error' ] = $this->_get_alert_message( $data[ 'error' ] );
		}
		else {
			// A customer updating his own data, no need to pass his account number on url.
			// Pull data from cookies
			if( empty( $user_no ) ) {
				$user_no = misc::sanitize( $user[ 'user_no' ] );
				$email = misc::sanitize( $user[ 'email' ] );
			}
			else
				$email = base64_decode( $this->uri->segment( 3 ) );

			$data[ 'user' ] = $this->userdao->get_personal_info( $user_no, $email );
		}

		// $data[ 'login' ] points to _login widget
		$data[ 'login' ]->_data[ 'user_active' ] = true;
		$data[ 'isadmin' ] = $user[ 'admin' ];

		$this->load->view( 'new/user-edit', $data );
	}

	/**
	 *
	 */
	function logout() {
		$user = parent::_valid_connection();
		$this->userdao->logout_user( $user[ 'id' ] );
	}

	/**
	 *
	 */
	function invoices_creditnotes() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		$type =  misc::sanitize( $this->uri->segment( 2 ) );
		$start = ( int ) $this->uri->segment( 3 );
		$params = array(
			'user_id' => $user[ 'user_no' ],
			'type' => $type,
			'start' => $start,
			'limit' => 30
		);
		$data[ 'inv_cred' ] = $this->userdao->invoices_creditnotes( $params );

		$this->load->library( 'Pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			BASE_URL.'invoices-credit-notes/'.$type,
			$start,
			30,
			$this->userdao->invoices_creditnotes_count( $user[ 'user_no' ], $type )
		);

		$data[ 'title' ] = $type == 'Invoice' ? 'Invoices' : 'Credit notes';
		$data[ 'login' ]->_data[ 'user_active' ] = true;

		$this->load->view( 'new/invoices-credit-notes', $data );
	}

	/**
	 *
	 */
	function invoices_creditnotes_download() {
		$user = parent::_valid_connection();

		$doc_no = $this->uri->segment( 2 );
		if( !empty( $doc_no ) ) {
			$params = array(
				'user_id' => $user[ 'user_no' ],
				'doc_no' => misc::sanitize( $doc_no )
			);
			
			$doc = $this->userdao->read_invoices_creditnotes( $params );
			if( !empty( $doc ) )
				$this->_generate_inv_cred_report( $doc );
			else
				die( '{"err":"'.misc::esc_json( E_ITEM_NOT_FOUND ).'"}' );
		}
		else
			die( '{"err":"'.misc::esc_json( E_EMPTY_FIELD ).'"}' );
	}

	/**
	 *
	 */
	function _correct_address() {
		$required = array( 'recipient', 'address', 'town', 'county' );
		foreach( $required as $req )
			if( empty( $_POST[ $req ] ) )
				return E_EMPTY_FIELD;

		return true;
	}

	/**
	 *
	 */
	function _correct_personal_info() {
		$required = array(
			'user' => array( 'user_no', 'fname', 'lname', 'email' )
		);
		foreach( $required as $key => $reqs )
			foreach( $reqs as $req )
				if( empty( $_POST[ $key ][ $req ] ) )
					return E_EMPTY_FIELD;

		return true;
	}

	/**
	 *
	 */
	function _generate_inv_cred_report( $doc ) {
		require_once APPPATH.'libraries/ntlm_http.php';

		$ns = new NTLM_HTTP( 'pda.panelsandlamps.co.uk:7047', 'mal', 'P4n3l5Lamps' );

		$hdr = array(
			'Connection: Keep-Alive',
			'Content-type: text/xml; charset=utf-8',
			'SOAPAction: "urn:microsoft-dynamics-schemas/codeunit/GetInvoice:GenerateReport"'
		);

		$customer_id = misc::esc_xml_data( $doc[ 'customer_id' ] );
		$doc_no = misc::esc_xml_data( $doc[ 'doc_no' ] );
		$sell_id = misc::esc_xml_data( $doc[ 'sell_id' ] );
		$doc_type = $doc[ 'doc_type' ] == 'Invoice' ? '0' : '1';

		$postvalues = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><GenerateReport xmlns="urn:microsoft-dynamics-schemas/codeunit/GetInvoice"><customerIn>'.$sell_id.'</customerIn><invoiceCreditNoIn>'.$doc_no.'</invoiceCreditNoIn><_InvoiceCredit>'.$doc_type.'</_InvoiceCredit><pDFDocument /></GenerateReport></soap:Body></soap:Envelope>';

		$res = $ns->post( '/DynamicsNav/WS/Direct%20Automotive%20Ltd/Codeunit/GetInvoice', $hdr, $postvalues );

		$dom = new DomDocument();
		$dom->loadXML( $res[ 'body' ] );

		if( 400 > $res[ 'status' ] && !empty( $res[ 'headers' ][ 'Content-Length' ] ) ) {
			$generated_doc = $dom->getElementsByTagName( 'pDFDocument' );
			if( !empty( $generated_doc ) )
				parent::download( $doc_no.'.pdf', base64_decode( $generated_doc->item( 0 )->nodeValue ) );
		}
		else {
			$error_msg = $dom->getElementsByTagName( 'faultstring' );
			if( !empty( $error_msg ) )
				echo $error_msg->item( 0 )->nodeValue;
		}
	}

	/**
	 *
	 */
	function _get_alert_message( $msg = false ) {
		$msg = strToUpper( $msg );

		switch( $msg ) {
			case V_OK: return '<p class="alert alert-success">'.$msg.'</p>';

			case E_INCORRECT_PASSWORD:
			case E_EMPTY_FIELD: return '<p class="alert alert-danger">'.$msg.'</p>';
			default: return '';
		}
	}
}