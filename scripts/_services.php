<?php
require_once '__services.php';

$Services = new Services;

// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

if($action === 'list'){
	$Services->getFullList();
	die();
}

$Administrator = new Administrator;

if(!$Administrator->isLoggedIn()) exit('false');

switch ($action) {
		// get all services
	case 'get':
		$Services->getTableData();
		die();

		// create a new service
	case 'new':
		// service name, description, image and excerpt are required
		if (!isset($_POST['n'], $_POST['d'], $_FILES['f'], $_POST['e'])) exit('false');

		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$description = $Administrator->sanitise(trim(filter_var($_POST['d'])));
		$excerpt = $Administrator->sanitise($_POST['e']);

		$file = $_FILES['f'];

		if ($name === '' || $description === '' || $excerpt === '') exit('false');

		if($Services->new($name, $description, $excerpt, $file)) exit('true');
		else exit('false');

		// edit service
	case 'edit':;
		// service id, service name, description, image and excerpt are required
		if (!isset($_POST['i'], $_POST['n'], $_POST['d'], $_POST['e'])) exit('false');

		$id = (int) $_POST['i'];
		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$description = $Administrator->sanitise(trim(filter_var($_POST['d'])));
		$excerpt = $Administrator->sanitise($_POST['e']);

		$file = isset($_FILES['f']) ? $_FILES['f'] : [];

		if ($name === '' || $description === '' || $excerpt === '') exit('false');

		if($Services->edit($id, $name, $description, $excerpt, $file)) exit('true');
		else exit('false');

		// delete a service
	case 'delete':
		// service id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if($Services->delete($id)) exit('true');
		else exit('false');
}
