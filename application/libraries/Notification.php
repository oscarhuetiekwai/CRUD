<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** Use this class for Email and SMS notification */

class Notification {
	private $_ci;

	function __construct()
	{
		$this->_ci = &get_instance();
	}

	public function email_notification_generic($arrData){
		$to      = $arrData['email_recipient'];
		$subject = $arrData['email_subject'];
		$message = $arrData['email_message'];
		$headers = 'From: support@gowaterless.com.my' . "\r\n";
		mail($to, $subject, $message, $headers);
	}

	public function email_notification_booking($arrData){

	/*	$email_recipient = 'charles.ling@uvsms.com';
		$email_subject = 'Booking Receipit from Go Waterless';
		$email_notificationArr = array('email_recipient'=>$email_recipient, 'email_subject'=>$email_subject,
		'booking_number'=>$booking_ref, 'wash_location'=>$booking_ref, 'booking_datetime'=>$date_booking, 'remarks'=>$customer_booking_remark);
		$this->notification->email_notification_booking($email_notificationArr);
	*/
		$to      = $arrData['email_recipient'];
		$subject = $arrData['email_subject'];
		$headers = '';
		$message = '<html><head><title>Booking receipt</title></head>
		<body><p>Dear ,</p>
		  <p>Booking receipt from Go Waterless Car Wash</p>
		  <p>Please make sure that all confirmed dates/location are what you originally booked; in the unlikely event
		  the dates are off, or you need to make a last mintues change, please contact our booking management at 012-3456789.</p>
		  <table border=0>
			<tr><td>Booking Number</td><td>'.$arrData['booking_number'].'</td></tr>
			<tr><td>Wash Location</td><td>'.$arrData['wash_location'].'</td></tr>
			<tr><td>Date Time/td><td>'.$arrData['booking_datetime'].'</td></tr>
			<tr><td>Remarks / Request/td><td>'.$arrData['remarks'].'</td></tr>
		  </table>
		  <p>Thank you for your business.</p>
		</body></html>';
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers = 'From: support@gowaterless.com.my' . "\r\n";
		mail($to, $subject, $message, $headers);
	}

	public function sms_notification($arrData){

	}
}