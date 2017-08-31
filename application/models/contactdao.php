<?php
class ContactDao extends CI_Model {
	
	/**
	 *  
	 */
	function save( $params ) {
		$qry = "INSERT INTO contacts SET ";
		$qry.= "name='{$params[ 'name' ]}',";
		$qry.= "email='{$params[ 'email' ]}',";
		$qry.= "comments='{$params[ 'comments' ]}',";
		$qry.= "date=NOW()";
		return $this->db->query( $qry );
	}
}