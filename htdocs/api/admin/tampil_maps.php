<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pelaporan Kebakaran</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-ambulance"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Pelaporan Kebakaran</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-cog"></i>
                    <span>Daftar Laporan Kebakaran</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="tambah_laporan.php">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Tambah Laporan</span>
                </a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item bg-primary active">
                <a class="nav-link collapsed" href="tampil_maps.php">
                    <i class="fas fa-map"></i>
                    <span class=" font-weight-bold"> Map Titik Kebakaran</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>



                    </ul>

                </nav>

                <!-- TAMPILAN MAP -->

                <?php

                require 'koneksi.php';

                $sql = mysqli_query($koneksi, "SELECT * FROM studio WHERE studio.verifikasi = 'y'");

                $result = array();
                while ($row = mysqli_fetch_array($sql)) {
                    array_push($result, $row);
                }
                $totalVerif = count($result);

                //ambil total seluruh data.
                $dataSeluruh = mysqli_query($koneksi, "SELECT * FROM studio");
                $totalSemua = 0;
                while (mysqli_fetch_assoc($dataSeluruh)) {
                    $totalSemua++;
                }
                ?>
                <div class="col-lg-12">
                    <h2>Tampilan Maps Titik Kebakaran</h2>
                    <p>ada <b class="text-danger"><?php echo $totalVerif; ?></b> dari <b class="text-danger"><?php echo $totalSemua; ?> laporan</b> yang sudah diverifikasi</p>
                    <div id="googleMap" style="margin:auto;height:65vh;"></div>
                </div>

                <script>
                    // Initialize and add the map
                    function initMap() {
                        // The location of Jakarta
                        const jakarta = {
                            lat: -6.200000,
                            lng: 106.816666
                        };
                        // The map, centered at Jakarta

                        const map = new google.maps.Map(document.getElementById("googleMap"), {
                            zoom: 6,
                            center: jakarta,
                        });

                        <?php

                        for ($i = 0; $i < $totalVerif; $i++) { ?>
                            newMarker({
                                coords: {
                                    lat: <?php echo $result[$i]['latitude']; ?>,
                                    lng: <?php echo $result[$i]['longitude']; ?>
                                },
                                nameLocation: "<?php echo $result[$i]['nama']; ?>"
                            });
                        <?php
                        } ?>

                        function newMarker(latLong) {
                            var marker = new google.maps.Marker({
                                position: latLong.coords,
                                map: map,
                            });

                            //membuat label content tiap marker
                            if (latLong.nameLocation) {
                                var infoWindow = new google.maps.InfoWindow({
                                    content: latLong.nameLocation
                                });

                                marker.addListener('click', function() {
                                    infoWindow.open(map, marker);
                                });
                            }

                        }
                    }

                    window.initMap = initMap;
                </script>

                <script async defer src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtZd08p2_hIAB8Dx2cuH0Y1dohcGXfu4I&callback=initMap">
                </script>

                <!-- TAMPILAN MAP -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; UAS GIS TI6J 2022</span><br><br>
                            <p>1911500002, 1911500045, 1911500049</p>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>