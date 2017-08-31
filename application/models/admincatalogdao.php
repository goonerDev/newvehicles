<?php
class Admincatalogdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO catalog(id, manufacture, model, start_year, end_year, model_type, sku, product, side, manufacture_image, model_image, product_image, certificate, app_parts, ebay_carriage, group_desc, cross_reference) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_id = 
		$this->_temp_manufacture = 
		$this->_temp_model = 
		$this->_temp_start_year = 
		$this->_temp_end_year = 
		$this->_temp_model_type = 
		$this->_temp_sku = 
		$this->_temp_product = 
		$this->_temp_side = 
		$this->_temp_manufacture_image = 
		$this->_temp_model_image = 
		$this->_temp_product_image = 
		$this->_temp_certificate = 
		$this->_temp_app_parts = 
		$this->_temp_ebay_carriage =
		$this->_temp_group_desc =
		$this->_temp_cross_reference = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'catalog'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE catalog RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE catalog SELECT * FROM {$this->_table_name} WHERE 1=0" ) &&
				$this->db->query( "ALTER TABLE catalog CHANGE id id INT PRIMARY KEY AUTO_INCREMENT" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "DROP TABLE {$this->_table_name}" );
	}

	/**
	 *
	 */
	function additional_treatment( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "ALTER TABLE catalog ENGINE=MyISAM" ) && 
				$this->db->query( "ALTER TABLE catalog ADD FULLTEXT(manufacture, model, model_type, sku, product, cross_reference)" );
	}

	/**
	 *
	 */
	function add_id( $id ) {
		$this->_temp_id .= $id;
	}

	function add_manufacture( $manufacture ) {
		$this->_temp_manufacture .= $manufacture;
	}

	function add_model( $model ) {
		$this->_temp_model .= $model;
	}

	function add_start_year( $start_year ) {
		$this->_temp_start_year .= $start_year;
	}

	function add_end_year( $end_year ) {
		$this->_temp_end_year .= $end_year;
	}

	function add_model_type( $model_type ) {
		$this->_temp_model_type .= $model_type;
	}

	function add_sku( $sku ) {
		$this->_temp_sku .= $sku;
	}

	function add_product( $product ) {
		$this->_temp_product .= $product;
	}

	function add_side( $side ) {
		$this->_temp_side .= $side;
	}

	function add_manufacture_image( $manufacture_image ) {
		$this->_temp_manufacture_image .= $manufacture_image;
	}

	function add_model_image( $model_image ) {
		$this->_temp_model_image .= $model_image;
	}

	function add_product_image( $product_image ) {
		$this->_temp_product_image .= $product_image;
	}

	function add_certificate( $certificate ) {
		$this->_temp_certificate .= $certificate;
	}

	function add_app_parts( $app_parts ) {
		$this->_temp_app_parts .= $app_parts;
	}

	function add_ebay_carriage( $ebay_carriage ) {
		$this->_temp_ebay_carriage .= $ebay_carriage;
	}

	function add_group_desc( $group_desc ) {
		$this->_temp_group_desc .= $group_desc;
	}

	function add_cross_reference( $cross_reference ) {
		$this->_temp_cross_reference .= $cross_reference.',';
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE catalog FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\r\n'" );
	}

	/**
	 * Action on addresses consists of inserting only
	 */
	function run_query( $type ) {
		if( empty( $this->_temp_group_desc ) )
			$this->_temp_group_desc = 'Misc';

		$sp_pos = strpos( $this->_temp_product, ' ' );
		$this->_temp_side = $sp_pos === false ? trim( $this->_temp_product ) : substr( $this->_temp_product, 0, $sp_pos );

		$temp = "('{$this->_temp_id}', '{$this->_temp_manufacture}', '{$this->_temp_model}', '{$this->_temp_start_year}', '{$this->_temp_end_year}', '{$this->_temp_model_type}', '{$this->_temp_sku}','{$this->_temp_product}', '{$this->_temp_side}', '{$this->_temp_manufacture_image}', '{$this->_temp_model_image}', '{$this->_temp_product_image}', '{$this->_temp_certificate}', '{$this->_temp_app_parts}', '{$this->_temp_ebay_carriage}', '{$this->_temp_group_desc}', '{$this->_temp_cross_reference}'),";


		// Bulk insert on "rebuild"
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {

			$q = $this->_query.$temp;

			// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
			if( strlen( $q ) >= 4096 ) {
				$this->db->query( rtrim( $this->_query, ',' ) );
				unset( $this->_query );
				$this->_query = $this->_insert_instr.$temp;
			}
			else
				$this->_query = $q;

		} elseif( $type == 'update' ) {

			$q = $this->_insert_instr.rtrim( $temp, ',' );
			$q.= " ON DUPLICATE KEY UPDATE";
			$q.= " manufacture='{$this->_temp_manufacture}',";
			$q.= " model='{$this->_temp_model}',";
			$q.= " start_year='{$this->_temp_start_year}',";
			$q.= " end_year='{$this->_temp_end_year}',";
			$q.= " model_type='{$this->_temp_model_type}',";
			$q.= " sku='{$this->_temp_sku}',";
			$q.= " product='{$this->_temp_product}',";
			$q.= " side='{$this->_temp_side}',";
			$q.= " manufacture_image='{$this->_temp_manufacture_image}',";
			$q.= " model_image='{$this->_temp_model_image}',";
			$q.= " product_image='{$this->_temp_product_image}',";
			$q.= " certificate='{$this->_temp_certificate}',";
			$q.= " app_parts='{$this->_temp_app_parts}',";
			$q.= " ebay_carriage='{$this->_temp_ebay_carriage}',";
			$q.= " group_desc='{$this->_temp_group_desc}',";
			$q.= " cross_reference='{$this->_temp_cross_reference}'";

			$this->db->query( $q );

			// Clear the request
			$this->_query = '';
		}

		$this->_temp_id = 
		$this->_temp_manufacture = 
		$this->_temp_model = 
		$this->_temp_start_year = 
		$this->_temp_end_year = 
		$this->_temp_model_type = 
		$this->_temp_sku = 
		$this->_temp_product = 
		$this->_temp_side = 
		$this->_temp_manufacture_image = 
		$this->_temp_model_image = 
		$this->_temp_product_image = 
		$this->_temp_certificate = 
		$this->_temp_app_parts =
		$this->_temp_ebay_carriage = 
		$this->_temp_group_desc = 
		$this->_temp_cross_reference = '';
	}
}