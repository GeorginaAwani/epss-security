<?php
require_once 'functions.php';

class Gallery extends DBConnections
{
	private $table = 'gallery';
	private $ROOT = ROOT;

	private function gallery_folder()
	{
		return "{$this->ROOT}/files/gallery/";
	}

	/**
	 * Creates a new gallery media
	 * @param array $file gallery image or video file
	 * @param string $description media description
	 */
	public function new(array $file, string $description)
	{
		try {
			$File = new FileWorker;
			// save new file
			$filename = $File->saveSingleFile($file, $this->gallery_folder(), null);
			if (!$filename) throw new Exception('Invalid file type or size');

			$filetype = $file['type'];
			$filetype = strpos($filetype, 'image') === false ? 'video' : 'image';

			$query = $this->query("INSERT INTO `{$this->table}`(`filename`, `filetype`, `description`) VALUES ('$filename', '$filetype', '$description')");

			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display gallery in tabular form
	 */
	public function getTableData()
	{
		try {
			$query = $this->query("SELECT * FROM `{$this->table}` ORDER BY `media_id` DESC");

			$mediaClass = 'table-img rounded border mx-1';

			while ($record = $query->fetch_assoc()) {
				$mid = $record['media_id'];
				$filename = $record['filename'];
				$filetype = $record['filetype'];
				$desc = $record['description'];

				$src = "/files/gallery/$filename";

				$file = $filetype === 'image' ? "<img class='$mediaClass' data-media-id='$mid' src='$src' alt='$desc'/>" : "<video class='$mediaClass' src='$src' aria-label='$desc'></video>";

				echo "<tr data-gallery-id='$mid'>
				<td><div class=''>$file</div></td>
				<td><div class='desc-wp'>$desc</div></td>
				<td>
					<div class='dropdown'>
						<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
						<div class='dropdown-menu mt-2 py-0 shadow-sm'>
							<button class='dropdown-item ease py-2' data-gallery-action='edit'>Edit</button>
							<button class='dropdown-item ease py-2' data-gallery-action='delete'>Delete</button>
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
	 * Edits an existing gallery media
	 * @param int $mid media id 
	 * @param string $descrption media description
	 * @param array $file media file
	 */
	public function edit(int $mid, string $description, array $file)
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			$pdo->exec("UPDATE `{$this->table}` SET `description`= '$description' WHERE `media_id` = $mid");

			if (!empty($file)) {
				try {
					// get old file
					$query = $this->query("SELECT `filename` FROM `{$this->table}` WHERE `media_id` = $mid");
					if (!$query->num_rows) throw new Error('Previous gallery file not found');

					$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $this->gallery_folder(), null);
					if (!$filename) throw new Exception('Invalid file type or size');

					$filetype = $file['type'];
					$filetype = strpos($filetype, 'image') === false ? 'video' : 'image';

					$pdo->exec("UPDATE `{$this->table}` SET `filename`= '$filename', `filetype` = '$filetype' WHERE `media_id` = $mid");

					// delete old file
					unlink("{$this->gallery_folder()}$oldFile");
				} catch (\Throwable $th) {
					echo $th->getMessage();
					$pdo->rollBack();
					return false;
				}
			}

			return $pdo->commit() ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			if (!is_null($pdo)) $pdo->rollBack();
			return false;
		} finally {
			unset($pdo);
		}
	}

	/**
	 * Delete a gallery media
	 */
	public function delete(int $mid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// get any attached files
			$query = $conn->query("SELECT `filename` FROM `{$this->table}` WHERE `media_id` = $mid");

			if ($query->num_rows !== 0) {
				unlink("{$this->gallery_folder()}{$query->fetch_array(MYSQLI_NUM)[0]}");
			}

			// delete from database
			$conn->query("DELETE FROM `{$this->table}` WHERE `media_id` = $mid");

			return true;
		} catch (\Throwable $th) {
			return false;
		} finally {
			if (!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	/**
	 * Displays gallery as cards
	 */
	public function getCards($lid = 0)
	{
		$limit = 12;

		try {
			$sql = "SELECT * FROM `{$this->table}` ";

			if ($lid !== 0) $sql .= "WHERE media_id < $lid ";

			$sql .= "ORDER BY media_id DESC LIMIT $limit";

			$query = $this->query($sql);

			$rows = $query->num_rows;

			$displayed = 0;

			for ($i = 0; $i < $rows; ++$i) {
				$record = $query->fetch_assoc();
				$mid = $record['media_id'];
				$filename = $record['filename'];
				$description = $record['description'];
				$filetype = $record['filetype'];

				++$displayed;

				if ($displayed === 1) {
					echo "<div class='row'>";
				}

				$media = media_file($filename, $filetype, $description, 'gallery');

				echo "<div class='col-sm-4 px-0'>
				<div class='img-wp position-relative overlay overflow-hidden img-view img-item ease' tabindex='0'>
					<div class='filter-wp position-relative w-100 h-100'>$media</div>
					<div class='d-flex flex-column h-100 img-text-wp justify-content-between p-3 p-md-4 position-absolute text-white w-100'>
						<div class='h1 img-icon mb-0 pr-3 text-right font-weight-light'>&plus;</div>
						<div class='heading font-sm img-text py-2 text-left'>$description</div>
					</div>
				</div>
				</div>";

				if ($displayed === 3 || $i == ($rows - 1)) {
					$displayed = 0;
					echo "</div>";
				}
			}

			if ($rows === $limit) {
				echo "<div class='text-center py-4'><button class='btn btn-light px-4 py-3 rounded-0' type='button' data-load-id='$mid' id='galleryLoadBtn'>Load more</button></div>";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}
}
