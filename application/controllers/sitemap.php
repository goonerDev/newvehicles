<?php
require_once 'home.php';
class Sitemap extends Home {
	/**
	 *
	 */
	function index() {
		$data = parent::_init_widgets();

		$this->load->view( $this->view_dir.'/header', $data );
		$this->load->view( $this->view_dir.'/sitemap.html' );
		$this->load->view( $this->view_dir.'/footer', $data );
	}
}
