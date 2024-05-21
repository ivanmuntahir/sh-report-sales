<?php
    session_start();
    require_once 'db_conn.php';
    require_once 'utilities/sanitizer.php';
    require_once 'utilities/log_handler.php';
    require_once 'logged_user_check.php';
    require_once 'utilities/alert_handler.php';

    function deleteReport($conn){
        $reportId = $_GET['item_id'];

        $queryString = 'DELETE FROM report WHERE id='.$reportId.'';

        if($conn->query($queryString)){
            createLog($_SESSION['ID'], "DELETE_REPORT", $conn);
            return true;
        }
        else{
            return false;
        }
    }
    // Check permissions here
    //TODO: Add permission check for delete, and checks for the request

    if(isset($_GET['item_id'])){
        $queryString = "SELECT * FROM report WHERE id = ".$_GET['item_id'];
        $report = $conn->query($queryString)->fetch_assoc();

        if($_SESSION['ROLE'] == "admin" || ($report['report_by'] == $_SESSION['ID'] && $report['status'] == 0)){
            deleteReport($conn);
            setAlert("Sukses menghapus laporan", "success");
        }
        else {
            setAlert("Anda tidak memiliki akses untuk melakukan aksi ini", "danger");
        }
        
        if (($_SESSION['ROLE']) == "sales"){
            header("location: user_dashboard.php");
        }
        else if($_SESSION['ROLE'] === "supervisor" || $_SESSION['ROLE'] === "manager" || $_SESSION['ROLE'] === "gmanager" || $_SESSION['ROLE'] === "director"){
            header("location: manager_dashboard.php");
        }
        else if ($_SESSION['ROLE'] == "admin"){
            header("location: admin_dashboard.php");
        }
        else {
            header("location: logout.php");
        }
    }
?>