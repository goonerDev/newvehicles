<?php
require_once 'admintask.php';
class Adminuser extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminuserdao' );
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
				'dao' => $this->adminuserdao,
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

			$data[ 'import_title' ] = 'Import users';
			$data[ 'url' ] = base_url().'import-user';
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
			case 'id':
				$this->adminuserdao->add_id( ( int ) $data );
				break;
			case 'user_no':
				$this->adminuserdao->add_user_no( misc::sanitize( $data ) );
				break;
			case 'fname':
				$this->adminuserdao->add_fname( misc::sanitize( $data ) );
				break;
			case 'lname':
				$this->adminuserdao->add_lname( misc::sanitize( $data ) );
				break;
			case 'email':
				$this->adminuserdao->add_email( misc::sanitize( $data ) );
				break;
			case 'password':
				$this->adminuserdao->add_password( misc::sanitize( $data ) );
				break;
			case 'suspended':
				$this->adminuserdao->add_suspended( $data );
				break;
			case 'vrm_access':
				$this->adminuserdao->add_vrm_access( $data );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'user':
				$this->adminuserdao->run_query( $this->type );
				break;
			case 'users':
				$this->adminuserdao->run_the_remain_query();
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'insert': return 'partial_import_users';
			case 'rebuild-daily': return 'daily_rebuild_users';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}