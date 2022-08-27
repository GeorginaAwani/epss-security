<?php
require_once '__testimonials.php';

$Administrator = new Administrator;

if(!$Administrator->isLoggedIn()) exit('false');
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$Testimonials = new Testimonials;

switch ($action) {
		// get all testimonials
	case 'get':
		exit($Testimonials->getTableData());

		// create a new testimonial
	case 'new':
		// name, position, company, quote are required
		if (!isset($_POST['n'], $_POST['p'], $_POST['c'], $_POST['q'])) exit('false');

		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$position = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$company = $Administrator->sanitise(trim(filter_var($_POST['c'])));
		$quote = $Administrator->sanitise(trim(filter_var($_POST['q'])));

		if ($name === '' || $position === '' || $company === '' || $quote === '') exit('false');

		if($Testimonials->new($name, $position, $company, $quote)) exit('true');
		else exit('false');

		// edit testimonial
	case 'edit':
		// id, name and description are required
		if (!isset($_POST['i'], $_POST['n'], $_POST['p'], $_POST['c'], $_POST['q'])) exit('false');

		$id = (int) $_POST['i'];
		$name = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$position = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$company = $Administrator->sanitise(trim(filter_var($_POST['c'])));
		$quote = $Administrator->sanitise(trim(filter_var($_POST['q'])));


		if ($name === '' || $position === '' || $company === '' || $quote === '') exit('false');

		if($Testimonials->edit($id, $name, $position, $company, $quote)) exit('true');
		else exit('false');

		// delete a testimonial
	case 'delete':
		// service id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		// delete from database
		if($Testimonials->delete($id)) exit('true');
		else exit('false');
}
