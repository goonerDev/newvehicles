<?php
class Clogin extends MX_Controller {

	/**
	 *
	 */
	function index( $view_dir, $store_id ) {
		$data[ '_e' ] = $data[ '_s' ] = $data[ '_r' ] = false;
		$data[ 'referer' ] = @$_SERVER[ 'HTTP_REFERER' ];

		if( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
			$_POST = misc::sanitize( $_POST );
			extract( $_POST );

			if( empty( $password ) )
				$data[ '_r' ][] = E_EMPTY_PASSWORD;

			if( empty( $email ) )
				$data[ '_r' ][] = E_EMPTY_EMAIL_ADDR;

			if( empty( $data[ '_r' ] ) ) {
				$this->load->module( 'user' );

				// Is it a customer?
				if( ( $user = $this->user->valid_user( $email, $password ) ) == false ) {
					// No? Maybe an admin
					if( ( $user = $this->userdao->valid_user_admin( $email, $password ) ) == false )
						$data[ '_r' ][] = E_UNAUTHORIZED_ACCESS;
					else {
						// $this->userdao->set_admin_user_cookie( $user[ 'id' ], $this->session->userdata( 'session_id' ) );

						$this->session->set_userdata( array(
							'id' => $user[ 'id' ],
							'admin' => 1,
							'user_no' => '',
							'email' => $email,
							'customer_group' => '',
							'store_id' => $store_id )
						);
					}
				}
				else {
					// $this->userdao->set_user_cookie( $user[ 'id' ], $this->session->userdata( 'session_id' ) );
					$this->session->set_userdata( array(
						'id' => $user[ 'id' ],
						'admin' => 0,
						'user_no' => $user[ 'user_no' ],
						'email' => $email,
						'customer_group' => str_replace( ',', "','", $user[ 'customer_group' ] ),
						'store_id' => $store_id )
					);
				}
			}

			$this->load->view( $this->view_dir.'/login-after', $data );
		}
		else
			$this->load->view( $this->view_dir.'/login' );
	}
}