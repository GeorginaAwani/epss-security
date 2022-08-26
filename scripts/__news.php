<?php
require_once 'functions.php';

class News extends DBConnections
{
	private $table = 'news';
	private $media_table = 'news_media';
	private $ROOT = ROOT;

	private function news_folder()
	{
		return "{$this->ROOT}/files/news/";
	}
	/**
	 * Creates a new news article
	 * @param string $title article title
	 * @param string $body article body
	 * @param string $eventDate scheduled date for an event
	 * @param array $files attached media
	 * @param array $description media descriptions
	 */
	public function new(string $title, string $body, string $eventDate, array $files, array $descriptions)
	{
		$pdo = null;
		try {
			$pdo = $this->pdo();

			if ($eventDate !== '') {
				try {
					$eventDate = new DateTime($eventDate);

					$pdo->exec("INSERT INTO `{$this->table}`(`title`, `event_date`, `body`) VALUES ('$title', '{$eventDate->format("Y-m-d h:i:s")}', '$body')");
				} catch (\Throwable $th) {
					throw $th;
				}
			} else {
				$pdo->exec("INSERT INTO `{$this->table}`(`title`, `body`) VALUES ('$title', '$body')");
			}

			if (!empty($files) && !empty($descriptions)) {
				$aid = $pdo->lastInsertId();
				try {
					$FW = new FileWorker;
					$FW->save($pdo, $this->news_folder(), $this->media_table, null, $files, $descriptions, ['article_id' => $aid], $this);
				} catch (\Throwable $th) {
					throw $th;
				}
			}

			if ($pdo->commit()) return true;
			else return false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			if (!is_null($pdo)) $pdo->rollBack();
			return false;
		} finally {
			unset($pdo);
		}
	}

	/**
	 * Display news in tabular form
	 */
	public function getTableData()
	{
		$conn = null;
		try {
			$conn = $this->conn();

			$query = $conn->query("SELECT * FROM `{$this->table}` ORDER BY `id` DESC");

			while ($record = $query->fetch_assoc()) {
				$nid = $record['id'];
				$title = $record['title'];
				$eventDate = $record['event_date'];
				$body = $record['body'];
				$date = new DateTime($record['date']);

				if (is_null($eventDate)) $event = '';
				else {
					$eventDate = new DateTime($eventDate);
					$event = "Event for - <span class='article-event-date' data-article-date='{$eventDate->format('Y-m-d H:i')}'>{$eventDate->format(DATETIME_FORMAT)}</div>";
				}

				echo "<tr data-article-id='$nid'>
					<td>
						<div class='article-title font-weight-bold h6 mb-1 ws-nowrap'>$title</div>
						<div class='font-italic font-sm testimonial-position text-black-50 ws-nowrap'>$event</div>
					</td>
					<td><div class='article-date'>{$date->format(DATETIME_FORMAT)}</div></td>
					<td><div class='desc-wp'>$body</div></td>
					<td>";

				// get any attached media
				$mediaSQL = "SELECT `media_id`, `filename`, `filetype`, `description` FROM `{$this->media_table}` WHERE article_id = $nid";
				$mediaQuery = $conn->query($mediaSQL);

				if ($mediaQuery->num_rows) {
					echo "<div class='d-flex flex-wrap justify-content-center'>";
					$mediaClass = 'table-img rounded border mx-1';

					while ($mediaRecord = $mediaQuery->fetch_assoc()) {
						$mid = $mediaRecord['media_id'];
						$filename = $mediaRecord['filename'];
						$filetype = $mediaRecord['filetype'];
						$desc = $mediaRecord['description'];

						$src = "../files/news/$filename";

						echo $filetype === 'image' ? "<img class='$mediaClass' data-media-id='$mid' src='$src' alt='$desc'/>" : "<video class='$mediaClass' src='$src' aria-label='$desc'></video>";
					}

					echo "</div>";
				} else echo '<div class="text-center font-italic text-muted">No attachment</div>';

				echo "</td>
					<td>
						<div class='dropdown'>
							<button type='button' class='btn text-muted ws-nowrap' data-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis-vertical'></i></button>
							<div class='dropdown-menu mt-2 py-0 shadow-sm'>
								<button class='dropdown-item ease py-2' data-article-action='edit'>Edit</button>
								<button class='dropdown-item ease py-2' data-article-action='delete'>Delete</button>
							</div>
						</div>
					</td>
				</tr>";
			}
		} catch (\Throwable $th) {
			//throw $th;
			return;
		} finally {
			if (!is_null($conn)) {
				$conn->close();
				unset($conn);
			}
		}
	}

	/**
	/**
	 * Creates a new news article
	 * @param int $aid article id 
	 * @param string $title article title
	 * @param string $body article body
	 * @param string $eventDate scheduled date for an event
	 * @param array $files attached media
	 * @param array $description media descriptions
	 */
	public function edit(int $aid, string $title, string $body, string $eventDate)
	{
		try {
			if ($eventDate !== '') {
				try {
					$eventDate = new DateTime($eventDate);

					$query = $this->query("UPDATE `{$this->table}` SET `title`= '$title', `event_date`='{$eventDate->format("Y-m-d h:i:s")}', `body`= '$body' WHERE `id` = $aid");
				} catch (\Throwable $th) {
					throw $th;
				}
			} else {
				$query = $this->query("UPDATE `{$this->table}` SET `title`= '$title', `body`= '$body', `event_date` = NULL WHERE `id` = $aid");
			}

			return $query ? true : false;
		} catch (\Throwable $th) {
			$th = $th->getMessage();
			return false;
		}
	}

	/**
	 * Delete new
	 */
	public function delete(int $nid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			// delete from database
			$conn->query("DELETE FROM `{$this->table}` WHERE `id` = $nid");

			// get any attached files
			$query = $conn->query("SELECT `filename` FROM `{$this->media_table}` WHERE `article_id` = $nid");

			// delete each attached file
			while ($record = $query->fetch_assoc()) {
				unlink("{$this->news_folder()}{$record['filename']}");
			}

			return true;
		} catch (\Throwable $th) {
			return false;
		} finally {
			if (!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	/**
	 * Display news excerpt as card
	 */
	public function getCards($conn = null)
	{
		try {
			$conn = is_null($conn) ? $this->conn() : $conn;
			$query = $conn->query("SELECT `id`, `title`, `event_date`, LEFT(body, 115) AS `body`, `pinned`, `date` FROM `{$this->table}` ORDER BY `pinned` DESC, `id` DESC LIMIT 3");

			$rows = $query->num_rows;
			if (!$rows) return;

			while ($record = $query->fetch_assoc()) {
				$nid = $record['id'];
				$title = $record['title'];
				$eventDate = $record['event_date'];
				$body = $record['body'];
				$date = new DateTime($record['date']);
				$pinned = $record['pinned'] == 1 ? 'pinned' : '';

				echo "<article class='align-items-stretch col-lg-4 d-flex flex-column px-0 news-item $pinned' aria-labelledby='newsExcerptTitle_{$nid}'>";

				$mediaQuery = $conn->query("SELECT `filename`, `filetype`, `description` FROM news_media WHERE article_id = $nid LIMIT 1");

				if ($mediaQuery->num_rows !== 0) {
					$mediaRecord = $mediaQuery->fetch_assoc();
					$filename = $mediaRecord['filename'];
					$filetype = $mediaRecord['filetype'];
					$desc = $mediaRecord['description'];

					$media = media_file($filename, $filetype, $desc, 'news');

					echo "<a href='news.php?id=$nid' class='ease'>$media</a>";
				}

				echo "
					<div class='p-5 news-body flex-fill'>
						<p class='font-sm mb-4 mt-2 text-black-50 text-bar d-flex align-items-center text-uppercase' aria-label='News Date'>{$date->format('j M Y')}</p>
						<h1 class='font-weight-normal h5 heading mb-3' id='newsExcerptTitle_{$nid}'>$title</h1>
						<p class='article-body'>$body</p>";

				if (!is_null($eventDate)) {
					$eventDate = new DateTime($eventDate);
					echo "<p class='font-italic font-sm text-black-50'>Event for <span class='font-weight-bold'>{$eventDate->format(DATETIME_FORMAT)}</span></p>";
				}

				echo "
						<a href='news.php?id=$nid' class='btn btn-sm btn-link px-0 d-inline-flex align-items-center ease font-weight-bold'>Read more</a>
					</div>
				</article>
				";
			}
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	/**
	 * Display news excerpt in full list
	 * @param int $lid if of last loaded news
	 */
	public function getNewsList(int $lid = 0)
	{
		$conn = null;
		$limit = 10;
		try {
			$conn = $this->conn();

			// query pinned news
			// including pinned news in general SQL messes up load more functionality
			if ($lid === 0) {
				$pinnedSQL = "SELECT `id`, `title`, `event_date`, LEFT(body, 300) AS `body`, `pinned`, `date` FROM `{$this->table}` WHERE `pinned` IS TRUE ORDER BY `id` DESC LIMIT 1";
				$pinnedQuery = $conn->query($pinnedSQL);

				// display pinned news if any
				if ($pinnedQuery->num_rows !== 0) {
					$pinnedRecord = $pinnedQuery->fetch_assoc();

					$nid = $this->display_news_item($pinnedRecord, $conn);
					// if pinned news exists, display one less unpinned news
					$limit -= 1;
				}
			}

			// get all unpinned news articles
			$sql = "SELECT `id`, `title`, `event_date`, LEFT(body, 300) AS `body`, `pinned`, `date` FROM `{$this->table}` WHERE `pinned` IS FALSE ";

			if ($lid !== 0) $sql .= "AND `id` < $lid ";

			$sql .= "ORDER BY `id` DESC LIMIT $limit";

			$query = $conn->query($sql);

			$rows = $query->num_rows;
			if (!$rows) return;

			// display unpinned news
			while ($record = $query->fetch_assoc()) {
				$nid = $this->display_news_item($record, $conn);
			}

			// display load more
			if ($rows === $limit) {
				// LogLoss
				echo "<div class='text-center'><button class='btn btn-dark px-4 py-3 rounded-0' data-load-id='$nid' id='newsLd'>Load More</button></div>";
			}
		} catch (\Throwable $th) {
			echo "<br>Error: <code>{$th->getMessage()}</code> in <b>{$th->getFile()}</b>, on line <b>{$th->getLine()}</b><br>Trace: <b>{$th->getTraceAsString()}</b><br>";
			return;
		} finally {
			if (!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	/**
	 * Display a single nes article
	 * @param int $nid news id
	 */
	public function getSingleNews(int $nid)
	{
		$conn = null;
		try {
			$conn = $this->conn();

			$query = $conn->query("SELECT `title`, `event_date`, `body`, `date` FROM `news` WHERE `id` = $nid");
			if (!$query->num_rows) return;

			$record = $query->fetch_assoc();
			$title = $record['title'];
			$eventDate = $record['event_date'];
			$body = $record['body'];
			$date = new DateTime($record['date']);

			$mediaQuery = $conn->query("SELECT `filename`, `filetype`, `description` FROM news_media WHERE article_id = $nid");

			$mediaRows = $mediaQuery->num_rows;

			echo "<article class='align-items-stretch mb-4 d-flex flex-column px-0 news-item' id='single_news_{$nid}' data-news-id='$nid' aria-labelledby='singleNewsTitle_${nid}'>";

			if ($mediaRows !== 0) {
				echo "<div id='singleNewsCarousel_${nid}' class='carousel slide' data-interval='false'><div class='carousel-inner'>";
				for ($i = 0; $i < $mediaRows; ++$i) {
					$mediaRecord = $mediaQuery->fetch_assoc();
					$filename = $mediaRecord['filename'];
					$filetype = $mediaRecord['filetype'];
					$desc = $mediaRecord['description'];

					$active = $i === 0 ? 'active' : '';

					// $media = media_file($filename, $filetype, $desc, 'news');

					$src = "/epss/files/news/$filename";

					if ($filetype === 'image') {
						$media = "<div class='single-media'><img src='$src' class='img-fluid' alt='$desc'/></div>";
					} else {
						$media = "<div class='single-media position-relative'><video class='img-fluid h-100' src='$src'></video><button type='button' class='btn media-btn position-absolute'><i class='fa-regular fa-circle-play media-icon'></i></button></div>";
					}

					echo "<div class='carousel-item $active'>$media</div>";
				}
				if ($mediaRows !== 1) echo "<a class='carousel-control-prev h4' href='#singleNewsCarousel_${nid}' data-slide='prev'><i class='fa-solid fa-chevron-left'></i><span class='sr-only'>Previous media</span></a> <a class='carousel-control-next h4' href='#singleNewsCarousel_${nid}' data-slide='next'><i class='fa-solid fa-chevron-right'></i><span class='sr-only'>Next media</span></a>";
				echo "</div></div>";
			}

			echo "<div class='p-5 news-body flex-fill'>
			<h1 class='font-weight-normal h3 heading mb-3 news-title' id='singleNewsTitle_${nid}'>$title</h1>
			<p class='font-sm mb-4 mt-2 text-black-50 text-bar d-flex align-items-center text-uppercase' aria-label='News Date'>{$date->format('j M Y, g:ia')}</p>";

			if (!is_null($eventDate)) {
				$eventDate = new DateTime($eventDate);
				echo "<p class='font-italic font-sm text-black-50'>Event for <span class='font-weight-bold'>{$eventDate->format(DATETIME_FORMAT)}</span></p>";
			}

			echo "<div class='news-body-text'>$body</div>";
		} catch (\Throwable $th) {
			//throw $th;
		} finally {
			if (!is_null($conn)) $conn->close();
			unset($conn);
		}
	}

	private function display_news_item(array $record, \mysqli $conn)
	{
		$nid = $record['id'];
		$title = $record['title'];
		$eventDate = $record['event_date'];
		$body = $record['body'];
		$date = new DateTime($record['date']);
		$pinned = $record['pinned'] == 1 ? 'pinned' : '';

		$mediaQuery = $conn->query("SELECT `filename`, `filetype`, `description` FROM news_media WHERE article_id = $nid");

		$mediaRows = $mediaQuery->num_rows;

		echo "<article class='align-items-stretch mb-4 d-flex flex-column px-0 news-item {$pinned}' id='news_{$nid}' data-news-id='$nid' aria-labelledby='newsTitle_${nid}'>";

		if ($mediaRows !== 0) {
			echo "<div id='newsCarousel_${nid}' class='carousel slide' data-interval='false'><div class='carousel-inner'>";
			for ($i = 0; $i < $mediaRows; ++$i) {
				$mediaRecord = $mediaQuery->fetch_assoc();
				$filename = $mediaRecord['filename'];
				$filetype = $mediaRecord['filetype'];
				$desc = $mediaRecord['description'];

				$active = $i === 0 ? 'active' : '';

				$media = media_file($filename, $filetype, $desc, 'news');

				echo "<div class='carousel-item $active'>$media</div>";
			}
			if ($mediaRows !== 1) echo "<a class='carousel-control-prev h4' href='#newsCarousel_${nid}' data-slide='prev'><i class='fa-solid fa-chevron-left'></i><span class='sr-only'>Previous media</span></a> <a class='carousel-control-next h4' href='#newsCarousel_${nid}' data-slide='next'><i class='fa-solid fa-chevron-right'></i><span class='sr-only'>Next media</span></a>";
			echo "</div></div>";
		}

		echo "<div class='p-5 news-body flex-fill'>
		<p class='font-sm mb-4 mt-2 text-black-50 text-bar d-flex align-items-center text-uppercase' aria-label='News Date'>{$date->format('j M Y, g:ia')}</p>
		<h1 class='font-weight-normal h5 heading mb-3' id='newsTitle_${nid}'>$title</h1>
		<p>$body</p>";

		if (!is_null($eventDate)) {
			$eventDate = new DateTime($eventDate);
			echo "<p class='font-italic font-sm text-black-50'>Event for <span class='font-weight-bold'>{$eventDate->format(DATETIME_FORMAT)}</span></p>";
		}

		echo "<a href='news.php?id=$nid' data-news-id='$nid' class='btn btn-sm btn-link px-0 d-inline-flex align-items-center ease font-weight-bold _news_ld'>Read more</a>
		</div></article>";

		return $nid;
	}
}
