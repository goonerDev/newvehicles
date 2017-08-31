<?php
require_once 'admintask.php';
class Adminorderstatus extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminorderstatusdao' );
	}

	/**
	 *
	 */
	function import() {
		if( !$this->is_admin && !$this->_bot ) {
			header( 'location:'.base_url() );
			exit;
		}

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$this->type = @$_POST[ 'import_type' ];
			$this->format = @$_POST[ 'import_format' ];
			$this->source = @$_POST[ 'import_source' ];

			parent::import( array(
				'dao' => $this->adminorderstatusdao,
				'file' => $this->_get_filename(),
				'import_type' => $this->type,
				'format' => $this->format,
				'source' => $this->source
			) );
		}
		else {
			$data = parent::_init_widgets();
			$data[ 'scripts' ][] = base_url().'assets/js/bootstrap.fileinput.js';
			$data[ 'scripts' ][] = base_url().'assets/js/file-uploader.js';

			$data[ 'import_title' ] = 'Import orders tracking';
			$data[ 'url' ] = base_url().'import-price';
			$data[ 'rebuild_d_active' ] = false;
			$data[ 'rebuild_w_active' ] = false;
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
			case 'order_no':
				$this->adminorderstatusdao->add_order_no( misc::sanitize( $data ) );
				break;
			case 'status':
				$this->adminorderstatusdao->add_status( misc::sanitize( $data ) );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'order_status':
				$this->adminorderstatusdao->run_query( $this->type );
				break;
			case 'orders':
				$this->adminorderstatusdao->run_the_remain_query( $this->type );
				break;
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'update': return 'partial_update_orders_status';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}