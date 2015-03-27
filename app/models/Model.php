<?php

class Model extends Nette\Object
{
	/** @var Nette\Database\Table\Selection */
	private $db;

	public function __construct(Nette\Database\Connection $db)
	{
		$this->db = $db;
	}

	public function getDataSource($table){
		$dataSource = $this->db->table($table);
		return $dataSource;
	}
	
	public function getDb() {
	    return $this->db;
	}
}
