<?php 
session_start();
require 'function.php';

// cek apakah ada session
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
}

$pro = '';
$dw = '';

// ambil data prodi dan dosen untuk combo box
$prodi = tampil("SELECT id_prodi, nama_prodi FROM prodi WHERE status='on'");
$dosen = tampil("SELECT id_dosen, nama_dosen FROM dosen WHERE status='on'");

// ambil nrp dari url
$id = $_GET['id'];

// ambil data dari tbl_mahasiswa sesuai nrp tadi
$query = "SELECT * FROM tbl_mahasiswa WHERE id_mahasiswa=$id";
$result = $conn->query($query);
$data = $result->fetch();

$nama = $data['nama_mahasiswa'];
$nrp = $data['nrp'];

if (isset($_POST['btn-tambah'])) {

	// utk cek apakah ada nrp yang sama
	$nrp2 = htmlspecialchars($_POST["nrp"]);
	$query = "SELECT * FROM mahasiswa WHERE nrp='$nrp2'";
	$result = $conn->query($query);

	// jika user malah memilih option paling atas dari combobox (tidak ada value)
	if ($_POST['prodi'] == "novalue" || $_POST['dosenwali'] == "novalue" || $result->rowcount() > 0 && $nrp !== $_POST['nrp']) {

		// cek apakah prodi dan dosen wali sudah dipilih
		if ($_POST['prodi'] == "novalue") {
			$prodikosong = true;
		} 

		if ($_POST['dosenwali'] == "novalue") {
			$dosenkosong = true;
		}

		if ($result->rowcount() > 0 ) {
			$nrpinvalid = true;
			$pro = $_POST['prodi'];
			$dw = $_POST['dosenwali'];
		}

		$nrp = $_POST['nrp'];
		$nama= $_POST['nama'];

	} else {

		// pindahkan data dari $_POST ke variabel
		$data = $_POST;
		$nrp = htmlspecialchars($data['nrp']);
		$nama = htmlspecialchars($data["nama"]);
		$idprodi = htmlspecialchars($data["prodi"]);
		$iddosen = htmlspecialchars($data["dosenwali"]);

		// prepare sql dan bind parameters
		$pst = $conn->prepare("UPDATE mahasiswa SET id_prodi=:id_prodi, id_dosen=:id_dosen, nrp=:nrp, nama_mahasiswa=:nama_mahasiswa WHERE id_mahasiswa=:id_mahasiswa");
		$pst->bindParam(':id_prodi', $idprodi);
		$pst->bindParam(':id_dosen', $iddosen);
		$pst->bindParam(':nrp', $nrp);
		$pst->bindParam(':nama_mahasiswa', $nama);
		$pst->bindParam(':id_mahasiswa', $id);

		// execute query
		if ($pst->execute()) {
			echo "
			<script>
				alert('data berhasil diupdate!');
				document.location.href = 'index.php';
			</script>
			";
		} else {
			echo "
			<script>
				alert('data gagal diupdate!');
				document.location.href = 'ubah.php';
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
				<h2 class="datamahasiswa">Ubah Data Mahasiswa</h2>
				
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
									<option value="<?= $row['id_prodi']; ?>" <?= ($row['nama_prodi'] == $data['nama_prodi']) ? 'selected' : '' ?> <?= ($row['id_prodi'] == $pro) ? 'selected' : '' ?>><?= $row['nama_prodi']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						
						<div class="input-group">
							<label for="dosenwali">Dosen wali</label>
							<select name="dosenwali" id="dosenwali" required <?= (isset($dosenkosong)) ? 'class="error"' : 'class=""' ?>onfocusout="myFunction2()">
								<option value="novalue">--Pilih Dosen Wali--</option>

								<!-- masukkan nama dosen ke combo box dosen -->
								<?php foreach ($dosen as $row) : ?>
									<option value="<?= $row['id_dosen']; ?>" <?= ($row['nama_dosen'] == $data['nama_dosen']) ? 'selected' : '' ?> <?= ($row['id_dosen'] == $dw) ? 'selected' : '' ?>><?= $row['nama_dosen']; ?></option>
								<?php endforeach; ?>
								
							</select>
						</div>
						
						<button type="submit" name="btn-tambah">Ubah Data</button>

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