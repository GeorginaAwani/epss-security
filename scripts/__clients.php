<?php

require_once 'functions.php';

class Clients extends DBConnections
{
	private $table = 'clients';
	private $ROOT = ROOT;

	private function clients_folder()
	{
		return "{$this->ROOT}/files/clients/";
	}

	/**
	 * Creates a new client
	 * @param string $name client name
	 * @param array $file member image file
	 */
	public function new(string $name, array $file)
	{
		try {
			$File = new FileWorker;
			// save new file
			$filename = $File->saveSingleFile($file, $this->clients_folder());
			if (!$filename) throw new Exception('Invalid file type or size');

			$query = $this->query("INSERT INTO `{$this->table}`(`image_file`, `name`) VALUES ('$filename', '$name')");
			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display clients in tabular form
	 */
	public function getTableData()
	{
		try {
			$query = $this->query("SELECT * FROM `{$this->table}`");

			$mediaClass = 'table-img rounded border mx-1';

			while ($record = $query->fetch_assoc()) {
				$mid = $record['client_id'];
				$filename = $record['image_file'];
				$name = $record['name'];

				echo "<tr data-client-id='$mid'>
					<td>
						<div class='align-items-center d-flex'>
							<div><img class='$mediaClass' data-media-id='$mid' src='../files/clients/$filename' alt='$name'/></div>
							<div class='client-name font-weight-bold ml-2'>$name</div>
						</div>
					</td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-client-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-client-action='delete'>Delete</button>
							</div>
						</div>
					</td>
				</tr>";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}

	/**
	 * Edit client
	 * @param int $cid client id
	 * @param string $name client name
	 * @param array $file client image file
	 */
	public function edit(int $cid, string $name, array $file)
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			$pdo->exec("UPDATE `{$this->table}`SET `name`= '$name' WHERE `client_id` = $cid");

			if (!empty($file)) {
				try {
					// get old file
					$query = $this->query("SELECT `image_file` FROM `{$this->table}` WHERE `client_id` = $cid");
					if (!$query->num_rows) throw new Error('Previous image file not found');

					$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $this->clients_folder());
					if (!$filename) throw new Exception('Invalid file type or size');

					$pdo->exec("UPDATE `{$this->table}` SET `image_file`= '$filename' WHERE `client_id` = $cid");

					// delete old file
					unlink("{$this->clients_folder()}$oldFile");
				} catch (\Throwable $th) {
					echo $th->getMessage();
					$pdo->rollBack();
					return false;
				}
			}

			if ($pdo->commit()) return true;
			else return false;
		} catch (\Throwable $th) {
			//throw $th;
			if(!is_null($pdo)) $pdo->rollBack();
			return false;
		}
		finally{
			unset($pdo);
		}
	}

	/**
	 * Delete client
	 */
	public function delete(int $cid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// get image file
			$query = $conn->query("SELECT `image_file` FROM `{$this->table}` WHERE `client_id` = $cid");

			if (!$query->num_rows) return false;

			$file = $query->fetch_array(MYSQLI_NUM)[0];

			// delete from database
			$conn->query("DELETE FROM `{$this->table}` WHERE client_id = $cid");

			// delete image file
			unlink("{$this->clients_folder()}$file");
			return true;
		} catch (\Throwable $th) {
			return false;
		}
		finally{
			if(!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	/**
	 * Displays clients in cards
	 */
	public function getCards(\mysqli $conn = null)
	{
		try {
			$conn = is_null($conn) ? $this : $conn;
			$query = $conn->query("SELECT * FROM `{$this->table}`");

			$rows = $query->num_rows;

			if (!$rows) return;

			$displayed = 0;

			for ($i = 0; $i < $rows; ++$i) {
				$record = $query->fetch_assoc();
				$cid = $record['client_id'];
				$name = $record['name'];

				$imgSrc = "/epss/files/clients/{$record['image_file']}";

				++$displayed;

				if ($displayed === 1) {
					echo "<div class='row mt-3'>";
				}

				echo "<div class='col-md-3 mb-3 mb-md-0'>
					<div class='align-items-center border border-light d-flex h-100 justify-content-center p-5 position-relative rounded-lg client-im'>
						<div class='client-icon'><img class='img-fluid' src='$imgSrc' alt='$name'></div>
						<div class='client-desc ease p-4 position-absolute rounded-lg d-flex flex-column justify-content-between'><div class='heading mb-0 client-ttl ease font-weight-bold'>$name</div></div>
					</div>
				</div>";

				if ($displayed === 4 || $i == ($rows - 1)) {
					$displayed = 0;
					echo "</div>";
				}
				
			}
		} catch (\Throwable $th) {
			return;
		}
	}
}
