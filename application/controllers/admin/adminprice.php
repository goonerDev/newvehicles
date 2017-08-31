<?php
require_once 'admintask.php';
class Adminprice extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminpricedao' );
	}

	/**
	 *
	 */
	function import() {
		$user = parent::_valid_connection();

		if( $user[ 'admin' ] != 1 && !$this->_bot ) {
			header( 'location:'.base_url() );
			exit;
		}

		$cart_id = parent::_valid_cart();

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$this->type = @$_POST[ 'import_type' ];
			$this->format = @$_POST[ 'import_format' ];
			$this->source = @$_POST[ 'import_source' ];

			parent::import( array(
				'dao' => $this->adminpricedao,
				'file' => $this->_get_filename(),
				'import_type' => $this->type,
				'format' => $this->format,
				'source' => $this->source
			) );
		}
		else {
			$data = parent::_init_widgets( $user, $cart_id );
			$data[ 'scripts' ][] = base_url().'assets/js/bootstrap.fileinput.js';
			$data[ 'scripts' ][] = base_url().'assets/js/file-uploader.js';

			$data[ 'import_title' ] = 'Import prices';
			$data[ 'url' ] = base_url().'import-price';
			$data[ 'rebuild_d_active' ] = true;
			$data[ 'rebuild_w_active' ] = true;
			$data[ 'update_active' ] = true;
			$data[ 'insert_active' ] = false;

			$this->load->view( 'new/admin/import', $data );
		}
	}

	/**
	 *
	 */
	function _data_xml( $parser, $data ) {
		if( $this->_cur_tag == false )
			return;

		switch( $this->_cur_tag ) {
			case 'sku':
				$this->adminpricedao->add_sku( misc::sanitize( $data ) );
				break;
			case 'customer_group':
				$this->adminpricedao->add_customer_group( misc::sanitize( $data ) );
				break;
			case 'price':
				$this->adminpricedao->add_price( $data );
				break;
			case 'price_type':
				$this->adminpricedao->add_price_type( misc::sanitize( $data ) );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'sales_price':
				$this->adminpricedao->run_query( $this->type );
				break;
			case 'prices':
				$this->adminpricedao->run_the_remain_query( $this->type );
				break;
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'update': return 'partial_update_sales_prices';
			case 'rebuild-daily': return 'daily_rebuild_sales_prices';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}