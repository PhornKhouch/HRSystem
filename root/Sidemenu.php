<!DOCTYPE html>
<html>
<head>
<?php
     include("header.php");
?>
    <!-- Google Fonts - Professional font combination -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&family=Noto+Sans+Khmer:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="Style/sidemenu.css">
</head>
<body>
    <div class="menu">
        <!-- <div class="brand-logo">
            <img src="../assets/images/yrm-logo.png" alt="YRM">
        </div> -->
        <div class="menu-search">
            <input type="text" placeholder="Search menu..." class="form-control">
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="../Dasborad/IndexDasbord.php" target="content">
                    <i class="fa fa-home"></i>Dasborad
                </a>
            </li>
            
            <!-- Master Set up -->
            <li>
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-cog"></i><span lang="km">Master Set up</span>
                </a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a href="../view/JobAnalysis/index.php" target="content">Job Analysis</a>
                    </li>
                    <li>
                        <a href="../view/PayrollSetting/index.php" target="content">Payroll</a>
                    </li>
                    <li>
                        <a href="../view/LeavePolicy/index.php" target="content">Leave Policy</a>
                    </li>
                    <li>
                        <a href="../view/Menu/index.php" target="content">General Settings</a>
                    </li>
                    <li>
                        <a href="../view/TelegramConfig/index.php" target="content">Telegram config</a>
                    </li>
                </ul>
            </li>

            <!-- Employee -->
            <li>
                <a href="#Order" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-users"></i><span lang="km">Employee</span>
                </a>
                <ul class="collapse list-unstyled" id="Order">
                    <li>
                        <a href="../view/StaffProfile/index.php" target="content">Staff Profile</a>
                    </li>
                    <li>
                        <a href="../view/CareerHistory/index.php" target="content">Career History</a>
                    </li>
                </ul>
            </li>

            <!-- Self Service -->
            <li>
                <a href="#ESS" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-book"></i><span lang="km">Self Service</span>
                </a>
                <ul class="collapse list-unstyled" id="ESS">
                    <li>
                        <a href="Customer/index.php" target="content">Sale Product</a>
                    </li>
                    <li>
                        <a href="Customer/index.php" target="content">Stock Product</a>
                    </li>
                </ul>
            </li>

            <!-- Leave -->
            <li>
                <a href="#Leave" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-users"></i><span lang="km">Leave </span>
                </a>
                <ul class="collapse list-unstyled" id="Leave">
                    <li>
                        <a href="../view/LeaveBalance/index.php" target="content">Leave Balance</a>
                    </li>
                    <li>
                        <a href="../view/LeaveRequest/index.php" target="content">Leave Request</a>
                    </li>
                    <li>
                        <a href="../view/LeaveApproval/index.php" target="content">Leave Approval</a>
                    </li>
                </ul>
            </li>

            <!-- Payroll -->
            <li>
                <a href="#User" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-money-bill"></i><span lang="km">Payroll</span>
                </a>
                <ul class="collapse list-unstyled" id="User">
                    <li>
                        <a href="../AddUserAdmin/index.php" target="content">User Admin</a>
                    </li>
                    <li>
                        <a href="../AddNormalUser/index.php" target="content">User</a>
                    </li>
                </ul>
            </li>

            <!-- Recruitment -->
            <li>
                <a href="#Recruite" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-book"></i><span lang="km">Recruitment</span>
                </a>
                <ul class="collapse list-unstyled" id="Recruite">
                    <li>
                        <a href="Customer/index.php" target="content">Sale Product</a>
                    </li>
                    <li>
                        <a href="Customer/index.php" target="content">Stock Product</a>
                    </li>
                </ul>
            </li>

            <!-- Report -->
            <li>
                <a href="#Report" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-book"></i><span lang="km">Report</span>
                </a>
                <ul class="collapse list-unstyled" id="Report">
                    <li>
                        <a href="Customer/index.php" target="content">Sale Product</a>
                    </li>
                    <li>
                        <a href="Customer/index.php" target="content">Stock Product</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</body>
</html>