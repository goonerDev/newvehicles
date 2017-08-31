<?php
class Catalogdao extends CI_Model {

	/**
	 * Get all manufactures
	 * @params array which keys are start - limit
	 */
	function manufactures( $params ) {
		extract( $params );

		$where = $filter != false ? "WHERE manufacture LIKE '%".$filter."%' " : '';

		$qry = "SELECT manufacture, manufacture_image, group_desc, catref FROM catalog WHERE catref <>'Nordic'";
		$qry.= $where;
		$qry.= " GROUP BY manufacture ";
		$qry.= " ORDER BY manufacture ";
		$qry.= " LIMIT ".$start.",".$limit;
		//echo $qry;die;
		return $this->db->query( $qry )->result_array();
	}
	

	/**
	 * Get models by manufacture.
	 * @params array which keys are manufacture - start - limit - filter
	 */
	function models( $params ) {
		extract( $params );

		$where = "WHERE 1=1 ";
		$where.= empty( $filter ) ? '' : " AND model LIKE '%".$filter."%'";

		$qry = "SELECT manufacture, model, model_image FROM catalog ";
		$qry.= $where;
		$qry.= ( $manufacture == false ? '' : "AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= " AND catref <>'Nordic' ";
		$qry.= "GROUP BY manufacture, model ";
		$qry.= "ORDER BY model ";
		$qry.= "LIMIT ".$start.",".$limit;
		
		$res = $this->db->query( $qry )->result_array();
		
		return count( $res ) > 0 ? array(
			'manufacture_name' => $res[ 0 ][ 'manufacture' ],
			'data' => $res
		) : $res;
	}

	/**
	 * This function created on 18/10/2014 was called after a vrm search when the item_ktypes table had had a mess.
	 * What happened is the ktype didn't match the vehicle data so it didn't display the correct vehicle items.
	 * Then instead of listing those items just display list of model type that may suits the vehicle
	 */
	function models_like( $params ) {
		extract( $params );

		if( empty( $filters ) )
			return false;

		$w = '';
		$where = "WHERE manufacture='".$manufacture."' AND (";
		do {
			$filter = '';
			for( $i = 0, $n = count( $filters ); $i < $n; $i++ )
				$filter.= ' '.$filters[ $i ];

			$w.= " OR model LIKE '".substr( $filter, 1 )."%'";
			array_pop( $filters );
		}
		while( !empty( $filters ) );

		if( !empty( $w ) ) {
			$where.= substr( $w, 3 ).')';

			return $this->db->query( "SELECT manufacture,model,model_type,model_image FROM catalog ".$where." GROUP BY manufacture,model,model_type" )->result_array();
		}

		return false;
	}

	/**
	 * Get existing start and end year based on manufacture and models
	 * @params array which keys are manufacture - model - start - limit - pattern - category
	 */
	function start_end_year( $params ) {
		extract( $params );

		$qry = "SELECT manufacture, model, model_type, model_image FROM catalog ";
		$qry.= "WHERE 1=1 ";

		$qry.= ( empty( $manufacture ) ? '' : "AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( empty( $model )? '' : "AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );
		$qry.= ( empty( $pattern ) ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$pattern."' " );
		$qry.= " AND catref <>'Nordic' ";
		$qry.= "GROUP BY manufacture, model, model_type ";
		$qry.= "ORDER BY model_type ";
		$qry.= "LIMIT ".$start.",".$limit;
		//echo $qry;die;
		$res = $this->db->query( $qry )->result_array();

		return count( $res ) > 0 ? array(
			'manufacture_name' => $res[ 0 ][ 'manufacture' ],
			'model_name' => $res[ 0 ][ 'model' ],
			'data' => $res ) : $res;
	}

	/**
	 * Get all products matching the filter.
	 * @params array which keys are manufacture - model - model_type - start - limit - pattern - customer_group - category - certified
	 */
	function products( $params, $fulltext = true ) {
		extract( $params );

		$qry = "SELECT c.*, s.qty stock,s.due_date ";

		// For connected users add price
		$qry.= empty( $customer_group ) ? '' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT'))) price ";
		$qry.= ",(SELECT markup_price FROM markup_price  WHERE product_group=c.group_desc AND site_id='".$site_id."') markup_price ";
		$qry.= "FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
		$qry.= "WHERE 1=1 ";

		$qry.= ( empty( $manufacture ) ? '' : "AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( empty( $model ) ? '' : "AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );
		$qry.= ( empty( $model_type ) ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$model_type."' " );
		$qry.= ( empty( $category ) ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."' " );

		if( $pattern != false && $pattern != '-' ) {
			if( $fulltext ) {
				$pattern = '+'.preg_replace( '/\s+/', '* +', trim( $pattern ) ).'*';
				$qry.= " AND MATCH(manufacture, model, model_type, c.sku, product) AGAINST ('".$pattern."' IN BOOLEAN MODE)";
			}
			else
				$qry.= " AND product LIKE'%".$pattern."%'";
		}

		$qry.= !empty( $certified ) && $certified === true ? " AND app_parts='Yes'" : '';
		$qry.= !empty( $category ) ? " AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."'" : '';
		$qry.= !empty( $side ) ? " AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(side,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$side."'" : '';

		$qry.= "ORDER BY product LIMIT ".$start.",".$limit;

		$res = @$this->db->query( $qry )->result_array();

		if( !empty( $res ) )
			return array(
			'manufacture_name' => $res[ 0 ][ 'manufacture' ],
			'model_name' => $res[ 0 ][ 'model' ],
			'model_type_name' => $res[ 0 ][ 'model_type' ],
			'data' => $res
		);

		// If the result is empty return the manufacture_name, model_name and model type name
		$res = $this->start_end_year( $params );
		 if( !empty( $res ) )
			return  array(
			'manufacture_name' => $res[ 'manufacture_name' ],
			'model_name' => $res[ 'model_name' ],
			'model_type_name' => $res[ 'data' ][ 0 ][ 'model_type' ],
			'data' => array()
			);
	}

	/**
	 * Number of products that match the filter
	 */
	function products_count( $params, $fulltext = true ) {
		extract( $params );

		$qry = "SELECT COUNT(*) nb FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
		$qry.= "WHERE 1=1 ";

		$qry.= ( empty( $manufacture ) ? '' : "AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( empty( $model ) ? '' : "AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );
		$qry.= ( empty( $model_type ) ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$model_type."' " );
		$qry.= ( empty( $category ) ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."' " );

		if( $pattern != false && $pattern != '-' ) {
			if( $fulltext ) {
				$pattern = '+'.preg_replace( '/\s+/', '* +', trim( $pattern ) ).'*';
				$qry.= " AND MATCH(manufacture, model, model_type, c.sku, product) AGAINST ('".$pattern."' IN BOOLEAN MODE)";
			}
			else
				$qry.= " AND product LIKE'%".$pattern."%'";
		}

		$qry.= !empty( $certified ) && $certified === true ? " AND app_parts='Yes'" : '';
		$qry.= !empty( $category ) ? " AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."'" : '';
		$qry.= !empty( $side ) ? " AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(side,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$side."'" : '';

		$res = $this->db->query( $qry )->row_array();
		return $res[ 'nb' ];
	}

	/**
	 *
	 */
	function products_by_ktype( $params ) {
		extract( $params );

		return $this->db->query( "SELECT ik.item, ik.sku, ik.models_ktype_fits, ik.certificate, (SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price sp WHERE (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT')) AND sp.sku=ik.sku)price, (SELECT qty FROM stock WHERE sku=ik.sku)stock,(SELECT product_image FROM catalog WHERE sku=ik.sku LIMIT 1)product_image FROM item_ktypes ik WHERE ktype='".$ktype."' ORDER BY item LIMIT ".$start.", ".$limit )->result_array();
	}

	/**
	 *
	 */
	function products_by_ktype_count( $params ) {
		extract( $params );

		$res = $this->db->query( "SELECT COUNT(*) nb FROM item_ktypes WHERE ktype='".$ktype."'" )->row_array();
		return @$res[ 'nb' ];
	}

	/**
	 * @params array - user_no - customer_group - ktype - sku
	 */
	function item_by_id_from_vrm_result( $params ) {
		extract( $params );

		return $this->db->query( "SELECT ik.item, ik.sku, ik.models_ktype_fits, ik.certificate, (SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price sp WHERE (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT')) AND sp.sku=ik.sku)price, (SELECT qty FROM stock WHERE sku=ik.sku)stock,(SELECT product_image FROM catalog WHERE sku=ik.sku LIMIT 1)product_image FROM item_ktypes ik WHERE ktype='".$ktype."' AND ik.sku='".$sku."'" )->row_array();
	}

	/**
	 * Get a product by its ascendant + sku
	 */
	function item( $params ) {
		extract( $params );

		return $this->db->query( "SELECT c.*, s.qty stock, s.due_date FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku WHERE REPLACE(REPLACE(manufacture,' ',''),'/','')='$manufacture' AND REPLACE(REPLACE(model,' ',''),'/','')='$model' AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='$model_type' AND REPLACE(c.sku,'-','')='$sku'" )->row_array();
	}
	
	/**
	 * Get a product by its sku
	 */
	function item_by_id( $sku ) {
		return $this->db->query( "SELECT *, (SELECT qty FROM stock WHERE sku=c.sku)stock FROM catalog c WHERE sku='".$sku."'" )->row_array();
	}

	/**
	 * Get a product by its id or its cross reference
	 */
	function item_by_id_or_x_ref( $sku ) {
		return $this->db->query( "SELECT *, (SELECT qty FROM stock WHERE sku=c.sku)stock FROM catalog c WHERE sku='".$sku."' OR FIND_IN_SET('".$sku."', cross_reference)" )->result_array();
	}

	/**
	 * Get price of a product according to the customer group
	 */
	function price_by_customer_group( $params ) {
		extract( $params );

		$res = $this->db->query( "SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') price FROM sales_price WHERE (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT')) AND REPLACE(sku,'-','')='".$item_id."'" )->row_array();

		return count( $res ) > 0 ? $res[ 'price' ] : false;
	}

	/**
	 * On delivery address screen if the customer chooses another address than default then apply additional fees
	 */
	function carriage_by_customer_group( $params ) {
		extract( $params );

		$res = $this->db->query( "SELECT price FROM sales_price WHERE (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."')) AND sku='ZCARRIAGE' ORDER BY price LIMIT 1" )->row_array();
		return count( $res ) > 0 ? $res[ 'price' ] : false;
	}

	/**
	 *
	 */
	function existing_categories( $manufacture, $model, $model_type ) {
		$qry = "SELECT group_desc FROM catalog c WHERE 1=1 ";
		$qry.= ( $manufacture == false ? '' : "AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( $model == false ? '' : "AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );
		$qry.= ( $model_type == false ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$model_type."' " );
		$qry.= " AND catref <>'Nordic' ";
		$qry.= " GROUP BY 1 ORDER BY 1";

		return $this->db->query( $qry )->result_array();
	}

	/**
	 *
	 */
	function category_name( $category ) {
		return $this->db->query( "SELECT group_desc FROM catalog WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."' LIMIT 1" )->row_array();
	}

	/**
	 *
	 */
	function sides( $manufacture, $model, $category, $model_type ) {
		$qry = "SELECT side FROM catalog WHERE 1=1";
		$qry.= ( $manufacture == false ? '' : " AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( $model == false ? '' : " AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );
		$qry.= ( $model_type == false ? '' : "AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(model_type,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(','')='".$model_type."' " );
		$qry.= " AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(group_desc,' ',''),'/',''),'<',''),'>',''),'+',''),')',''),'(',''),'&','')='".$category."'";
		$qry.= " GROUP BY 1 ORDER BY 1";

		return $this->db->query( $qry )->result_array();
	}

	/**
	 * Get all vehicles that can bear the item
	 */
	function vehicles_fit_product( $item_id, $manufacture = false, $model = false ) {
		$qry = "SELECT * FROM catalog WHERE REPLACE(sku,'-','')='".$item_id."'";
		$qry.= ( $manufacture == false ? '' : " AND REPLACE(REPLACE(manufacture,' ',''),'/','')='".$manufacture."' " );
		$qry.= ( $model == false ? '' : " AND REPLACE(REPLACE(REPLACE(model,' ',''),'/',''),'-','')='".$model."' " );

		return $this->db->query( $qry )->result_array();
	}

	/**
	 * $date is in the format yyyymdd
	 */
	function can_use_carweb_ws( $user_no, $date ) {
		$res = $this->db->query( "SELECT value-(SELECT COUNT(*) FROM vrm_usage WHERE usage_date LIKE '".$date."%' AND customer_id='".$user_no."') nb_usage FROM access_limit WHERE name='vrm'" )->row_array();

		return ( int ) @$res[ 'nb_usage' ] > 0;
	}

	/**
	 * @params array vrm - user_no - email
	 */
	function save_carweb_usage( $params ) {
		extract( $params );

		return $this->db->query( "INSERT INTO vrm_usage(customer_id, email_address, usage_date, vrm) VALUES('".$user_no."', '".$email."', DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s'), '".$vrm."' )" );
	}

	/**
	 *
	 */
	function user_can_continue_or_limit_reached( $store, $user_no, $email ) {
		$res = $this->db->query( "SELECT nb_access<=(SELECT value FROM access_limit WHERE name='page') can FROM price_page_usage WHERE user_no='".$user_no."' AND email='".$email."' AND usage_date=DATE_FORMAT(CURDATE(),'%d-%m-%Y') AND store_id='".$store."'" )->row_array();

		return count( $res ) == 0 || $res[ 'can' ];
	}

	/**
	 *
	 */
	function user_increment_visit( $store_id, $user_no, $email ) {
		return $this->db->query( "INSERT INTO price_page_usage SET nb_access=IFNULL(nb_access,0)+1, user_no='".$user_no."', email='".$email."', store_id='".$store_id."', usage_date=DATE_FORMAT(CURDATE(),'%d-%m-%Y') ON DUPLICATE KEY UPDATE nb_access=IFNULL(nb_access,0)+1" );
	}
	
	
    function parts( $params, $fulltext = true  ) {
                extract( $params );
				
				$part_type = str_replace('and','&',$part_type);
				$part_type = str_replace('-',' ',$part_type);
				$qry = "SELECT c.*, s.qty stock,s.due_date ";
				$qry.= empty( $customer_group ) ? '' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND customer_group IN ('".$customer_group."')) price ";
				$qry.= ",(SELECT markup_price FROM markup_price  WHERE product_group=c.group_desc AND site_id='".$site_id."') markup_price ";
				$qry.= "FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
				$qry.= "WHERE 1=1 ";
				$qry.=  " AND group_desc = '$part_type'";
				$qry.= " GROUP BY manufacture, model";
				$qry.= " ORDER BY stock DESC LIMIT ".$start.",".$limit;
			 // echo $qry;die;
		return $res = @$this->db->query( $qry )->result_array();
    }

        function parts_count( $params, $fulltext = true ) {
			extract( $params );
			$part_type = str_replace('and','&',$part_type);
				$part_type = str_replace('-',' ',$part_type);
				$qry = "SELECT c.*, s.qty stock,s.due_date ";
				$qry.= empty( $customer_group ) ? '' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND customer_group IN ('".$customer_group."')) price ";
				$qry.= ",(SELECT markup_price FROM markup_price  WHERE product_group=c.group_desc AND site_id='".$site_id."') markup_price ";
				$qry.= "FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
				$qry.= "WHERE 1=1 ";
				$qry.=  " AND group_desc = '$part_type'";
				$qry.= " GROUP BY manufacture, model";
				
			//$qry.= " ORDER BY product LIMIT ".$start.",".$limit;
			//  echo $qry;die;
			$res = @$this->db->query( $qry )->result_array();
			return count($res);
        }
		
		function get_parts( $part_type) {
			$qry = "SELECT group_desc,group_sub_desc FROM catalog";
			$qry.= " WHERE 1=1 ";
			$qry.=  " AND group_desc = '$part_type'";
			$qry.= " GROUP BY group_sub_desc ";
			return $res = @$this->db->query( $qry )->result_array();
         }
		 
		 
	function sub_parts( $params, $fulltext = true  ) {
                extract( $params );
				
				$part_type = str_replace('and','&',$part_type);
				$part_type = str_replace('-',' ',$part_type);
				$qry = "SELECT c.*, s.qty stock,s.due_date ";
				$qry.= empty( $customer_group ) ? '' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND customer_group IN ('".$customer_group."')) price ";
				$qry.= ",(SELECT markup_price FROM markup_price  WHERE product_group=c.group_desc AND site_id='".$site_id."') markup_price ";
				$qry.= "FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
				$qry.= "WHERE 1=1 ";
				$qry.=  " AND group_sub_desc = '$part_type'";
				//$qry.= " GROUP BY sku";
				$qry.= " ORDER BY stock DESC LIMIT ".$start.",".$limit;
			 // echo $qry;die;
		return $res = @$this->db->query( $qry )->result_array();
    }

        function sub_parts_count( $params, $fulltext = true ) {
			extract( $params );
			$part_type = str_replace('and','&',$part_type);
				$part_type = str_replace('-',' ',$part_type);
				$qry = "SELECT c.*, s.qty stock,s.due_date ";
				$qry.= empty( $customer_group ) ? '' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND customer_group IN ('".$customer_group."')) price ";
				$qry.= ",(SELECT markup_price FROM markup_price  WHERE product_group=c.group_desc AND site_id='".$site_id."') markup_price ";
				$qry.= "FROM catalog c LEFT OUTER JOIN stock s ON s.sku=c.sku ";
				$qry.= "WHERE 1=1 ";
				$qry.=  " AND group_sub_desc = '$part_type'";
				//$qry.= " GROUP BY sku";
				
			//$qry.= " ORDER BY product LIMIT ".$start.",".$limit;
			//  echo $qry;die;
			$res = @$this->db->query( $qry )->result_array();
			return count($res);
        }
		
		function get_sub_parts( $part_type) {
			$qry = "SELECT group_desc,group_sub_desc FROM catalog";
			$qry.= " WHERE 1=1 ";
			$qry.=  " AND group_sub_desc = '$part_type'";
			$qry.= " GROUP BY group_sub_desc ";
			return $res = @$this->db->query( $qry )->result_array();
         }	 
			

}