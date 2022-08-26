<?php
require_once '__clients.php';

$Administrator = new Administrator;

if (!$Administrator->isLoggedIn()) exit('false');
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Clients = new Clients;

switch ($action) {
		// create a new client
	case 'new':
		// only clients media and name are required
		if (!isset($_FILES['f'], $_POST['n'])) exit('false');

		$file = $_FILES['f'];
		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));

		if($Clients->new($name, $file)) exit('true');
		else exit('false');

	// get all clients media
	case 'get':
		$Clients->getTableData();

		die();

		// edit client
	case 'edit':
		// client is and name are required
		if (!isset($_POST['i'], $_POST['n'])) exit('name not given');

		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$id = (int) $_POST['i'];

		if ($name === '') exit('name is false');

		$file = isset($_FILES['f']) ? $_FILES['f'] : [];

		if($Clients->edit($id, $name, $file)) exit('true');
		else exit('false');

		// delete news article
	case 'delete':
		// AWARD id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if($Clients->delete($id)) exit('true');
		else exit('false');

		break;
}
