<?php
require_once APPPATH.'controllers/home.php';
class Catalog extends Home {

	function __construct() {
		parent::__construct();

		$this->load->helper( 'url' );

		$this->load->model( 'catalogdao' );
	}

	/**
	 * Display a list of makes
	 */
	function manufactures( $ajaxCall = false, $format = false, $filter = false ) {
		$params = array( 'start' => 0, 'limit' => 999, 'filter' => misc::sanitize( $filter ) );
		$res = $this->catalogdao->manufactures( $params );

		if( $ajaxCall == 'ajax' ) {
			if( $format == 'json' )
				$this->load->view( $this->view_dir.'/manufacture-json-list', array( 'manufactures' => $res ) );
			elseif( $format == 'html' )
				$this->load->view( $this->view_dir.'/manufacture-html-list', array( 'manufactures' => $res ) );
			elseif( $format == 'menu' )
				$this->load->view( $this->view_dir.'/manufacture-list-cols-menu', array( 'manufactures' => $res ) );
			elseif( $format == 'raw' )
				return $res;

			return;
		}

		$data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'meta_title' ] = 'Replacemant Car Parts UK - Crash Repair Parts Online';
		$data[ 'meta_description' ] = 'We supply Replacement Car Parts Online to the UK includign Ford Focus bumpers, Astra headlights and Vauxhall Corsa headlights';
		$data[ 'meta_keywords' ] = 'Replacement Car Parts, Auto repair Parts, Online car Parts, UK car Parts, Repair Panels, headlights, Bonnets, Bumpers';
		$data[ 'catalog_active' ] = true;

		if( $ajaxCall == '404' )
			$data[ 'error_msg' ] = E_404_ERROR;

		$this->load->view( $this->view_dir.'/manufacture-list-cols', $data );
	}

	/**
	 * Display a list of models for a make
	 */
	function models( $manufacture, $ajaxCall = false, $format = false, $filter = false ) {
		if( empty( $manufacture ) )
			return false;
		
		$p = $this->is_parts($manufacture);
		if($p == 0){ 
		$params = array( 'manufacture' => $manufacture, 'start' => 0, 'limit' => 1000, 'filter' => misc::sanitize( $filter ) );
		$res = $this->catalogdao->models( $params );

		$res[ 'manufacture_url' ] = $manufacture;

		if( $ajaxCall == 'ajax' ) {
			if( $format == 'json' )
				$this->load->view( $this->view_dir.'/model-json-list', $res );
			elseif( $format == 'html' )
				$this->load->view( $this->view_dir.'/model-html-list', $res );
			elseif( $format == 'htmlselect' )
				$this->load->view( $this->view_dir.'/model-htmlselect-list', $res );

			return;
		}

		if( $format != 'html' && $format != 'htmlselect' && ( empty( $res ) || empty( $res[ 'data' ] ) ) ) {
			header( 'Location: '.BASE_URL.'make/404' );
			exit;
		}

		$data = parent::_init_widgets();
		$data = array_merge( $data, $res );

		$data[ 'meta_title' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-title.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ] ) );

		$model_types = $this->start_end_year( $manufacture, false, 'ajax', 'raw' );

		$data[ 'meta_description' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-description.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'models' => $res[ 'data' ], 'model_types' => @$model_types[ 'data' ] ) );

		$data[ 'meta_keywords' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-keyword.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'models' => $res[ 'data' ], 'model_types' => @$model_types[ 'data' ] ) );

		$data[ 'catalog_active' ] = true;

		$this->load->view( $this->view_dir.'/model-list-cols', $data );
		}elseif($p == 2){ 
		 $ajaxCall = false;
		$pattern = false; 
		$start = 0;
		$format = 'raw';
		$fulltext = true;
		$default_url = true;
		$part_type = $manufacture;
        $customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;
        $params = array(
			'pattern' => $pattern,
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'start' => $start,
			'limit' => 50,
			'user_id' => $user_no,
			'site_id' => $this->store_id,
            'part_type'=>$part_type,
		);
        $res = $this->catalogdao->sub_parts($params);
		$get_parts = $this->catalogdao->get_sub_parts($part_type);
		$sub_parts = $sub_keywords= '';
		//echo '<pre>';print_R($res);die;
		if($get_parts){
			foreach($get_parts as $subpart){
			if($subpart['group_sub_desc'] != ''){ 	
			$sub_parts .= ' - '.$subpart['group_sub_desc'];
			$sub_keywords .= ', '.$subpart['group_sub_desc'];
			}
			}
		}
		//echo $sub_parts;die;
		
		$part_type1 = str_replace('and','&',$part_type);
		$part_type2 = str_replace('-',' ',$part_type1);
        $data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'catalog_active' ] = true;
        $data[ 'parts' ] = $res;
		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
        $data[ 'part_type'] = $part_type2;
		$data[ 'sub_parts'] = $sub_parts;
		$data[ 'sub_keywords'] = $sub_keywords;
        // echo '<pre>';print_R($data);die;
		if( $ajaxCall == '404' )
		$data[ 'error_msg' ] = E_404_ERROR;
               $pagination_url = BASE_URL.'/'.$part_type;
               $this->load->library( 'pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			$pagination_url,
			$start,
			50,
			$this->catalogdao->sub_parts_count( $params, $fulltext )
		);
        $this->load->view( $this->view_dir.'/group_sub_desc_list', $data );
		}else{ 
		$ajaxCall = false;
		$pattern = false; 
		$start = 0;
		$format = 'raw';
		$fulltext = true;
		$default_url = true;
		$part_type = $manufacture;
        $customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;
        $params = array(
			'pattern' => $pattern,
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'start' => $start,
			'limit' => 50,
			'user_id' => $user_no,
			'site_id' => $this->store_id,
            'part_type'=>$part_type,
		);
        $res = $this->catalogdao->parts($params);
		$get_parts = $this->catalogdao->get_parts($part_type);
		$sub_parts = $sub_keywords= '';
		//echo '<pre>';print_R($res);die;
		if($get_parts){
			foreach($get_parts as $subpart){
			if($subpart['group_sub_desc'] != ''){ 	
			$sub_parts .= ' '.$subpart['group_sub_desc'];
			$sub_keywords .= ', '.$subpart['group_sub_desc'];
			}
			}
		}
		//echo $sub_parts;die;
		
		$part_type1 = str_replace('and','&',$part_type);
		$part_type2 = str_replace('-',' ',$part_type1);
        $data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'catalog_active' ] = true;
        $data[ 'parts' ] = $res;
		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
        $data[ 'part_type'] = $part_type2;
		$data[ 'sub_parts'] = $sub_parts;
		$data[ 'sub_keywords'] = $sub_keywords;
        // echo '<pre>';print_R($data);die;
		if( $ajaxCall == '404' )
		$data[ 'error_msg' ] = E_404_ERROR;
               $pagination_url = BASE_URL.'/'.$part_type;
               $this->load->library( 'pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			$pagination_url,
			$start,
			50,
			$this->catalogdao->parts_count( $params, $fulltext )
		);
        $this->load->view( $this->view_dir.'/group_desc_list', $data );
		}
	}

	/**
	 * @param string $manufacture
	 * @param string $model_like
	 */
	function models_from_vrm_result( $manufacture, $model_like ) {
		$manufacture = misc::sanitize( $manufacture );
		$model = misc::sanitize( $model_like );

		$params[ 'manufacture' ] = $manufacture;
		$params[ 'filters' ] = preg_split( '/\++/', $model_like );

		return $this->catalogdao->models_like( $params );
	}

	/**
	 * Display a list of model type by a make/model
	 */
	function start_end_year( $manu, $model, $ajaxCall = false, $format = false, $pattern = false ) { 
	  $p = $this->is_parts($manu);
		if($p == 0){
	  $params = array( 'manufacture' => $manu, 'model' => $model, 'pattern' => $pattern, 'start' => 0, 'limit' => 9999 );
		$res = $this->catalogdao->start_end_year( $params );

		$res[ 'manufacture_url' ] = $manu;
		$res[ 'model_url' ] = $model;

		if( $ajaxCall == 'ajax' ) {
			if( $format == 'json' )
				$this->load->view( $this->view_dir.'/start-end-year-json-list', $res );
			elseif( $format == 'htmlselect' )
				$this->load->view( $this->view_dir.'/start-end-year-htmlselect-list', $res );
			elseif( $format == 'html' )
				$this->load->view( $this->view_dir.'/start-end-year-html-list', $res );
			elseif( $format == 'menu' )
				$this->load->view( $this->view_dir.'/start-end-year-list-cols-menu', $res );
			elseif( $format == 'raw' )
				return $res;

			return;
		}

		// Is this a normal manufacture url or one that matches the old. Old manufacture url is like volvo-13506, alfa-romeo-13460?
		$dash_pos = strrpos( $manu, '-' );
		// New: alfaromeo - Old: alfaromeo-31676
		if( $dash_pos > -1 )
			$manufacture = str_replace( '-', '', substr( $manu, 0, $dash_pos ) );

		$dash_pos = strrpos( $model, '-' );
		if( $dash_pos > -1 )
			$model = str_replace( '-', '', substr( $model, 0, $dash_pos ) );

		if( $format != 'html' && $format != 'htmlselect' && ( empty( $res ) || empty( $res[ 'data' ] ) ) ) {
			header( 'Location: '.BASE_URL.'make/404' );
			exit;
		}

		$data = parent::_init_widgets();
		$data = array_merge( $data, $res );

		$data[ 'meta_title' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-type-title.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ] ) );

		$data[ 'meta_description' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-type-description.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ] ) );

		$data[ 'meta_keywords' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/model-type-keyword.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ] ) );

		$data[ 'catalog_active' ] = true;

		if( substr( $ajaxCall, 0, 4 ) == 'cat-' ) {
			$data[ 'google_ppc' ] = true;
			$data[ 'category_name' ] =  $this->category_name( misc::sanitize( substr( $ajaxCall, 4 ) ) );
			$data[ 'category_url' ] = $ajaxCall;
		}

		$this->load->view( $this->view_dir.'/start-end-year-list-cols', $data );
		}else if($p == 2){ 
			$ajaxCall = false;
		$pattern = false; 
		$start = $model;
		$format = 'raw';
		$fulltext = true;
		$default_url = true;
		$part_type = $manu;
        $customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;
        $params = array(
			'pattern' => $pattern,
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'start' => $start,
			'limit' => 50,
			'user_id' => $user_no,
			'site_id' => $this->store_id,
            'part_type'=>$part_type,
		);

        $res = $this->catalogdao->sub_parts($params);
		$get_parts = $this->catalogdao->get_sub_parts($part_type);
		$sub_parts = $sub_keywords= '';
		if($get_parts){
			foreach($get_parts as $subpart){
			if($subpart['group_sub_desc'] != ''){ 	
			$sub_parts .= ' | '.$subpart['group_sub_desc'];
			$sub_keywords .= ', '.$subpart['group_sub_desc'];
			}
			}
		}
		$part_type1 = str_replace('and','&',$part_type);
		$part_type2 = str_replace('-',' ',$part_type1);
        $data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'catalog_active' ] = true;
        $data[ 'parts' ] = $res;
		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
        $data[ 'part_type'] = $part_type2;
		$data[ 'sub_parts'] = $sub_parts;
		$data[ 'sub_keywords'] = $sub_keywords;
       if( $ajaxCall == '404' )
		$data[ 'error_msg' ] = E_404_ERROR;
               $pagination_url = BASE_URL.'/'.$part_type;
               $this->load->library( 'pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			$pagination_url,
			$start,
			50,
			$this->catalogdao->sub_parts_count( $params, $fulltext )
		);
        $this->load->view( $this->view_dir.'/group_desc_list', $data );
		}else{
		$ajaxCall = false;
		$pattern = false; 
		$start = $model;
		$format = 'raw';
		$fulltext = true;
		$default_url = true;
		$part_type = $manu;
        $customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;
        $params = array(
			'pattern' => $pattern,
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'start' => $start,
			'limit' => 50,
			'user_id' => $user_no,
			'site_id' => $this->store_id,
            'part_type'=>$part_type,
		);

        $res = $this->catalogdao->parts($params);
		$get_parts = $this->catalogdao->get_parts($part_type);
		$sub_parts = $sub_keywords= '';
		if($get_parts){
			foreach($get_parts as $subpart){
			if($subpart['group_sub_desc'] != ''){ 	
			$sub_parts .= ' | '.$subpart['group_sub_desc'];
			$sub_keywords .= ', '.$subpart['group_sub_desc'];
			}
			}
		}
		$part_type1 = str_replace('and','&',$part_type);
		$part_type2 = str_replace('-',' ',$part_type1);
        $data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'catalog_active' ] = true;
        $data[ 'parts' ] = $res;
		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
        $data[ 'part_type'] = $part_type2;
		$data[ 'sub_parts'] = $sub_parts;
		$data[ 'sub_keywords'] = $sub_keywords;
       if( $ajaxCall == '404' )
		$data[ 'error_msg' ] = E_404_ERROR;
               $pagination_url = BASE_URL.'/'.$part_type;
               $this->load->library( 'pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			$pagination_url,
			$start,
			50,
			$this->catalogdao->parts_count( $params, $fulltext )
		);
        $this->load->view( $this->view_dir.'/group_desc_list', $data );
	}	
	}

	/**
	 * Display a list of items for a make/model/model_type
	 */
	function products( $manu, $model, $model_type, $pattern = false, $start = 0, $format = 'raw', $fulltext = true, $default_url = true ) {
		$customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;

		$params = array(
			'manufacture' => $manu,
			'model' => $model,
			'model_type' => $model_type,
			'pattern' => $pattern,
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'start' => $start,
			'limit' => 20,
			'user_id' => $user_no,
			'site_id' => $this->store_id
		);

		// This particular case is not directly reached by users. It is used by google PPC
		// if( substr( $model_type, 0, 4 ) == 'cat-' ) {
			// $category = $model_type;
			// $params[ 'model_type' ] = false;
			// $params[ 'category' ] = substr( $category, 4 ) == 'all' ? '' : substr( $category, 4 );
			// $params[ 'pattern' ] = false;
			// $params[ 'start' ] = empty( $pattern ) || !is_numeric( $pattern ) ? 0 : $pattern;
			// $side = $params[ 'side' ] = !is_numeric( $pattern ) ? misc::sanitize( $pattern ) : '';
			// $google_ppc = true;
		// }
		// else
		if( substr( $pattern, 0, 4 ) == 'cat-' ) {
			$category = $pattern;
			$params[ 'category' ] = substr( $category, 4 ) == 'all' ? '' : substr( $category, 4 );
			$params[ 'pattern' ] = false;
			if( !is_numeric( $start ) ) {
				$side = $params[ 'side' ] = misc::sanitize( $start );
				$params[ 'start' ] = 0;
			}
		}
		elseif( $pattern == 'certified' ) {
			$params[ 'pattern' ] = false;
			$params[ 'certified' ] = true;
			$certified = true;
		}
		else {
			$params[ 'pattern' ] = $pattern == '-' ? false : $pattern;
			$pattern = $pattern == false ? '-' : $pattern;
		}

		$res = $this->catalogdao->products( $params, $fulltext );

		$data = parent::_init_widgets();

		$data = array_merge( $data, $res );

		if( empty( $google_ppc ) ) { // Normal use
			// Use the provided url (called default here) or the catalog reserved one
			// $pagination_url = $default_url === true ? BASE_URL.'fcatalog/products/'.$manu.'/'.$model.'/'.$model_type.'/'.$pattern : $default_url;
			$pagination_url = $default_url === true ? BASE_URL.$manu.'/'.$model.'/'.$model_type.'/'.$pattern : $default_url;
		}
		else { // google PPC
			$data[ 'google_ppc' ] = true;
			$data[ 'vehic_will_fit' ] = true;

			$pagination_url = BASE_URL.$manu.'/'.$model.'/'.$model_type;
		}

		$this->load->library( 'pagination' );
		$data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			$pagination_url,
			$start,
			20,
			$this->catalogdao->products_count( $params, $fulltext )
		);

		$data[ 'view_dir' ] = $this->view_dir;
		$data[ 'manufacture_url' ] = $manu;
		$data[ 'model_url' ] = $model;

		$data[ 'certified' ]  = @$certified;
		$data[ 'model_type_url' ] = $model_type;

		$data[ 'category_url' ] = !empty( $category ) ? $category : false;
		$data[ 'side_url' ] = !empty( $side ) ? $side : false;

		if( empty( $res ) || empty( $res[ 'data' ] ) ) {
			header( 'Location: '.BASE_URL.'make/404' );
			exit;
		}

		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
		$data[ 'categories' ] = $this->categories( $manu, $model, $model_type, 'raw' );

		// $data[ 'sides' ] = Modules::run( 'catalog/ccatalog/sides', false, $manufacture, $model, $result[ 'category_url' ], $model_type, false, 'raw' );

		// if( substr( $model_type, 0, 4 ) == 'cat-' ) { // google PPC, never reached on normal use
			// $data[ 'category_name' ] =  Modules::run( 'catalog/ccatalog/category_name', misc::sanitize( substr( $model_type, 4 ) ) );
			// $data[ 'model_type_url' ] = false;
		// }
		// else
			$data[ 'category_name' ] =  $this->category_name( misc::sanitize( substr( $pattern, 4 ) ) );

		$data[ 'meta_title' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/product-title.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ], 'model_type' => $res[ 'model_type_name' ] ) );

		$data[ 'meta_description' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/product-description.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ], 'model_type' => $res[ 'model_type_name' ], 'refine_search' => $data[ 'categories' ] ) );

		$data[ 'meta_keywords' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/product-keyword.txt' ), array( 'manufacture' => $res[ 'manufacture_name' ], 'model' => $res[ 'model_name' ], 'model_type' => $res[ 'model_type_name' ], 'refine_search' => $data[ 'categories' ] ) );

		if( $manu == 'consumables' )
			$data[ 'consumable_active' ] = true;
		else
			$data[ 'catalog_active' ] = true;

		$this->load->view( $this->view_dir.'/product-list-cols', $data );
		
	}

	/**
	 *
	 */
	function item( $manu, $model, $model_type, $sku, $format = false ) {
		$customer_group = empty( $this->customer_group ) ? $this->fixed_customer_group : $this->customer_group;
		$user_no = empty( $this->user_no ) ? $this->fixed_user_no : $this->user_no;

		$item = explode( '-', $sku, 2 );
		$item_id = $item[ 0 ];

		$params = array(
			'manufacture' => $manu,
			'model' => $model,
			'model_type' => $model_type,
			'sku' => $item_id
		);
		$res[ 'item' ] = $this->catalogdao->item( $params );

		$params = array(
			'user_no' => $user_no,
			'customer_group' => $customer_group,
			'item_id' => $item_id
		);
		$res[ 'price' ] = $this->catalogdao->price_by_customer_group( $params );

		$res[ 'vehic_fit' ] = $this->catalogdao->vehicles_fit_product( $item_id );

		if( empty( $res ) || empty( $res[ 'item' ] ) ) {
			header( 'Location: '.BASE_URL.'make/404' );
			exit;
		}

		$data = parent::_init_widgets();
		$data = array_merge( $data, $res );
		$data[ 'user_no' ] = $user_no;
		$data[ 'vat' ] = $this->vat;

		$data[ 'item_in_cart' ] = Modules::run( 'cart/item_by_sku', $res[ 'item' ][ 'sku' ] );

		$data[ 'meta_title' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/item-title.txt' ), array( 'manufacture' => $res[ 'item' ][ 'manufacture' ], 'model' => $res[ 'item' ][ 'model' ], 'model_type' => $res[ 'item' ][ 'model_type' ], 'item' => $res[ 'item' ][ 'product' ], 'refine_search' => array( array( 'group_desc' => $res[ 'item' ][ 'group_desc' ] ) ), 'side' => $res[ 'item' ][ 'side' ] ) );

		$data[ 'meta_description' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/item-description.txt' ), array( 'manufacture' => $res[ 'item' ][ 'manufacture' ], 'model' => $res[ 'item' ][ 'model' ], 'model_type' => $res[ 'item' ][ 'model_type' ], 'item' => $res[ 'item' ][ 'product' ], 'refine_search' => array( array( 'group_desc' => $res[ 'item' ][ 'group_desc' ] ) ), 'certificate' => $res[ 'item' ][ 'certificate' ] ) );

		$data[ 'meta_keywords' ] = $this->_substitute_tags_in_meta( file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/item-keyword.txt' ), array( 'manufacture' => $res[ 'item' ][ 'manufacture' ], 'model' => $res[ 'item' ][ 'model' ], 'model_type' => $res[ 'item' ][ 'model_type' ], 'item' => $res[ 'item' ][ 'product' ], 'refine_search' => array( array( 'group_desc' => $res[ 'item' ][ 'group_desc' ] ) ) ) );

		$data[ 'catalog_active' ] = true;

		$this->load->view( $this->view_dir.'/product-details', $data );
	}

	/**
	 *
	 */
	function item_by_id( $sku ) {
		return $this->catalogdao->item_by_id( $sku );
	}

	/**
	 *
	 */
	function item_by_id_or_x_ref( $sku ) {
		return $this->catalogdao->item_by_id_or_x_ref( $sku );
	}

	/**
	 *
	 */
	function categories( $manufacture, $model, $model_type, $format = false ) {
		if( substr( $model_type, 0, 4 ) == 'cat-' )
			$model_type = false;

		$res = $this->catalogdao->existing_categories( $manufacture, $model, $model_type );

		if( $format = 'raw' )
			return $res;

		$data[ 'view_dir' ] = $this->view_dir;
		$data[ 'manufacture_url' ] = $manufacture;

		$data[ 'model_url' ] = $model;
		$data[ 'modeltype_url' ] = $model_type;
		$data[ 'categories' ] = $res;

		$this->load->view( $this->view_dir.'/products-categories', $data );
	}

	/**
	 *
	 */
	function category_name( $category ) {
		return empty( $category ) ? array() : $this->catalogdao->category_name( $category );
	}

	/**
	 *
	 */
	function sides( $manufacture, $model, $category, $model_type = false, $ajaxCall = false, $format = false ) {
		if( empty( $category ) || substr( $category, 0, 4 ) != 'cat-' )
			if( $ajaxCall == 'ajax' )
				return false;

		if( $category == $model_type )
			$model_type = false;

		$data[ 'sides' ] = $this->catalogdao->sides( $manufacture, $model, substr( $category, 4 ), $model_type );

		if( $format == 'raw' )
			return $data[ 'sides' ];

		if( $model_type == false )
			$data[ 'base_url' ] = BASE_URL.$manufacture.'/'.$model.'/'.$category;
		else
			$data[ 'base_url' ] = BASE_URL.$manufacture.'/'.$model.'/'.$model_type.'/'.$category;

		$this->load->view( $this->view_dir.'/products-sides', $data );

	}

	/**
	 *
	 */
	function metal( $model_type, $start = 0 ) {
		if( $model_type == 'sheet' )
			$product_type = 'sheet metal';
		elseif( $model_type == 'angle-section' )
			$product_type = 'angle section';
		elseif( $model_type == 'box-section' )
			$product_type = 'box section';
		else
			die( '{"err":"'.misc::esc_json( E_EMPTY_FIELD ).'"}' );

		$data = parent::_init_widgets();
		$data[ 'user_no' ] = $this->user_no;
		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'vat' ] = $this->vat;
		$data[ 'metal_active' ] = true;

		$metal = $this->products( false, false, false, $product_type, $start, 'raw', false, BASE_URL.'fcatalog/metal/'.$model_type );

		$data = array_merge( $data, $metal );

		$this->load->view( $this->view_dir.'/product-list-cols', $data );
	}

	/**
	 *
	 */
	function vehicle_product_will_fit( $sku, $manufacture = false, $model = false ) {
		return $this->catalogdao->vehicles_fit_product( $sku, $manufacture, $model );
	}

	/**
	 *
	 */
	function user_can_continue_or_limit_reached( $store_id, $email ) {
		if( $this->catalogdao->user_can_continue_or_limit_reached( $store_id, $this->user_no, $email ) ) {
			$this->catalogdao->user_increment_visit( $store_id, $this->user_no, $email );
			return true;
		}
		return false;
	}

	/**
	 *
	 */
	function _substitute_tags_in_meta( $content, $replacement ) {
		foreach( $replacement as $k => $v )
			switch( $k ) {
				case 'manufacture':
					$content = str_replace( '#{MANUFACTURE}', $v, $content );
					break;

				case 'model':
					$content = str_replace( '#{MODEL}', $v, $content );
					break;

				case 'models':
					$models = '';
					if( is_array( $v ) )
						foreach( $v as $u )
							if( !empty( $u[ 'model' ] ) )
								$models.= ', '.$u[ 'model' ];

					$content = str_replace( '#{MODELS}', substr( $models, 1 ), $content );
					break;

				case 'model_type':
					$content = str_replace( array( '#{MODEL_TYPE}', '>' ), array( $v, '+' ), $content );
					break;

				case 'model_types':
					$model_types = '';
					if( is_array( $v ) )
						foreach( $v as $u )
							if( !empty( $u[ 'model_type' ] ) )
								$model_types.= ', '.str_replace( '>', '+', $u[ 'model_type' ] );

					$content = str_replace( '#{MODEL_TYPES}', substr( $model_types, 1 ), $content );
					break;

				case 'item':
					$content = str_replace( '#{ITEM}', $v, $content );
					break;

				case 'side':
					$content = str_replace( array( '#{SIDE}', '>', '(', ')' ), array( $v, '+', ' ', ' ' ), $content );
					break;

				case 'refine_search':
					$category = '';
					if( is_array( $v ) )
						foreach( $v as $u )
							if( !empty( $u[ 'group_desc' ] ) )
								$category.= ', '.$u[ 'group_desc' ];

					$content = str_replace( '#{REFINE_SEARCH}', substr( $category, 1 ), $content );
					break;

				case 'certificate':
					$content = str_replace( array( '#{CERTIFICATE}', '>' ), array( $v, '+' ), $content );
					break;
			}

		return ucwords( strtolower( $content ) );
	}	
	
	function is_parts($manufacture){	
		switch($manufacture){
			case 'bumpers':
			$p = 1;
			break;
			case 'bonnets':
			$p = 1;
			break;
			case 'wings':
			$p = 1;
			break;
			case 'window-regulators':
			$p = 1;
			break;
			case 'inner-panels':
			$p = 1;
			break;
			case 'cooling':
			$p = 1;
			break;
			case 'grilles':
			$p = 1;
			break;
			case 'lighting':
			$p = 1;
			break;
			case 'door-mirrors':
			$p = 1;
			break;
			case 'misc':
			$p = 1;
			break;
			case 'mouldings-and-trims':
			$p = 1;
			break;
			case 'steering-and-suspension':
			$p = 1;
			break;
			case 'tanks-and-sumps':
			$p = 1;
			break;
			case 'repair-panels':
			$p = 1;
			break;
			case 'tailgates-and-doors':
			$p = 1;
			break;
			case 'front-bumpers':
			$p = 2;
			break;
			case 'rear-bumpers':
			$p = 2;
			break; 
			case 'front-wings': 
			$p = 2;
			break; 
			case 'mouldings-and-wing-extensions': 
			$p = 2;
			break; 
			case 'cross-members-reinforcers': 
			$p = 2;
			break; 
			case 'grilles': 
			$p = 2;
			break; 
			case 'inner-wings': 
			$p = 2;
			break; 
			case 'indicators-and-daylight-running': 
			$p = 2;
			break; 
			case 'radiators-and-condensors': 
			$p = 2;
			break; 
			case 'headlamps': 
			$p = 2;
			break; 
			case 'fog-and-spot-lamps': 
			$p = 2;
			break; 
			case 'front-panels-mounting-panels': 
			$p = 2;
			break; 
			case 'bumper-end-caps': 
			$p = 2;
			break; 
			case 'spoilers': 
			$p = 2;
			break; 
			case 'undertrays': 
			$p = 2;
			break; 
			case 'rear-lamps': 
			$p = 2;
			break; 
			case 'bumper-spoilers': 
			$p = 2;
			break; 
			case 'radiator-cowls': 
			$p = 2;
			break; 
			case 'radiator-fans': 
			$p = 2;
			break; 
			case 'fuel-tanks-engine-sumps': 
			$p = 2;
			break; 
			case 'side-repeaters': 
			$p = 2;
			break; 
			case 'tow-hook-covers': 
			$p = 2;
			break; 
			case 'rear-panels': 
			$p = 2;
			break; 
			case 'rear-wings': 
			$p = 2;
			break; 
			case 'hinges-and-brackets': 
			$p = 2;
			break; 
			case 'misc-panels': 
			$p = 2;
			break; 
			case 'doors': 
			$p = 2;
			break; 
			case 'subframes': 
			$p = 2;
			break; 
			case 'tailgates ': 
			$p = 2;
			break; 
			case 'boot-lids': 
			$p = 2;
			break;
			default:
			$p = 0;
		}
	return $p;
	}

   
}