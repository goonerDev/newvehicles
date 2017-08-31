<?php
require_once 'home.php';
class Bug extends Home {
	function __construct() {
		parent::__construct();

		$this->load->model( 'bugdao' );
	}
	/**
	 *
	 */
	function index() {
		header( 'Location: '.BASE_URL.'bug-report' );
		exit;
	}

	/**
	 *
	 */
	function report() {
		$err = false;
		$issue = false;
		$issue_page = false;
		$improvement = false;

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			$issue = @$_POST[ 'issue' ];
			$issue_page = @$_POST[ 'issue_page' ];
			$improvement = @$_POST[ 'improvement' ];
			$word = @$_POST[ 'word' ];

			if( empty( $issue ) || empty( $issue_page ) || empty( $word ) )
				$err = E_EMPTY_FIELD;
			elseif( $this->bugdao->check_captcha( array( 'ip' => $_SERVER[ 'REMOTE_ADDR' ], 'word' => misc::sanitize( $word ) ) ) === false )
				$err = E_INCORRECT_CAPTCHA;
			else {
				misc::mail( array(
					'recipient' => 'bary.raniry@yahoo.fr',
					'from' => 'no-reply@newvehicleparts.co.uk',
					'subject' => 'Bug report',
					'msg' => '<p><strong>Issue:</strong></p><p>'.$issue.'</p><p><strong>Issue page:</strong></p><p>'.$issue_page.'</p><p><strong>Improvement:</strong></p><p>'.nl2br( $improvement ).'</p>'
				) );
				header( 'Location:'.BASE_URL.'thank-you/contact' );
				exit;
			}
		}

		$data = parent::_init_widgets();

		$data[ 'error' ] = $err;
		$data[ 'issue' ] = $issue;
		$data[ 'issue_page' ] = $issue_page;
		$data[ 'improvement' ] = $improvement;

		$this->load->helper( 'captcha' );
		$data[ 'captcha' ] = create_captcha( array(
			'img_path' => 'captcha/',
			// 'img_url' => 'http://www.newvehicleparts.co.uk/captcha/',
			'img_url' => 'http://nvp.co.uk/captcha/',
			'font_path' => 'captcha/font.ttf',
			'img_width' => 270,
			'img_height' => 60
		) );
		$data[ 'captcha' ][ 'ip' ] = $_SERVER[ 'REMOTE_ADDR' ];

		$this->bugdao->save_captcha( $data[ 'captcha' ] );

		$this->load->view( $this->view_dir.'/bug-report', $data );
	}
}