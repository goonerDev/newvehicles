<?php
require_once 'home.php';
class Adminmisc extends Home {

	function __construct() {
		parent::__construct();

		$this->user = parent::_valid_connection();

		if( $this->user[ 'admin' ] != 1 ) {
			header( 'location:'.base_url() );
			exit;
		}

		$cart_id = parent::_valid_cart();
		$this->data = $this->_init_widgets( $this->user, $cart_id );
		$this->data[ 'admin_active' ] = true;

		$this->load->model( 'adminmiscdao' );
	}

	/**
	 * 
	 */
	function vrm_access_limit() {
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			$this->adminmiscdao->set_vrm_access_limit( ( int ) @$_POST[ 'vrm_access_limit' ] );

		$this->data[ 'value' ] = $this->adminmiscdao->get_vrm_access_limit();

		$this->load->view( 'new/admin/vrm-access-limit', $this->data );
	}

	/**
	 * 
	 */
	function vrm_report() {
		$params = $this->_vrm_report_params( $original_params );

		$this->data[ 'lines_report' ] = $this->adminmiscdao->get_vrm_usage_report( $params );

		$this->data[ 'date_from' ] = $original_params[ 'date_from' ];
		$this->data[ 'date_to' ] = $original_params[ 'date_to' ];
		$this->data[ 'like_email' ] = $original_params[ 'like_email' ];
		$this->data[ 'order_by' ] = $original_params[ 'order_by' ];
		$this->data[ 'order_dir' ] = $original_params[ 'order_dir' ];

		$this->load->library( 'pagination' );
		$this->pagination->uri_segment = 7;
		$this->data[ 'pagination' ] = misc::pagination(
			$this->pagination,
			base_url().'vrm-report/'.$original_params[ 'date_from' ].'/'.$original_params[ 'date_to' ].'/'.$original_params[ 'like_email' ].'/'.$original_params[ 'order_by' ].'/'.$original_params[ 'order_dir' ].'/',
			$original_params[ 'start' ],
			$original_params[ 'limit' ],
			$this->adminmiscdao->get_vrm_usage_report_count( $params )
		);

		$this->data[ 'field1_order_dir' ] = $original_params[ 'order_by' ] == 'field1' ? ( $original_params[ 'order_dir' ] == 'asc' ? 'desc' : 'asc' ) : 'asc';
		$this->data[ 'field2_order_dir' ] = $original_params[ 'order_by' ] == 'field2' ? ( $original_params[ 'order_dir' ] == 'asc' ? 'desc' : 'asc' ) : 'asc';
		$this->data[ 'field3_order_dir' ] = $original_params[ 'order_by' ] == 'field3' ? ( $original_params[ 'order_dir' ] == 'asc' ? 'desc' : 'asc' ) : 'asc';
		$this->data[ 'field4_order_dir' ] = $original_params[ 'order_by' ] == 'field4' ? ( $original_params[ 'order_dir' ] == 'asc' ? 'desc' : 'asc' ) : 'asc';
		$this->data[ 'field5_order_dir' ] = $original_params[ 'order_by' ] == 'field5' ? ( $original_params[ 'order_dir' ] == 'asc' ? 'desc' : 'asc' ) : 'asc';
		
		$this->load->view( 'new/admin/vrm-report', $this->data );
	}

	/**
	 *
	 */
	function vrm_report_to_excel() {
		$params = $this->_vrm_report_params( $original_params );

		$params[ 'limit' ] = 999999999;
		$lines_report = $this->adminmiscdao->get_vrm_usage_report( $params );

		$this->load->library( 'PHPExcel/Classes/PHPExcel' );

		$sheet = $this->phpexcel->getActiveSheet();

		$sheet->setCellValue( 'A1', 'Date' );
		$sheet->setCellValue( 'B1', 'Hour' );
		$sheet->setCellValue( 'C1', 'Customer ID' );
		$sheet->setCellValue( 'D1', 'Customer' );
		$sheet->setCellValue( 'E1', 'Email' );
		$sheet->setCellValue( 'F1', 'VRM' );

		$row = 2;
		foreach( $lines_report as $line ) {
			$sheet->setCellValue( 'A'.$row, substr( $line[ 'usage_date' ], 0, 10 ) );
			$sheet->setCellValue( 'B'.$row, substr( $line[ 'usage_date' ], 11 ) );
			$sheet->setCellValue( 'C'.$row, $line[ 'customer_id' ] );
			$sheet->setCellValue( 'D'.$row, $line[ 'fname' ].' '.$line[ 'lname' ] );
			$sheet->setCellValue( 'E'.$row, $line[ 'email_address' ] );
			$sheet->setCellValue( 'F'.$row, $line[ 'vrm' ] );
			$row++;
		}

		$filename = APPPATH.'temp/'.base64_encode( time() );

		$this->load->library( 'PHPExcel/Classes/PHPExcel/Writer/Excel2007' );
		$this->excel2007->setPHPExcel( $this->phpexcel );
		$this->excel2007->save( $filename );

		header( 'Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ); header( 'Content-disposition: attachment; filename="VRM usage report '.date( 'd-m-Y H-i-s' ).'.xlsx"' );
		readfile( $filename );
	}

	/**
	 *
	 */
	function page_access_limit() {
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			$this->adminmiscdao->set_page_access_limit( ( int ) @$_POST[ 'page_access_limit' ] );

		$this->data[ 'value' ] = $this->adminmiscdao->get_page_access_limit();

		$this->load->view( 'new/admin/page-access-limit', $this->data );
	}

	/**
	 *
	 */
	function _vrm_report_params( &$original_params ) {
		// The expected array is like the following
		// vrm-report/date-from/date-to/customer-like-email/order-by/order-direction/offset
		$segs = $this->uri->segment_array();

		$original_params[ 'date_from' ] = misc::checkdate( @$segs[ 2 ], date( 'd-m-Y' ) );
		$original_params[ 'date_to' ] = misc::checkdate( @$segs[ 3 ], date( 'd-m-Y' ) );
		$original_params[ 'like_email' ] = misc::sanitize( @urldecode( $segs[ 4 ] ), '-' );
		$original_params[ 'order_by' ] = misc::sanitize( @$segs[ 5 ], 'field1' );
		$original_params[ 'order_dir' ] = @$segs[ 6 ] == 'asc' || @$segs[ 6 ] == 'desc' ? @$segs[ 6 ] : 'asc';
		$original_params[ 'start' ] = ( int ) @$segs[ 7 ];
		$original_params[ 'limit' ] = 30;

		return array(
			'date_from' => misc::fr_2_mysqldate( $original_params[ 'date_from' ], '00:00:00' ),
			'date_to' => misc::fr_2_mysqldate( $original_params[ 'date_to' ], '23:59:59' ),
			'like_email' => $original_params[ 'like_email' ] == '-' ? '' : $original_params[ 'like_email' ],
			'order_by' => $original_params[ 'order_by' ] == '-' ? '' : $original_params[ 'order_by' ],
			'order_dir' => $original_params[ 'order_dir' ] == '-' ? '' : $original_params[ 'order_dir' ],
			'offset' => $original_params[ 'start' ],
			'limit' => $original_params[ 'limit' ]
		);
	}
}
