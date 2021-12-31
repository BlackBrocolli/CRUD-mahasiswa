<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

$nrp = '';
$nama = '';
$pro = '';
$dw = '';

// ambil data prodi dan dosen
$prodi = tampil("SELECT id_prodi, nama_prodi FROM prodi WHERE status='on'");
$dosen = tampil("SELECT id_dosen, nama_dosen FROM dosen WHERE status='on'");

// cek apakah tombol tambah data ditekan
if (isset($_POST['btn-tambah'])) {

	if ($_POST['prodi'] == "novalue" || $_POST['dosenwali'] == "novalue") {

		// cek apakah prodi dan dosen wali sudah dipilih
		if ($_POST['prodi'] == "novalue") {
			$prodikosong = true;
		} 

		if ($_POST['dosenwali'] == "novalue") {
			$dosenkosong = true;
		}

		$nrp = $_POST['nrp'];
		$nama= $_POST['nama'];
		$pro = $_POST['prodi'];
		$dw = $_POST['dosenwali'];

	} else { // jika semua data sudah terisi

		// masukkan data yang dikirim ke variabel data
		$data = $_POST;
		$nrp = htmlspecialchars($data["nrp"]);
		$nama = htmlspecialchars($data["nama"]);
		$idprodi = htmlspecialchars($data["prodi"]);
		$iddosen = htmlspecialchars($data["dosenwali"]);

		// cek apakah ada nrp yang sama
		$query = "SELECT * FROM mahasiswa WHERE nrp='$nrp'";
		$result = $conn->query($query);

		// jika tidak ada, insert data
		if ($result->rowcount() == 0) {

			// prepare sql dan bind parameters
			$pst = $conn->prepare("INSERT INTO mahasiswa VALUES ('', :prodi, :dosen, :nrp, :nama)");
			$pst->bindParam(':prodi', $idprodi);
			$pst->bindParam(':dosen', $iddosen);
			$pst->bindParam(':nrp', $nrp);
			$pst->bindParam(':nama', $nama);

			// execute query
			if ($pst->execute()) {
				echo "
				<script>
					alert('data berhasil diinsert!');
					document.location.href = 'index.php';
				</script>
				";
			} else {
				echo "
				<script>
					alert('data gagal diinsert!');
					document.location.href = 'tambah.php';
				</script>
				";
			}
		} else {
			$nrpinvalid = true;
			$pro = $_POST['prodi'];
			$dw = $_POST['dosenwali'];
		}
	
	}
	
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Halaman Mahasiswa</title>
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
				<div class="mahasiswa active">
					<a href="index.php">Mahasiswa</a>
				</div>
				<div class="prodi">
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
				<h2 class="datamahasiswa">Tambah Data Mahasiswa</h2>
				
				<form action="" method="post">

					<div class="input">

						<div class="input-group">
							<label for="nrp">NRP</label>
							<input type="text" name="nrp" id="nrp" required autocomplete="off" value="<?= $nrp; ?>" <?= (isset($nrpinvalid)) ? 'class="nrp-invalidborder"' : 'class=""' ?>>
							<?php if (isset($nrpinvalid)) : ?>
								<label class="nrp-invalid">NRP sudah ada!</label>
							<?php endif; ?>
						</div>
						
						<div class="input-group">
							<label for="nama">Nama</label>
							<input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $nama; ?>">
						</div>
						
						<div class="input-group">
							<label for="prodi">Prodi</label>
							<select name="prodi" id="prodi" required <?= (isset($prodikosong)) ? 'class="error"' : 'class=""' ?>
							onfocusout="myFunction1()">
								<option value="novalue">--Pilih Prodi--</option>

								<!-- masukkan nama prodi ke combo box prodi -->
								<?php foreach ($prodi as $row) : ?>
									<option value="<?= $row['id_prodi']; ?>" <?= ($row['id_prodi'] == $pro) ? 'selected' : '' ?>><?= $row['nama_prodi']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						
						<div class="input-group">
							<label for="dosenwali">Dosen wali</label>
							<select name="dosenwali" id="dosenwali" required <?= (isset($dosenkosong)) ? 'class="error"' : 'class=""' ?>onfocusout="myFunction2()">
								<option value="novalue">--Pilih Dosen Wali--</option>

								<!-- masukkan nama dosen ke combo box dosen -->
								<?php foreach ($dosen as $row) : ?>
									<option value="<?= $row['id_dosen']; ?>" <?= ($row['id_dosen'] == $dw) ? 'selected' : '' ?>><?= $row['nama_dosen']; ?></option>
								<?php endforeach; ?>
								
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
function myFunction1() {
  var prodi = document.getElementById("prodi");
  prodi.classList.add('lostfocus');
}

function myFunction2() {
  var dosenwali = document.getElementById("dosenwali");
  dosenwali.classList.add('lostfocus');
}
</script>
	
</body>
</html>