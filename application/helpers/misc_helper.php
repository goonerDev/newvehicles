<?php
class Misc {
	static function sanitize( $var, $default = '' ) {
		if( empty( $var ) )
			return $default;

		if( is_array( $var ) ) {
			foreach( $var as $k => $v )
				$var[ $k ] = self::sanitize( $v, $default );
			return $var;
		}
		// return str_replace( "'", "''", htmlentities( $var ) );
		return str_replace( "'", "''", $var );
	}
	
	static function esc_attr( $var ) {
		if( is_array( $var ) ) {
			foreach( $var as $k => $v )
				$var[ $k ] = self::esc_attr( $v );
			return $var;
		}
		return str_replace( '"', '&quot;', $var );
	}

	static function esc_json( $var ) {
		if( is_array( $var ) ) {
			foreach( $var as $k => $v )
				$var[ $k ] = self::esc_json( $v );
			return $var;
		}
		return str_replace( array( '"', "\n", "\t", "\r" ), array( '\"', '', '', '' ), $var );
	}

	static function urlencode( $var ) {
		return str_replace( array( ' ', '/', '>', '<', '+', ')', '(', '&' ), '', $var );
	}

	static function urldecode( $var ) {
		if( is_array( $var ) ) {
			foreach( $var as $k => $v )
				$var[ $k ] = self::urldecode( $v );

			return $var;
		}
		return urldecode( $var );
	}

	static function valid_email( $email ) {
		return preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email );
	}

	static function valid_telephone( $phone ) {
		return preg_match( '/^[\d\s]+$/', $phone );
	}

	static function mysqldate2fr( $date ) {
		return empty( $date ) ? '' : substr( $date, 6, 2 ).'/'.substr( $date, 4, 2 ).'/'.substr( $date, 0, 4 );
	}

	static function esc_xml_data( $var ) {
		return str_replace( array( '&', '<', '>' ), array( '&amp;', '&lt;', '&gt;' ), $var );
	}

	static function pagination( $p, $u, $start, $limit, $count ) {
		$up = explode( '/', $u );
		$cfg = array(
			'base_url' => $u,
			'uri_segment' => count( $up ),
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_tag_close' => '</li>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a>',
			'cur_tag_close' => '</a></li>',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'cur_page' => $start,
			'per_page' => $limit,
			'total_rows' => $count
		);

		$p->initialize( $cfg );

		return $p->create_links();
	}

	/**
	 * The format in input can be dd-mm-YYYY or YYYYmmdd
	 * return dd-mm-YYYY
	 */
	static function checkdate( $v, $default = '' ) {
		if( preg_match( '/([\d]{2})-([\d]{2})-([\d]{4})/', $v, $matches ) )
			return checkdate( $matches[ 2 ], $matches[ 1 ], $matches[ 3 ] ) ? $v : $default;

		if( preg_match( '/[\d]{8}/', $v ) ) {
			$date = substr( $v, 6, 2 );
			$month = substr( $v, 4, 2 );
			$year = substr( $v, 0, 4 );
			return checkdate( $month, $date, $year ) ? $date.'-'.$month.'-'.$year : $default;
		}

		return $default;
	}

	/**
	 * Output a mysql date from string on fr date
	 * @v string dd-mm-YYYY
	 * @time if provided will be appended to the date
	 * return YYYY-mm-dd
	 */
	static function fr_2_mysqldate( $v, $time = '' ) {
		return empty( $v ) ? '' : substr( $v, 6 ).'-'.substr( $v, 3, 2 ).'-'.substr( $v, 0, 2 ).' '.$time;
	}

	/**
	 *
	 */
	static function get_available_prices( $oprice, $user_no, $vat, $shipping = 0 ) {
		if( is_array( $oprice ) && array_key_exists( 'price', $oprice ) ) {
			$oprice[ 'price' ] = $oprice[ 'price' ];
			return $oprice;
		}

		$price = 0;
		$charge_out = 0;

		// line[ 'price' ] is in the format customer_group1; price1 | customer_group2; price2 | ....
		if( !empty( $oprice ) ) {
			$available_price_parts = explode( '|', $oprice );

			foreach( $available_price_parts as $p ) {
				$price_parts = explode( ';', $p );

				if( $price_parts[ 0 ] == $user_no ) // Customer price override
					$price = $price_parts[ 1 ];
				elseif( $price_parts[ 0 ] == 'CHOUT' ) // Bodyshop customer
					$charge_out = $price_parts[ 1 ];
				elseif( $price == 0 )
					$price = $price_parts[ 1 ];
			}
		}

		// These items are no longer in sell
		if( $price == 0 || $price == 0.01 || $price == 0.02 )
			$price = E_CALL_FOR_PRICE;
		else
			$price = number_format( $price * ( 1 + $vat/100 ), 2 );

		return array( 'price' => $price, 'charge_out' => number_format( $charge_out, 2 ) );
	}

	/**
	 *
	 */
	static function display_price( $oprice, $user_no, $vat, $shipping = 0 ) {

		if( !empty( $oprice ) ) {
			$prices = self::get_available_prices( $oprice, $user_no, $vat, $shipping );

			echo '<strong>'.CURRENCY.$prices[ 'price' ].'bnmbbmnmnb</strong>';

			echo '<br/><br/>';

			if( $prices[ 'charge_out' ] > 0 )
				echo 'Suggested selling price:<br/><strong>'.CURRENCY.$prices[ 'charge_out' ].'</strong>';

			return $prices[ 'price' ];
		}

		echo '<strong>'.CURRENCY.'ljljjlk</strong>';

		return $oprice;
	}

	/**
	 *
	 */
	function mail( $params ) {
		extract( $params );

		require_once APPPATH.'libraries/PHPMailer/SMTP.php';
		require_once APPPATH.'libraries/PHPMailer/PHPMailer.php';

		$mail = new PHPMailer( false );
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "mail.newvehicleparts.co.uk"; // SMTP server
		//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only.
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "mail.daily.co.uk";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "no-reply@newvehicleparts.co.uk";  // GMAIL username
		$mail->Password   = "N0-R3ply";    
		
		/*$mail = new PHPMailer( false );
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = 'mail.prasco.co.uk';
		$mail->Username = 'mab';
		$mail->Password = 'supp0rt';*/
		
		$recipients = explode( ',', $recipient );
		foreach( $recipients as $recipient )
			if( !empty( $recipient ) )
				$mail->addAddress( $recipient );

		$mail->addAddress( $recipient );

		if( !empty( $bcc ) ) {
			$bccs = explode( ',', $bcc );
			if( is_array( $bccs ) )
				foreach( $bccs as $bcc )
					if( !empty( $bcc ) )
						$mail->addBCC( $bcc );
		}

		$mail->setFrom( $from, empty( $from_name ) ? 'no-reply' : $from_name );
		$mail->Subject = $subject;
		$mail->msgHTML( $msg );

		if (!$mail->send())
			die( $error_label . $mail->ErrorInfo );
	}
}