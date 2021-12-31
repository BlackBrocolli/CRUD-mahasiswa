<?php 
session_start();

require 'function.php';

// cek cookie sbelum cek session
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) { 

	// masukkan cookie ke variabel
	$id = $_COOKIE['id'];
	$key = $_COOKIE['key'];

 	// siapkan dan jalankan query
	$query = "SELECT username, nama FROM user WHERE id='$id'";
	$result = $conn->query($query); // execute
	$row = $result->fetch(); // ambil data

 	// cek cookie dan username
 	if ($key === hash('sha256', $row['username'])) { // jika cookie $key sama dengan hash dari data username
 		
 		// jika cookie ada, set session
 		// sehingga user tidak perlu login ulang
 		$_SESSION['login'] = true;
 		$_SESSION['nama'] = $row['nama'];
 	}
}

// cek apakah ada session
if (isset($_SESSION['login'])) {
	header("Location: index.php");
	exit;
}

$username = '';

// cek apakah tombol login sudah ditekan
if (isset($_POST["login"])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	// siapkan dan jalankan query
	$query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($query);
	$row = $result->fetch();
	
	$rowaffected = $result->rowcount();

	// jika data user ada
	if ($rowaffected == 1) {
		$_SESSION['login'] = true;
		$_SESSION['nama'] = $row['nama'];

		// cek remember me
		if (isset($_POST["remember"])) { // jika checkbox dicentang

			// buat cookie
			setcookie('id', $row['id'], time()+3600); // buat cookie id, untuk 3600detik
			setcookie('key', hash('sha256', $row['username']), time()+3600); // buat cookie username yang diacak
		}

		header("Location: index.php");
		exit;
	} 

	// jika data user tidak ada
	$error = true;
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="css/login.css">
 	<title>Login Page</title>
</head>
<body>
	<div class="login-wrapper">
		<form action="" method="post" class="form">
			<img src="img/avatar.png" alt="avatar">
			<h2>Login</h2>

			<?php if (isset($error)): ?>
			<div class="validation">
				<label>Invalid username or password!</label>
			</div>
			<?php endif ?>
			
			<div class="input-group">
				<input type="text" name="username" id="loginUser" value="<?= $username;?>" required autocomplete="off">
				<label for="loginUser">Username</label>
			</div>
			<div class="input-group">
				<input type="password" name="password" id="loginPassword" required autocomplete="off"
				<?= (isset($error)) ? 'autofocus' : '' ?>>
				<label for="loginPassword">Password</label>
			</div>
			<input type="checkbox" name="remember" id="remember" class="remember">
			<label for="remember">Remember me</label>
			<input type="submit" value="Login" class="submit-btn" name="login">
		</form>

	</div>
</body>
</html>