<?php
require_once 'functions.php';

$Administrator = new Administrator;
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

switch ($action) {
		// get all contacts
	case 'get':
		if (!$Administrator->isLoggedIn()) exit('false');
		
		$conn = $Administrator->conn();

		$query = $conn->query("SELECT * FROM `contacts`");

		$mediaClass = 'table-img rounded border mx-1';

		while ($record = $query->fetch_assoc()) {
			$cid = $record['contact_id'];
			$name = $record['name'];
			$email = $record['email'];
			$subject = $record['subject'];
			$message = $record['message'];
			$phone = $record['phone'];

			$time = new DateTime($record['time']);

			echo "
				<tr data-contact-id='$cid'>
					<td>$name</td>
					<td>
						<div class='mb-1'><a href='mailto:$email' target='_blank'>$email</a></div>
						<div class='small text-muted'>$phone</div>
					</td>
					<td>$subject</td>
					<td>
						<div class='desc-wp'>$message</div>
					</td>
					<td>{$time->format(DATETIME_FORMAT)}</td>
				</tr>
			";
		}

		die();

	case 'set':
		// email address, message, phone, name and subject are required
		if (!isset($_POST['e'], $_POST['m'], $_POST['p'], $_POST['n'], $_POST['s'])) exit('false');

		$email = $Administrator->sanitise(trim(filter_var($_POST['e'], FILTER_SANITIZE_EMAIL)));
		$message = $Administrator->sanitise(trim(filter_var($_POST['m'])));
		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$subject = $Administrator->sanitise(trim(filter_var($_POST['s'])));
		$phone = $Administrator->sanitise(trim(filter_var($_POST['p'])));

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) exit('false');
		if ($message === '' || $name === '' || $subject === '' || $phone === '') exit('false');

		$query = $Administrator->query("INSERT INTO `contacts`(`name`, `phone`, `email`, `subject`, `message`) VALUES ('$name', '$phone', '$email', '$subject', '$message')");

		if ($query) exit('true');
		else exit('false');
}
