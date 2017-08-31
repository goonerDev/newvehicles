<?php
class Bugdao extends CI_Model {
	/**
	 * @param array $params time - ip - word
	 */
	function save_captcha( $params ) {
		extract( $params );

		$this->db->query( "INSERT INTO captcha SET time='".$time."', word='".$word."', ip='".$ip."'" );
	}

	/**
	 * @param array $params ip - word
	 */
	function check_captcha( $params ) {
		extract( $params );

		// Clean up the database from old captcha
		$expiration = time() - 7200;
		$this->db->query( "DELETE FROM captcha WHERE time<=".$expiration );

		$res = $this->db->query( "SELECT COUNT(*) nb FROM captcha WHERE time>".$expiration." AND word='".$word."' AND ip='".$ip."'" )->row_array();
		return $res[ 'nb' ] != 0;
	}
}