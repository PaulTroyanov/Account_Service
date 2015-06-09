<?php
	

	// Standart funnction that connects to MySQL database;
	function getConnect() {							
		$config = parse_ini_file('config/db.ini');
		$db = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['user'], $config['password']);
		
		return $db;
	}


	// Function that return an array of keys with "IS_MARKED" = false;
	function getKeys($db) {							
		$keys = array();
		$query = $db->prepare("SELECT * FROM keys WHERE is_marked = false");
		$query->execute();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$keys[] = $row;
		}
		
		return $keys;
	}


	// Function that update 'is_marked' to 'true'
	// and create connection between user 
	// and key.
	function markKey($key, $user_id, $db) {												
		$query = $db->prepare("UPDATE keys SET is_marked = true WHERE key = :key");
		$query->bindParam(':key', $key, PDO::PARAM_STR);
		
		$query->execute();

		$query = $db->prepare("INSERT INTO keys (user_id) VALUES (:user_id)");
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		
		$query->execute();
	}


	// Function that compare key from html form with NySQL keys. $key --> string;
	function keyValidation($key) {				
		$allKeys = getKeys(getConnect());			// returns all keys. getConnect() func. that connect to MySQL database;
		if(array_key_exists($key, $allKeys)) {
//			markKey($key, $user_id, getConnect());
			return true;
		}

		return false;
	}