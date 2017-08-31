<?php
class Home extends MX_Controller {

	function __construct() {
		// Only use https in checkout page
		/*if( preg_match( '/^\/checkout/', $_SERVER[ 'REQUEST_URI' ] ) ) {
			if( empty( $_SERVER[ 'HTTPS' ] ) ) {
				header( 'Location:https://'.$_SERVER[ 'HTTP_HOST' ].$_SERVER[ 'REQUEST_URI' ] );
				exit;
			}
		}
		else {
			if( !empty( $_SERVER[ 'HTTPS' ] ) ) {
				if( empty( $_POST[ 'origin' ] ) || $_POST[ 'origin' ] != 'checkout' ) {
					header( 'Location:http://'.$_SERVER[ 'HTTP_HOST' ].$_SERVER[ 'REQUEST_URI' ] );
					exit;
				}
			}
		}*/

		parent::__construct();

		$this->load->database();
		$this->load->helper( 'url' );
		$this->load->helper( 'misc' );
		$this->load->model( 'homedao' );

		$this->_bot = false;

		if( !defined( 'BASE_URL' ) )
			define( 'BASE_URL', base_url() );

		$this->which_store();

		$this->view_dir = $this->get_setting( 'view_dir' );
		$this->login_required = $this->get_setting( 'login_required' ) == 1;
		$this->vat = ( double ) $this->get_setting( 'vat' );
		$this->fixed_customer_group = $this->get_setting( 'customer_group' );
		$this->fixed_user_no = $this->get_setting( 'user_no' );

		$this->load->library( 'session' );

		$user_info = $this->session->all_userdata();
		$this->cart_id = @$_COOKIE[ 'cart_id' ];
		$this->user_no = !empty( $this->fixed_user_no ) ? $this->fixed_user_no : @$user_info[ 'user_no' ]; // Used on pricing

		$this->customer_group = !empty( $this->fixed_customer_group ) ? $this->fixed_customer_group : @$user_info[ 'customer_group' ];
		$this->email = @$user_info[ 'email' ];
		$this->is_admin = @$user_info[ 'admin' ] == 1;
		$this->is_connected = !empty( $this->user_no ) && !empty( $this->email );
	}

	/**
	 *
	 */
	function _init_widgets() {
		// For an authenticated customer user_no is not empty.
		// Else if admin, admin = 1.
		// Otherwise a simple visitor.
		$data[ 'connected_user' ] = $this->is_connected || $this->is_admin;

		// Login widget
		if( $this->login_required ) {
			if( $this->is_connected )
				$data[ 'login' ] = Modules::run( 'login/user_menu', $this->view_dir, $this->store_id );
			else
				$data[ 'login' ] = Modules::run( 'login/index', $this->view_dir, $this->store_id );
		}
		else
			$data[ 'login' ] = '';

		if( $this->is_connected && $this->get_setting( 'quote' ) == 1 )
		$data[ 'quote' ] = true;

		$data[ 'settings' ] = $this->get_setting( 'all' );

		// Mini quick search widget.
		$data[ 'mini_quick_search' ] = Modules::run( 'search/mini_quick_search' );

		// Manufactures menu
		$data[ 'manufactures' ] = Modules::run( 'catalog/manufactures', 'ajax', 'raw' );

		// Consumables menu
		$data[ 'consumables' ] = Modules::run( 'catalog/start_end_year', 'consumables', 'consumable', 'ajax', 'raw' );

		// Show cart status
		if( ( $this->login_required && $this->is_connected ) || !$this->login_required ) {
			// Cart widget
			$data[ 'cart' ] = Modules::run( 'cart/total', false, false );
		}
		else
			$data[ 'cart' ] = false;

		if( $this->login_required && $this->is_connected ) {
			// Search by vrm widget
			if( $this->get_setting( 'vrm_required' ) == 1 ) {
				if( $this->catalogdao->can_use_carweb_ws( $this->user_no, date( 'd-m-Y' ) ) )
					$data[ 'search_by_vrm' ] = $this->load->module( 'ssearch' )->vrm_search( $this->view_dir );
				else
					$data[ 'search_by_vrm' ] = E_NO_MORE_VRM_ACCESSED;
			}
		}

		$data[ 'view_dir' ] = $this->view_dir;
		$data[ 'links'][] = BASE_URL.'assets/'.$this->view_dir.'/css/bootstrap.css';
	
		$data[ 'links'][] = BASE_URL.'assets/'.$this->view_dir.'/css/bootstrap-select.css';
		$data[ 'links'][] = BASE_URL.'assets/'.$this->view_dir.'/css/yamm.css';
		$data[ 'links'][] = BASE_URL.'assets/'.$this->view_dir.'/css/smoothzoom.css';
		$data[ 'links'][] = BASE_URL.'assets/'.$this->view_dir.'/css/style.css?_='.rand();
		$data[ 'links'][] = '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css';
		$data[ 'links' ][] = BASE_URL.'assets/'.$this->view_dir.'/css/jquery-ui/jquery.ui.accordion.css';

		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/jquery.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/typeahead.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/cookie.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/bootstrap.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/bootstrap-select.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/jquery-ui/jquery.ui.accordion.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/tabs.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/common.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/easing.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/smoothzoom.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/scripts.js';

		if( preg_match( '/(?i)msie [4-8]/', $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
			// $data[ 'links'][] = BASE_URL.'assets/css/bootstrap-2.3.2/bootstrap.css';
			// $data[ 'links'][] = BASE_URL.'assets/css/glyphicon-ie7.css';
			// $data[ 'links'][] = BASE_URL.'assets/css/ie7.css';
			// $data[ 'scripts'][] = BASE_URL.'assets/js/bootstrap-2.3.2/bootstrap.js';
			$data[ 'scripts'][] = BASE_URL.'assets/'.$this->view_dir.'/js/respond.js';
		}

		return $data;
	}

	/**
	 *
	 */
	function which_store() {
		$custom_config_file = APPPATH.'config/custom-config.php';
		if( file_exists( $custom_config_file ) )
			require_once $custom_config_file;

		$this->host = $this->get_setting( 'host' );
		$this->store_id = $this->homedao->which( $this->host );

		if( $this->store_id == false )
			die( E_UNKNOWN_STORE );
	}

	/**
	 *
	 */
	function get_setting( $setting ) {
		global $settings;

		$setting = str_replace( ' ', '_', strtolower( $setting ) );
		return $setting == 'all' ? $settings : @$settings[ $setting ];
	}

	/**
	 *
	 */
	function index() {
		$this->is_valid_connection( false );
		$data = $this->_init_widgets();
		$data[ 'mini_quick_search' ] = false;
		$data[ 'home_active' ] = true;
		$data[ 'quick_search' ] = Modules::run( 'search/quick_search', $this->view_dir );

		$data[ 'best_seller' ] = $this->get_setting( 'best_seller' ) == 1 ? Modules::run( 'best-seller/cbestseller/random', $this->view_dir, 4, $this->customer_group, $this->user_no, $this->vat, $this->is_admin ) : false;

		$data[ 'links' ][] = BASE_URL.'assets/'.$this->view_dir.'/css/slidesjs.css';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/jquery.slides.min.js';
		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/home.js';

		$data[ 'meta_title' ] = file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/home-title.txt' );
		$data[ 'meta_description' ] = file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/home-description.txt' );
		$data[ 'meta_keywords' ] = file_get_contents( APPPATH.'meta_infos/'.$this->view_dir.'/home-keyword.txt' );

		$this->load->view( $this->view_dir.'/home', $data );
	}

	/**
	 *
	 */
	function is_valid_connection( $redirect = true ) {
		if( $this->login_required == false )
			return true;

		if( $this->is_connected || $this->is_admin || $this->_bot )
			return true;

		if( $redirect ) {
			header( 'Location:'.BASE_URL );
			exit;
		}

		return false;
	}

	/**
	 *
	 */
	function _user_can_continue_or_limit_reached( $user_no, $email ) {
		if( Modules::run( 'catalog/user_can_continue_or_limit_reached', $this->store_id, $user_no, $email ) )
			$this->catalogdao->user_increment_visit( $this->store_id, $user_no, $email );
		else {
			header( 'Location:'.BASE_URL );
			exit;
		}
	}

	/**
	 *
	 */
	function download( $name, $content ) {
		header( 'Content-disposition: attachment; filename="'.$name.'"' );
		header( 'Content-type: application/force-download; charset=utf-8' );
		echo $content;
	}
}