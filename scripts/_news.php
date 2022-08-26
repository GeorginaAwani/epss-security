<?php
require_once '__news.php';

// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$News = new News;

if($action === 'list'){
	// load is optional
	if(isset($_POST['l'])){
		$lid = (int) $_POST['l'];
	} else $lid = 0;

	$News->getNewsList($lid);
	die();
}
elseif($action === 'single'){
	// news id is required
	if(!isset($_POST['i'])) exit('false');
	
	$nid = (int) $_POST['i'];
	$News->getSingleNews($nid);
	die();
}

$Administrator = new Administrator;

if (!$Administrator->isLoggedIn()) exit('false');

switch ($action) {
		// create a new article
	case 'new':
		// article title, body, attached media and media description are required
		if (!isset($_POST['t'], $_POST['b'])) exit('title or body not given');

		$title = $Administrator->sanitise(trim(filter_var($_POST['t'])));
		$body = $Administrator->sanitise(trim(filter_var($_POST['b'])));

		if ($title === '' || $body === '') exit('title or body is false');

		$eventDate = isset($_POST['edt']) ? trim(filter_var($_POST['edt'])) : '';

		if (isset($_FILES['f'], $_POST['d'])) {
			$files = $_FILES['f'];
			$desc = $_POST['d'];
		} else {
			$files =  $desc = [];
		}

		if ($News->new($title, $body, $eventDate, $files, $desc)) exit('true');
		else exit('false');

		// get all news and events
	case 'get':
		$News->getTableData();
		die();

		// edit article
	case 'edit':
		if (!isset($_POST['t'], $_POST['b'], $_POST['i'])) exit('title or body not given');

		$title = $Administrator->sanitise(trim(filter_var($_POST['t'])));
		$body = $Administrator->sanitise(trim(filter_var($_POST['b'])));
		$id = (int) $_POST['i'];

		if ($title === '' || $body === '') exit('title or body is false');

		$edt = isset($_POST['edt']) ? trim(filter_var($_POST['edt'])) : '';

		if ($News->edit($id, $title, $body, $edt)) exit('true');
		else exit('false');

		// delete news article
	case 'delete':
		// article id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if ($News->delete($id)) exit('true');
		else exit('false');

		break;
}
