<?php 
    session_start();
    // include "logged_user_check.php";
    include_once "db_conn.php";
    include_once "utilities/util.php";
    include_once "utilities/alert_handler.php";
    include_once "utilities/session_handler.php";
    include_once "utilities/user_director_handler.php";
    include "logged_user_check.php";

    // if (isset($_GET['item_id'])){
    //     $queryString = "SELECT * FROM report WHERE ID=".$_GET['item_id']."";
        
    //     $result = $conn->query($queryString);

    //     $item = mysqli_fetch_assoc($result);

    //     $queryString = "SELECT * FROM user WHERE ID=".$_SESSION['ID'];

    //     $result = $conn->query($queryString);

    //     $user_reports_to = mysqli_fetch_assoc($result)['reports_to'];

    //     $queryString = "SELECT r.action_at as action_at, r.comment as comment, u.username as username, u.role as role, u.id as id FROM report_action r, user u where r.report_id=".$_GET['item_id']." and r.user = u.id ORDER BY action_at DESC";

    //     $result = $conn->query($queryString);

    //     $history = [];

    //     while($row = mysqli_fetch_assoc($result)){
    //         array_push($history, $row);
    //     }
    // }
    // else {
    //     echo "No item ID given";
    // }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandana Intranet | Manager Dashboard </title>
    <link rel="icon" href="asset/logo.png" type="image/x-icon">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <style>
        .main {
            color: rgb(30, 30, 60);
        }

        .bg-main {
            background-color: rgb(30, 30, 60);
        }

        a.nav-link, a.nav-link:hover{
            color: white;
        }

        a.nav-link.active {
            color: black;
        }

        .input-number {
            outline: none;
            border: none;
        }
    </style>
</head>
<body class="bg-main">
    <?php if(isset($_SESSION['ALERT'])) { ?>
        <div class="position-absolute end-0 me-3" style="margin-top: 84px;">
            <div class="alert alert-<?= $_SESSION['ALERT']['TYPE'] ?> mb-0 p-2 fade show" role="alert">
                <?= $_SESSION['ALERT']['MESSAGE'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 10px; height: 10px;"></button>
            </div>
        </div>
    <?php } unsetAlert(); ?>
    <section id="section-header">
        <nav class="navbar navbar-expand-lg bg-body-tertiary p-3 d-flex justify-content-between">
            <button id="btn-create-report" class="btn btn-primary <?= $_SESSION['ROLE'] == "director" ? "invisible" : "" ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal-create-report">Buat Laporan</button>
            <?php if($_SESSION['ROLE'] != "director") { ?>
                <form method="GET" action="manage_atasan_test.php" class="mb-0">
                    <button type="submit" class="btn btn-danger" >Edit Atasan</button>
                </form>
                <?php } else { ?>
                    <?php } ?>
           
            <div class="modal fade" role="dialog" tabindex="-1" id="modal-create-report">
                <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Buat Laporan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="form-create-report" action="create_report.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="fw-bold">Proyek</label>
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-1">
                                                    <label class="form-check-label" for="input-check-proyek-1">Alkes - Radiologi</label>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-2">
                                                    <label class="form-check-label" for="input-check-proyek-2">Alkes - Non Radiologi</label>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-3">
                                                    <label class="form-check-label" for="input-check-proyek-3">Nurse Call - NC</label>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-4">
                                                    <label class="form-check-label" for="input-check-proyek-4">IGM</label>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-5">
                                                    <label class="form-check-label" for="input-check-proyek-5">PTS</label>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-6">
                                                    <label class="form-check-label" for="input-check-proyek-6">MOT</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input" type="checkbox" name="input-proyek" id="input-check-proyek-7">
                                                    <input type="text" class="ms-2 form-control" placeholder="lainnya">
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(isset($_SESSION['ERROR']['input-proyek'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-proyek'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="input-location" class="form-label mb-0 fw-bold">Instansi</label>
                                        <input type="text" class="form-control" id="input-location" name="input-location" required>
                                        <?php if(isset($_SESSION['ERROR']['input-proyek'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-proyek'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="input-city" class="form-label mb-0 fw-bold">Kota Kunjungan</label>
                                        <input type="text" class="form-control" id="input-city" name="input-city" required>
                                        <?php if(isset($_SESSION['ERROR']['input-city'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-city'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label for="input-visit" class="form-label mb-0 fw-bold">Kunjungan Ke</label>
                                        <input type="number" class="form-control" id="input-visit" name="input-visit" step="1" required>
                                        <?php if(isset($_SESSION['ERROR']['input-visit'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-visit'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-5 mb-3">
                                        <label for="input-prospect" class="form-label mb-0 fw-bold">Nilai Prospek</label>
                                        <div class="form-control d-flex align-items-center justify-content-between">
                                            <p class="mb-0 me-3">Rp</p>
                                            <input type="text" class="input-number input-thousand-separator w-100" id="input-prospect" required>
                                            <?php if(isset($_SESSION['ERROR']['input-prospect'])) { ?>
                                                <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                    <?= $_SESSION['ERROR']['input-prospect'] ?>
                                                </div>
                                            <?php } ?>
                                            <input type="hidden" name="input-prospect">
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label for="input-opportunity" class="form-label mb-0 fw-bold">Peluang</label>
                                        <div class="form-control d-flex align-items-center justify-content-between">
                                            <input type="number" class="input-number w-100" id="input-opportunity" name="input-opportunity" max="100" min="0">
                                            <p class="mb-0">%</p>
                                        </div>
                                        <?php if(isset($_SESSION['ERROR']['input-opportunity'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-opportunity'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="input-competitor" class="form-label mb-0 fw-bold">Pesaing / Kandidat Pesaing</label>
                                        <input type="text" class="form-control" id="input-competitor" name="input-competitor">
                                        <?php if(isset($_SESSION['ERROR']['input-competitor'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-competitor'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="input-due" class="form-label mb-0 fw-bold">Dibutuhkan Kapan</label>
                                        <input type="date" class="form-control" id="input-due" name="input-due">
                                        <?php if(isset($_SESSION['ERROR']['input-due'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-due'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="input-note" class="form-label mb-0 fw-bold">Keterangan</label>
                                        <textarea name="input-note" class="form-control" id="input-note"></textarea>
                                        <?php if(isset($_SESSION['ERROR']['input-note'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-note'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="input-sales-note" class="form-label mb-0 fw-bold">Komentar untuk Management</label>
                                        <textarea name="input-sales-note" class="form-control" id="input-sales-note"></textarea>
                                        <?php if(isset($_SESSION['ERROR']['input-sales-note'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-sales-note'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="input-attachment" class="form-label mb-0 fw-bold">Lampiran</label>
                                        <input type="file" class="form-control" id="input-attachment" name="input-attachment[]" accept="image/png, image/jpg, image/jpeg, image/jfif" multiple>
                                        <?php if(isset($_SESSION['ERROR']['input-attachment'])) { ?>
                                            <div class="alert alert-danger mb-0 py-2 fade show" role="alert">
                                                <?= $_SESSION['ERROR']['input-attachment'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="input-latitude" id="input-latitude">
                            <input type="hidden" name="input-longitude" id="input-longitude">
                            <input type="hidden" name="input-project" id="input-project">
                            <div class="modal-footer text-end">
                                <button type="submit" class="btn btn-primary" id="btn-save-report">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <h5 class="me-3 mb-0">Masuk sebagai, <?= $_SESSION['USERNAME'] ?> !</h5>
                <form method="GET" action="logout.php">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </nav>
    </section>
    <section id="section-content">
        <div class="container">

            <ul role="tablist" class="nav nav-tabs position-relative border-bottom-0 mt-5">
                <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" href="#tab-unapproved-report" class="nav-link active">Dashboard</a></li>
                <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" href="#tab-report-history" class="nav-link">History</a></li>
                <?php if($_SESSION['ROLE'] == "director") { ?>
                    <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" href="#tab-director" class="nav-link">Direktur</a></li>
                <?php } else { ?>    
                    <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" href="#tab-my-unapproved-report" class="nav-link">Laporan Saya</a></li>
                    <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" href="#tab-my-evaluated-report" class="nav-link">Evaluasi</a></li>
                <?php } ?>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab-unapproved-report">
                    <div class="card" style="border-top-left-radius: 0;">
                        <div class="card-body">
                            <h2>Aktivitas Laporan</h2>
                            <hr>
                            <table class="table table-hover" id="table-unapproved-report">
                                <thead>
                                    <tr>
                                        <th>ID Laporan</th>
                                        <th>Tanggal Upload</th>
                                        <th>Pelapor</th>
                                        <th>Instansi</th>
                                        <th>Status</th>
                                        
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $statusReport = 0;
                                    

                                    if ($_SESSION['ROLE'] === "manager"){
                                        $statusReport = 6;
                                        $queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', u.username as 'username' FROM report r, user u WHERE r.report_by = u.id AND r.need_approval_by_2 = " . $_SESSION['ID'] . " AND r.status < " . $statusReport;
                                    }
                                    else if($_SESSION['ROLE'] === "gmanager"){
                                        $statusReport = 7;
                                        $queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', u.username as 'username' FROM report r, user u WHERE r.report_by = u.id AND r.need_approval_by_3 = " . $_SESSION['ID'] . " AND r.status < " . $statusReport;
                                    }
                                    else if($_SESSION['ROLE'] === "director"){
                                        $statusReport = 8;
                                        $queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', u.username as 'username' FROM report r, user u WHERE r.report_by = u.id AND r.need_approval_by = " . $_SESSION['ID'] . " AND r.status < " . $statusReport;
                                    }
                                    else if($_SESSION['ROLE'] === "supervisor"){
                                        $statusReport = 9;
                                        $queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', u.username as 'username' FROM report r, user u WHERE r.report_by = u.id AND r.need_approval_by = " . $_SESSION['ID'] . " AND r.status < " . $statusReport;
                                    }

                                    // $userapproval = "SELECT r.id as 'id', u.username as 'userapprov', r.need_approval_by as 'need_approval_by' FROM report r, user u WHERE r.need_approval_by = u.id";
                                    
                                    //$queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', u.username as 'username' FROM report r, user u WHERE r.report_by = u.id AND r.need_approval_by_2 = " . $_SESSION['ID']. " OR r.need_approval_by_3 = " . $_SESSION['ID'] . " AND r.status < " . $statusReport;
                                     //$queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', r.need_approval_by as 'need_approval_by', u.username as 'username', u.username as 'userapprov' FROM report r, user u WHERE r.report_by = u.id";
                                     //$queryString = "SELECT r.id as 'id', r.location as 'location', r.upload_at as 'upload_at', u.emp_id as 'emp_id', r.status as 'status', r.need_approval_by as 'need_approval_by', u.username as 'username', u.username as 'userapprov' FROM report r, user u WHEN r.report_by = u.id THEN r.need_approval_by = u.id"; 


                                    try{
                                        $result = $conn->query($queryString);
                                        // $approv = $conn->query($userapproval);
                                    }
                                    catch(Exception $e){
                                        echo $e;
                                    }

                                    while($row = mysqli_fetch_assoc($result)){
                                        // $rows = mysqli_fetch_assoc($approv);
                                ?>
                                    <tr>
                                        
                                        <td><?= $row['id'] ?></td>
                                        <td><?= date('d-M-Y', strtotime($row['upload_at'])) ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['location'] ?></td>
                                        <td><?= parseReportStatus($row['status']) ?></td>
                                        
                                        <td class="py-1 px-2 text-end">
                                            <form method="GET" action="detail_report.php">
                                                <input type="hidden" name="item_id" value=<?= $row['id'] ?>>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">Details</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab-report-history">
                    <div class="card">
                        <div class="card-body">
                            <h2>Riwayat Laporan</h2>
                            <hr>
                            <table class="table table-hover" id="table-report-history">
                                <thead>
                                    <tr>
                                        <th>ID Laporan</th>
                                        <th>Tanggal Upload</th>
                                        <th>Pelapor</th>
                                        <th>Instansi</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $queryString = "SELECT r.id as id, r.attachment as attachment, r.note as note, r.location as location, r.upload_at as upload_at, r.report_by as report_by, ra.type as status, u.username as username FROM report r, report_action ra, user u where r.id = ra.report_id and u.id = r.report_by and ra.user = ".$_SESSION['ID']." ORDER BY ra.action_at DESC";

                                    try{
                                        $result = $conn->query($queryString);
                                    }
                                    catch(Exception $e){
                                        echo $e;
                                    }
                                    
                                    $numericNum = 1;
                                    while($row = mysqli_fetch_assoc($result)){
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= date('d-M-Y', strtotime($row['upload_at'])) ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['location'] ?></td>
                                        <td>
                                            <?php 
                                                if ($row['status'] == 1) {
                                                    echo "Approved";
                                                } else if ($row['status'] == 0) {
                                                    echo "Rejected";
                                                }
                                            ?>
                                        </td>
                                        <td class="py-1">
                                            <form method="GET" action="detail_report.php">
                                                <input type="hidden" name="item_id" value=<?= $row['id'] ?>>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">Details</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php 
                                    $numericNum++;
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if($_SESSION['ROLE'] == "director") { ?>
                    <div role="tabpanel" class="tab-pane" id="tab-director">
                        <div class="card">
                            <div class="card-body">
                                <h2>Laporan Bawahan</h2>
                                <hr>
                                <table class="table table-hover" id="table-subordinate-report">
                                    <thead>
                                        <tr>
                                            <th>ID Laporan</th>
                                            <th>Tanggal Upload</th>
                                            <th>Pelapor</th>
                                            <th>Instansi</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach(fetchDirectorReports($_SESSION['ID'], $conn) as $row) { ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= date('d-M-Y', strtotime($row['upload_at'])) ?></td>
                                            <td><?= $row['username'] ?></td>
                                            <td><?= $row['location'] ?></td>
                                            <td class="py-1 text-end">
                                                <form method="GET" action="detail_report.php">
                                                    <input type="hidden" name="item_id" value=<?= $row['id'] ?>>
                                                    <button class="btn btn-sm btn-outline-primary" type="submit">Details</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div role="tabpanel" class="tab-pane" id="tab-my-unapproved-report">
                        <div class="card">
                            <div class="card-body">
                                <h2>Laporan saya yang belum di setujui</h2>
                                <hr>
                                <table class="table table-hover" id="table-my-unapproved-report">
                                    <thead>
                                            <tr>
                                                <th>ID Laporan</th>
                                                <th>Tanggal Upload</th>
                                                <th>Instansi</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $queryString = "SELECT * FROM report WHERE report_by = '".$_SESSION['ID']."' AND STATUS = 0";
                                            
                                            $result = $conn->query($queryString);

                                            while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= date('d-M-Y', strtotime($row['upload_at'])) ?></td>
                                                <td><?= $row['location'] ?></td>
                                                <td><?= parseReportStatus($row['status']) ?></td>
                                                <td class="py-1">
                                                    <div class="d-flex justify-content-end">
                                                        <form class="mb-0" method="GET" action="detail_report.php">
                                                            <input type="hidden" name="item_id" value=<?=$row['id']?>>
                                                            <button class="btn btn-sm btn-outline-primary" type="submit">Detail</button>
                                                        </form>
                                                        <form class="mb-0 ms-2" method="GET" action="delete_report.php">
                                                            <input type="hidden" name="item_id" value=<?=$row['id']?>>
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                            }
                                        ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab-my-evaluated-report">
                        <div class="card">
                            <div class="card-body">
                                <h2>Laporan saya yang sudah di evaluasi</h2>
                                <hr>
                                <table class="table table-hover" id="table-my-evaluated-report">
                                    <thead>
                                        <tr>
                                            <th>ID Laporan</th>
                                            <th>Tanggal Upload</th>
                                            <th>Instansi</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $queryString = "SELECT * FROM report WHERE report_by = '".$_SESSION['ID']."' AND STATUS <> 0";
                                        
                                        $result = $conn->query($queryString);

                                        while($row = mysqli_fetch_assoc($result)){
                                    ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= date('d-M-Y', strtotime($row['upload_at'])) ?></td>
                                            <td><?= $row['location'] ?></td>
                                            <td><?= parseReportStatus($row['status']) ?></td>
                                            <td class="py-1 text-end">
                                                <form class="mb-0" method="GET" action="detail_report.php">
                                                    <input type="hidden" name="item_id" value=<?=$row['id']?>>
                                                    <button class="btn btn-sm btn-outline-primary" type="submit">Detail</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</body>
</html>

<script>
    $(document).ready(function() {
        let tableUnapprovedReport = new DataTable('#table-unapproved-report',  {
            columns: [
                null,
                null,
                null,
                null,
                null,
        
                { orderable: false }
            ]
        });

        let tableReportHistory = new DataTable('#table-report-history',  {
            columns: [
                null,
                null,
                null,
                null,
                null,
                { orderable: false }
            ]
        });

        let tableSubordinateReport;
        if ($('#tab-director').length) {
            let tableSubordinateReport = new DataTable('#table-subordinate-report',  {
                columns: [
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });
        }

        if ($('#tab-my-unapproved-report').length) {
            let tableMyUnapprovedReport = new DataTable('#table-my-unapproved-report',  {
                columns: [
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });
        }

        if ($('#tab-my-unapproved-report').length) {
            let tableMyEvaluatedReport = new DataTable('#table-my-evaluated-report',  {
                columns: [
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });
        }

        $('#btn-create-report').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#input-latitude').val(position.coords.latitude);
                    $('#input-longitude').val(position.coords.longitude);
                });
            } else { 
                alert("Geolocation is not supported by this browser.");
            }
        });

        $('#btn-save-report').on('click', function() {
            let projects = '';
            $('.form-check-input').each(function(index, element) {
                if ($(element).is(':checked')) {
                    if ($(element).next().is('LABEL')) {
                        projects += $(element).next().text();
                    }
                    else {
                        projects += $(element).next().val();
                    }
                    projects += ';';
                }
            });
            $('#input-project').val(projects);
            $('#form-create-report').submit();
        });

        $('.input-thousand-separator').on('focus', function() {
            let val = $(this).val();
            $(this).attr('type', 'number');
            if (val != '') {
                while(val.indexOf('.') != -1) {
                    val = val.replace('.', '');
                }
                let number = parseInt(val);
                $(this).val(number);
            }
        }).on('blur', function() {
            let val = $(this).val();
            $(this).attr('type', 'text');
            if (val != '') {
                while(val.indexOf('.') != -1) {
                    val = val.replace('.', '');
                }
                let number = parseInt(val);
                $(this).siblings('input[type=hidden]').val(number);
                $(this).val(number.toLocaleString(['ban', 'id']));
            }
        });

        $('input[type=number]').on('change', function() {
            let maxValue = parseInt($(this).attr('max'));
            let enteredValue = parseInt($(this).val());

            if (enteredValue > maxValue) {
                $(this).val(maxValue);
            }
        });
    });
</script>