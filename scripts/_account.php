<?php
require_once 'functions.php';

$Administrator = new Administrator;

if (!$Administrator->isLoggedIn()) exit('false');
// action must be given
if (!isset($_POST['a'])) exit('false');

$action = $_POST['a'];

$userId = $_SESSION[Administrator::SESSION_USER_ID];
$ROOT = ROOT;

define('ADMIN_FOLDER', "{$ROOT}/files/admin/");
$ADMIN_FOLDER = ADMIN_FOLDER;

switch ($action) {
		// get current account details
	case 'a':
		@session_start();

		$details = [
			'id' => $userId,
			'user' => $_SESSION[Administrator::SESSION_USERNAME],
			'name' => $_SESSION[Administrator::SESSION_FULLNAME],
			'profile' => "/epss/files/admin/{$_SESSION[Administrator::SESSION_PROFILE_IMG]}",
			'role' => $_SESSION[Administrator::SESSION_ROLE],
			'date' => $_SESSION[Administrator::SESSSION_DATE]
		];

		exit(json_encode($details));

		// get other users
	case 'u':
		$query = $Administrator->query("SELECT `user_id`, `username`, `fullname`, `role`, `date_created`, `profile_img` FROM `administrators` WHERE `privilege` != 1");

		while ($record = $query->fetch_assoc()) {
			$dateCreated = new DateTime($record['date_created']);
			echo "
				<tr data-user-id='{$record['user_id']}'>
					<td>
						<div class='d-flex align-items-center'>
							<div>
								{$Administrator->profilePhoto($record['profile_img'])}
							</div>
							<div class='ml-2 ws-nowrap'>
								<div class='font-weight-bold h6 m-0 account-name text-black'>{$record['fullname']}</div>
								<div class='font-sm account-user text-black-50'>{$record['username']}</div>
							</div>
						</div>
					</td>
					<td><div class='account-role'>{$record['role']}</div></td>
					<td><div class='account-date'>{$dateCreated->format(DATETIME_FORMAT)}</div></td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-user-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-user-action='delete'>Delete</button>
							</div>
						</div>
					</td>
				</tr>
			";
		}

		die();

		// log out current user
	case 'l':
		$Administrator->logOut();
		exit('true');

	case 'new':
		// fullname, username, password, role and image file are required
		if (!isset($_POST['n'], $_POST['u'], $_POST['p'], $_POST['r'], $_FILES['f'])) exit('false');

		$fullname = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$username = $Administrator->sanitise(trim(filter_var($_POST['u'])));
		$password = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$role = $Administrator->sanitise(trim(filter_var($_POST['r'])));

		$file = $_FILES['f'];

		if ($fullname === '' || $username === '' || $password === '' || $role === '') exit('false');

		$File = new FileWorker;

		$conn = $Administrator->conn();

		// check if username already exists
		if ($Administrator->usernameExists($username, $conn)) exit('user');

		try {
			// save profile photo to folder first
			$filename = $File->saveSingleFile($file, $ADMIN_FOLDER);
			if (!$filename) throw new Exception('Invalid file type or size');
		} catch (\Throwable $th) {
			echo $th->getMessage();
			exit('false');
		}

		$password = password_hash($password, PASSWORD_BCRYPT);

		$query = $conn->query("INSERT INTO `administrators`(`username`, `fullname`, `role`, `password`, `created_by`, `profile_img`) VALUES ('$username', '$fullname', '$role', '$password', $userId, '$filename')");

		if ($query) exit('true');
		else exit('false');

		// edit service
	case 'edit':
		// id, fullname, username, password role and image file are required
		if (!isset($_POST['i'], $_POST['n'], $_POST['u'], $_POST['p'], $_POST['r'])) exit('false');

		$fullname = $Administrator->sanitise(trim(filter_var($_POST['n'])));
		$username = $Administrator->sanitise(trim(filter_var($_POST['u'])));
		$password = $Administrator->sanitise(trim(filter_var($_POST['p'])));
		$role = $Administrator->sanitise(trim(filter_var($_POST['r'])));

		$id = (int) $_POST['i'];

		if ($fullname === '' || $username === '' || $role === '') exit('false');

		// prevent super admin from being edited
		if ($id == 1 && $userId != 1) exit('false');

		// check if username already exists
		$existingUsername = $Administrator->usernameExists($username);
		// if username exists, check if the username belongs to the account being edited
		// if yes, the username was not edited and is allowed
		if ($existingUsername && $existingUsername != $id) exit('user');

		$pdo = null;
		try {
			$pdo = $Administrator->pdo();

			$isSuperAdmin = $_SESSION[Administrator::SESSION_PRIVILEGE] == 1;
			$profileUpdated = isset($_FILES['f']);

			// edit table
			// only privilege of 1 can edit username and role !!!
			if ($isSuperAdmin) {
				$pdo->exec("UPDATE `administrators` SET `username`= '$username', `fullname`= '$fullname', `role`= '$role' WHERE `user_id` = $id");
			} else {
				$pdo->exec("UPDATE `administrators` SET `fullname`= '$fullname' WHERE `user_id` = $id");
			}

			if ($password !== '') {
				$password = password_hash($password, PASSWORD_BCRYPT);
				$pdo->exec("UPDATE `administrators` SET `password`= '$password' WHERE `user_id` = $id");
			}

			if ($profileUpdated) {
				$file = $_FILES['f'];

				try {
					// save new file first
					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $ADMIN_FOLDER);

					// get old file
					$query = $Administrator->query("SELECT `profile_img` FROM `administrators` WHERE `user_id` = $id");

					// delete old file if found
					if ($query->num_rows !== 0) {
						$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

						if (!$filename) throw new Exception('Invalid file type or size');
						// delete old file
						unlink("{$ADMIN_FOLDER}$oldFile");
					}


					$pdo->exec("UPDATE `administrators` SET profile_img = '$filename' WHERE `user_id` = $id");
				} catch (\Throwable $th) {
					throw $th;
				}
			}

			if ($pdo->commit()) {
				// user edited their own account, make session updates
				if ($userId == $id) {
					@session_start();
					$_SESSION[Administrator::SESSION_FULLNAME] = $fullname;

					if ($profileUpdated) $_SESSION[Administrator::SESSION_PROFILE_IMG] = $filename;

					if ($isSuperAdmin) {
						$_SESSION[Administrator::SESSION_USERNAME] = $username;
						$_SESSION[Administrator::SESSION_ROLE] = $role;
					}
				}

				exit('true');
			} else throw new Error('commit failed');
		} catch (\Throwable $th) {
			//throw $th;
			echo $th->getMessage();
			if (!is_null($pdo)) $pdo->rollBack();
			exit('false');
		} finally {
			unset($pdo);
		}

	case 'delete':
		// user id is required
		if (!isset($_POST['i'])) exit('false');

		$id = (int) $_POST['i'];

		if ($id == $userId) exit('false');

		$conn = $Administrator->conn();

		// get image file
		$query = $conn->query("SELECT profile_img FROM `administrators` WHERE `user_id` = $id");

		if ($query->num_rows !== 0) {
			$file = $query->fetch_array(MYSQLI_NUM)[0];
			// delete image file
			unlink("{$ADMIN_FOLDER}$file");
		}

		// delete from database
		$conn->query("DELETE FROM `administrators` WHERE `user_id` = $id");
		exit('true');
		break;
}
