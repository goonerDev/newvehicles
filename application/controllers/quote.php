<?php
require_once 'home.php';
class Quote extends Home {

	function __construct() {
		parent::__construct();

		$this->load->model( 'quotedao' );
	}

	/**
	 *
	 */
	function index() {
		header( 'Location:'.base_url().'quote/all' );
		exit;
	}

	/**
	 * Display a list of the last 30 quotes
	 */
	function all() {
		$user = parent::_valid_connection( true );
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		$segs = $this->uri->segment_array();

		$params = array(
			'user_id' => @$user[ 'user_no' ]
		);
		$data[ 'quotes' ] = $this->quotedao->all( $params );

		$data[ 'user_active' ] = true;

		$data[ 'meta_title' ] = file_exists( APPPATH.'meta_infos/quote-title.txt' ) ? file_get_contents( APPPATH.'meta_infos/quote-title.txt' ) : '';
		$data[ 'meta_description' ] = file_exists( APPPATH.'meta_infos/quote-description.txt' ) ? file_get_contents( APPPATH.'meta_infos/quote-description.txt' ) : '';
		$data[ 'meta_keywords' ] = file_exists( APPPATH.'meta_infos/quote-keywords.txt' ) ? file_get_contents( APPPATH.'meta_infos/quote-keywords.txt' ) : '';

		$this->load->view( 'new/quote-list', $data );
	}

	/**
	 * Get a quote details: quote_no, acc_no, quote_date, your_ref, insurance_company and parts
	 */
	function read() {
		$user = parent::_valid_connection();
		$cart_id = parent::_valid_cart();
		$data = parent::_init_widgets( $user, $cart_id );

		$quote_id = misc::sanitize( $this->uri->segment( 3 ) );
		if( !empty( $quote_id ) ) {
			$params = array(
				'user_id' => $user[ 'user_no' ],
				'quote_no' => $quote_id
			);
			$data[ 'quote' ] = $this->quotedao->read( $params );

			$data[ 'login' ]->_data[ 'user_active' ] = true;

			$this->load->view( 'new/quote-details', $data );
		}
		else {
			header( 'Location:'.base_url().'quote/all' );
			exit;
		}
	}
}
