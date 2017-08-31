<?php
require_once 'home.php';
class Thankyou extends Home {

	/**
	 *
	 */
	function after_contact() {
		$data = parent::_init_widgets();
		$data[ 'msg' ] = 'Thanks for contacting us, we will get back to you with an answer in the next 24 - 48 hours if it is urgent you might want to call us: 01405 810 876 during office hours mon - fri 9am - 5pm';

		$this->load->view( $this->view_dir.'/thank-you', $data );
	}

	/**
	 *
	 */
	function after_signup() {
		$data = parent::_init_widgets();
		$data[ 'msg' ] = 'Thanks for your registration';

		$this->load->view( $this->view_dir.'/thank-you', $data );
	}

	/**
	 * 
	 */
	function after_checkout() {
		$data = parent::_init_widgets();
		$data[ 'msg' ] = '<p>Thanks you for placing your order with us, your order is now been processed, you will receive an confirmation email with all the order details, including delivery address</p><p><b>Your order will be sent to the delivery address you gave when you confirmed your order.</b></p><p><b> All orders placed before 5pm Mon - Thurs, we will aim to deliver on the next working day, Fri - Sunday we aim to deliver Mon or Tuesday.</b></p><h4>Tracking Services</h4><p>We have an tracking service in place, you will receive a second email with information about tracking your order and date of delivery once the item has been marked as dispatched from our warehouses. </p><h4>Delivery Times</h4><p>All orders placed before 5pm Monday - Thursday, we will aim to deliver on the next working day.<br  />All orders placed AFTER 5pm Monday- Wednesday we aim to deliver within 2 working days.<br />Orders placed Friday to Sunday we aim to deliver Monday or Tuesdays, this is dependent of volume of orders place over the weekend and processing times.</p><p>Delivery times are calculated in working days Monday to Friday. If you order after 5 pm the next working day will be considered the first working day for delivery. In case of bank holidays and over the Christmas period, please allow an extra two working days.</p><p>We aim to deliver within 1 working days but sometimes due to high order volume certain in sales periods please allow at least 3 days before contacting us. </p><h4>Reveiw Our Service</h4><p>As part of the order completion process, you will receive an email from reviews.co.uk who are an independent reviewing service, to allow you to provide feedback of the services you have received while ordering off newvehicleparts.co.uk, please take the time to fill it in as honestly with suggestion for improvement or any issues if you have had with our site, this will help us to improve our services.<br><br>Thanks<br><br>New Vehicle Parts Team!</p>';
	$this->load->view( $this->view_dir.'/thank-you', $data );
	}
}