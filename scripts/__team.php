<?php

require_once 'functions.php';

class Team extends DBConnections
{
	private $table = 'team_members';
	private $ROOT = ROOT;

	private function team_folder()
	{
		return "{$this->ROOT}/files/team/";
	}
	/**
	 * Creates a new team member
	 * @param string $name member name
	 * @param string $position member position
	 * @param string $description member description
	 * @param array $file member imafe file
	 */
	public function new(string $name, string $position, string $description, array $file)
	{
		try {
			$File = new FileWorker;

			$filename = $File->saveSingleFile($file, $this->team_folder());
			if (!$filename) throw new Exception('Invalid file type or size');

			$query = $this->query("INSERT INTO `{$this->table}`(`name`, `position`, `description`, `image_file`) VALUES ('$name', '$position', '$description', '$filename')");

			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display team members in tabular form
	 */
	public function getTableData()
	{
		try {
			$query = $this->query("SELECT * FROM `{$this->table}`");

			while ($record = $query->fetch_assoc()) {
				$mid = $record['member_id'];
				$name = $record['name'];
				$position = $record['position'];
				$description = $record['description'];
				$file = $record['image_file'];

				$imgSrc = "/epss/files/team/{$file}";
				echo "
				<tr data-member-id='$mid'>
					<td>
						<div class='d-flex align-items-center'>
							<div>
								<div class='bg-img table-img rounded-lg border border-light' style='background-image: url($imgSrc);'><img src='$imgSrc' hidden/></div>
							</div>
							<div class='ml-2 ws-nowrap'>
								<div class='font-weight-bold h6 m-0 member-name text-black'>$name</div>
								<div class='font-sm member-position text-black-50'>$position</div>
							</div>
						</div>
					</td>
					<td>
						<div class='desc-wp'>$description</div>
					</td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-member-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-member-action='delete'>Delete</button>
							</div>
						</div>
					</td>
				</tr>
			";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}

	/**
	 * Edit team member
	 * @param int $mid member id
	 * @param string $name member name
	 * @param string $position member position
	 * @param string $description member description
	 * @param array $file member image file
	 */
	public function edit(int $mid, string $name, string $position, string $description, array $file)
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			$pdo->exec("UPDATE `{$this->table}` SET `name`= '$name', `position`='$position', `description`= '$description' WHERE member_id = $mid");

			if (!empty($file)) {
				try {
					// get old file
					$query = $this->query("SELECT image_file FROM `{$this->table}` WHERE member_id = $mid");
					if (!$query->num_rows) throw new Error('Previous image file not found');

					$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $this->team_folder());
					if (!$filename) throw new Exception('Invalid file type or size');

					$pdo->exec("UPDATE `{$this->table}` SET `image_file` = '$filename' WHERE `member_id` = $mid");

					// delete old file
					unlink("{$this->team_folder()}$oldFile");
				} catch (\Throwable $th) {
					echo $th->getMessage();
					$pdo->rollBack();
					return false;
				}
			}

			if ($pdo->commit()) return true;
			else return false;
		} catch (\Throwable $th) {
			if (!is_null($pdo)) $pdo->rollBack();
			return false;
		} finally {
			unset($pdo);
		}
	}

	/**
	 * Delete team member
	 */
	public function delete(int $mid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// get image file
			$query = $conn->query("SELECT image_file FROM `{$this->table}` WHERE member_id = $mid");

			if (!$query->num_rows) return false;

			$file = $query->fetch_array(MYSQLI_NUM)[0];

			// delete from database
			$conn->query("DELETE FROM `{$this->table}` WHERE member_id = $mid");

			// delete image file
			unlink("{$this->team_folder()}$file");
			return true;
		} catch (\Throwable $th) {
			return false;
		} finally {
			if (!is_null($conn)) $conn->close();
		}
	}

	/**
	 * Displays members in list
	 */
	public function getFullList(\mysqli $conn = null)
	{
		try {
			$conn = is_null($conn) ? $this : $conn;
			$query = $conn->query("SELECT `member_id`, `name`, `position`, `description`, image_file FROM `{$this->table}`");

			$rows = $query->num_rows;

			if (!$rows) return;

			$nav = $panes = '';

			for ($i = 0; $i < $rows; ++$i) {
				$record = $query->fetch_assoc();
				$mid = $record['member_id'];

				$imgSrc = "/epss/files/team/{$record['image_file']}";

				$active = $i === 0 ? 'active' : '';
				$fade = $i === 0 ? 'active' : 'fade';

				$nav .= "<li class='mt-sm-3 mx-2 mx-sm-0 nav-item'>
					<a class='nav-link bg-transparent $active p-0' data-toggle='pill' href='#mgt_{$mid}'>
						<div class='position-relative bg-img bg-light border border-light overlay ease mgt-img rounded-circle' aria-labelledby='mgt_{$mid}_Ttl' style='background-image: url($imgSrc);' id='mgt_nav_img_{$mid}' aria-describedby='mgt_{$mid}_Pos'></div>
					</a>
				</li>";

				$panes .= "<div class='tab-pane $fade' id='mgt_{$mid}'>
					<div class='flex-sm-row-reverse row'>
						<div class='col-sm-4 mb-3 mb-sm-0'>
							<div class='mgt-mn-img position-relative bg-img border border-light' style='background-image: url($imgSrc);'></div>
						</div>
						<div class='col-sm-7'>
							<h1 class='heading h4 text-uppercase mb-1' id='mgt_{$mid}_Ttl'>{$record['name']}</h1>
							<p class='font-weight-normal h6 heading text-secondary' id='mgt_{$mid}_Pos'>{$record['position']}</p>

							<div class='border-light border-top mt-0 mt-md-4 pt-2' style='text-align: justify;'>{$record['description']}</div>
						</div>
					</div>
				</div>";
			}

			echo "<div class='col-md-3'><ul class='nav nav-pills flex-sm-column align-items-center' id='mgtNav'>$nav</ul></div><div class='col-md-9'><div class='tab-content mt-4 mt-md-0 pl-2 pl-md-0'>$panes</div></div>";
		} catch (\Throwable $th) {
			echo "<br>Error: <code>{$th->getMessage()}</code> in <b>{$th->getFile()}</b>, on line <b>{$th->getLine()}</b><br>Trace: <b>{$th->getTraceAsString()}</b><br>";
			return;
		}
	}
}
