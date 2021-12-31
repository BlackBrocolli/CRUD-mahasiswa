<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

// ambil id_prodi dari url
$id_prodi = $_GET['id'];

// ambil data prodi sesuai id yang dikirim
$query = "SELECT * FROM prodi WHERE id_prodi=$id_prodi";
$result = $conn->query($query);
$data = $result->fetch();

// buat variabel utk menambuh jenjang
// variabel ini digunakan utk menentukan option mana selected di combobox
$selected_jenjang = $data['jenjang'];

// cek apakah tombol ubah data ditekan
if (isset($_POST['btn-ubah'])) {
	
	if ($_POST['jenjang'] == "novalue") {

		$jenjangkosong = true;

		$nama= $_POST['nama'];

	} else {

		// pindahkan data dari $_POST ke variabel
		$data = $_POST;
		$nama_prodi = htmlspecialchars($data['nama']);
		$jenjang = htmlspecialchars($data['jenjang']);

		// prepare sql dan bind parameters
		$pst = $conn->prepare("UPDATE prodi SET nama_prodi=:nama_prodi, jenjang=:jenjang WHERE id_prodi=:id_prodi");
		$pst->bindParam(':nama_prodi', $nama_prodi);
		$pst->bindParam(':jenjang', $jenjang);
		$pst->bindParam(':id_prodi', $id_prodi);

		// execute query
		if ($pst->execute()) {
			echo "
			<script>
				alert('data berhasil diupdate!');
				document.location.href = 'prodi.php';
			</script>
			";
		} else {
			echo "
			<script>
				alert('data gagal diupdate!');
				document.location.href = 'ubahprodi.php';
			</script>
			";
		}
		
	}

	

}





?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Halaman Prodi</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

	<div class="container">
		<header>
			<h2>CRUD Mahasiswa</h2>
			<div class="profil">
				<p><?= $_SESSION['nama']; ?></p>
				<img src="img/profile.png" alt="foto-profil">
			</div>
		</header>

		<main>

			<nav>
				<div class="mahasiswa">
					<a href="index.php">Mahasiswa</a>
				</div>
				<div class="prodi active">
					<a href="prodi.php">Prodi</a>
				</div>
				<div class="dosen">
					<a href="dosen.php">Dosen</a>
				</div>
				<div class="logout">
					<a href="logout.php">Logout!</a>
				</div>
			</nav>

			<article class="article">
				<h2 class="datamahasiswa">Ubah Data Prodi</h2>
				
				<form action="" method="post">

					<div class="input">
						
						<div class="input-group">
							<label for="nama">Nama Prodi</label>
							<input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $data['nama_prodi']; ?>">
						</div>
						
						<div class="input-group">
							<label for="jenjang">Jenjang</label>
							<select name="jenjang" id="jenjang" required <?= (isset($jenjangkosong)) ? 'class="error"' : 'class=""' ?>
							onfocusout="myFunction()">
								<option value="novalue">--Pilih Jenjang--</option>
								<option value="S1" <?= ($selected_jenjang == 'S1') ? 'selected' : '' ?>>S1</option>
								<option value="S2" <?= ($selected_jenjang == 'S2') ? 'selected' : '' ?>>S2</option>
								<option value="S3" <?= ($selected_jenjang == 'S3') ? 'selected' : '' ?>>S3</option>
								<option value="D3" <?= ($selected_jenjang == 'D3') ? 'selected' : '' ?>>D3</option>
								<option value="D4" <?= ($selected_jenjang == 'D4') ? 'selected' : '' ?>>D4</option>
							</select>
						</div>
						
						<button type="submit" name="btn-ubah">Ubah Data</button>

					</div>
							
				</form>

			</article>

		</main>
	</div>

<!-- script untuk menghilangkan warna merah pada combobox -->
<script>
function myFunction() {
  var jenjang = document.getElementById("jenjang");
  jenjang.classList.add('lostfocus');
}

</script>
	
</body>
</html>