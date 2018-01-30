<?php
/**
 * Database Manipulation
 */

namespace DareDev;


class DataBase {

	/*
	 * Import Data from a CSV file to a specific table in your database.
	 *
	 * Simple usage:
	 * $mydata    = new \DareDev\DataBase( $file, $table );
	 * $mydata->updateData();
	 *
	 * Detailed:
	 * $mydata    = new \DareDev\DataBase( $file, $table );
	 * if($mydata->result === 'success') {
	 * $mydata->updateData();
	 * } else {
	 * echo 'Something went wrong';
	 * }
	 *
	 */
	public $file;
	public $table;

	public function __construct( $file = '', $table = '' ) {
		global $wpdb;
		$this->wpdb   = $wpdb;
		$this->file   = $file;
		$this->table  = $table;
		$this->result = self::validation();
	}

	/*
	 * Update Data.
	 * It clears the table and
	 * imports the new data
	 */
	public function updateData() {
		self::clearTable();
		self::importData();
	}

	/*
	 * Clear table.
	 * Deletes all entries
	 * from the table.
	 */
	public function clearTable() {
		$this->wpdb->query(
			"TRUNCATE TABLE $this->table"
		);
	}

	/*
	 * Import data.
	 * Imports the file's data
	 * to the table.
	 */
	public function importData() {
		$this->wpdb->query( $this->wpdb->prepare(
			"	
				LOAD DATA LOCAL INFILE '%s' INTO TABLE $this->table
				CHARACTER SET UTF8
				FIELDS TERMINATED BY ','
				IGNORE 1 LINES;
				",
			$this->file
		) );
	}

	/*
	 * Some validation.
	 * If $file parameter is set and file is uploaded
	 * and if table is set and exists in the database,
	 * return "success"
	 */
	private function validation() {
		if ( isset( $this->file ) && isset( $this->table ) && $this->wpdb->get_var( "SHOW TABLES LIKE '$this->table'" ) === $this->table && ! empty( $this->file ) ) {
			$result = 'success';
		} elseif ( empty( $this->table ) || $this->wpdb->get_var( "SHOW TABLES LIKE '$this->table'" ) !== $this->table ) {
			$result = 'no_table';
		} elseif ( empty( $this->file ) || ! isset( $this->file ) ) {
			$result = 'no_file';
		} else {
			$result = 'error';
		}

		return $result;
	}

}