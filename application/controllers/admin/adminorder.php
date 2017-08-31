<?php
require_once 'admintask.php';
class Adminorder extends Admintask {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->model( 'adminorderdao' );
	}

	/**
	 *
	 */
	function export() {
		if( !$this->is_admin && !$this->_bot ) {
			header( 'location:'.BASE_URL );
			exit;
		}

		// The user may defined the order he wants to export. Here the value is the order ID
		$order_id = ( int ) @$_POST[ 'cart_id' ];

		// Last exported order id got from the database. Then if the user hasn't specified from which id to start
		// this value will be taken into account
		$current_last_id = ( int ) $this->adminorderdao->get_last_export_id( 'order', $this->store_id );
		// Start order id from the user
		$last_id = ( int ) @$_POST[ 'export_from' ];
		$last_id = $last_id == 0 ? $current_last_id : $last_id;

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$params = array(
				'order_id' => $order_id,
				'last_id' => $last_id,
				'store_id' => $this->store_id,
				'nb' =>  ( int ) @$_POST[ 'how_many' ]
			);
			$orders = $this->adminorderdao->get_from_last_export( $params );
			if( count( $orders ) == 0 )
				die( E_NO_NEW_LINES_TO_EXPORT );

			$max_id = $this->_export_according_to_file_type( $orders, $current_last_id, $last_id, $order_id );
			if( $max_id == false )
				die( E_UNKNOWN_IMPORT_FORMAT );

			$this->adminorderdao->set_last_export_id( $max_id, $this->store_id );
		}
		else {
			$data = parent::_init_widgets();

			$data[ 'last_id' ] = $current_last_id;

			$this->load->view( $this->view_dir.'/admin/export-order', $data );
		}
	}

	/**
	 *
	 */
	function _get_export_filename( $order_id, $customer_id ) {
		return 'order-'.$order_id.'-'.$customer_id.'-'.date( 'dmY-His' ).'.xml';
	}

	/**
	 *
	 */
	function _export_according_to_file_type( $orders, $current_last_id, $last_id, $order_id = 0 ) {
		if( @$_POST[ 'export_filetype' ] == 'csv' )
			return $this->_export_csv( $orders );
		if( @$_POST[ 'export_filetype' ] == 'xml' )
			return $this->_export_xml( $orders, $current_last_id, $last_id, $order_id );

		return false;
	}

	/**
	 * 
	 */
	function _export_csv( $orders ) {
		return false;
	}

	/**
	 * If $order_id is specified, the user wants to export a defined order. So prevent from incrementing the current last order id
	 * to avoid the next export jumping.
	 * The user is able to specify from which order_id start the export. If he does that value is passed through @$last_id.
	 * @$current_last_id is the last order_id set by this program after an automated export
	 */
	function _export_xml( $orders, $current_last_id, $last_id, $order_id = 0 ) {
		$max_id = $current_last_id;

		foreach( $orders as $order ) {
			// Items
			$items = Modules::run( 'cart/ccart/items', false, $order[ 'id' ], $order[ 'customer_id' ], 'raw' );

			$output = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n<orders>\n";
			$output.= "\t<order>\n";
			$output.= "\t\t<id>{$order[ 'order_id' ]}</id>\n";
			$output.= "\t\t<customer_id>{$order[ 'customer_id' ]}</customer_id>\n";
			$output.= "\t\t<creation_date>{$order[ 'creation_date' ]}</creation_date>\n";
			$output.= "\t\t<shipping_recipient>".misc::esc_xml_data( @$order[ 'recipient' ] )."</shipping_recipient>\n";
			$output.= "\t\t<shipping_address>".misc::esc_xml_data( @$order[ 'address' ] )."</shipping_address>\n";
			$output.= "\t\t<shipping_town>".misc::esc_xml_data( @$order[ 'town' ] )."</shipping_town>\n";
			$output.= "\t\t<shipping_county>".misc::esc_xml_data( @$order[ 'county' ] )."</shipping_county>\n";
			$output.= "\t\t<shipping_postcode>".misc::esc_xml_data( @$order[ 'postcode' ] )."</shipping_postcode>\n";
			$output.= "\t\t<shipping_telephone>".misc::esc_xml_data( @$order[ 'telephone' ] )."</shipping_telephone>\n";

			$output.= "\t\t<payment_method>".misc::esc_xml_data( $order[ 'payment_method' ] )."</payment_method>\n";
			$output.= "\t\t<comments>".misc::esc_xml_data( $order[ 'comments' ] )."</comments>\n";

			$output.= "\t\t<items>\n";
			foreach( $items as $item ) {
				$output.= "\t\t\t<item>\n";
				$output.= "\t\t\t\t<sku>".$item[ 'sku' ]."</sku>\n";
				$output.= "\t\t\t\t<product>".misc::esc_xml_data( $item[ 'product' ] )."</product>\n";
				$output.= "\t\t\t\t<qty>".$item[ 'qty' ]."</qty>\n";
				$output.= "\t\t\t\t<pu>".$item[ 'pu' ]."</pu>\n";
				$output.= "\t\t\t\t<quote_no>".$item[ 'quote_no' ]."</quote_no>\n";
				$output.= "\t\t\t</item>\n";
			}
			$output.= "\t\t</items>\n";
			$output.= "\t\t<your_reference>".misc::esc_xml_data( $order[ 'your_reference' ] )."</your_reference>\n";
			$output.= "\t\t<vrm>".misc::esc_xml_data( $order[ 'vrm' ] )."</vrm>\n";
			$output.= "\t</order>\n";
			$output.= "</orders>";

			if( $order_id == 0 && $last_id == $current_last_id ) {
				if( $max_id < $order[ 'order_id' ] )
					$max_id = $order[ 'order_id' ];
			}
			else
				$max_id = $current_last_id - 1; // Because few lines below (~146) it is incremented. Then this is to prevent the order of export from being altered

			$filename = $this->_get_export_filename( $order[ 'order_id' ], $order[ 'customer_id' ] );
			parent::save_exported_file( COMPLETE_EXPORTXML_PATH.'/'.$filename, $output );
			parent::download_exported_if_needed( $filename, $output );
		}
		
		return $max_id + 1;
	}
}