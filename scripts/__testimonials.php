<?php
require_once 'functions.php';

class Testimonials extends DBConnections
{
	private $table = 'testimonials';

	/**
	 * Creates a new testimonial
	 * @param string $name quoter name
	 * @param string $position quoter position
	 * @param string $company quoter company
	 * @param array $quote quote
	 */
	public function new(string $name, string $position, string $company, string $quote)
	{
		try {
			$query = $this->query("INSERT INTO {$this->table}(`quote`, `name`, `position`, `company`) VALUES ('$quote', '$name', '$position', '$company')");

			return $query ? true: false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Display testimonials in tabular form
	 */
	public function getTableData()
	{
		try {
			$query = $this->query("SELECT * FROM {$this->table}");

			while ($record = $query->fetch_assoc()) {
				$tid = $record['testimonial_id'];
				$quote = $record['quote'];
				$name = $record['name'];
				$position = $record['position'];
				$company = $record['company'];

				echo "<tr data-testimonial-id='$tid'>
					<td>
						<div class='ws-nowrap'>
							<div class='font-weight-bold h6 m-0 testimonial-name'>$name</div>
							<div class='font-sm testimonial-position text-black-50'>$position</div>
						</div>
					</td>
					<td><div class='testimonial-company'>$company</div></td>
					<td>
						<div class='desc-wp'>$quote</div>
					</td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-testimonial-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-testimonial-action='delete'>Delete</button>
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
	 * Edit testimonial
	 * @param string $name quoter name
	 * @param string $position quoter position
	 * @param string $company quoter company
	 * @param array $quote quote
	 */
	public function edit(int $tid, string $name, string $position, string $company, string $quote)
	{
		try {
			$query = $this->query("UPDATE {$this->table} SET `quote`= '$quote', `name`= '$name', `position`='$position', `company`= '$company' WHERE `testimonial_id` = $tid");

			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Delete testimonial
	 */
	public function delete(int $tid)
	{
		try {
			$query = $this->query("DELETE FROM {$this->table} WHERE `testimonial_id` = $tid");

			return $query ? true : false;
		} catch (\Throwable $th) {
			return false;
		}
	}

	/**
	 * Display testimonials as slides
	 */
	public function getSlides($conn = null)
	{
		try {
			$conn = is_null($conn) ? $this : $conn;
			$query = $conn->query("SELECT `quote`, `name`, `position`, `company` FROM `{$this->table}`");

			$rows = $query->num_rows;
			if (!$rows) return;

			$isSingle = $rows === 1;

			echo "<div id='carouselSlide' class='carousel slide' data-ride='carousel'>
			<div class='mb-3'>";
			if(!$isSingle) echo "<a class='carousel-control-prev d-inline-block h2 mb-0 p-2 text-warning position-relative ease' href='#carouselSlide' data-slide='prev' aria-label='Previous testimonial' role='button'><i class='fa-solid fa-chevron-left'></i></a>";
			echo "</div><div class='carousel-inner px-sm-5 px-4'>";

			for ($i = 0; $i < $rows; ++$i) {
				$record = $query->fetch_assoc();
				$active = $i === 0 ? 'active' : '';
				echo "<div class='carousel-item $active'>
					<div class='h3 mb-3 text-black-50'><i class='fa-solid fa-quote-left'></i></div>
					<blockquote>
						<p>{$record['quote']}</p>
						<div>
							<div class='testimonial-author font-weight-bold'>{$record['name']}</div>
							<div class='text-black-50 text-bar d-flex align-items-center'>{$record['position']}, {$record['company']}</div>
						</div>
					</blockquote>
				</div>";
			}

			echo "</div>";
			if(!$isSingle) echo "<div class='text-right'><a class='carousel-control-next d-inline-block h2 mb-0 p-2 text-warning position-relative ease' href='#carouselSlide' data-slide='next' aria-label='Next testimonial' role='button'><i class='fa-solid fa-chevron-right'></i></a></div>";
			echo "</div>";
		} catch (\Throwable $th) {
			//throw $th;
			return;
		}
	}
}
