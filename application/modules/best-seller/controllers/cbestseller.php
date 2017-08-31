<?php
class Cbestseller extends MX_Controller {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->model( 'bestsellerdao' );
	}
	/**
	 *
	 */
	function random( $view_dir, $limit, $customer_group, $user_no, $vat, $is_admin, $format = false ) {
		$seller_scores = $this->bestsellerdao->random( $limit, $customer_group, $user_no, $vat );

		if( $format == 'raw' )
			return $seller_scores;

		$data[ 'data' ] = $seller_scores;
		$data[ 'user_no' ] = $user_no;
		$data[ 'view_dir' ] = $view_dir;
		$data[ 'vat' ] = $vat;
		$data[ 'is_admin' ] = $is_admin;
		$this->load->view( $view_dir.'/best-seller', $data );
	}

	/**
	 *
	 */
	function update_by_new_order( $store_id, $items ) {
		$this->bestsellerdao->update_by_new_order( $store_id, $items );
	}
}