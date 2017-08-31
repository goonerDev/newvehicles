<?php
require_once APPPATH.'controllers/home.php';
class Search extends Home {

	/**
	 *
	 */
	function free_search() {
		$this->load->view( $this->view_dir.'/simple-search-input' );
	}

	/**
	 *  
	 */
	function process_free_search( $pattern, $format ) {
		$q = '';

		if( !empty( $pattern ) && substr( $pattern, 0, 2 ) == 'q-' ) {

			$start = 0;
			$limit = 20;
			$q = misc::sanitize( urldecode( substr( trim( $pattern ), 2 ) ) );

			if( strlen( $q ) > 2 ) {
				// Check first if the search is against sku. If so then go straigh away to the item page
				$item = Modules::run( 'catalog/item_by_id_or_x_ref', $q );
				if( count( $item ) > 0 ) {
					$data[ 'result' ] = $item;
					$this->load->view( $this->view_dir.'/simple-search-json-result-one-item', $data );
					die;
				}
				else
				// if not then check over main fields
					$data[ 'result' ] = $this->catalogdao->products( array( 'manufacture' => false, 'model' => false, 'model_type' => false, 'start' => $start, 'limit' => $limit, 'pattern' => $q, 'site_id' => $this->store_id ) );
			}
			else
				$data[ 'result' ] = false;

			$data[ 'pattern' ] = $q;

			if( $format == 'html' )
				$this->load->view( $this->view_dir.'/simple-search-html-result', $data );
			elseif( $format == 'json' )
				$this->load->view( $this->view_dir.'/simple-search-json-result', $data );
		}
	}

	/**
	 *
	 */
	function mini_quick_search() {
		$this->load->view( $this->view_dir.'/mini-quick-search-input' );
	}

	/**
	 *
	 */
	function quick_search() {
		$data[ 'manufacturess' ] = Modules::run( 'catalog/manufactures', 'ajax', 'html' );
		$this->load->view( $this->view_dir.'/quick-search-input' );
	}

	/**		
	 *
	 */
	function vrm_search() {
		$this->load->view( $this->view_dir.'/search-by-vrm-input' );
	}

	/**
	 *
	 */
	function process_vrm() {
		parent::is_valid_connection();
		$data = parent::_init_widgets();

		if( $data[ 'connected_user' ] )
			parent::_user_can_continue_or_limit_reached( $this->user_no, $this->email );

		$data[ 'is_admin' ] = $this->is_admin;
		$data[ 'user_no' ] = $this->user_no;

		$segs = $this->uri->segment_array();

		// The format of the url is: /search/process_vrm/q-vrmno/ktype/offset
		if( !empty( $segs[ 3 ] ) && substr( $segs[ 3 ], 0, 2 ) == 'q-' ) {
			$vrm = substr( $segs[ 3 ], 2 );
			$params = array(
				'vrm' => $vrm,
				'user_no' => $this->user_no,
				'email' => $this->email
			);

			$data[ 'items' ] = false;
			$data[ 'vehicle' ] = false;

			if( $this->catalogdao->can_use_carweb_ws( $this->user_no, date( 'Ymd' ) ) ) {
				if( empty( $segs[ 4 ] ) ) { // The ktype is not known yet than pull it
					$vehicle = $this->_run_carweb_ws( $params );
					if( count( $vehicle ) > 0 ) {
						// params is already set up few lines above
						$this->catalogdao->save_carweb_usage( $params );
						setcookie( '_vrm', $vrm );
					}
				}
				else {
					// Normally only ktype is required to pull vehicle parts but there was a time
					// the item_ktypes table got messed so the ktype didn't match the correct parts.
					// Then to avoid the customer buying wrong items just display model type of vehicle
					// instead of vehicle items
					if( is_numeric( $segs[ 4 ] ) ) {
						$vehicle[ 'ktype' ] = $segs[ 4 ];
					}
					else {
						$vehicle[ 'make' ] = $segs[ 4 ];
						$vehicle[ 'model' ] = $segs[ 5 ];
					}
				}

				// /search/process_vrm/q-vrm_no/ktype/category/start
				if( empty( $segs[ 4 ] ) || is_numeric( $segs[ 4 ] ) ) {
					$category = !empty( $segs[ 5 ] ) ? $segs[ 5 ] : null;
					$start = !empty( $segs[ 6 ] ) ? ( int ) $segs[ 6 ] : 0;

					$params = array(
						'user_id' => $this->user_no,
						'ktype' => $vehicle[ 'ktype' ],
						'customer_group' => $this->customer_group,
						'category' => misc::sanitize( $category ),
						'start' => $start,
						'limit' => 20
					);

					$data[ 'items' ] = $vehicle != false ? Modules::run( 'catalog/products_by_ktype', $params ) : false;

					if( !empty( $data[ 'items' ] ) ) {
						$tmp_skus = '';
						foreach( $data[ 'items' ] as $item )
							$tmp_skus.= ",'".$item[ 'sku' ]."'";
						$skus = substr( $tmp_skus, 1 );
						$data[ 'categories' ] = Modules::run( 'catalog/existing_categories_by_skus', $skus );
					}
					else
						$data[ 'categories' ] = null;

					if( empty( $category ) )
						$data[ 'category_url' ] = 'all';
					else
						$data[ 'category_url' ] = misc::urlencode( $category );

					$data[ 'vehicle' ] = $vehicle;

					$this->load->library( 'Pagination' );
					$data[ 'pagination' ] = misc::pagination(
						$this->pagination,
						BASE_URL.'search/process_vrm/'.$segs[ 3 ].'/'.$vehicle[ 'ktype' ].'/',
						$start,
						20,
						$this->catalogdao->products_by_ktype_count( $params )
					);

					if( !empty( $segs[ 4 ] ) ) { // ktype is not empty, this means we get here from vrm search pagination
						$data[ 'data' ] = $data[ 'items' ];
						$this->load->view( $this->view_dir.'/search-by-vrm-result-cols-list', $data );
					}
				}
				// /search/process_vrm/q-vrm_no/make/model
				// This case is just temporary
				elseif( !empty( $vehicle[ 'make' ] ) && !empty( $vehicle[ 'model' ] ) ) {
					$data = parent::_init_widgets();
					$data[ 'start_end_years' ] = Modules::run( 'catalog/models_from_vrm_result', $vehicle[ 'make' ], $vehicle[ 'model' ] );

					if( !empty( $data[ 'start_end_years' ] ) ) {
						$data[ 'manufacture_url' ] = strtolower( misc::urlencode( $data[ 'start_end_years' ][ 0 ][ 'manufacture' ] ) );
						$data[ 'manufacture_name' ] = $data[ 'start_end_years' ][ 0 ][ 'manufacture' ];
						$data[ 'model_url' ] = strtolower( misc::urlencode( $data[ 'start_end_years' ][ 0 ][ 'model' ] ) );
						$data[ 'model_name' ] = $data[ 'start_end_years' ][ 0 ][ 'model' ];
					}
					else {
						redirect( BASE_URL.$vehicle[ 'make' ] );
					}

					$data[ 'meta_title' ] = '';
					$data[ 'meta_description' ] = '';
					$data[ 'meta_keywords' ] = '';

					$this->load->view( $this->view_dir.'/search-by-vrm-result-model-type', $data );
					return;
				}
			}

			$data[ 'meta_title' ] = '';
			$data[ 'meta_description' ] = '';
			$data[ 'meta_keywords' ] = '';
			if( empty( $segs[ 4 ] ) )
				$this->load->view( $this->view_dir.'/search-by-vrm-result', $data );
		}
	}

	/**
	 * Display a selected item (its details) after vrm lookup
	 */
	function vrm_result() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		if( $data[ 'connected_user' ] )
			parent::_user_can_continue_or_limit_reached( $user[ 'user_no' ], $user[ 'email' ] );

		$data[ 'is_admin' ] = $user[ 'admin' ];

		$segs = $this->uri->segment_array();
		if( !empty( $segs[ 2 ] ) && !empty( $segs[ 3 ] ) ) {
			$sku = explode( '-', $segs[ 2 ], 2 );
			$sku = $sku[ 0 ];
			$params = array(
				'user_id' => @$user[ 'user_no' ],
				'customer_group' => @implode( "','", $user[ 'customer_group' ] ),
				'sku' => $sku,
				'ktype' => $segs[ 3 ]
			);
			$data[ 'item' ] = $this->catalogdao->item_by_id_from_vrm_result( $params );
			$data[ 'user_no' ] = @$user[ 'user_no' ];

			$params = array(
				'user_id' => @$user[ 'user_no' ],
				'customer_group' => $user != false ? @implode( "','", @$user[ 'customer_group' ] ) : '',
				'item_id' => $sku
			);
			$data[ 'price' ] = $this->catalogdao->price_by_customer_group( $params );

			$data[ 'vehic_fit' ] = $this->catalogdao->vehicles_fit_product( $sku );

			$data[ 'meta_title' ] = '';
			$data[ 'meta_description' ] = '';
			$data[ 'meta_keywords' ] = '';

			$this->load->view( 'new/product-details-from-vrm', $data );
		}
	}

	/**
	 *
	 */
	function _run_carweb_ws( $params ) {
		extract( $params );

		if( empty( $vrm ) || empty( $user_no ) || empty( $email ) )
			return false;

		$url = 'https://www1.carwebuk.com/CarweBVrrB2Bproxy/carwebVrrWebService.asmx/strB2BGetVehicleByVRM';
		$url.= '?strUserName=DirectAuto';
		$url.= '&strPassword=5814779';
		$url.= '&strKey1=dt12au09';
		$url.= '&strVRM='.$vrm;
		$url.= '&strVersion=0.31.2';
		$url.= '&strClientRef='.$user_no;
		$url.= '&strClientDescription='.$email;
		$c = curl_init( $url );
		curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$resp = curl_exec( $c );

/*
$resp='<?xml version="1.0" encoding="utf-8"?>
<GetVehicles release="8.1" environment="Production" lang="en-US" xmlns="">
  <ApplicationArea>
    <Sender>
      <LogicalId>0</LogicalId>
      <Component>CarweBVRRWebService</Component>
      <Task>strB2BGetVehicleByVRM</Task>
      <ReferenceId>0</ReferenceId>
      <AuthorizationId>0</AuthorizationId>
      <CreatorNameCode>0</CreatorNameCode>
      <SenderNameCode>0</SenderNameCode>
      <SenderURI>http://www.carwebuk.uk.com</SenderURI>
      <DealerNumber>
      </DealerNumber>
      <StoreNumber>
      </StoreNumber>
      <AreaNumber>
      </AreaNumber>
      <DealerCountry>
      </DealerCountry>
      <Language>en-GB</Language>
      <DeliverPendingMailInd>0</DeliverPendingMailInd>
      <Password>
      </Password>
      <SystemVersion>
      </SystemVersion>
    </Sender>
    <CreationDateTime>2014-04-01T14.43.36Z</CreationDateTime>
    <BODId>
    </BODId>
    <Destination>
      <DestinationNameCode>1</DestinationNameCode>
      <DestinationURI>127.0.0.1</DestinationURI>
      <DestinationSoftwareCode>1</DestinationSoftwareCode>
      <DestinationSoftware>VRRB2B</DestinationSoftware>
      <DealerNumber>
      </DealerNumber>
      <StoreNumber>
      </StoreNumber>
      <AreaNumber>
      </AreaNumber>
      <DealerCountry>UK</DealerCountry>
    </Destination>
  </ApplicationArea>
  <DataArea>
    <Error>
      <Header>
        <DocumentDateTime>2014-04-01T14.43.36Z</DocumentDateTime>
      </Header>
    </Error>
    <Vehicles>
      <Vehicle>
        <Combined_EngineCapacity>2171</Combined_EngineCapacity>
        <Combined_ForwardGears>5</Combined_ForwardGears>
        <Combined_FuelType>PETROL</Combined_FuelType>
        <Combined_Make>BMW</Combined_Make>
        <Combined_Model>3 SERIES 320CI SE</Combined_Model>
        <Combined_Transmission>AUTOMATIC</Combined_Transmission>
        <ColourCurrent>BLUE</ColourCurrent>
        <NumberOfDoors>2</NumberOfDoors>
        <BodyStyle> COUPE</BodyStyle>
        <ModelVariantDescription>320CI SE</ModelVariantDescription>
        <DateFirstRegistered>2001-09-12</DateFirstRegistered>
        <KTypeNumber>49</KTypeNumber>
        <EngineModelCode>M54B22</EngineModelCode>
        <Combined_VIN>WBABN12000JV89202</Combined_VIN>
      </Vehicle>
    </Vehicles>
  </DataArea>
</GetVehicles>';
*/
		$doc = new DomDocument();
		$doc->loadXML( $resp );

		$vehicles = $doc->getElementsByTagName( 'Vehicles' );
		$result = array();

		if( !empty( $vehicles ) && count( $vehicles ) > 0 ) {
			$vehicle = $vehicles->item( 0 );

			$result[ 'vrm' ] = $vrm;

			$engine_capacity = $vehicle->getElementsByTagName( 'Combined_EngineCapacity' );
			$result[ 'engine_capacity' ] = empty( $engine_capacity ) ? false : $engine_capacity->item( 0 )->textContent;

			$forward_gears = $vehicle->getElementsByTagName( 'Combined_ForwardGears' );
			$result[ 'forward_gears' ] = empty( $forward_gears ) ? false : $forward_gears->item( 0 )->textContent;

			$fuel_type = $vehicle->getElementsByTagName( 'Combined_FuelType' );
			$result[ 'fuel_type' ] = empty( $fuel_type ) ? false : $fuel_type->item( 0 )->textContent;

			$make = $vehicle->getElementsByTagName( 'Combined_Make' );
			$result[ 'make' ] = empty( $make ) ? false : $make->item( 0 )->textContent;

			$model = $vehicle->getElementsByTagName( 'Combined_Model' );
			$result[ 'model' ] = empty( $model ) ? false : $model->item( 0 )->textContent;

			$transmission = $vehicle->getElementsByTagName( 'Combined_Transmission' );
			$result[ 'transmission' ] = empty( $transmission ) ? false : $transmission->item( 0 )->textContent;

			$color = $vehicle->getElementsByTagName( 'ColourCurrent' );
			$result[ 'color' ] = empty ( $color ) ? false : $color->item( 0 )->textContent;

			$nb_doors = $vehicle->getElementsByTagName( 'NumberOfDoors' );
			$result[ 'nb_doors' ] = empty ( $nb_doors ) ? false : $nb_doors->item( 0 )->textContent;

			$body_style = $vehicle->getElementsByTagName( 'BodyStyle' );
			$result[ 'body_style' ] = empty ( $body_style ) ? false : $body_style->item( 0 )->textContent;

			$variant_descr = $vehicle->getElementsByTagName( 'ModelVariantDescription' );
			$result[ 'variant_descr' ] = empty ( $variant_descr ) ? false : $variant_descr->item( 0 )->textContent;

			$date_first_registered = $vehicle->getElementsByTagName( 'DateFirstRegistered' );
			$result[ 'date_first_registered' ] = empty ( $date_first_registered ) ? false : $date_first_registered->item( 0 )->textContent;

			$ktype = $vehicle->getElementsByTagName( 'KTypeNumber' );
			$result[ 'ktype' ] = empty ( $ktype ) ? false : $ktype->item( 0 )->textContent;

			$engine_code = $vehicle->getElementsByTagName( 'EngineModelCode' );
			$result[ 'engine_code' ] = empty ( $engine_code ) ? false : $engine_code->item( 0 )->textContent;

			$vin = $vehicle->getElementsByTagName( 'Combined_VIN' );
			$result[ 'vin' ] = empty ( $vin ) ? false : $vin->item( 0 )->textContent;
		}

		return $result;
	}
}