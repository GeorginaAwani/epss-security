<?php
if (!isset($_POST['u'], $_POST['p'])) exit('false');

require_once 'functions.php';

$DBC = new DBConnections;
$username = $DBC->sanitise(trim(filter_var($_POST['u'])));
$password = $DBC->sanitise(filter_var($_POST['p']));

if ($username === '' || $password === '') exit('false');

$Administrator = new Administrator;
if ($Administrator->logIn($username, $password)) exit('true');
else exit('false');
