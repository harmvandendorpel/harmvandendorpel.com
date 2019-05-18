<?php
	date_default_timezone_set('Europe/Berlin');
 
	function displayResult($result) {
		foreach ($result as $row) {
			$caption = $row['title'];
			$perma = $row['perma'];
			$time = $row['time'];
			$rowid = $row['rowid'];
			echo "<a href='/$perma'>$caption</a> $rowid";
			echo "<br>";
		}
	}
 
	try {
		$file_db = new PDO('sqlite:data/harmvandendorpel.sqlite3');
		$file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$import_data = json_decode(file_get_contents("_/work.json"), true)['content'];
		$file_db->exec("Create TABLE IF NOT EXISTS items (
						id INTEGER PRIMARY KEY,
						title TEXT,
						link TEXT,
						cat TEXT,
						date TEXT,
						location TEXT,
						perma TEXT,
						descr TEXT,
						private INTEGER,
						indexPic INTEGER,
						tags TEXT,
						time TEXT)");
 
		$items = $import_data;
		$insert = "INSERT INTO items (title, descr, time, perma)
				   VALUES (:title, :descr, :time, :perma)";
 
		$stmt = $file_db->prepare($insert);
 
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':descr', $descr);
		$stmt->bindParam(':time', $time);
		$stmt->bindParam(':perma', $perma);
 
		foreach ($items as $m) {
			$title = $m['title'];
			$descr = $m['descr'];
			$time = $m['time'];
 			$perma = $m['perma'];
			$stmt->execute();
		}
 
		$result = $file_db->query('SELECT * FROM items');
 
		displayResult($result);
 
		$update = "UPDATE items SET time = :time
				   WHERE id == :id";
		$stmt = $file_db->prepare($update);
 
		$result = $file_db->query('SELECT * FROM items');
 
		foreach ($result as $m) {
			$stmt->bindValue(':id', $m['id'], SQLITE3_INTEGER);
 
			$formatted_time = date('Y-m-d H:i:s', $m['time']);
			$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);
			$stmt->execute();
		}
 
		$result = $file_db->query('SELECT * FROM items');
 
		displayResult($result);
 
		$file_db->exec("DROP TABLE items");
 
		$file_db = null;
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
