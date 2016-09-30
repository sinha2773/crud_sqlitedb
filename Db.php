<?php
class Db {

	public $db;

	public function __construct($db){
		$this->db = new SQLite3($db);
		$this->init();
	}

	private function init(){
		//$this->dropStudentTable();
		$this->createStudentTable();
	}


	public function createStudentTable(){
		return $this->db->exec('CREATE TABLE IF NOT EXISTS students (name STRING, email STRING)');
	}

	public function dropStudentTable(){
		return $this->db->exec('DROP TABLE students');
	}

	public function insert($name, $email){
		return $this->db->exec("INSERT INTO students (name, email) VALUES ('$name', '$email')");
	}

	public function update($id, $name, $email){
		return $this->db->query("UPDATE students set name='$name', email='$email' WHERE rowid=$id");
	}

	public function delete($id){
		return $this->db->query("DELETE FROM students WHERE rowid=$id");
	}

	public function getAll(){
		return $this->db->query("SELECT rowid, * FROM students");
	}

	public function getById($id){
		return $this->db->query("SELECT rowid, * FROM students WHERE rowid=$id");
	}
}

?>
