<?php
require_once APPPATH.'controllers/home.php';
class Cart extends Home {

	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->model( 'cartdao' );
	}

	/**
	 *
	 */
	function read( $cart ) {
		return $this->cartdao->read( $cart );
	}

	/**
	 *
	 */
	function add_item( $item_id, $qty = 1 ) {
		$qty = ( int ) ( empty( $_POST[ 'qty' ] ) ? $qty : $_POST[ 'qty' ] );

		$item = Modules::run( 'catalog/item_by_id', $item_id );
		if( $item[ 'stock' ] - $qty < 0 )
			die( '{"err":"'.E_NOT_ENOUGH_QTY.'"}' );

		$this->cart_id = $this->_create_cart_if_empty( $this->email );
//die($this->cart_id); die;
		$params = array(
			'item_id' => $item_id,
			'user_no' => $this->user_no,
			'customer_group' => $this->customer_group,
			'cart_id' => $this->cart_id,
			'qty' => $qty,
			'vat' => $this->vat,
			'ebay_shipping' => $item[ 'ebay_carriage' ]
		);
		$item[ 'price' ] = $this->cartdao->add_item( $params );

		// Used in product-stock-level.php
		$user_no = $this->user_no;
		$vat = $this->vat;
		$is_admin = $this->is_admin;
		// The UI matching the new qty
		$href = BASE_URL.strtolower( misc::urlencode( $item[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $item[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $item[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $item[ 'sku' ] ) ) ).'-'.strtolower( url_title( $item[ 'product' ] ) );
		// Decrement the stock level
		$item[ 'stock' ]--;
		// $line is the variable name used to enumerate products in product-stock-level.php
		$line = $item;
		ob_start();
		require_once VIEW_PATH.'/'.$this->view_dir.'/product-stock-level.php';
		$ui = ob_get_contents();
		ob_end_clean();

		$cart =	array( 'item' => $item_id, 'cart_id' => $this->cart_id, 'ui' => $ui );
		
		setcookie( 'cart_id', $this->cart_id, time() + 86400, '/', $this->get_setting( 'host' ) );

		$user_details = $this->get_customer_details();
		if( !empty( $user_details ) ) {
			$flate_rate = $this->get_shipping_flate_rate( $user_details[ 'postcode' ], $user_details[ 'county' ] );
			$this->add_shipping_rate_flate( $flate_rate );
		}

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) // We are getting here by ajax call
			echo json_encode( $cart );
		else {
			$referer = empty( $_SERVER[ 'HTTP_REFERER' ] ) ? BASE_URL : $_SERVER[ 'HTTP_REFERER' ];
			redirect( $referer );
		}
	}
	
	/**
	 * Add an item from a previously stored quote.
	 * The item has already a price then no need to look up in sales_price table
	 * $id is the ID of the line not the item's
	 */
	function add_item_from_quote( $id, $email ) {
		if( !empty( $id ) ) {
			// Get first the quote
			$this->load->model( 'quotedao' );
			$quote = $this->quotedao->read_by_line_id( $id );

			// Then get the item to test whether it exists or not.
			// No need to check for available qty
			$item = Modules::run( 'catalog/item_by_id', $quote[ 'part_no' ] );
			if( count( $item ) == 0 )
				die( '{"err":"'.misc::esc_json( E_ITEM_NOT_FOUND ).'"}' );

			$cart_id = $this->_create_cart_if_empty( $email );

			$params = array(
				'cart_id' => $cart_id,
				'item_id' => $quote[ 'part_no' ],
				'qty' => 1,
				'customer_group' => false,
				'user_no' => $this->user_no,
				'price' => number_format( $quote[ 'unit_price' ], 2 ),
				'quote_no' => $quote[ 'quote_no' ]
			);
			$this->cartdao->add_item( $params );

			echo '{"item":"'.$quote[ 'part_no' ].'", "cart_id":"'.$cart_id.'"}';
		}
	}

	/**
	 *
	 */
	function delete_item( $id ) {
		$params = array(
			'id' => ( int ) $id,
			'user_no' => $this->user_no
		);
		$this->cartdao->delete_item( $params );

		$items = $this->items( 'raw', 'NO' );
		if( empty( $items ) )
			redirect( BASE_URL.'cart/discard' );

		$user_details = $this->get_customer_details();
		if( !empty( $user_details ) ) {
			$flate_rate = $this->get_shipping_flate_rate( $user_details[ 'postcode' ], $user_details[ 'county' ] );
			$this->add_shipping_rate_flate( $flate_rate );
		}

		// ajax is only sent by post when deleting an item from the mini cart menu
		if( empty( $_POST[ 'ajax' ] ) || $_POST[ 'ajax' ] != 1 ) {
			if( empty( $_SERVER[ 'HTPP_REFERER' ] ) )
				header( 'Location:'.BASE_URL.'checkout' );
			else
				header( 'Location:'.$_SERVER[ 'HTTP_REFERER' ] );
			exit;
		}
	}

	/**
	 *
	 */
	function _update_qty( $old_item_vals, $qty ) {
		$item = Modules::run( 'catalog/item_by_id', $old_item_vals[ 'sku' ] );

		if( $item[ 'stock' ] + $old_item_vals[ 'qty' ] - $qty < 0 )
			die( '{"err":"'.E_NOT_ENOUGH_QTY.'", "qty":"'.$old_item_vals[ 'qty' ].'"}' );

		$params = array(
			'id' => $old_item_vals[ 'id' ],
			'qty' => $qty,
			'user_no' => $this->user_no
		);
		$this->cartdao->set_qty_item( $params );
	}

	/**
	 *
	 */
	function update_qty() {
		$id = ( int ) $_POST[ 'id' ];
		$line_item = $this->cartdao->item( $id );

		$qty = ( int ) $_POST[ 'qty' ];
		$this->_update_qty( $line_item, $qty );
	}

	/**
	 *
	 */
	function update_qty_by_sku( $sku, $qty = 1 ) {
		$qty = ( int ) $_POST[ 'qty' ];

		// Check first whether the item is already added to the cart, ...
		$line_item = $this->cartdao->item_by_sku( $this->cart_id, $this->user_no, $sku );
		if( empty( $line_item ) )
			// If the item is not yet in the cart then add it to
			$this->add_item( $sku, $qty );
		else
			// else just update the qty
			$this->_update_qty( $line_item, $qty );

	}

	/**
	 *
	 */
	function total( $ajaxCall = true, $format = false, $zcarriage = false ) {
		//echo $this->cart_id;die;
		$params = array(
			'cart_id' => ( int ) $this->cart_id,
			'user_no' => $this->user_no,
			'zcarriage' => $zcarriage
		);
		$total = $this->cartdao->total( $params );

		if( $ajaxCall ) {
			if( $format == 'json' )
				echo '{"total":"'.CURRENCY.number_format( $total, 2, '.', ' ' ).'"}';
			elseif( $format == 'raw' )
				echo CURRENCY.number_format( $total, 2, '.', ' ' );
		}
		else {
			if( $format == 'raw' )
				return $total;

			$this->load->view( $this->view_dir.'/cart-total', array( 'total' => $total ) );
		}
	}

	/**
	 *
	 */
	function checkout( $cart_id = false, $user_no = false, $format = false ) {
            
		$cart_id = empty( $cart_id ) ? $this->cart_id : $cart_id;
		$user_no = empty( $user_no ) ? $this->user_no : $user_no;

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

			if( empty( $_POST[ 'payment_method' ] ) && @$_POST[ 'ajaxCall' ] == 'ajax' ) {
				die( '{"err":"1","msg":"Please specify what payment method to use"}' );
			}

			if( ( $err = $this->save_customer_details( 'raw' ) ) !== true ) {
				if( @$_POST[ 'ajaxCall' ] == 'ajax' ) {
					die( '{"err":"1","msg":"'.misc::esc_json( $err ).'"}' );
				}
			}
			else {
				$this->get_shipment_flate_rate( 'raw' ); // To check whether the delivery country is coverable
				$this->save_items();

				if( $this->_correct_items_values() === false ) {
					if( @$_POST[ 'ajaxCall' ] == 'ajax' ) {
						die( '{"err":"1","msg":"'.misc::esc_json( E_INCORRECT_ITEMS ).'"}' );
					}
					$err = 'err2'; // Maybe the cart is empty or total items is 0
				}
				else {
					if( @$_POST[ 'ajaxCall' ] == 'ajax' ) {
						header( 'Content-type:text/plain' );
						die( '{"err":"0","msg":"'.misc::esc_json( $this->payment_form() ).'"}' );
					}
					else {
						die( $this->payment_form() );
					}
				}
			}
		}

		$params = array(
			'cart_id' => ( int ) $cart_id,
			'user_no' => misc::sanitize( $user_no ),
			'zcarriage' => 'NO'
		);
		$res[ 'items' ] = $this->cartdao->items( $params );
		$res[ 'cart_details' ] = $this->cartdao->read( $params );

		$current_shipping_rate = $this->get_current_shipping_flate_rate( $cart_id, $user_no );
		$res[ 'shipment_rate' ] = empty( $current_shipping_rate ) ? $this->get_shipping_flate_rate( @$res[ 'cart_details' ][ 'postcode' ], @$res[ 'cart_details' ][ 'county' ] ) : $current_shipping_rate[ 'pu' ];

		$res[ 'referer' ] = @$_SERVER[ 'HTTP_REFERER' ];
		$res[ 'view_dir' ] = $this->view_dir;

		if( $format == 'raw' )
			return $res;

		$data = parent::_init_widgets();

		$data = array_merge( $data, $res );

		if( !empty( $res[ 'cart_details' ][ 'recipient' ] ) && trim( $res[ 'cart_details' ][ 'recipient' ] ) != '' ) {
			$recipient = $res[ 'cart_details' ][ 'recipient' ];
			$last_space = strrpos( $recipient, ' ' );
			$data[ 'cart_details' ][ 'firstname' ] = $last_space === false ? '' : substr( $recipient, 0, $last_space );
			$data[ 'cart_details' ][ 'lastname' ] = $last_space === false ? '' : substr( $recipient, $last_space + 1 );
		}
		elseif( !empty( $_POST[ 'cart_details' ] ) )
			$data[ 'cart_details' ] = $_POST[ 'cart_details' ];

		$data[ 'scripts' ][] = BASE_URL.'assets/'.$this->view_dir.'/js/cart-confirm.js';
		$data[ 'cur_page' ] = BASE_URL.'checkout';

		if( !empty( $err ) )
			$data[ 'err' ] = $err;

		$data[ 'paypal_form' ] = $this->payment_form( 'Paypal instant update' );

		if( @$_COOKIE[ 'screen_width' ] < 768 )
			$this->load->view( $this->view_dir.'/cart-confirm-mobile', $data );
		else
			$this->load->view( $this->view_dir.'/cart-confirm', $data );
	}

	/**
	 *
	 */
	function discard() {
		$params = array(
			'user_no' => misc::sanitize( $this->user_no ),
			'cart_id' => ( int ) $this->cart_id
		);
		$this->cartdao->discard( $params );

		setcookie( 'cart_id', false, time() - 86400, '/', $this->get_setting( 'host' ) );

		redirect( BASE_URL.'checkout'  );
	}

	/**
	 *
	 */
	function item_by_sku( $sku ) {
		return $this->cartdao->item_by_sku( $this->cart_id, $this->user_no, $sku );
	}

	/**
	 * @$zcarriage possible values are NO and false. If NO the zcarriage shouldn't be added to the result.
	 * If false the list of items will contain zcarriage
	 */
	function items( $format = false, $zcarriage = false ) {
                        
            if(isset($_COOKIE['cart_id'])){
                $crt_id= $_COOKIE['cart_id'];
            }else{
                $crt_id= $this->cart_id;
             }
           
		$params = array(
			'cart_id' => ( int )$crt_id,
			'user_no' => $this->user_no,
			'zcarriage' => $zcarriage
		);
		$data[ 'items' ] = $this->cartdao->items( $params );

		if( $format == 'raw' )
			return $data[ 'items' ];

		$data[ 'view_dir' ] = $this->view_dir;
		$data[ 'cart_id' ] = $this->cart_id;
		$data[ 'user_no' ] = $this->user_no;

		$this->load->view( $this->view_dir.'/quick-mini-cart-items', $data );
	}

	/**
	 * The use of this function is generally to save vrm if provided at checkout page.
	 * It is followed by selection of shipping address
	 */
	function submit_order( $vrm, $your_reference, $cart_id ) {
		if( !empty( $vrm ) )
			$this->cartdao->save_vrm( array( 'vrm' => $_POST[ 'vrm' ], 'cart_id' => $cart_id ) );

		if( !empty( $your_reference ) )
			$this->cartdao->save_your_reference( array( 'your_reference' => $your_reference, 'cart_id' => $cart_id ) );
	}

	/**
	 *
	 */
	
	function get_customer_details() {
		if( empty( $this->cart_id ) || empty( $this->user_no ) )
			die( '{"err":"1", "msg":"'.misc::esc_json( E_QUOTE_NOT_FOUND ).'"}' );

		return $this->cartdao->get_customer_details( ( int ) $this->cart_id, misc::sanitize( $this->user_no ) );
	}

	/**
	 * Save customer details. Generally this is used when the site doesn't require login process then retrieve customer data
	 * on payment stage
	 */
	function save_customer_details( $format = false, $telephone_required = true ) {
		$_post = misc::sanitize( $_POST );

		$params = array(
			'cart_id' => $this->cart_id,
			'user_no' => $this->user_no,
			'firstname' => $_post[ 'firstname' ],
			'lastname' => $_post[ 'lastname' ],
			'email' => $_post[ 'email' ],
			'telephone' => $_post[ 'telephone' ],
			'address' => $_post[ 'address' ],
			'city' => $_post[ 'town' ],
			'postcode' => $_post[ 'postcode' ],
			'country' =>  $_post[ 'country' ],
			'comments' =>  htmlentities( $_post[ 'comments' ] ),
			'payment_method' => $_post[ 'payment_method' ]
		);
		$check = $this->_not_valid_customer_details( $params, $telephone_required );
		if( $check !== true ) {
			if( $format == false )
				die( '{"err":"2", "msg":"'.misc::esc_json( $check ).'"}' );
			return $check;
		}

		$this->cartdao->save_customer_details( $params );

		return true;
	}

	/**
	 *
	 */
	function save_userdetails() {
		switch( @$_POST[ 'key' ] ) {
			case 'recipient':
			case 'email':
			case 'telephone':
			case 'address':
			case 'town':
			case 'postcode':
				$fieldname = @$_POST[ 'key' ];
				break;
			default:
				return;
		}

		$this->cartdao->save_individual_customer_detail( array(
			'fieldname' => $fieldname,
			'value' => misc::sanitize( @$_POST[ 'val' ] ),
			'cart_id' => $this->cart_id,
			'user_no' => $this->user_no
		) );
	}

	/**
	 *
	 */
	function save_items() {
		if( is_array( @$_POST[ 'qty' ] ) ) {
			$qty = $_POST[ 'qty' ];
			foreach( $qty as $sku => $val ) {
				$_POST[ 'qty' ] = $val; // This can be understood at line 50, update_qty_by_sku function
				$this->update_qty_by_sku( $sku );
			}
		}
	}

	/**
	 *
	 */
	function save_payment_method() {
		$post = misc::sanitize( $_POST );
		$this->cartdao->save_payment_method( $this->cart_id, $this->user_no, $post[ 'pm' ] );
	}

	function get_current_shipping_flate_rate( $cart_id, $user_no ) {
		return $this->cartdao->get_current_shipping_flate_rate( $cart_id, $user_no );
	}

	/**
	 *
	 */
	function get_shipping_flate_rate( $postcode = '', $country = '' ) {
		if( empty( $country ) )
			return 0;

		// if( empty( $postcode ) ) {
			// $addr = $this->cartdao->read( array( 'cart_id' => $cart_id, 'user_no' => $user_no ) );
			// $postcode = $addr[ 'postcode' ];
			// $country = $addr[ 'county' ];
		// }

		if( strtolower( $country ) == 'united kingdom' ) {

			if( empty( $postcode ) )
				return 0;

			$shipping_postcode = strtolower( str_replace( ' ', '', $postcode ) );

			if( preg_match( '/[a-z]{2}/', $shipping_postcode ) )
				$letters_in_first_part_of_postcode = 2;
			else
				$letters_in_first_part_of_postcode = 1;

			if ( preg_match( '/[a-z]+[0-9]{4}/', $shipping_postcode ) )
				$numbers_in_first_part_of_postcode = 2;
			elseif( preg_match( '/[a-z]+[0-9]{3}/', $shipping_postcode ) )
				$numbers_in_first_part_of_postcode = 2;
			elseif( preg_match( '/[a-z]+[0-9]{2}/', $shipping_postcode ) )
				$numbers_in_first_part_of_postcode = 1;
			else
				$numbers_in_first_part_of_postcode = 1;

			$shipping_postcode_first_part = substr( $shipping_postcode, 0, ( $letters_in_first_part_of_postcode + $numbers_in_first_part_of_postcode ) );

			$shipping_pc = strtolower( substr( $shipping_postcode_first_part, 0, 2 ) );
			$shipping_pc2 = substr( $shipping_postcode_first_part, 2 );

			// In UK exclusion zones
			if( $shipping_pc == 'bt' ) {
				return 30.00;
			}
			if( $shipping_pc == "iv" )
				return 30.00;
			elseif( $shipping_pc == 'kw' ) {
				if( 1 <= $shipping_pc2 && $shipping_pc2 <= 14 )
					return 30.00;
			}
			elseif( $shipping_pc == 'pa' ) {
				if( $shipping_pc2 == 34 || ( 37 <= $shipping_pc2 && $shipping_pc2 <= 39 ) )
					return 30.00;
			}
			
			elseif( $shipping_pc == 'ph' ) {
				if( ( 19 <= $shipping_pc2 && $shipping_pc2 <= 26 ) ||
					( 30 <= $shipping_pc2 && $shipping_pc2 <= 40 ) ||
					  49 == $shipping_pc2 ||
					  50 == $shipping_pc2 )
					return 30.00;
			}

			// In UK non-exclusion zones and non islands
			$items = $this->items( 'raw', 'NO' );
			if( count( $items ) == 1 && $items[ 0 ][ 'qty' ] == 1 && 0 < $items[ 0 ][ 'ebay_shipping' ] && $items[ 0 ][ 'ebay_shipping' ] < 9.95 )
				return $items[ 0 ][ 'ebay_shipping' ];

			return 9.95;
		}

		return 30.00;
	}

	/**
	 *
	 */
	function get_shipment_flate_rate( $format = false ) {
		// $cart_details is empty if the request comes from Paypal Instant Update else
		// from checkout page
		$_post = misc::sanitize( empty( $_POST[ 'cart_details' ] ) ? $_POST : $_POST[ 'cart_details' ] );

		if( strtolower( $_post[ 'country' ] ) == 'other' ) {
			if( $format == false )
				echo( '{"err":"1", "msg":"'.misc::esc_json( E_UNCOVERED_SHIPPING_COUNTRY ).'"}' );
			elseif( $format == 'raw' )
				return E_UNCOVERED_SHIPPING_COUNTRY;
		}
		else {
			$flate_rate = $this->get_shipping_flate_rate( $_post[ 'postcode' ], $_post[ 'country' ] );
			$this->cartdao->save_individual_customer_detail( array( 'fieldname' => 'county', 'value' => misc::sanitize( @$_POST[ 'country' ] ), 'cart_id' => $this->cart_id, 'user_no' => $this->user_no ) );
			$this->cartdao->save_individual_customer_detail( array( 'fieldname' => 'postcode', 'value' => misc::sanitize( @$_POST[ 'postcode' ] ), 'cart_id' => $this->cart_id, 'user_no' => $this->user_no ) );
			$this->add_shipping_rate_flate( $flate_rate );

			if( $format == false )
				echo( '{"err":"0", "msg":"'.CURRENCY.number_format( $flate_rate, 2 ).'"}' );
			elseif( $format == 'raw' )
				return CURRENCY.number_format( $flate_rate, 2 );
			elseif( $format == 'rawwithoutprice' )
				return number_format( $flate_rate, 2 );
		}
	}

	/**
	 *
	 */
	function add_shipping_rate_flate( $rate ) {
		$this->cartdao->add_shipping_rate_flate( $this->cart_id, $this->user_no, $rate );
	}

	/**
	 *
	 */
	function payment_form( $payment_method = false, $format = false ) {
		$cart = $this->read( array( 'cart_id' => $this->cart_id, 'user_no' => $this->user_no ) );

		$payment_method = urldecode( $payment_method );
		if( $payment_method != false )
			$cart[ 'payment_method' ] = $payment_method;

		ob_start();

		$success_url = $this->get_setting( $cart[ 'payment_method' ].' success url' );
		$failure_url = $this->get_setting( $cart[ 'payment_method' ].' failure url' ).'/'.base64_encode( $this->cart_id );
               

		$this->_payment_form( $cart, $success_url, $failure_url, $this->vat, $payment_method );

		$res = ob_get_contents();
		ob_end_clean();

		if( $format == 'raw' )
			die( $res );

		return $res;
	}

	/**
	 * Sometimes one may need the payment form bypassing the stored payment method.
	 * For such case $payment_method variable is provided
	 */
	function _payment_form( $cart, $success_url, $failure_url, $vat, $payment_method = false ) {
		if( empty( $cart[ 'id' ] ) )
			return;

		$params = array(
			'cart_id' => $cart[ 'id' ],
			'user_no' => $this->user_no,
			'zcarriage' => $payment_method == 'Paypal instant update' ? 'NO' : false // If Paypal instant update the shipping rate is computed on Paypal checkout page, otherwise take it into account here
		);
		$items = $this->cartdao->items( $params );

		if( empty( $items ) )
			return;

		$recipient = @$cart[ 'recipient' ];
		$last_space_pos = stripos( $recipient, ' ' );
		$cart[ 'firstname' ] = substr( $recipient, 0, $last_space_pos );
		$cart[ 'lastname' ] = substr( $recipient, $last_space_pos + 1 );

		$cart[ 'total' ] = str_replace( CURRENCY, '', $this->total( false, 'raw', $payment_method == 'Paypal instant update' ? 'NO' : false ) );

		if( $payment_method != false )
			$cart[ 'payment_method' ] = $payment_method;

		if( $cart[ 'payment_method' ] == 'Sagepay' ) {
			$this->load->library( 'sagepay' );
			$this->sagepay->get_form( $cart, $items, $success_url, $failure_url, $vat );
		}
		elseif( $cart[ 'payment_method' ] == 'Paypal' ) {
			$this->load->library( 'paypal' );
			$this->paypal->get_form( $cart, $items, $success_url, $failure_url );
		}
		elseif( $cart[ 'payment_method' ] == 'Paypal instant update' ) {
			$this->load->library( 'paypal_instant_update' );
			$this->paypal_instant_update->get_form( $cart, $items, $success_url, $failure_url );
		}
		elseif( $cart[ 'payment_method' ] == 'Paypal mobile' ) {
			$this->load->library( 'paypal_mobile' );
			$this->paypal_mobile->get_form( $cart, $items, $success_url, $failure_url );
		}
	}

	/**
	 *
	 */
	function process_order( $store_id, $real_user_no, $transaction_id, $confirmation_mail_params = array() ) {
		$params = array( 'cart_id' => $this->cart_id, 'user_no' => $this->user_no, 'transaction_id' => $transaction_id );

		$items = $this->cartdao->items( $params );

		if( !empty( $real_user_no ) ) {
			$this->cartdao->set_real_user_no( $this->cart_id, $this->user_no, $real_user_no );
			$params[ 'user_no' ] = $real_user_no;
		}

		$this->cartdao->mark_as_processed( $params );

		// The result of this call will contain order_id which is set by the previous function
		$cart = $this->cartdao->read( $params );
		$this->cartdao->save_customer_to_final_order( $cart );
		$this->cartdao->set_order_status( $cart[ 'order_id' ], 'Placed' );

		Modules::run( 'best-seller/cbestseller/update_by_new_order', $store_id, $items );

		$params = array( 'cart_id' => $this->cart_id, 'user_no' => $real_user_no );
		$total = $this->cartdao->total( $params );

		$this->_send_email_to_admin_to_confirm_order( $cart, $items, $confirmation_mail_params );

		$this->_send_email_to_customer( $cart, $items, $total, $confirmation_mail_params );
	}

	/**
	 *
	 */
	function on_sagepay_payment_success() {
		if( empty( $_GET[ 'crypt' ] ) )
			fputs( fopen( 'sagepay payment error.txt', 'a' ), date( 'd-m-Y H:i:s' ).'|'.$this->cart_id."|Crypt is empty\n"  );
		else {
			$this->load->library( 'sagepay' );
			if( ( $return_data = $this->sagepay->decode_data( $_GET[ 'crypt' ] ) ) != false ) {

				if( $return_data[ 'Status' ] == 'OK' )
					$this->_finalize_order_process( $return_data[ 'VPSTxId' ] );
					redirect( BASE_URL.'thank-you/checkout' );
					
			}
		}

		// If this point is reached this means errors have found
		header( 'Location:'.BASE_URL.'thank-you/checkout' );
		exit;
	}

	/**
	 *
	 */
	function on_sagepay_payment_failure( $hashed_cart_id ) {
		if( empty( $this->cart_id ) ) {
			$this->cart_id = base64_decode( $hashed_cart_id );
			setcookie( 'cart_id', $this->cart_id, time() + 86400, '/', $this->get_setting( 'host' ) );
		}

		if( empty( $_GET[ 'crypt' ] ) )
			fputs( fopen( 'sagepay payment error.txt', 'a' ), date( 'd-m-Y H:i:s' ).'|'.$this->cart_id."|Crypt is empty\n"  );
		else {
			$this->load->library( 'sagepay' );
			$return_data = $this->sagepay->decode_data( $_GET[ 'crypt' ] );
			fputs( fopen( 'sagepay payment error.txt', 'a' ), json_encode( $return_data )."\n" );
		}

		redirect( BASE_URL.'checkout' );
	}

	/**
	 * Paypal calls this function to update the shipping fee according to the delivery address
	 */
	function on_paypal_callback_received() {
		if( @$_POST[ 'METHOD' ] != 'CallbackRequest' )
			return;

		$this->load->library( 'paypal_instant_update' );

		if( empty( $_POST[ 'SHIPTOZIP' ] ) || empty( $_POST[ 'SHIPTOCOUNTRY' ] ) )
			$this->paypal_instant_update->raise_no_shipment_options();
		else {
			$_POST[ 'postcode' ] = $_POST[ 'SHIPTOZIP' ];

			if( $_POST[ 'SHIPTOCOUNTRY' ] == 'GB' ) { // Great Britain
				if( @$_POST[ 'SHIPTOSTATE' ] == 'Isle of Wight' ||
					@$_POST[ 'SHIPTOSTATE' ] == 'Isles of Scilly' ||
					@$_POST[ 'SHIPTOSTATE' ] == 'Isle of Man' ||
					@$_POST[ 'SHIPTOSTATE' ] == 'Channel Islands' )
					$_POST[ 'country' ] = 'Isle of Wight'; // The goal is to have £30.00 flat rate so no matter the return result is Isle of Wight or Channel Islands or Isles of Scilly
				elseif( preg_match_all( '/isle\s+of\s+wight|chann?els?\s+island|isles?\s+of\s+scill?y/i', $_POST[ 'SHIPTOCITY' ], $matches ) )
					$_POST[ 'country' ] = 'Isle of Wight';
				else
					$_POST[ 'country' ] = 'United Kingdom';
				$flat_rate = $this->get_shipment_flate_rate( 'rawwithoutprice' );
				$this->paypal_instant_update->update_shipping_fee( $flat_rate );
			}
			elseif( $_POST[ 'SHIPTOCOUNTRY' ] == 'IE' ) { // Ireland
				$_POST[ 'country' ] = 'Northern Ireland';

				$flat_rate = $this->get_shipment_flate_rate( 'rawwithoutprice' );
				$this->paypal_instant_update->update_shipping_fee( $flat_rate );
			}
			else {
				$this->paypal_instant_update->raise_no_shipment_options();
				exit;
			}
		}
	}

	/**
	 * The customer has been agree to use Paypal
	 */
	function on_paypal_payment_accepted() {
		$this->load->library( 'paypal' );

		$res = $this->paypal->get_express_checkout_details( $_GET );

		$_POST[ 'firstname' ] = @$res[ 'FIRSTNAME' ];
		$_POST[ 'lastname' ] = @$res[ 'LASTNAME' ];
		$_POST[ 'email' ] = @$res[ 'EMAIL' ];
		$_POST[ 'telephone' ] = '';
		$_POST[ 'address' ] = @$res[ 'SHIPTOSTREET' ];
		$_POST[ 'town' ] = @$res[ 'SHIPTOCITY' ];
		$_POST[ 'postcode' ] = @$res[ 'SHIPTOZIP' ];
		$_POST[ 'country' ] = @$res[ 'SHIPTOCOUNTRYNAME' ];
		$_POST[ 'comments' ] = false;
		$_POST[ 'payment_method' ] = false;
		$this->save_customer_details( false, false );

		$this->add_shipping_rate_flate( @$res[ 'PAYMENTREQUEST_0_SHIPPINGAMT' ] );

		$res = $this->paypal->do_payment( $res );

		if( $res[ 'ACK' ] == 'Success' ) {
			
			$this->_finalize_order_process( $res[ 'PAYMENTINFO_0_TRANSACTIONID' ] );
			header( 'Location:'.BASE_URL.'thank-you/checkout' );
		} else {
			fputs( fopen( 'paypal payment error.txt', 'a' ), date( 'd-m-Y H:i:s' ).'|'.$this->cart_id."|".$res[ 'L_LONGMESSAGE0' ]."\n"  );
			header( 'Location:'.BASE_URL.'checkout' );
			exit;
		}
	}

	/**
	 * The customer has cancelled the payment process on Paypal site. Redirect him to the checkout page
	 */
	function on_paypal_payment_cancelled( $hashed_cart_id ) {
		if( empty( $this->cart_id ) ) {
			$this->cart_id = base64_decode( $hashed_cart_id );
			setcookie( 'cart_id', $this->cart_id, time() + 86400, '/', $this->get_setting( 'host' ) );
		}

		redirect( BASE_URL.'checkout' );
	}

	/**
	 *
	 */
	function track_order( $hashed_email, $hashed_order_id, $format = false ) {
		$email = base64_decode( $hashed_email );
		$order_id = base64_decode( $hashed_order_id );

		$res = $this->cartdao->get_order_status( $email, $order_id );

		$data = parent::_init_widgets();

		if( $res === false );
		else {
			$data[ 'status' ] = $res[ 'status' ];
			$res = $this->checkout( $res[ 'id' ], $res[ 'customer_id' ], 'raw' );
			$data = array_merge( $data, $res );
		}

		$this->load->view( $this->view_dir.'/track-order', $data );

	}

	/**
	 * The user may have set 0 on all items qty. Then check if the total of the basket is greater than 0
	 */
	function _correct_items_values() {
		$res = $this->total( false, 'raw' );
		return $res != CURRENCY.number_format( 0, 2, '.', ' ' );
	}

	/**
	 *
	 */
	function _finalize_order_process( $transaction_id ) {
		// Send email to customer and admin,
		// set the order as processed,
		// update best-seller by this order items
		$confirmation_mail_params = array(
			'from' => $this->get_setting( 'cart_confirmation_mail_exped' ),
			'subject' => $this->get_setting( 'cart_confirmation_mail_subject' ),
			'text' => $this->get_setting( 'cart_confirmation_mail_text' ),
			'bcc' => $this->get_setting( 'cart_confirmation_mail_bcc' )
		);

		$real_user_no = $this->get_setting( 'order_user_no' );

		$this->process_order( $this->store_id, $real_user_no, $transaction_id,  $confirmation_mail_params );
		
		setcookie( 'cart_id', false, time() - 86400, '/', $this->get_setting( 'host' ) );
	}

	/**
	 *
	 */
	function _not_valid_customer_details( $post, $telephone_required = true ) {
		if( empty( $post[ 'firstname' ] ) || empty( $post[ 'lastname' ] ) || empty( $post[ 'email' ] ) ||
		empty( $post[ 'address' ] ) || empty( $post[ 'city' ] ) ||
		( empty( $post[ 'postcode' ] ) && strtolower( $post[ 'country' ] ) == 'united kingdom' ) || empty( $post[ 'country' ] ) || ( $telephone_required && empty( $post[ 'telephone' ] ) ) )
			return E_EMPTY_FIELD;

		if( misc::valid_email( $post[ 'email' ] ) == false )
			return E_INVALID_EMAIL_ADDR;

		if( !empty( $post[ 'telephone' ] ) && misc::valid_telephone( $post[ 'telephone' ] ) == false )
			return E_INVALID_PHONE_NUMBER;

		return true;
	}

	/**
	 *  
	 */
	function _create_cart_if_empty( $email ) {
		$cart = $this->read( array( 'cart_id' => $this->cart_id, 'user_no' => $this->user_no ) );

		if( empty( $cart ) ) {
			$params = array(
				'store_id' => $this->store_id,
				'user_id' => $this->user_no,
				'email' => $email,
				'vrm' => @$_POST[ 'vrm' ]
			);
			return $this->cartdao->create( $params );
		}
		return $cart[ 'id' ];
	}

	/**
	 *
	 */
	function _send_email_to_admin_to_confirm_order( $cart, $items, $confirmation_mail_params ) {
		$msg = "Order ID: ".$cart[ 'order_id' ]."<br/>";
		$msg.= "Customer ID: ".$cart[ 'customer_id' ]."<br/>";
		$msg.= "Date: ".$cart[ 'creation_date' ]."<br/><br/>";

		$msg.= "Contact: ".$cart[ 'recipient' ].'<br/>';
		$msg.= "Address: ".$cart[ 'address' ].'<br/>';
		$msg.= "Town: ".$cart[ 'town' ].'<br/>';
		$msg.= "County: ".$cart[ 'county' ].'<br/><br/>';
		$msg.= "Postal code: ".$cart[ 'postcode' ].'<br/>';
		$msg.= "Telephone: ".$cart[ 'telephone' ].'<br/>';
		$msg.= "Email Address: ".$cart[ 'email' ].'<br/>';
		$msg.= "Comments: ".$cart[ 'comments' ].'<br/>';

		if( !empty( $cart[ 'payment_method' ] ) )
			$msg.= "Payment method: ".$cart[ 'payment_method' ].'';


		$msg.= $this->_email_list_items( $items );

		misc::mail( array(
			'recipient' => $this->get_setting( 'email' ),
			//'recipient' => 'newvehicleparts@gmail.com,kdw@prasco.co.uk,p-deakes@prasco.co.uk',
			'bcc' => 'malblackshaw71@gmail.com',
			'subject' => 'New vehicle parts - New order',
			'from' => $confirmation_mail_params[ 'from' ],
			'msg' => $msg,
			'error_label' => 'Thank you<br> Your order has been processed successfully</br>unfortunatly the confirmation email have not been sent to you<br><b> PLEASE DON NOT PRESS THE BACK BUTTON</b><br> This will result in you getting charged twice, please just close the browser<br>We are aware of this issue<br>Regards<br> New Vehicle Parts  <br><br> Error Code:  ' 
		) );
		
	}

	/**
	 *
	 */
	function _send_email_to_customer( $cart, $items, $total, $confirmation_mail_params ) {
		if( empty( $cart[ 'email' ] ) )
			return;

		$from = $confirmation_mail_params[ 'from' ];
		$subject = str_replace( '#{ORDER_ID}', $cart[ 'order_id' ], $confirmation_mail_params[ 'subject' ] );
		$msg = $this->_substitute_tags_in_confirmation_mail( $confirmation_mail_params[ 'text' ], $cart, $items, $total );

		misc::mail( array(
			'recipient' => $cart[ 'email' ],
			'bcc' => $confirmation_mail_params[ 'bcc' ],
			//'bcc' => 'new-vehicle-parts@b2b.reviews.co.uk,pracosale@gmail.com',
			'subject' => $subject,
			'from' => $from,
			'msg' => $msg,
			'error_label' => 'Thank you<br> Your order has been processed successfully</br>unfortunatly the confirmation email haven not been sent to you<br><b> PLEASE DON NOT PRESS THE BACK BUTTON</b><br> This will result in you getting charged twice, please just close the browser<br>We are aware of this issue<br>Regards<br> New Vehicle Parts  <br><br> Error Code:'
		) );
		
	}

	/**
	 * 
	 */
	function _email_list_items( $items ) {
		$items_to_email = '';
		$total = 0;
		$i = 1;
		foreach( $items as $item ) {
			$ss_total = round( $item[ 'qty' ] * $item[ 'pu' ], 2 );
			$total += $ss_total;

			$items_to_email.= "<tr>";
			$items_to_email.= "<td>$i</td>";
			$items_to_email.= "<td>".$item[ 'sku' ]."</td>";
			$items_to_email.= "<td>".( $item[ 'sku' ] == 'zcarriage' ? 'Carriage' : $item[ 'product' ] )."</td>";
			$items_to_email.= "<td>".number_format( $item[ 'pu' ], 2 )."</td>";
			$items_to_email.= "<td>".$item[ 'qty' ]."</td>";
			$items_to_email.= "<td>".number_format( $ss_total, 2 )."</td>";
			$items_to_email.= "</tr>";
			$i++;
		}

		// Total
		$items_to_email.= "<tr>";
		$items_to_email.= "<td colspan=\"4\"></td>";
		$items_to_email.= "<td>TOTAL(".CURRENCY.")</td>";
		$items_to_email.= "<td>".number_format( $total, 2 )."</td>";
		$items_to_email.= "</tr>";

		$table_items = "<table width=\"100%\" border=\"1\">";
		$table_items.= 		"<thead>";
		$table_items.= 			"<tr>";
		$table_items.= 				"<th width=\"5%\"></th>";
		$table_items.= 				"<th width=\"15%\">Sku</th>";
		$table_items.= 				"<th width=\"35%\">Item</th>";
		$table_items.= 				"<th width=\"15%\">Price(".CURRENCY.")</th>";
		$table_items.= 				"<th width=\"15%\">Qty</th>";
		$table_items.= 				"<th width=\"15%\">Total(".CURRENCY.")</th>";
		$table_items.= 			"</tr>";
		$table_items.= 		"</thead>";
		$table_items.= 		"<tbody>";
		$table_items.= 			$items_to_email;
		$table_items.= 		"</tbody>";
		$table_items.= "</table>";

		return $table_items;
	}

	/**
	 *
	 */
	function _substitute_tags_in_confirmation_mail( $txt, $cart, $items, $total ) {
		return str_replace(
			array(
				'#{ORDER_ID}',
				'#{RECIPIENT}',
				'#{ORDER_DATE}',
				'#{ADDRESS}',
				'#{PAYMENT_METHOD}',
				'#{TOTAL}',
				'#{ORDER_ITEMS}',
				'#{HASHED_ORDER_ID}',
				'#{HASHED_EMAIL}'
			),
			array(
				$cart[ 'order_id' ],
				$cart[ 'recipient' ],
				str_replace( '-', '/', $cart[ 'creation_date' ] ),
				$cart[ 'address' ].'<br/>'.$cart[ 'town' ].'<br/>'.$cart[ 'county' ].' - '.$cart[ 'postcode' ],
				!empty( $cart[ 'payment_method' ] ) ? $cart[ 'payment_method' ] : '',
				CURRENCY.number_format( $total, 2 ),
				$this->_email_list_items( $items ),
				base64_encode( $cart[ 'order_id' ] ),
				base64_encode( $cart[ 'email' ] )
			),
			$txt
		);
	}
}