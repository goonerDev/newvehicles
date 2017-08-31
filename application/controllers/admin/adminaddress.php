<?php
require_once 'admintask.php';
class Adminaddress extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminaddressdao' );
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
				'dao' => $this->adminaddressdao,
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

			$data[ 'import_title' ] = 'Import addresses';
			$data[ 'url' ] = base_url().'import-address';
			$data[ 'rebuild_d_active' ] = true;
			$data[ 'rebuild_w_active' ] = true;
			$data[ 'update_active' ] = true;
			$data[ 'insert_active' ] = true;

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
			case 'id':
				$this->adminaddressdao->add_id( ( int ) $data );
				break;
			case 'customer_id':
			case 'ship-to-id':
				$this->adminaddressdao->add_customer_id( misc::sanitize( $data ) );
				break;
			case 'recipient':
			case 'ship-to-recipient':
				$this->adminaddressdao->add_recipient( misc::sanitize( $data ) );
				break;
			case 'address':
			case 'ship-to-address':
				$this->adminaddressdao->add_address( misc::sanitize( $data ) );
				break;
			case 'town':
			case 'ship-to-town':
				$this->adminaddressdao->add_town( misc::sanitize( $data ) );
				break;
			case 'county':
			case 'ship-to-county':
				$this->adminaddressdao->add_county( misc::sanitize( $data ) );
				break;
			case 'post_code':
			case 'ship-to-postcode':
				$this->adminaddressdao->add_post_code( $data );
				break;
			case 'by_default':
			case 'ship-to-default':
				$this->adminaddressdao->add_by_default( ( int ) $data );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'customer':
			case 'shipping':
				$this->adminaddressdao->run_query( $this->type );
				break;
			case 'customers':
				$this->adminaddressdao->run_the_remain_query( $this->type );
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'update': return 'partial_update_addresses';
			case 'insert': return 'partial_import_addresses';
			case 'rebuild-daily': return 'daily_rebuild_addresses';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}