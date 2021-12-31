<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

// panggil fungsi tampil
$data = tampil("SELECT * FROM prodi WHERE status='on'");

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
				<h2 class="datamahasiswa">Data Program Studi</h2>
				<a href="tambahprodi.php" class="tambah">Tambah Data</a>

				<table border="1" cellspacing="0" cellpadding="20">
		
					<tr>
						<th>No.</th>
						<th>Nama Prodi</th>
						<th>Jenjang</th>
						<th class="aksi">Aksi</th>
					</tr>

					<?php $nomor = 1; ?>
					<?php foreach ($data as $baris) : ?>
					<tr>
						<td><?= $nomor++; ?></td>
						<td><?= $baris['nama_prodi']; ?></td>
						<td><?= $baris['jenjang']; ?></td>
						<td>
							<a href="ubahprodi.php?id=<?= ($baris["id_prodi"]); ?>">Ubah</a> | 
							<a href="hapusprodi.php?id=<?= ($baris["id_prodi"]); ?>" onclick="return confirm('Are you sure you want to delete this data?');" class="btn-hapus">Hapus</a>
						</td>
					</tr>
					<?php endforeach; ?>

				</table>

			</article>

		</main>
	</div>

	
</body>
</html>