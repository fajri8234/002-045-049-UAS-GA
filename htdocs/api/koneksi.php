<?php
	$konek = new mysqli("localhost","root","","api_002_045_049");

	if ($konek->connect_errno) {
		echo "Failed to connect o MySQL: " . $koneksi->coonection_error;
		exit();
	}
?>