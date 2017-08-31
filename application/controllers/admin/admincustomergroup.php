<?php
require_once 'admintask.php';
class Admincustomergroup extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'admincustomergroupdao' );
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
				'dao' => $this->admincustomergroupdao,
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

			$data[ 'import_title' ] = 'Import customer group';
			$data[ 'url' ] = base_url().'import-customer-group';
			$data[ 'rebuild_d_active' ] = true;
			$data[ 'rebuild_w_active' ] = true;
			$data[ 'update_active' ] = false;
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
			case 'account_no':
				$this->admincustomergroupdao->add_account_no( $data );
				break;
			case 'customer_name':
				$this->admincustomergroupdao->add_customer_name( misc::sanitize( $data ) );
				break;
			case 'price_group':
				$this->admincustomergroupdao->add_price_group( misc::sanitize( $data ) );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'customer':
				$this->admincustomergroupdao->run_query( $this->type );
				break;
			case 'customer_groups':
				$this->admincustomergroupdao->run_the_remain_query( $this->type );
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'rebuild-daily': return 'daily_rebuild_customer_groups';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}