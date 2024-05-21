<?php
    session_start();
    include_once "db_conn.php";  
    include_once "utilities/log_handler.php";
    include_once "utilities/alert_handler.php";

    function updateReportStatus($report_id, $updated_status, $need_approval_by, $need_approval_by_2, $need_approval_by_3, $conn){
        $queryString = "UPDATE report SET status = ".$updated_status.", need_approval_by = ".$need_approval_by." WHERE id = ".$report_id."";
        $conn->query($queryString);
    }

    function addReportAction($type, $report_id, $comment, $conn){
        $queryString = "INSERT INTO report_action (type, user, comment, report_id) values (".$type.", ".$_SESSION['ID'].", '".$comment."', ".$report_id.")";
        $conn->query($queryString);
    }

    if($report['need_approval_by'] == null){
        
    }

    if ($_POST['item_id']){
            
        // Check whether the current user's role is allowed to approve or reject this report
        $queryString = "SELECT * from report WHERE id=".$_POST['item_id']."";

        $result = $conn->query($queryString);

        $report = mysqli_fetch_assoc($result);
        
        // Cek apakah report ini boleh di approve / tidak

        if ($_SESSION['ID'] == $report['need_approval_by'] || $_SESSION['ID'] == $report['need_approval_by_2'] || $_SESSION['ID'] == $report['need_approval_by_3'] || $_SESSION['ROLE'] === "director" || $_SESSION['ROLE'] === "manager" || $_SESSION['ROLE'] === "gmanager"){
            $newStatus = 0;
            if (isset($_POST['approve'])){
                if ($_SESSION['ROLE'] == "manager"){
                    $newStatus = 1;
                }
                elseif ($_SESSION['ROLE'] == "gmanager"){
                    $newStatus = 2;
                }
                elseif ($_SESSION['ROLE'] == "director"){
                    $newStatus = 3;
                }
                elseif ($_SESSION['ROLE'] == "supervisor"){
                    $newStatus = 4;
                }
            }
            else if(isset($_POST['reject'])){
                $newStatus = -1;
            }

            
            $queryString = "SELECT * FROM user WHERE id=".$_SESSION['ID']."";
            $result = $conn->query($queryString);

            $user = mysqli_fetch_assoc($result);

            updateReportStatus($_POST['item_id'], $newStatus, $user['reports_to_lead_1'], $user['reports_to_lead_2'], $user['reports_to_lead_3'], $conn);
            if($newStatus > 0){
                createLog($_SESSION['ID'], "APPROVE_REPORT", $conn);
                addReportAction(1,$_POST['item_id'], $_POST['input-comment'], $conn);
            }
            else{
                createLog($_SESSION['ID'], "REJECT_REPORT", $conn);
                addReportAction(0,$_POST['item_id'], $_POST['input-comment'], $conn);
            }
            setAlert("Berhasil menanggapi laporan", "success");
        }
        else {
            //TODO: Add error alert here
            setAlert("Anda tidak memiliki akses untuk melakukan aksi ini", "danger");
        }
    }
    header("location: manager_dashboard.php");
?>