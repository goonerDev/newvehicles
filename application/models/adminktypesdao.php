<?php
class Adminktypesdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO item_ktypes(sku, ktype, models_ktype_fits, item, certificate) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_sku = 
		$this->_temp_ktype = 
		$this->_temp_models_ktype_fits = 
		$this->_temp_item = 
		$this->_temp_certificate = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'item_ktypes'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE item_ktypes RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE item_ktypes SELECT * FROM {$this->_table_name} WHERE 1=0" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "DROP TABLE {$this->_table_name}" ) &&
				$this->db->query( "ALTER TABLE item_ktypes ADD INDEX item_ktypes(sku,ktype)" );
	}

	/**
	 *
	 */
	function additional_treatment() {
		return $this->db->query( "ALTER TABLE item_ktypes ADD INDEX(sku)" );
	}

	function add_sku( $sku ) {
		$this->_temp_sku.= $sku;
	}

	function add_ktype( $ktype ) {
		$this->_temp_ktype.= $ktype;
	}

	function add_models_ktype_fits( $models_ktype_fits ) {
		$this->_temp_models_ktype_fits.= $models_ktype_fits;
	}

	function add_item( $item ) {
		$this->_temp_item.= $item;
	}

	function add_certificate( $certificate ) {
		$this->_temp_certificate.= $certificate;
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE item_ktypes FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\n'(sku, ktype, models_ktype_fits, item, certificate)" );
	}

	/**
	 * Action on item ktypes consists of inserting only
	 */
	function run_query( $type ) {
		$this->_total++;

		$temp = "('{$this->_temp_sku}', '{$this->_temp_ktype}', '{$this->_temp_models_ktype_fits}', '{$this->_temp_item}', '{$this->_temp_certificate}'),";


		// Bulk insert on "rebuild"
		//if( $type == 'rebuild' ) {

		$q = $this->_query.$temp;

		// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
		if( strlen( $q ) >= 4096 ) {
			$this->db->query( rtrim( $this->_query, ',' ) );

			$this->_query = $this->_insert_instr.$temp;
		}
		else
			$this->_query = $q;

		$this->_temp_sku = 
		$this->_temp_models_ktype_fits = 
		$this->_temp_ktype = 
		$this->_temp_item = 
		$this->_temp_certificate = '';
	}
}