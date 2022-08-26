<?php
require_once '__statistics.php';

$Administrator = new Administrator;

if(!$Administrator->isLoggedIn()) exit('false');
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Statistics = new Statistics;

switch ($action) {
		// get statistics
	case 'get':
		exit(json_encode($Statistics->get()));

	case 'set':
		if (!isset($_POST['on'], $_POST['cf'], $_POST['cfc'], $_POST['gc'], $_POST['gcc'])) exit('failed');

		$offices = (int) $_POST['on'];
		$clients = (int) $_POST['cf'];
		$estimateClients = (int) $_POST['cfc'];
		$guards = (int) $_POST['gc'];
		$estimateGuards = (int) $_POST['gcc'];

		if($Statistics->set($offices, $clients, $estimateClients, $guards, $estimateGuards)) exit('true');
		else exit('false');
}
