<?php
require_once 'admintask.php';
class Admininvoicescredits extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'admininvoicescreditsdao' );
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
				'dao' => $this->admininvoicescreditsdao,
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

			$data[ 'import_title' ] = 'Import invoices / credits';
			$data[ 'url' ] = base_url().'import-invoices-credits';
			$data[ 'rebuild_d_active' ] = true;
			$data[ 'rebuild_w_active' ] = true;
			$data[ 'update_active' ] = false;
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
			case 'cust_id':
				$this->admininvoicescreditsdao->add_user_no( misc::sanitize( $data ) );
				break;
			case 'doc_date':
				$this->admininvoicescreditsdao->add_doc_date( misc::sanitize( $data ) );
				break;
			case 'doc_no':
				$this->admininvoicescreditsdao->add_doc_no( misc::sanitize( $data ) );
				break;
			case 'doc_type':
				$this->admininvoicescreditsdao->add_doc_type( misc::sanitize( $data ) );
				break;
			case 'sell_id':
				$this->admininvoicescreditsdao->add_sell_id( misc::sanitize( $data ) );
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'ledger_entry':
				$this->admininvoicescreditsdao->run_query( $this->type );
				break;
			case 'inv_Cre':
				$this->admininvoicescreditsdao->run_the_remain_query();
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'rebuild-daily': return 'daily_rebuild_inv_cre';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}