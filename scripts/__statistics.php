<?php
require_once 'functions.php';

class Statistics extends DBConnections{
	/**
	 * Get statistics
	 * @param \mysqli|null $conn an existing database connection. If not provided, the program will create a new connection
	 */
	public function get(mysqli $conn = null){
		try {
			$conn = is_null($conn) ? $this : $conn;
			$query = $conn->query("SELECT * FROM `stats`");

			return $query->fetch_assoc();
		} catch (\Throwable $th) {
			//throw $th;
			return null;
		}
	}

	/**
	 * Set statistics
	 * @param int $offices number of offices
	 * @param int $clients number of clients
	 * @param int $estimateClients if 1, number of clients will have a '+' appended to it
	 * @param int $guards number of clients
	 * @param int $estimateGuards if 1, number of guards will have a '+' suspended to it
	 */
	public function set(int $offices, int $clients, int $estimateClients, int $guards, int $estimateGuards){
		$pdo = null;
		try {
			$pdo = $this->pdo();
			$pdo->exec("DELETE FROM stats");
			$pdo->exec("INSERT INTO `stats`(`offices`, `clients`, `estimate_clients`, `guards`, `estimate_guards`) VALUES ($offices, $clients, $estimateClients, $guards, $estimateGuards)");

			if($pdo->commit()) return true;
			else throw new Error('Failed to commit stats transaction');
		} catch (\Throwable $th) {
			if(!is_null($pdo)) $pdo->rollBack();
			echo $th->getMessage();
			return false;
		}
		finally{
			unset($pdo);
		}
	}
}

?>