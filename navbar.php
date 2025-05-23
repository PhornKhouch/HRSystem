<?php
include("root/header.php");
?>
<style>
    body{
        padding: 0;
        margin: 0;
    }
    .navbar-custom {
        background-color: rgb(42, 39, 57);
        padding: 0.8rem 2rem;
        box-shadow: 0 2px 4px rgba(171, 172, 214, 0.1);
    }

    .navbar-custom .navbar-brand {
        color: #ffffff;
        font-weight: 500;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
        margin-left: 10px;
    }

    .navbar-custom .nav-link {
        color: #ffffff;
        padding: 0.5rem 1rem;
        font-weight: 400;
        transition: color 0.3s ease;
    }

    .navbar-custom .nav-link:hover {
        color: rgba(255,255,255,0.8);
    }

    .navbar-nav .icon-button {
        color: #ffffff;
        padding: 0.5rem;
        margin: 0 0.3rem;
        border-radius: 50%;
        transition: background-color 0.3s;
    }

    .navbar-nav .icon-button:hover {
        background-color: rgba(255,255,255,0.1);
        color: #ffffff;
    }

    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #ff4444;
        color: white;
        border-radius: 50%;
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
    }

    .language-select {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        margin-left: 1rem;
    }

    .language-select option {
        background: rgb(42, 39, 57);
        color: white;
    }

    .navbar-toggler {
        border-color: rgba(255,255,255,0.5);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }

    .right-icons {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CLUBCODE-HR</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="right-icons">
                    <!-- Notification Icon -->
                    <a href="#" class="icon-button position-relative">
                        <i class="fas fa-bell" style="color: white;"></i>
                        <!-- <span class="notification-badge">3</span> -->
                    </a>
                    <!-- Language Selector -->
                    <select class="language-select">
                        <option value="en">English</option>
                        <option value="KH">Khmer</option>
                    </select>
                    <a href="view/Login/logout_confirm.php" target="content" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
 

    function showLoginModal() {
        Swal.fire({
            title: 'Login',
            html: `
                <form id="loginForm" class="text-start">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                
                if (!username || !password) {
                    Swal.showValidationMessage('Please fill in all fields');
                    return false;
                }

                const formData = new FormData();
                formData.append('username', username);
                formData.append('password', password);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token'] ?? ''; ?>');

                return fetch('/PHP8/HR_SYSTEM/action/Login/login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(error.message);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Login successful',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function showProfileModal() {
        Swal.fire({
            title: 'User Profile',
            html: `
                <div class="text-start">
                    <p><strong>Username:</strong> <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['username']) : ''; ?></p>
                    <p><strong>Role:</strong> <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['role']) : ''; ?></p>
                </div>
            `,
            confirmButtonText: 'Close'
        });
    }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>