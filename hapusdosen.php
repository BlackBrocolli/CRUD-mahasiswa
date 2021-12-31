<?php 

require 'function.php';

$id = $_GET["id"];

$query = "UPDATE dosen SET status='off' WHERE id_dosen=$id";
if (hapus($id, $query) > 0) { // jika data berhasil dihapus
	echo "
		<script>
			alert('data berhasil dihapus!');
			document.location.href = 'dosen.php';
		</script>
		";
} else {
	echo "
		<script>
			alert('data gagal dihapus!');
			document.location.href = 'dosen.php';
		</script>
		";
}

?>