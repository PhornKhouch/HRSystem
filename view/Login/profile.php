<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /PHP8/HR_SYSTEM/view/Login/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'User Profile',
            html: `
                <div class="text-start">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user']['id']); ?></p>
                </div>
            `,
            confirmButtonText: 'Close',
            allowOutsideClick: false
        }).then(() => {
            window.history.back();
        });
    });
    </script>
</body>
</html>
