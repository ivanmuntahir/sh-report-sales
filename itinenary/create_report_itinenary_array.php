<?php
 session_start();
// Database connection
    include_once 'db_conn_itenenary.php'; // Adjust to your actual database connection file
    include_once "utilities/util.php";
    include_once "utilities/session_handler.php";
    include_once "utilities/alert_handler.php";
    include_once "utilities/sanitizer.php";
    include_once "utilities/log_handler.php";
    include_once "utilities/image_util.php";
    include_once "utilities/user_director_handler.php";


if(checkSession() && checkSessionRole(["sales", "supervisor", "manager", "gmanager"])){

    // Retrive input single
    $sppd = isset($_POST['input-sppd'])? sanitizeInput($_POST['input-sppd']): '';
    $duration = isset($_POST['input-duration'])? sanitizeInput($_POST['input-duration']): '';
    $type = isset($_POST['input-type-activity'])? sanitizeInput($_POST['input-type-activity']): '';
    $tanggal_spi = isset($_POST['input-tanggal-spi'])? sanitizeInput($_POST['input-tanggal-spi']): '';
    $tanggal_kontrak = isset($_POST['input-tanggal-kontrak'])? sanitizeInput($_POST['input-tanggal-kontrak']): '';
    $input_role_spv = isset($_POST['input-reports-to-lead-1']) ? sanitizeInput($_POST['input-reports-to-lead-1']) : null;
    $input_role_manager = isset($_POST['input-reports-to-lead-2']) ? sanitizeInput($_POST['input-reports-to-lead-2']) : null;
    $input_role_gmanager = isset($_POST['input-reports-to-lead-3']) ? sanitizeInput($_POST['input-reports-to-lead-3']) : null;
    $input_role_director = isset($_POST['input-reports-to-lead-4']) ? sanitizeInput($_POST['input-reports-to-lead-4']) : null;
    $input_role_director_2 = isset($_POST['input-reports-to-lead-5']) ? sanitizeInput($_POST['input-reports-to-lead-5']) : null;

    // Retrieve form atasan
     $roles = [
            'spv' => $input_role_spv,
            'manager' => $input_role_manager,
            'gmanager' => $input_role_gmanager,
            'director' => $input_role_director,
            'director_2' => $input_role_director_2
        ];

        $approval_columns = [
            'spv' => 'need_approval_by',
            'manager' => 'need_approval_by_2',
            'gmanager' => 'need_approval_by_3',
            'director' => 'need_approval_by_4',
            'director_2' => 'need_approval_by_5'
        ];

        $columns = [];
        $values = [];

        foreach ($roles as $role => $value) {
            if ($value !== NULL) {
                $columns[] = $approval_columns[$role];
                $values[] = $value;
            }
        }

        if (empty($columns)) {
            $columns[] = 'need_approval_by';
            $values[] = $input_role_spv !== NULL ? $input_role_spv : "NULL";
        }

        $columns_str = implode(', ', $columns);
        $values_str = implode(', ', $values);


        // Retrieve form data array
        $projects = $_POST['input-project'] ?? [];
        $tanggal_aktivitas = $_POST['input-tanggal-aktivitas'] ?? [];
        $instansi = $_POST['input-instansi'] ?? [];
        $kota = $_POST['input-kota'] ?? [];
        $kode_proyek = $_POST['input-kode-proyek'] ?? [];
        $nama_proyek = $_POST['input-nama-proyek'] ?? [];
        $target = $_POST['input-target'] ?? [];
        $progress = $_POST['input-progress'] ?? [];
        $kegiatan = $_POST['input-kegiatan'] ?? [];

        // Assuming the form data for each project is correlated, you should iterate over them.
        for ($i = 0; $i < count($projects); $i++) {
            $project = $connDB->real_escape_string($projects[$i] ?? '');
            $tanggal_input = $tanggal_aktivitas[$i] ?? '';

            $tanggal_aktivitas = getdate(strtotime($tanggal_aktivitas));
            $tanggal_aktivitas = $tanggal_aktivitas["mday"]."/".$tanggal_aktivitas["mon"]."/".$tanggal_aktivitas['year'];

            $tanggal_spi = getdate(strtotime($tanggal_spi));
            $tanggal_spi = $tanggal_spi["mday"]."/".$tanggal_spi["mon"]."/".$tanggal_spi['year'];

            $tanggal_kontrak = getdate(strtotime($tanggal_kontrak));
            $tanggal_kontrak = $tanggal_kontrak["mday"]."/".$tanggal_kontrak["mon"]."/".$tanggal_kontrak['year'];
            
            
            $lokasi = $connDB->real_escape_string($instansi[$i] ?? '');
            $kota_value = $connDB->real_escape_string($kota[$i] ?? '');
            $kode = $connDB->real_escape_string($kode_proyek[$i] ?? '');
            $nama_proyek_value = $connDB->real_escape_string($nama_proyek[$i] ?? '');
            $target_value = $connDB->real_escape_string($target[$i] ?? '');
            $progress_value = $connDB->real_escape_string($progress[$i] ?? '');
            $kegiatan_value = $connDB->real_escape_string($kegiatan[$i] ?? '');
            $sppd = $connDB->real_escape_string($sppd);
            $duration = $connDB->real_escape_string($duration);
            $type = $connDB->real_escape_string($type);
            $columns_str = $connDB->real_escape_string($columns_str);
            $values_str = $connDB->real_escape_string($values_str);

            // Insert data array into the database
            $sql = "INSERT INTO full_report (
                project, tanggal_aktivitas, tanggal_spi, tanggal_kontrak, instansi, kota, kode_proyek, nama_proyek, target, progress, kegiatan, report_by, sppd, durasi, tipe_kegiatan, $columns_str
            ) VALUES (
                '$project', " . ($tanggal_aktivitas !== NULL ? "STR_TO_DATE('" . $tanggal_aktivitas . "', '%d/%m/%Y')" : "NULL") . ",  " . ($tanggal_spi !== NULL ? "STR_TO_DATE('" . $tanggal_spi . "', '%d/%m/%Y')" : "NULL") . ",  " . ($tanggal_kontrak !== NULL ? "STR_TO_DATE('" . $tanggal_kontrak . "', '%d/%m/%Y')" : "NULL") . ",'$lokasi', '$kota_value', '$kode', '$nama_proyek_value', '$target_value', '$progress_value', '$kegiatan_value', " . strval($_SESSION['ID']) . ", '$sppd', '$duration', '$type', $values_str
            )";

            if (!$connDB->query($sql)) {
                echo "Error array data: " . $connDB->error . "<br>";
            }
        }

        
        echo "Records inserted successfully.";

// Close the connection
$connDB->close();

}


?>
