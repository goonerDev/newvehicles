<?php
require_once 'admintask.php';
class Admincatalog extends Admintask {

	function __construct() {
		parent::__construct();

		$this->load->model( 'admincatalogdao' );
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
				'dao' => $this->admincatalogdao,
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

			$data[ 'import_title' ] = 'Import catalogue';
			$data[ 'url' ] = base_url().'import-catalogue';
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
			case 'catalog_item':
				$this->admincatalogdao->_temp_cross_reference = '';
				$this->admincatalogdao->_temp_product = '';
				$this->admincatalogdao->_temp_model_type = '';
				break;
			case 'id':
				$this->admincatalogdao->add_id( $data );
				break;
			case 'manufacture':
				$this->admincatalogdao->add_manufacture( misc::sanitize( $data ) );
				break;
			case 'model':
				$this->admincatalogdao->add_model( misc::sanitize( $data ) );
				break;
			case 'start_year':
				$this->admincatalogdao->add_start_year( ( int ) $data );
				break;
			case 'end_year':
				$this->admincatalogdao->add_end_year( ( int ) $data );
				break;
			case 'model_type':
				$this->admincatalogdao->add_model_type( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'sku':
				$this->admincatalogdao->add_sku( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'product':
				$this->admincatalogdao->add_product( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'side':
				$this->admincatalogdao->add_side( misc::sanitize( $data ) );
				break;
			case 'manufacture_image':
				$this->admincatalogdao->add_manufacture_image( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'model_image':
				$this->admincatalogdao->add_model_image( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'product_image':
				$this->admincatalogdao->add_product_image( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'certificate':
				$this->admincatalogdao->add_certificate( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'app_parts':
				$this->admincatalogdao->add_app_parts( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'group_desc':
				$this->admincatalogdao->add_group_desc( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'ebay_carriage':
				$this->admincatalogdao->add_ebay_carriage( misc::sanitize( html_entity_decode( $data ) ) );
				break;
			case 'crossref':
				$this->admincatalogdao->add_cross_reference( misc::sanitize( $data ) );
				break;
		}
	}

	/**
	 *
	 */
	function _end_tag( $parser, $name ) {
		switch( strtolower( $name ) ) {
			case 'catalog_item':
				$this->admincatalogdao->run_query( $this->type );
				break;
			case 'catalogs':
				$this->admincatalogdao->run_the_remain_query();
		}

		$this->_cur_tag = false;
	}

	/**
	 *
	 */
	function _get_filename() {
		switch( $this->type ) {
			case 'rebuild-daily': return 'daily_rebuild_catalogue';
		}
		die( '{"err":"'.E_UNKNOWN_IMPORT_ACTION.'"}' );
	}
}