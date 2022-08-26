<?php
$mailerRoot = "{$_SERVER['DOCUMENT_ROOT']}/dependencies/PHPMailer/";
require_once "{$mailerRoot}Exception.php";
require_once "{$mailerRoot}PHPMailer.php";
require_once "{$mailerRoot}SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
	private $username = 'georginaawani@gmail.com';
	private $password = 'georginaawani_123';

	function __construct()
	{
	}

	public function send($toAddress, $toName, $subject, $body)
	{
		$Mail = new PHPMailer(true);
		$Mail->isSMTP();
		$Mail->Host = 'smtp.gmail.com';
		$Mail->SMTPAuth = true;
		$Mail->Username = $this->username;
		//$Mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$Mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$Mail->Password = $this->password;
		$Mail->Port = 465;

		$Mail->setFrom($this->username, 'EPSS Private Security');
		$Mail->addAddress($toAddress, $toName);

		$Mail->isHTML(true);
		$Mail->Subject = $subject;
		$Mail->Body = $body;
		$Mail->AltBody = strip_tags($body);

		$sent = $Mail->send();
		if($sent) return true;
		else throw new Error('Mail Error: ' . $Mail->ErrorInfo);
	}
}
