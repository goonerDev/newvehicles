<?php
require_once 'admintask.php';
class Adminquote extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'adminquotedao' );
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
				'dao' => $this->adminquotedao,
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

			$data[ 'import_title' ] = 'Import quotes';
			$data[ 'url' ] = base_url().'import-quote';
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
			case 'quote_no':
				$this->adminquotedao->add_quote_no( misc::sanitize( $data ) );
				break;
			case 'acc_no':
				$this->adminquotedao->add_acc_no( misc::sanitize( $data ) );
				break;
			case 'quote_date':
				$this->adminquotedao->add_quote_date( misc::sanitize( $data ) );
				break;
			case 'your_ref':
				$this->adminquotedao->add_your_ref( misc::sanitize( $data ) );
				break;
			case 'insurance_oder_no':
				$this->adminquotedao->add_insurance_order_no( misc::sanitize( $data ) );
				break;
			case 'insurance_company':
				$this->adminquotedao->add_insurance_company( misc::sanitize( $data ) );
				break;
			case 'estimate_no':
				$this->adminquotedao->add_estimate_no( misc::sanitize( $data ) );
				break;
			case 'part_no':
				$this->adminquotedao->add_part_no( misc::sanitize( $data ) );
				break;
			case 'model':
				$this->adminquotedao->add_model( misc::sanitize( $data ) );
				break;
			case 'product_item':
				$this->adminquotedao->add_product_item( misc::sanitize( $data ) );
				break;
			case 'unit_price':
				$this->adminquotedao->add_unit_price( misc::sanitize( $data ) );
				break;
			case 'oe_price':
				$this->adminquotedao->add_oe_price( misc::sanitize( $data ) );
				break;
			case 'charge_out_price':
				$this->adminquotedao->add_charge_out_price( misc::sanitize( $data ) );
				break;
			case 'oe_part_no':
				$this->adminquotedao->add_oe_part_no( ( int ) $data );
				break;
		}
	}

	/**
	 * 
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'lines':
				$this->adminquotedao->run_query( $this->type );
				break;
			case 'quotes':
				$this->adminquotedao->run_the_remain_query( $this->type );
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'insert': return 'partial_import_quotes';
			case 'rebuild-daily': return 'daily_rebuild_quotes';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}