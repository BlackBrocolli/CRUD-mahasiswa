<?php 

	try {
		// koneksi ke db
		$conn = new PDO('mysql:host=localhost;dbname=db_pw1_mahasiswa', 'root', '');
		// PDO::setAttribute
		// manual -> https://www.php.net/manual/en/pdo.setattribute.php
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	} catch (PDOException $e) { // penanganan exception
		echo "Connection error: ".$e->getMessage();
	}

	function tampil($query) {
		global $conn;
		// operasi SELECT
		// jalankan query
		$result = $conn->query($query);
		// ambil data dari result
		$data = $result->fetchAll();

		return $data;
	}

	function hapus($id, $query) {
		global $conn;

		$result = $conn->query($query);

		return $result->rowcount();
	}

 ?>