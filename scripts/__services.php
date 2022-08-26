<?php

require_once 'functions.php';

class Services extends DBConnections
{
	private $table = 'services';
	private $ROOT = ROOT;

	private function services_folder()
	{
		return "{$this->ROOT}/files/services/";
	}

	/**
	 * Creates a new service
	 * @param string $name service name
	 * @param string $description full service description
	 * @param string $excerpt short service description
	 * @param array $file service image file
	 */
	public function new(string $name, string $description, string $excerpt, array $file)
	{
		try {
			$File = new FileWorker;

			try {
				// save image to folder first
				$filename = $File->saveSingleFile($file, $this->services_folder());
				if (!$filename) throw new Exception('Invalid file type or size');
			} catch (\Throwable $th) {
				return false;
			}

			$query = $this->query("INSERT INTO {$this->table}(`name`, `description`, `excerpt`, `image_file`) VALUES ('$name', '$description', '$excerpt', '$filename')");

			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display services in tabular form
	 */
	public function getTableData()
	{
		try {
			$query = $this->query("SELECT * FROM `{$this->table}`");

			while ($record = $query->fetch_assoc()) {
				$sid = $record['service_id'];
				$name = $record['name'];
				$excerpt = $record['excerpt'];
				$description = $record['description'];

				$file = $record['image_file'];

				$imgSrc = "../files/services/{$file}";

				echo "<tr data-service-id='$sid'>
					<td>
						<div class='d-flex align-items-center'>
							<div>
								<div class='bg-img table-img rounded border border-light' style='background-image: url($imgSrc);'><img src='$imgSrc' hidden/></div>
							</div>
							<div class='ml-2 ws-nowrap font-weight-bold h6 m-0 service-name'>$name</div>
						</div>
					</td>
					<td class='service-excerpt'>$excerpt</td>
					<td><div class='desc-wp'>$description</div></td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-service-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-service-action='delete'>Delete</button>
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
	 * Edit service
	 * @param int $sid service id
	 * @param string $name service name
	 * @param string $description service full description
	 * @param string $excerpt excerpt to be shown on home page
	 * @param array $file service image file
	 */
	public function edit(int $sid, string $name, string $description, string $excerpt, array $file = [])
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			$pdo->exec("UPDATE {$this->table} SET `name`= '$name', `description`= '$description', `excerpt` = '$excerpt' WHERE `service_id` = $sid");

			if (!empty($file)) {
				try {
					// get old file
					$query = $this->query("SELECT `image_file` FROM {$this->table} WHERE `service_id` = $sid");
					if (!$query->num_rows) throw new Error('Previous image file not found');

					$oldFile = $query->fetch_array(MYSQLI_NUM)[0];

					$File = new FileWorker;
					// save new file
					$filename = $File->saveSingleFile($file, $this->services_folder());
					if (!$filename) throw new Exception('Invalid file type or size');

					$pdo->exec("UPDATE {$this->table} SET image_file = '$filename' WHERE service_id = $sid");

					// delete old file
					unlink("{$this->services_folder()}$oldFile");
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
	 * Delete service
	 */
	public function delete(int $sid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// get image file
			$query = $conn->query("SELECT image_file FROM {$this->table} WHERE `service_id` = $sid");

			if (!$query->num_rows) return false;

			$file = $query->fetch_array(MYSQLI_NUM)[0];

			// delete from database
			$conn->query("DELETE FROM {$this->table} WHERE `service_id` = $sid");

			// delete image file
			unlink("{$this->services_folder()}$file");
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
	 * Display services in excerpt as card
	 */
	public function getExcerptCards($conn = null){
		try {
			$conn = is_null($conn) ? $this : $conn;
			$query = $conn->query("SELECT `service_id`, `name`, `excerpt`, `icon` FROM `{$this->table}`");

			$rows = $query->num_rows;

			$displayed = 0;

			for($i = 0; $i < $rows; ++$i){
				$record = $query->fetch_assoc();
				$sid = $record['service_id'];
				$name = $record['name'];
				$excerpt = $record['excerpt'];
				$icon = $record['icon'];

				++$displayed;

				if($displayed === 1){
					echo "<div class='row mt-4'>";
				}

				echo "<div class='col-lg-4 mb-4 mb-lg-0'>
				<div class='ease px-5 py-5 service-card text-left h-100 z-1 position-relative overflow-hidden'>
					<i class='{$icon} mt-3'></i>
					<div class='h5 heading font-weight-normal mb-4 mt-5 z-1'>
						<a href='services.php#service_{$sid}' class='ease text-reset text-decoration-none'>$name</a>
					</div>
					<p class='service-text text-black-50'>$excerpt</p>
					<a href='services.php#service_{$sid}' class='btn btn-sm btn-link d-inline-flex align-items-center ease font-weight-bold'>Read more</a>
				</div>
				</div>";

				if($displayed === 3){
					$displayed = 0;
					echo "</div>";
				}
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}

	/**
	 * Displays services in full
	 */
	public function getFullList(){
		try {
			$query = $this->query("SELECT `service_id`, `name`, `description`, `image_file`, `icon` FROM `{$this->table}`");

			$rows = $query->num_rows;

			for($i = 0; $i < $rows; ++$i){
				$record = $query->fetch_assoc();
				$sid = $record['service_id'];
				$description = $record['description'];
				$filename = $record['image_file'];
				$icon = $record['icon'];

				$j = $i + 1;
				if($j < 10) $j = "0{$j}";

				echo "
				<div class='mt-5 row service-row align-items-center' id='service_{$sid}'>
					<div class='col-sm-7 service-item'>
						<div class='service-counter'>$j.</div>
						<h2 class='heading h3 mb-4 mt-3 mt-md-4 pb-1 position-relative text-dark-blue'>{$record['name']}</h2>
						<p class='position-relative lead'>{$description}<i class='$icon position-absolute v-centered service-icon'></i></p>
					</div>
					<div class='col-sm-5'>
						<div class='position-relative overlay service-img'>
							<img src='/epss/files/services/$filename' alt='' class='img-fluid'>
						</div>
					</div>
				</div>
				";
			}
		} catch (\Throwable $th) {
			echo "<br>Error: <code>{$th->getMessage()}</code> in <b>{$th->getFile()}</b>, on line <b>{$th->getLine()}</b><br>Trace: <b>{$th->getTraceAsString()}</b><br>";
			return;
		}
	}
}
