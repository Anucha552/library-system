<?php

# หน้าหลักแอดมิน

session_start();

include_once('../includes/database.php');
include_once('../includes/book.php');
include_once('../includes/transaction.php');
include_once('../includes/user.php');

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


if (isset($_GET['page']))
{
    $page = $_GET['page'];

    if ($page == 'dashboard')
    {
        $Dasboard = 'current';
    }
    elseif ($page == 'manage_books')
    {
        $manage_books = 'current';
    }
    elseif ($page == 'manage_users')
    {
        $manage_users = 'current';
    } 
    elseif ($page == 'borrow_logs')
    {
        $borrow_logs = 'current'; 
    }
    else
    {
       header('Location: ../error/404.php');
        exit();
    }
} 
else
{
    header('Location: ../error/404.php');
    exit();
}

$db = new Database(); # สร้าง Object Database
$book = new Book(); # สร้าง Object book
$transection = new Transaction(); # สร้าง Object Transection
$user = new users(); # สร้าง Object user


$totalBook = $book->total_books($db->conn); # จำนวนหนังสือ
$transection->update_status_borrow($db->conn); # Update สถานะการยืม
$totalBB = $transection->status($db->conn, 'borrowed'); # จำนวนที่ยืมหน้งสือ
$totalOverdue = $transection->status($db->conn, 'late'); # จำนวนที่ยืมแล้วแต่เกินกำหนดคืน
$totalUser = $user->total_users_admin($db->conn, 'user'); # จำนวน user
$totalAdmin = $user->total_users_admin($db->conn, 'admin'); # จำนวน Admin

$db->close(); # ปิดการเชื่อมต่อฐานข้อมูล


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
            <div class="row pb-2">
                <div class="col-6">
                    <h3>Dasboard</h3>
                </div>
                <div class="col-6">
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-primary">Home</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-dark px-0">/</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pb-4 row-gap-4">
                <div class="col-md-6">
                    <div class="bg-info-subtle rounded-3 shadow-sm">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalBook; ?> <span>ชุด</span></h1>
                                <span style="font-size: 25px;">รวมชุดหนังสือที่มี</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-journal-medical text-info" style="font-size: 65px;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold bg-info rounded-bottom-3 py-2 text-white">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-success-subtle rounded-3 shadow-sm">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalBB; ?> <span>เล่ม</span></h1>
                                <span style="font-size: 25px;">หนังสือที่กำลังยืมอยู่</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-book-half text-success" style="font-size: 65px;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold bg-success rounded-bottom-3 py-2 text-white">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-4 row-gap-4">
                <div class="col-md-6">
                    <div class="rounded-3 shadow-sm" style="background-color: white">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalUser + $totalAdmin; ?> <span>คน</span></h1>
                                <span style="font-size: 25px;">รวมจำนวนผู้ใช้ทั้งหมด</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-person-video2" style="font-size: 65px; color: #858181;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold rounded-bottom-3 py-2 text-white" style="background-color: #858181;">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-danger-subtle rounded-3 shadow-sm">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalOverdue; ?> <span>รายการ</span></h1>
                                <span style="font-size: 25px;">เกินกำหนดคืนหนังสือ</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-alarm text-danger" style="font-size: 65px;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold bg-danger rounded-bottom-3 py-2 text-white">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pd-4 row-gap-4">
                <div class="col-md-6">
                    <div class="bg-warning-subtle rounded-3 shadow-sm">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalUser; ?> <span>คน</span></h1>
                                <span style="font-size: 25px;">Total Users</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-people text-warning" style="font-size: 65px;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold bg-warning rounded-bottom-3 py-2 text-white">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-primary-subtle rounded-3 shadow-sm">
                        <div class="row p-4">
                            <div class="col-lg-6 text-muted">
                                <h1 class="fw-bold"><?php echo $totalAdmin; ?> <span>คน</span></h1>
                                <span style="font-size: 25px;">Total Admins</span>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-person-bounding-box text-primary" style="font-size: 65px;"></i>
                            </div>
                        </div>
                        <div class="text-center fw-bold bg-primary rounded-bottom-3 py-2 text-white">
                            <span>DATA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
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