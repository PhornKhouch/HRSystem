<?php
include("../../root/Header.php");
?>
<link rel="stylesheet" href="../../Style/style.css">
</head>

<body>
    <div class="container-fluid mt-3" style="max-width: 1400px;">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Tax Setting</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <?php include("UserSetting.php"); ?>
            </div>
        </div>
    </div>
</body>

</html>