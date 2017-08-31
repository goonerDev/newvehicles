<?php
require_once 'home.php';
class Order extends Home {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->model( 'orderdao' );
	}

	/**
	 * Get all orders created by the current user
	 */
	function history() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();

		$data = parent::_init_widgets( $user, $cart_id );

		$params = array(
			'user_id' => misc::sanitize( $user[ 'id' ] )
		);
		$data[ 'orders' ] = $this->orderdao->history( $params );
		$data[ 'user_active' ] = true;

		$this->load->view( 'orders-list', $data );
	}
}
