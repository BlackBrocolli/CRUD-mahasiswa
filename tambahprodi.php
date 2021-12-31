<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

$nama = '';
$jenjang = '';

// cek apakah tombol tambah data ditekan
if (isset($_POST['btn-tambah'])) {

	if ($_POST['jenjang'] == "novalue") {

		$jenjangkosong = true;

		$nama= $_POST['nama'];
		
	} else {

		// masukkan inputan ke variabel data
		$data = $_POST;
		$namaprodi = htmlspecialchars($data['nama']);
		$jenjang = htmlspecialchars($data['jenjang']);

		// cek apakah ada prodi yang sama
		$query = "SELECT * FROM prodi WHERE nama_prodi='$namaprodi'";
		$result = $conn->query($query);

		// jika tidak ada, insert data
		if ($result->rowcount() == 0) {
			// prepare sql dan binding parameters
			$pst = $conn->prepare("INSERT INTO prodi VALUES ('', :namaprodi, :jenjang, 'on')");
			$pst->bindParam(':namaprodi', $namaprodi);
			$pst->bindParam(':jenjang', $jenjang);

			// execute query
			if ($pst->execute()) {
				echo "
				<script>
					alert('data berhasil diinsert!');
					document.location.href = 'prodi.php';
				</script>
				";
			} else {
				echo "
				<script>
					alert('data gagal diinsert!');
					document.location.href = 'tambahprodi.php';
				</script>
				";
			}
		} else {

			// jika prodi sudah ada
			$prodiinvalid = true;
			$nama= $_POST['nama'];
			$jenjang = $_POST['jenjang'];
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
				<h2 class="datamahasiswa">Tambah Data Prodi</h2>
				
				<form action="" method="post">

					<div class="input">
						
						<div class="input-group">
							<label for="nama">Nama Prodi</label>
							<input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $nama; ?>">
							<?php if (isset($prodiinvalid)) : ?>
								<label class="nrp-invalid">Prodi sudah ada!</label>
							<?php endif; ?>
						</div>
						
						<div class="input-group">
							<label for="jenjang">Jenjang</label>
							<select name="jenjang" id="jenjang" required <?= (isset($jenjangkosong)) ? 'class="error"' : 'class=""' ?>
							onfocusout="myFunction()">
								<option value="novalue">--Pilih Jenjang--</option>
								<option value="S1" <?= ($jenjang == 'S1') ? 'selected' : '' ?>>S1</option>
								<option value="S2" <?= ($jenjang == 'S2') ? 'selected' : '' ?>>S2</option>
								<option value="S3" <?= ($jenjang == 'S3') ? 'selected' : '' ?>>S3</option>
								<option value="D3" <?= ($jenjang == 'D3') ? 'selected' : '' ?>>D3</option>
								<option value="D4" <?= ($jenjang == 'D4') ? 'selected' : '' ?>>D4</option>
							</select>
						</div>
						
						<button type="submit" name="btn-tambah">Tambah Data</button>

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