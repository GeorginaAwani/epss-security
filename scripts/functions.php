<?php

define('DATETIME_FORMAT', 'j M, Y - g:ia');
define('ROOT', "{$_SERVER['DOCUMENT_ROOT']}/epss");
define('MEDIA_ROOT', '/epss/');
# define('ROOT', "{$_SERVER['DOCUMENT_ROOT']}");
# define('MEDIA_ROOT', '/');

class DBConnections
{
	private $db_host = 'localhost';
	private $db_name = 'epss';
	private $db_user = 'Georgina';
	private $db_pass = 'IAmLocalhost!';

	protected $ROOT = ROOT;
	protected $MEDIA_ROOT = MEDIA_ROOT;

	# private $db_name = 'id19293538_epss';
	# private $db_user = 'id19293538_georgina';
	# private $db_pass = 'IAmEPSSSecurity!5';

	/**
	 * Returns a PDO object with all connections
	 * @return \PDO
	 */
	public function pdo()
	{
		$db = null;
		try {
			$db = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_user, $this->db_pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();

			return $db;
		} catch (\Throwable $th) {
			throw $th;
		} finally {
			unset($db);
		}
	}

	/**
	 * Executes a mySql query
	 * @return \mysqli_result
	 */
	public function query(string $query)
	{
		$conn = $result = null;
		try {
			$conn = self::conn();
			$result = $conn->query($query);
			if (!$result) die($conn->error);
			return $result;
		} catch (Exception $e) {
			$e = $e->getMessage();
			echo $e;
		} finally {
			//$result->close();
			if (!is_null($conn))
				$conn->close();
			unset($conn);
			unset($result);
		}
	}

	/**
	 * Create a database connection
	 * @return \mysqli
	 */
	public function conn()
	{
		return new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
	}

	public function sanitise($string)
	{
		// It is important this comes before the htmlentities function 
		// It removes all angle brackets.
		$string = strip_tags($string);
		// This strips all html markup codes and replaces them with a 
		// format that does not allow the browser to act on the codes
		$string = htmlentities($string);
		// The stripslashes escapes all quotes and slashes to 
		// ensure what the user types remains the way it is
		$string = stripslashes($string);

		return $this->conn()->real_escape_string($string);
	}
}

class FileWorker
{
	function __construct()
	{
	}

	private $totalUploadSize = 0;
	const MAX_UPLOAD_SIZE = 20971520;

	private function generate_filename()
	{
		return md5(uniqid(mt_rand(0, mt_getrandmax())));
	}

	public function save(\PDO $pdo, string $folder, string $table, $allowedFiletype, array $files, array $descriptions, array $data, \DBConnections $db)
	{
		$length = count($files['name']);

		$fields = $values = [];

		foreach ($data as $name => $value) {
			$fields[] = "`$name`";
			$values[] = "'$value'";
		}

		$fields = implode(', ', $fields);
		$values = implode(', ', $values);

		$fieldsString = !$fields ? '' : ", $fields";
		$valuesString = !$values ? '' : ", $values";

		for ($i = 0; $i < $length; ++$i) {
			$filesize = $files['size'][$i];
			$filetype = $files['type'][$i];

			if (!$this->is_valid($filesize, $allowedFiletype, $filetype)) throw new Error('Invalid file encountered.');

			// set type to image|video
			$filetype = strpos($filetype, 'image') !== false ? 'image' : 'video';

			// get temporary file name and sent description
			$tempFilename = $files['tmp_name'][$i];
			$description = $descriptions[$i];

			$description = $db->sanitise(trim(filter_var($description)));

			$description = !$description ? 'NULL' : "'$description'";

			$filename = $this->generate_filename();

			$ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);

			// move file from temp location into folder
			$newFilePath = "{$folder}$filename.$ext";

			if (!move_uploaded_file($tempFilename, $newFilePath)) throw new Error('Failed to move temp file to new location');

			$pdo->exec("INSERT INTO `$table`(`filename`, `filetype`, `description`{$fieldsString}) VALUES('$filename.$ext', '$filetype', $description{$valuesString})");
		}
	}

	/**
	 * Save single file to a folder
	 */
	public function saveSingleFile(array $file, string $folderPath, $allowed = 'image')
	{
		$tempFilename = $file['tmp_name'];

		if (!$this->is_valid($file['size'], $allowed, $file['type'])) return false;

		$filename = $this->generate_filename();

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		// move file from temp location into folder
		$fullFilename = "$filename.$ext";
		$newFilePath = "{$folderPath}$fullFilename";

		if (!move_uploaded_file($tempFilename, $newFilePath)) throw new Error('Failed to move temp file to new location');

		return $fullFilename;
	}

	/**
	 * Checks if file is an image or video
	 */
	private function is_valid($size, $allowed, $type)
	{
		if ($this->totalUploadSize + $size > self::MAX_UPLOAD_SIZE) throw new OverflowException('Upload size exceeds 20MB');

		$this->totalUploadSize += $size;

		if (is_null($allowed)) return preg_match('/^(image|video)\//', $type);

		if ($allowed === 'image') return strpos($type, 'image/') !== false;
		if ($allowed === 'video') return strpos($type, 'video/') !== false;

		return false;
	}
}

class Administrator extends DBConnections
{
	const SESSION_USER_ID = 'epss_admin_user_id';
	const SESSION_USERNAME = 'epss_admin_username';
	const SESSION_FULLNAME = 'epss_admin_fullname';
	const SESSION_CREDENTIALS = 'epss_admin_credentials';
	const SESSION_PRIVILEGE = 'epss_admin_privilege';
	const SESSION_PROFILE_IMG = 'epss_admin_profile_img';
	const SESSION_ROLE = 'epss_admin_role';
	const SESSSION_DATE = 'epss_admin_created_date';

	/**
	 * Generate random key
	 * @param int length of key. default is 70
	 * @return string
	 */
	private function generate_key()
	{
		return substr(str_shuffle(MD5(microtime())), 0, 100);
	}

	public function logIn(string $username, string $password)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// attempt to get login details of this username
			$query = $conn->query("SELECT `user_id`, `password`, `fullname`, `privilege`, `profile_img`, `role`, `date_created` FROM `administrators` WHERE `username` = '$username'");

			if (!$query->num_rows) return false;

			$record = $query->fetch_assoc();

			$correctPassword = $record['password'];
			$userId = $record['user_id'];

			$passwordMatch = password_verify($password, $correctPassword);

			$credentials = $this->generate_key();

			// log attempt
			$conn->query("INSERT INTO `admin_log`(`user_id`, `successful`, `credentials`) VALUES ($userId, '$passwordMatch', '$credentials')");

			// if password is invalid, return false
			if (!$passwordMatch) return false;

			$dateCreated = new DateTime($record['date_created']);

			// store user details in sessin
			@session_start();
			$_SESSION[self::SESSION_USER_ID] = $userId;
			$_SESSION[self::SESSION_USERNAME] = $username;
			$_SESSION[self::SESSION_FULLNAME] = $record['fullname'];
			$_SESSION[self::SESSION_PRIVILEGE] = $record['privilege'];
			$_SESSION[self::SESSION_CREDENTIALS] = $credentials;
			$_SESSION[self::SESSION_PROFILE_IMG] = $record['profile_img'];
			$_SESSION[self::SESSION_ROLE] = $record['role'];
			$_SESSION[self::SESSSION_DATE] = $dateCreated->format(DATETIME_FORMAT);

			return true;
		} catch (\Throwable $th) {
			return false;
		} finally {
			$conn->close();
		}
	}

	public function isLoggedIn()
	{
		try {
			@session_start();
			if (!isset($_SESSION[self::SESSION_USER_ID], $_SESSION[self::SESSION_USERNAME], $_SESSION[self::SESSION_FULLNAME], $_SESSION[self::SESSION_CREDENTIALS])) return false;

			$userId = $_SESSION[self::SESSION_USER_ID];
			$credentials = $_SESSION[self::SESSION_CREDENTIALS];

			$query = $this->query("SELECT log_id FROM admin_log WHERE user_id = $userId AND credentials = '$credentials' AND successful IS TRUE");

			return $query->num_rows !== 0;
		} catch (\Throwable $th) {
			echo $th->getMessage();
			return false;
		}
	}

	public function profilePhoto($profile = 0)
	{
		@session_start();

		if ($profile === 0) $profile = $_SESSION[self::SESSION_PROFILE_IMG];

		if (!$profile) return '<div class="bg-light border border-light pfp rounded-lg"></div>';
		else return "<div class='bg-img border pfp rounded-lg border-light'><img src='{$this->MEDIA_ROOT}files/admin/${profile}' class='d-none'/></div>";
	}

	public function logOut()
	{
		try {
			// Destroys the user's session when user logs out to prevent
			// the user from accessing the page without logging in again
			@session_start();
			$_SESSION = []; // Display session variable as array

			// The session_id() function returns the current session's ID.
			// isset() checks if the session_name has been created
			if (session_id() !== '' || isset($_COOKIE[session_name()]))
				setcookie(session_name(), '', time() - 2592000, '/');
			session_destroy();
		} catch (\Throwable $e) {
			$e = $e->getMessage();
			session_destroy();
		}
	}

	/**
	 * Check if username already exists
	 */
	public function usernameExists(string $username, \mysqli $conn = null)
	{
		$conn = is_null($conn) ? $this : $conn;
		$query = $conn->query("SELECT `user_id` FROM `administrators` WHERE `username` = '$username'");

		if (!$query->num_rows === 0) return false;
		return $query->fetch_array(MYSQLI_NUM)[0];
	}
}

function media_file($filename, $filetype, $description, $folder)
{
	$src = MEDIA_ROOT . "files/$folder/$filename";

	if ($filetype === 'image') {
		return "<div class='media bg-img' style='background-image: url($src);'><img src='$src' class='sr-only' alt='$description'/></div>";
	} else {
		return "<div class='media bg-img position-relative'><video class='img-fluid h-100' src='$src'></video><button type='button' class='btn media-btn position-absolute'><i class='fa-regular fa-circle-play media-icon'></i></button></div>";
	}
}
