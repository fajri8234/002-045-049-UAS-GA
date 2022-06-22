<?php

$koneksi = mysqli_connect("localhost", "root", "", "api_002_045_049");

if (mysqli_connect_errno()) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
