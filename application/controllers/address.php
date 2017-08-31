<?php
require_once 'home.php';
class Address extends Home {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->library( '_address' );
		$this->load->model( 'addressdao' );

		$this->_address->addressdao = $this->addressdao;
	}

	/**
	 *
	 */
	function delivery() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart( true );
		$data = parent::_init_widgets( $user, $cart_id );

		$data[ 'addresses' ] = $this->userdao->addresses( array( 'customer_id' => @$user[ 'user_no' ] ) );
		
		$data[ 'zcarriage' ] = $this->catalogdao->carriage_by_customer_group( array( 'user_id' => @$user[ 'user_no' ], 'customer_group' => implode( "','", @$user[ 'customer_group' ] ) ) );

		// May be shipping address already set then reinit
		$this->load->model( 'cartdao' );
		$this->cartdao->set_shipping_address( array( 'cart_id' => $cart_id, 'user_id' => $user[ 'user_no' ], 'address' => 0 ) );
		$this->cartdao->remove_carriage_address(  array( 'cart_id' => $cart_id, 'user_id' => $user[ 'user_no' ] ) );

		$this->load->view( 'new/addresses-delivery-list', $data );
	}
}