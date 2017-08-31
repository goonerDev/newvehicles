<?php
class Adminquotedao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO quotes(quote_no, acc_no, quote_date, your_ref, insurance_oder_no, insurance_company, estimate_no, part_no, model, product_item, unit_price, oe_price, charge_out_price, oe_part_no) VALUES ";

		$this->_query = $this->_insert_instr;

		$this->_temp_quote_no = 
		$this->_temp_acc_no = 
		$this->_temp_quote_date = 
		$this->_temp_your_ref = 
		$this->_temp_insurance_order_no = 
		$this->_temp_insurance_company = 
		$this->_temp_estimate_no =
		$this->_temp_part_no =
		$this->_temp_model =
		$this->_temp_product_item =
		$this->_temp_unit_price =
		$this->_temp_oe_price =
		$this->_temp_charge_out_price =
		$this->_temp_oe_part_no = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'quotes'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE quotes RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE quotes SELECT * FROM {$this->_table_name} WHERE 1=0" ) &&
				$this->db->query( "ALTER TABLE quotes CHANGE id id INT PRIMARY KEY AUTO_INCREMENT" );
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
	function additional_treatment() {}

	function add_quote_no( $quote_no ) {
		$this->_temp_quote_no.= $quote_no;
	}

	function add_acc_no( $acc_no ) {
		$this->_temp_acc_no.= $acc_no;
	}

	function add_quote_date( $quote_date ) {
		$this->_temp_quote_date.= $quote_date;
	}

	function add_your_ref( $your_ref ) {
		$this->_temp_your_ref.= $your_ref;
	}

	function add_insurance_order_no( $insurance_order_no ) {
		$this->_temp_insurance_order_no.= $insurance_order_no;
	}

	function add_insurance_company( $insurance_company ) {
		$this->_temp_insurance_company.= $insurance_company;
	}

	function add_estimate_no( $estimate_no ) {
		$this->_temp_estimate_no.= $estimate_no;
	}

	function add_part_no( $part_no ) {
		$this->_temp_part_no.= $part_no;
	}

	function add_model( $model ) {
		$this->_temp_model.= $model;
	}

	function add_product_item( $product_item ) {
		$this->_temp_product_item.= $product_item;
	}

	function add_unit_price( $unit_price ) {
		$this->_temp_unit_price.= $unit_price;
	}

	function add_oe_price( $oe_price ) {
		$this->_temp_oe_price.= $oe_price;
	}

	function add_charge_out_price( $charge_out_price ) {
		$this->_temp_charge_out_price.= $charge_out_price;
	}

	function add_oe_part_no( $oe_part_no ) {
		$this->_temp_oe_part_no.= $oe_part_no;
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE quotes FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\r\n'(quote_no, acc_no, quote_date, your_ref, insurance_oder_no, insurance_company, estimate_no, part_no, model, product_item, unit_price, oe_price, charge_out_price, oe_part_no)" );
	}

	/**
	 * Action on quotes consists of inserting only
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_quote_no}', '{$this->_temp_acc_no}', '{$this->_temp_quote_date}', '{$this->_temp_your_ref}', '{$this->_temp_insurance_order_no}', '{$this->_temp_insurance_company}','{$this->_temp_estimate_no}','{$this->_temp_part_no}','{$this->_temp_model}','{$this->_temp_product_item}','{$this->_temp_unit_price}','{$this->_temp_oe_price}','{$this->_temp_charge_out_price}','{$this->_temp_oe_part_no}'),";

		$q = $this->_query.$temp;

		// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
		if( strlen( $q ) >= 4096 ) {
			$this->db->query( rtrim( $this->_query, ',' ) );
			$this->_query = $this->_insert_instr.$temp;
		}
		else
			$this->_query = $q;

		$this->_temp_quote_no = 
		$this->_temp_acc_no = 
		$this->_temp_quote_date = 
		$this->_temp_your_ref = 
		$this->_temp_insurance_order_no = 
		$this->_temp_insurance_company = 
		$this->_temp_estimate_no =
		$this->_temp_part_no =
		$this->_temp_model =
		$this->_temp_product_item =
		$this->_temp_unit_price =
		$this->_temp_oe_price =
		$this->_temp_charge_out_price =
		$this->_temp_oe_part_no = '';
	}

	/**
	 *
	 */
	function run_the_remain_query( $type = '' ) {
		if( !empty( $this->_query ) && $this->_query != $this->_insert_instr )
			return $this->db->query( rtrim( $this->_query, ',' ) );

		return true;
	}
}