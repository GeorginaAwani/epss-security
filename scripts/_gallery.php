<?php
require_once '__gallery.php';

// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Gallery = new Gallery;

if($action === 'card'){
	$lid = isset($_POST['l']) ?(int) $_POST['l'] : 0;
	$Gallery->getCards($lid);
	die();
}

$Administrator = new Administrator;

if (!$Administrator->isLoggedIn()) exit('false');

switch ($action) {
		// create a new gallery media
	case 'new':
		// only gallery media and description are required
		if (!isset($_FILES['f'], $_POST['d'])) exit('false');

		$file = $_FILES['f'];
		$desc = $Administrator->sanitise(trim(filter_var($_POST['d'])));

		if($Gallery->new($file, $desc)) exit('true');
		else exit('false');

	// get all gallery media
	case 'get':
		$Gallery->getTableData();
		die();

		// edit gallery media
	case 'edit':
		// gallery media is and description are required
		if (!isset($_POST['i'], $_POST['d'])) exit('desc not given');

		$desc = $Administrator->sanitise(trim(filter_var($_POST['d'])));
		$id = (int) $_POST['i'];

		if ($desc === '') exit('desc is false');

		$file = isset($_FILES['f']) ? $_FILES['f'] : [];

		if($Gallery->edit($id, $desc, $file)) exit('true');
		else exit('false');

		// delete news article
	case 'delete':
		// GALLERY id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if($Gallery->delete($id)) exit('true');
		else exit('false');

		break;
}
