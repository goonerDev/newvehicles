<?php
require_once 'home.php';
class Terms_conditions extends Home{

	/**
	 *
	 */
	function index() {
		$data = parent::_init_widgets();

		$data[ 'meta_title' ] = file_exists( APPPATH.'meta_infos/terms-conditions-title.txt' ) ? file_get_contents( APPPATH.'meta_infos/terms-conditions-title.txt' ) : '';
		$data[ 'meta_description' ] = file_exists( APPPATH.'meta_infos/terms-conditions-description.txt' ) ? file_get_contents( APPPATH.'meta_infos/terms-conditions-description.txt' ) : '';
		$data[ 'meta_keywords' ] = file_exists( APPPATH.'meta_infos/terms-conditions-keywords.txt' ) ? file_get_contents( APPPATH.'meta_infos/terms-conditions-keywords.txt' ) : '';

		$this->load->view( $this->view_dir.'/terms-conditions', $data );
	}
}