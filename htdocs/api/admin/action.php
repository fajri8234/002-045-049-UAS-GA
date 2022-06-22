<?php

require 'koneksi.php';

$id = $_GET['id'];

// JIKA MENEKAN TOMBOL VERIFIKASI
if (isset($_POST['verifikasi'])) {

    $verif = mysqli_query($koneksi, "UPDATE studio SET studio.verifikasi = 'y' WHERE studio.id = '$id'");

    //kembali ke index
    header('Location:index.php');
}

// JIKA MENEKAN TOMBOL TAMBAH LAPORAN
if (isset($_POST['tambah_laporan'])) {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = mysqli_query($koneksi, "INSERT INTO studio (nama, latitude, longitude, verifikasi) VALUES ('$nama', '$latitude', '$longitude', 'n')");

    header('Location:index.php');
}

// JIKA MENEKAN TOMBOL HAPUS LAPORAN
if (isset($_POST['hapus'])) {
    $delete = mysqli_query($koneksi, "DELETE FROM studio WHERE studio.id = '$id'");

    //kembali ke index
    header('Location:index.php');
}

//untuk menuju ke halaman ubah
if (isset($_POST['ubah'])) {
    header("Location:ubah_laporan.php?id=$id");
}

//JIKA MENEKAN TOMBOL UBAH LAPORAN
if (isset($_POST['ubah_laporan'])) {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $verifikasi = $_POST['verifikasi'];

    $sql = mysqli_query($koneksi, "UPDATE studio SET studio.nama = '$nama', studio.latitude = '$latitude', studio.longitude = '$longitude', studio.verifikasi = '$verifikasi' WHERE studio.id = '$id'");

    header('Location:index.php');
}
