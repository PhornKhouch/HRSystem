<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .swal2-popup {
            border-radius: 15px !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        .swal2-title {
            color: #2c3e50 !important;
            font-size: 1.8em !important;
            font-weight: 600 !important;
        }
        .swal2-html-container {
            color: #34495e !important;
            font-size: 1.2em !important;
        }
        .swal2-confirm {
            padding: 12px 30px !important;
            font-size: 1.1em !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            box-shadow: 0 3px 6px rgba(52, 152, 219, 0.3) !important;
        }
        .swal2-cancel {
            padding: 12px 30px !important;
            font-size: 1.1em !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            box-shadow: 0 3px 6px rgba(231, 76, 60, 0.3) !important;
        }
    </style>
</head>
<body>
    <script>
    window.onload = function() {
        Swal.fire({
            title: '<span style="color: #2c3e50">Confirm Logout</span>',
            html: '<div style="color: #34495e; font-size: 1.1em; margin-top: 10px;">Are you sure you want to end your session?</div>',
            icon: 'warning',
            iconColor: '#f1c40f',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#e74c3c',
            confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Yes, logout',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            allowOutsideClick: false,
            width: '400px',
            padding: '2em',
            background: '#fff',
            backdrop: `
                rgba(0,0,0,0.4)
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.744 0 9.951-.574 14.85-2h6.334zM77.38 0C85.239 2.966 90.502 4 100 4V2c-6.842 0-11.386-.542-16.396-2h-6.225zM0 14c8.44 0 13.718-1.21 22.272-4.402l1.768-.661C33.64 5.347 39.647 4 50 4c10.271 0 15.362 1.222 24.629 4.928C84.112 12.722 89.438 14 100 14v-2c-10.271 0-15.362-1.222-24.629-4.928C65.888 3.278 60.562 2 50 2 39.374 2 33.145 3.397 23.34 7.063l-1.767.662C13.223 10.84 8.163 12 0 12v2z' fill='%239C92AC' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E")
            `,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    html: '<div class="loading-spinner"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Send AJAX request to destroy session
                fetch('../../action/login/logout.php')
                    .then(() => {
                        window.parent.location.href = '../../view/Login/login.php';
                    });
            } else {
                // Go back to previous page
                window.history.back();
            }
        });
    }
    </script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</body>
</html>