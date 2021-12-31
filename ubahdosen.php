<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

// ambil id yang dikirim
$id_dosen = $_GET['id'];

// ambil data dosen sesuai id yang dikirim
$query = "SELECT * FROM dosen WHERE id_dosen=$id_dosen";
$result = $conn->query($query);
$data = $result->fetch();

// menentukan radio button yang checked
if ($data['jk'] == "Laki-laki") {
	$laki = true;
}

// cek apakah tombol ubah data ditekan
if (isset($_POST['btn-ubah'])) {

	// pindahkan data dari $_POST ke variabel
	$data = $_POST;
	$namadosen = htmlspecialchars($data['nama']);
	$nip = htmlspecialchars($data['nip']);
	$nidn = htmlspecialchars($data['nidn']);
	$jk = htmlspecialchars($data['jk']);

	// prepare sql dan bind parameters
	$pst = $conn->prepare("UPDATE dosen SET nama_dosen=:nama_dosen, nip=:nip, nidn=:nidn, jk=:jk WHERE id_dosen=:id_dosen");
	$pst->bindParam(':nama_dosen', $namadosen);
	$pst->bindParam(':nip', $nip);
	$pst->bindParam(':nidn', $nidn);
	$pst->bindParam(':jk', $jk);
	$pst->bindParam(':id_dosen', $id_dosen);

	// execute query
	if ($pst->execute()) {
		echo "
		<script>
			alert('data berhasil diupdate!');
			document.location.href = 'dosen.php';
		</script>
		";
	} else {
		echo "
		<script>
			alert('data gagal diupdate!');
			document.location.href = 'ubahdosen.php';
		</script>
		";
	}
}




?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Halaman Dosen</title>
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
				<div class="prodi">
					<a href="prodi.php">Prodi</a>
				</div>
				<div class="dosen active">
					<a href="dosen.php">Dosen</a>
				</div>
				<div class="logout">
					<a href="logout.php">Logout!</a>
				</div>
			</nav>

			<article class="article">
				<h2 class="datamahasiswa">Ubah Data Dosen</h2>
				
				<form action="" method="post">

					<div class="input">
						
						<div class="input-group">
							<label for="nama">Nama Dosen</label>
							<input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $data['nama_dosen']; ?>">
						</div>

						<div class="input-group">
							<label for="nip">NIP</label>
							<input type="text" name="nip" id="nip" required autocomplete="off" value="<?= $data['nip']; ?>">
						</div>

						<div class="input-group">
							<label for="nidn">NIDN</label>
							<input type="text" name="nidn" id="nidn" required autocomplete="off" value="<?= $data['nidn']; ?>">
						</div>
						
						<div class="input-group radio-btn">
							<label>Jenis Kelamin</label>
							<div class="radio-wrapper">
								<input type="radio" name="jk" value="Laki-laki" id="laki" class="radio" <?= (isset($laki)) ? 'checked' : '' ?>>
								<label for="laki">Laki-laki</label>
								<input type="radio" name="jk" value="Perempuan" id="perempuan" class="radio" <?= (isset($laki)) ? '' : 'checked' ?>> 
								<label for="perempuan">Perempuan</label>
							</div>
						</div>
						
						<button type="submit" name="btn-ubah">Ubah Data</button>

					</div>
							
				</form>

			</article>

		</main>
	</div>
	
</body>
</html>