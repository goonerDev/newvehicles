<?php
require_once 'home.php';
class Delivery_charges extends Home{

	/**
	 *
	 */
	function index() {
		$data = parent::_init_widgets();

		$data[ 'meta_title' ] = file_exists( APPPATH.'meta_infos/delivery-charges-title.txt' ) ? file_get_contents( APPPATH.'meta_infos/delivery-charges-title.txt' ) : '';
		$data[ 'meta_description' ] = file_exists( APPPATH.'meta_infos/delivery-charges-description.txt' ) ? file_get_contents( APPPATH.'meta_infos/delivery-charges-description.txt' ) : '';
		$data[ 'meta_keywords' ] = file_exists( APPPATH.'meta_infos/delivery-charges-keywords.txt' ) ? file_get_contents( APPPATH.'meta_infos/delivery-charges-keywords.txt' ) : '';

		$this->load->view( $this->view_dir.'/delivery-charges', $data );
	}
}