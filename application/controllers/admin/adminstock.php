<?php
require_once 'admintask.php';
class Adminstock extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminstockdao' );
	}

	/**
	 *
	 */
	function import() {
		if( $this->is_admin && !$this->_bot ) {
			header( 'location:'.base_url() );
			exit;
		}

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$this->type = @$_POST[ 'import_type' ];
			$this->format = @$_POST[ 'import_format' ];
			$this->source = @$_POST[ 'import_source' ];

			parent::import( array(
				'dao' => $this->adminstockdao,
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

			$data[ 'import_title' ] = 'Import stock';
			$data[ 'url' ] = base_url().'import-stock';
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
			case 'item_no':
				$this->adminstockdao->add_item_no( misc::sanitize( $data ) );
				break;
			case 'free_stock':
				$this->adminstockdao->add_free_stock( ( int ) $data );
				break;
			case 'due_date':
				$this->adminstockdao->add_due_date( $data );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'item':
				$this->adminstockdao->run_query( $this->type );
				break;
			case 'items':
				$this->adminstockdao->run_the_remain_query( $this->type );
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'update': return 'partial_rebuild_stock*';
			case 'rebuild-daily': return 'daily_rebuild_stock';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}