<?php
require_once 'functions.php';
$ROOT = ROOT;

class Awards extends DBConnections
{
	private $table = 'awards';
	private $ROOT = ROOT;

	private function awards_folder()
	{
		return "{$this->ROOT}/files/awards/";
	}

	/**
	 * Creates a new award
	 * @param array $file award image file
	 * @param string $description award description
	 */
	public function new(array $file, string $description)
	{
		try {
			$File = new FileWorker;
			// save new file
			$filename = $File->saveSingleFile($file, $this->awards_folder());
			if (!$filename) throw new Exception('Invalid file type or size');

			$query = $this->query("INSERT INTO `{$this->table}`(`filename`, `description`) VALUES ('$filename', '$description')");
			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display awards in tabular form
	 */
	public function getTableData()
	{
		$conn = null;
		try {
			$conn = $this->conn();

			$query = $conn->query("SELECT * FROM `{$this->table}`");

			$mediaClass = 'table-img rounded border mx-1';

			while ($record = $query->fetch_assoc()) {
				$mid = $record['award_id'];
				$filename = $record['filename'];
				$desc = $record['description'];

				echo "
				<tr data-award-id='$mid'>
					<td>
						<div><img class='$mediaClass' data-media-id='$mid' src='/epss/files/awards/$filename' alt='$desc'/></div>
					</td>
					<td>
						<div class='desc-wp'>$desc</div>
					</td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-award-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-award-action='delete'>Delete</button>
							</div>
						</div>
					</td>
				</tr>
			";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		} finally {
			if (!is_null($conn)) {
				$conn->close();
			}
			unset($conn);
		}
	}

	/**
	 * Edits an existing award
	 * @param int $aid award id 
	 * @param string $descrption award description
	 * @param array $file award file
	 */
	public function edit(int $aid, string $description, array $file)
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			$pdo->exec("UPDATE `{$this->table}` SET `description`= '$description' WHERE `award_id` = $aid");

			if (!empty($file)) {
				try {
					// get old file
					$query = $this->query("SELECT `filename` FROM `{$this->table}` WHERE `award_id` = $aid");
					if (!$query->num_rows) throw new Error('Previous awards file not found');

					$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $this->awards_folder(), null);
					if (!$filename) throw new Exception('Invalid file type or size');

					$pdo->exec("UPDATE `{$this->table}` SET `filename`= '$filename' WHERE `award_id` = $aid");

					// delete old file
					unlink("{$this->awards_folder()}$oldFile");
				} catch (\Throwable $th) {
					echo $th->getMessage();
					$pdo->rollBack();
					return false;
				}
			}

			return $pdo->commit() ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			if(!is_null($pdo)) $pdo->rollBack();
			return false;
		}
		finally{
			unset($pdo);
		}
	}

	/**
	 * Delete an award
	 */
	public function delete(int $aid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// get any attached files
			$query = $conn->query("SELECT `filename` FROM `{$this->table}` WHERE `award_id` = $aid");

			if ($query->num_rows !== 0) {
				unlink("{$this->awards_folder()}/{$query->fetch_array(MYSQLI_NUM)[0]}");
			}

			// delete from database
			$conn->query("DELETE FROM `{$this->table}` WHERE `award_id` = $aid");

			return true;
		} catch (\Throwable $th) {
			return false;
		} finally {
			if (!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	/**
	 * Displays awards as cards
	 */
	public function getCards($lid = 0){
		$limit = 9;

		try {
			$sql = "SELECT * FROM `{$this->table}` ";

			if($lid !== 0) $sql .= "WHERE award_id < $lid ";

			$sql .= "ORDER BY award_id DESC LIMIT $limit";

			$query = $this->query($sql);

			$rows = $query->num_rows;

			$displayed = 0;

			for($i = 0; $i < $rows; ++$i){
				$record = $query->fetch_assoc();
				$aid = $record['award_id'];
				$filename = $record['filename'];
				$description = $record['description'];

				++$displayed;

				if($displayed === 1){
					echo "<div class='row mb-5'>";
				}

				echo "<div class='col-lg-4 d-flex flex-column mb-4 mb-lg-0 award-item img-item' id='award{$aid}'>
				<a class='award-view-btn flex-fill img-view' href=''><div class='award-img bg-img border overflow-hidden rounded-lg'><img src='/epss/files/awards/$filename' class='img-fluid'/></div></a>

				<p class='font-sm mb-0 mt-3 text-muted img-text'>$description</p>

				<button type='button' class='sr-only btn award-view-btn img-view'>View award</button>
				</div>";

				if($displayed === 3 || $i == ($rows - 1)){
					$displayed = 0;
					echo "</div>";
				}
			}

			if($rows === $limit){
				echo "<div class='text-center'><button class='btn btn-dark px-4 py-3 rounded-0' type='button' data-load-id='$aid' id='awardLoadBtn'>Load more awards</button></div>";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}
}
