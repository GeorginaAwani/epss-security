<?php
require_once '__team.php';

$Administrator = new Administrator;

if(!$Administrator->isLoggedIn()) exit('false');
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Team = new Team;

switch ($action) {
		// get all team members
	case 'get':
		$Team->getTableData();

		die();

	// create a new member
	case 'new':
		if (!isset($_POST['n'], $_POST['p'], $_POST['d'], $_FILES['f'])) exit('false');

		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$pos = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$description = $Administrator->sanitise(trim(filter_var($_POST['d'])));

		$file = $_FILES['f'];

		if ($name === '' || $pos === '' || $description === '') exit('false');

		if($Team->new($name, $pos, $description, $file)) exit('true');
		else exit('false');
	
	// edit team member
	case 'edit':
		if (!isset($_POST['i'], $_POST['n'], $_POST['p'], $_POST['d'])) exit('false');

		$id = (int) $_POST['i'];
		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$pos = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$description = $Administrator->sanitise(trim(filter_var($_POST['d'])));

		if ($name === '' || $pos === '' || $description === '') exit('false');

		$file = isset($_FILES['f']) ? $_FILES['f'] : [];
		if($Team->edit($id, $name, $pos, $description, $file)) exit('true');
		else exit('false');
	// delete a team member
	case 'delete':
		if(!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if($Team->delete($id)) exit('true');
		else exit('false');
	break;
}
