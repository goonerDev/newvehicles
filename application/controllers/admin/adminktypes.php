<?php
require_once 'admintask.php';
class Adminktypes extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminktypesdao' );
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
				'dao' => $this->adminktypesdao,
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

			$data[ 'import_title' ] = 'Import ktypes';
			$data[ 'url' ] = base_url().'import-ktype';
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
			case 'sku':
				$this->adminktypesdao->add_sku( misc::sanitize( $data ) );
				break;
			case 'ktype':
				$this->adminktypesdao->add_ktype( ( int ) $data );
				break;
			case 'models_ktype_fits':
				$this->adminktypesdao->add_models_ktype_fits( misc::sanitize( $data ) );
				break;
			case 'item':
				$this->adminktypesdao->add_item( misc::sanitize( $data ) );
				break;
			case 'certificate':
				$this->adminktypesdao->add_certificate( misc::sanitize( $data ) );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'ktype_rec':
				$this->adminktypesdao->run_query( $this->type );
				break;
			case 'ktypes':
				$this->adminktypesdao->run_the_remain_query();
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'rebuild-daily': return 'daily_rebuild_ktypes';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}