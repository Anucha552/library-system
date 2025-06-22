<?php

# ประวัติการยืมคืนหนังสือ

session_start();

include_once('../includes/database.php');
include_once('../includes/transaction.php');

# เช็คระดับสิทธิ์ User
if (!(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'))
{
    header('Location: ../error/401.php');
    exit();
} 

# ตรวจสอบ url ว่าอยู่หน้าไหน
$Dasboard = "";
$manage_books = "";
$manage_users = "";
$borrow_logs = "";


if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if ($page == 'dashboard') {
        $Dasboard = 'current';
    } elseif ($page == 'manage_books') {
        $manage_books = 'current';
    } elseif ($page == 'manage_users') {
        $manage_users = 'current';
    } elseif ($page == 'borrow_logs') {
        $borrow_logs = 'current';
    } else {
        header('Location: ../error/404.php');
        exit();
    }
} else {
    header('Location: ../error/404.php');
    exit();
}

# สร้าง Object Database
$db = new Database();
# สร้าง Object Transaction
$transaction = new Transaction();

$transaction->update_status_borrow($db->conn); # Update สถานะการยืม

# ตรวจสอบการส่งฟอร์ม search
if (isset($_GET['search']) && $_GET['search'] == 'formTransaction') {   # ตรวจสอบวันที่ว่าว่างหรือเปล่า
    if ($_GET['start_date'] != '' && $_GET['end_date'] != '') {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        $rows = $transaction->select_transactions($db->conn, true, $start_date, $end_date);
    } else {
        $search = 'searchUnsuccessful.show();';
        $rows = $transaction->select_transactions($db->conn, false);
    }
} else {
    $rows = $transaction->select_transactions($db->conn, false);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dasboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/custom-main.css">
</head>

<body class="bg-body-secondary">

    <!-- include navber เข้ามาใช้งาน -->
    <?php include_once('../partials/navbar-admin.php') ?>

    <!-- include offcanvas เข้ามาใช้งาน -->
    <?php include_once('../partials/offcanvas-admin.php'); ?>

    <!-- include sidebar เข้ามาใช้งาน -->
    <?php include_once('../partials/sidebar-admin.php'); ?>

    <!-- content -->
    <main class="content">
        <div class="container-fluid" style="margin-top: 70px;">
            <div class="row">
                <div class="col-6">
                    <h3>รายงานการยืม-คืน</h3>
                </div>
                <div class="col-6">
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-primary">Home</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-dark px-0">/</span>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark">รายงานการยืม-คืน</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100" style="font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#ModalSearchTransaction">ค้นหา</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive-xxl">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr class="table-dark">
                                    <th>ลำดับ</th>
                                    <th>ชื่อผู้ยืม</th>
                                    <th>หนังสือ</th>
                                    <th>วันยืม</th>
                                    <th>วันครบกำหนด</th>
                                    <th>คืนแล้ว</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                # ฟังก์ชั้นเช็คสถานะการยืม
                                function chech_status($status)
                                {
                                    if ($status == "borrowed") {
                                        return "<span class='text-warning'>ยังยืมอยู่</span>";
                                    } elseif ($status == "returned") {
                                        return "<span class='text-success'>คืนแล้ว</span>";
                                    } elseif ($status == "late") {
                                        return "<span class='text-danger'>คืนช้า</span>";
                                    }
                                }

                                $numTransaction = 1;
                                $noData = false;
                                if (count($rows) > 0) {
                                    foreach ($rows as $row) {
                                        echo "<tr>
                                                <td>{$numTransaction}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['title']}</td>
                                                <td>{$row['borrow_date']}</td>
                                                <td>{$row['due_date']}</td>
                                                <td>{$row['return_date']}</td>
                                                <td>" . chech_status($row['status']) . "</td>
                                            </tr>";
                                        $numTransaction++;
                                    }
                                } else {
                                    $noData = true;
                                }

                                ?>
                            </tbody>
                        </table>
                        <div class="py-5">
                                <?php if ($noData){echo '<h2 class="text-center">ไม่มีข้อมูล</h2>';} ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Container modal -->
    <div class="container-modal">

        <div class="modal fade" id="ModalSearchTransaction">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ค้าหาตามวันยิมหน้งสือ</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="GET" id="FormSearchTransaction">
                            <input type="hidden" name="page" value="borrow_logs">
                            <label class="py-1" for="start_date">จากวันที่</label>
                            <input class="form-control" type="date" name="start_date" id="start_date">
                            <label class="py-1" for="end_date">ถึงวันที่</label>
                            <input class="form-control" type="date" name="end_date" id="end_date">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="search" value="formTransaction" class="btn btn-primary px-4" form="FormSearchTransaction">ค้นหา</button>
                        <button type="button" class="btn btn-danger px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Container สำหรับการแจ้งเตือน -->
    <div class="toast-container position-fixed top-0 end-0 m-3">
        <div class="toast text-bg-danger border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>วันที่ค้นหาห้ามมีช่องว่าง !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastEl = document.querySelectorAll('.toast');

        let searchUnsuccessful = new bootstrap.Toast(toastEl[0]);

        <?php

        if (isset($search)) {
            echo $search;
        }

        ?>
    </script>
    <script>
        // ซ่อน Query string 
        if (window.location.search)
        {
            const newUrl = window.location.href.split('.php')[0];
            window.history.replaceState({}, document.title, newUrl);
        }
    </script>

</body>

</html>