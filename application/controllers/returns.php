<?php
require_once 'home.php';
class Returns extends Home {
	function index() {
		$data = parent::_init_widgets();

		$this->load->view( $this->view_dir.'/returns', $data );
	}
}