<?php
require_once 'home.php';
class Recaptcha extends Home {

	/**
	 *
	 */
	function index() {
		$data = parent::_init_widgets();

		$this->load->view( $this->view_dir.'/recaptcha', $data );
	}
}