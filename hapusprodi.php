<?php 

require 'function.php';

$id = $_GET["id"];

$query = "UPDATE prodi SET status='off' WHERE id_prodi=$id";
if (hapus($id, $query) > 0) { // jika data berhasil dihapus
	echo "
		<script>
			alert('data berhasil dihapus!');
			document.location.href = 'prodi.php';
		</script>
		";
} else {
	echo "
		<script>
			alert('data gagal dihapus!');
			document.location.href = 'prodi.php';
		</script>
		";
}

?>