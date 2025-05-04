<?php
include("../../Root/Header.php");  
require_once("../../Config/conect.php");  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Telegram Configuration</title>
    <link rel="stylesheet" href="../../Style/style.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid mt-3" style="max-width: 1400px;">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-telegram-tab" data-bs-toggle="tab" data-bs-target="#nav-telegram" type="button" role="tab" aria-controls="nav-telegram" aria-selected="true">Telegram Configuration</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-telegram" role="tabpanel" aria-labelledby="nav-telegram-tab">
                <?php 
                // Check if the file exists before including
                $configFile = "telegram_config.php";
                if (file_exists($configFile)) {
                    include($configFile);
                } else {
                    echo "Error: Configuration file not found";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and its dependencies -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>