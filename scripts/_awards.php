<?php
require_once '__awards.php';

// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Awards = new Awards;

if($action === 'card'){
	$lid = isset($_POST['l']) ?(int) $_POST['l'] : 0;
	$Awards->getCards($lid);
	die();
}

$Administrator = new Administrator;

if (!$Administrator->isLoggedIn()) exit('false');

switch ($action) {
		// create a new award
	case 'new':
		// only awards media and description are required
		if (!isset($_FILES['f'], $_POST['d'])) exit('false');

		$file = $_FILES['f'];
		$desc = $Administrator->sanitise(trim(filter_var($_POST['d'])));

		if($Awards->new($file, $desc)) exit('true');
		else exit('false');

	// get all awards in table
	case 'get':
		$Awards->getTableData();
		die();

		// edit award
	case 'edit':
		// award is and description are required
		if (!isset($_POST['i'], $_POST['d'])) exit('desc not given');

		$desc = $Administrator->sanitise(trim(filter_var($_POST['d'])));
		$id = (int) $_POST['i'];

		if ($desc === '') exit('desc is false');

		$file = isset($_FILES['f']) ? $_FILES['f'] : [];

		if($Awards->edit($id, $desc, $file)) exit('true');
		else exit('false');

		// delete news article
	case 'delete':
		// AWARD id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if($Awards->delete($id)) exit('true');
		else exit('false');
}
