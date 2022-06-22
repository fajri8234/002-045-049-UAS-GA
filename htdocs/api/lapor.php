<?php
include 'koneksi.php';

//$id = $_POST['id'];
$nama = $_POST['nama'];
$latitude = $_POST['latitude'];
$longtitude = $_POST['longtitude'];

if ($nama != "" && $latitude != "" && $longtitude != "") {
	$sql = mysqli_query($konek, "insert into studio values (NULL,'$nama','$latitude','$longtitude', 'n')");
}

if ($sql) {
	echo "berhasil";
} else {
	echo "gagal";
}
