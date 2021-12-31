<?php 
session_start();

// hentikan session ketika logout, sehingga user harus login ulang
$_SESSION = [];
session_unset();
session_destroy();

// hapus cookie ketika logout
// dengan cara setcookie yang key-nya sama ketika kita membuat cookie
// namun valuenya kosong, dan expired nya dimundurkan
setcookie('id', '', time()-3600); // param: key, value, expired
setcookie('key', '', time()-3600);

// tendang user ke halaman login
header("Location: login.php");

?>