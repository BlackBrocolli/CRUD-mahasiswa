<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

$nama = '';
$nip = '';
$nidn = '';
$jk = '';

if (isset($_POST['btn-tambah'])) {
	
	$data = $_POST;
	$namadosen = htmlspecialchars($data['nama']);
	$nip = htmlspecialchars($data['nip']);
	$nidn = htmlspecialchars($data['nidn']);
	$jk = htmlspecialchars($data['jk']);

	// cek apakah ada NIP atau NIDN yang sama
	$query = "SELECT * FROM dosen WHERE nip='$nip' OR nidn='$nidn'";
	$result = $conn->query($query);

	// jika tidak ada, insert data
	if ($result->rowcount() == 0) {

		$pst = $conn->prepare("INSERT INTO dosen VALUES ('', :namadosen, :nip, :nidn, :jk, 'on')");
		$pst->bindParam(':namadosen', $namadosen);
		$pst->bindParam(':nip', $nip);
		$pst->bindParam(':nidn', $nidn);
		$pst->bindParam(':jk', $jk);

		if ($pst->execute()) {
			echo "
			<script>
				alert('data berhasil diinsert!');
				document.location.href = 'dosen.php';
			</script>
			";
		} else {
			echo "
			<script>
				alert('data gagal diinsert!');
				document.location.href = 'tambahdosen.php';
			</script>
			";
		}
	} else {

		global $nip;
		global $nidn;

		// cek apakah NIP atau NIDN yang sama
		$query = "SELECT * FROM dosen WHERE nip='$nip'";
		$result = $conn->query($query);

		// jika NIP sudah ada
		if ($result->rowcount() > 0) {
			$nipinvalid = true;
		}

		$query = "SELECT * FROM dosen WHERE nidn='$nidn'";
		$result = $conn->query($query);

		// jika NIDN sudah ada
		if ($result->rowcount() > 0) {
			$nidninvalid = true;
		}

		$nama = $_POST['nama'];
		$nip = $_POST['nip'];
		$nidn = $_POST['nidn'];
		$jk = $_POST['jk'];

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
				<h2 class="datamahasiswa">Tambah Data Dosen</h2>
				
				<form action="" method="post">

					<div class="input">
						
						<div class="input-group">
							<label for="nama">Nama Dosen</label>
							<input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $nama; ?>">
						</div>

						<div class="input-group">
							<label for="nip">NIP</label>
							<input type="text" name="nip" id="nip" required autocomplete="off" value="<?= $nip; ?>">
							<?php if (isset($nipinvalid)) : ?>
								<label class="nrp-invalid">NIP sudah ada!</label>
							<?php endif; ?>
						</div>

						<div class="input-group">
							<label for="nidn">NIDN</label>
							<input type="text" name="nidn" id="nidn" required autocomplete="off" value="<?= $nidn; ?>">
							<?php if (isset($nidninvalid)) : ?>
								<label class="nrp-invalid">NIDN sudah ada!</label>
							<?php endif; ?>
						</div>
						
						<div class="input-group radio-btn">
							<label>Jenis Kelamin</label>
							<div class="radio-wrapper">
								<input type="radio" name="jk" value="Laki-laki" id="laki" class="radio" <?= ($jk == 'Laki-laki' || $jk == '') ? 'checked' : '' ?>><label for="laki">Laki-laki</label>
								<input type="radio" name="jk" value="Perempuan" id="perempuan" class="radio" <?= ($jk == 'Perempuan') ? 'checked' : '' ?>> <label for="perempuan">Perempuan</label>
							</div>
						</div>
						
						<button type="submit" name="btn-tambah">Tambah Data</button>

					</div>
							
				</form>

			</article>

		</main>
	</div>
	
</body>
</html>