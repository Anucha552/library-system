<?php

# หน้าหลักของสมาชิก

session_start();

include_once('../includes/database.php');
include_once('../includes/user.php');
include_once('../includes/transaction.php');

# เช็คระดับสิทธิ์ User
if (!(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'user'))
{
    header('Location: ../error/401.php');
    exit();
} 

# ตรวจสอบ url ว่าอยู่หน้าไหน
$Dasboard = "";
$search_books = "";
$borrow_book = "";
$return_book = "";


if (isset($_GET['page']))
{
    $page = $_GET['page'];

    if ($page == 'dashboard')
    {
        $Dasboard = 'current';
    }
    elseif ($page == 'search_books')
    {
        $search_books = 'current';
    }
    elseif ($page == 'borrow_book')
    {
        $borrow_book = 'current';
    } 
    elseif ($page == 'return_book')
    {
        $return_book = 'current'; 
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
$user = new users(); # สร้าง Object user
$transaction = new Transaction(); # สร้าง Object transaction

$borrowed_all = $user->select_borrowed($db->conn, $_SESSION['user']['id'], 'all'); # หนังสือที่เคยยืม
$borrowed = $transaction->select_borrowed_book($db->conn, $_SESSION['user']['id']); # หนังสือกำลังยืม

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
    <?php include_once('../partials/navbar-user.php') ?>

    <!-- include offcanvas เข้ามาใช้งาน -->
    <?php include_once('../partials/offcanvas-user.php'); ?>

    <!-- include sidebar เข้ามาใช้งาน -->
    <?php include_once('../partials/sidebar-user.php'); ?>

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
            <div class="row row-gap-3">
                <div class="col-md-8">
                    <div class="shadow-sm bg-white rounded-2 py-3 px-3">
                        <div class="d-flex justify-content-between h4">
                            <span>หนังสือที่เคยยืม</span>
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="table-responsive-xxl">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>หนังสือ</th>
                                        <th>ชื่อผู้แต่ง</th>
                                        <th>วันที่ยืม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $num_borrowed_all = 1;

                                        if (count($borrowed_all) > 0)
                                        {
                                            foreach ($borrowed_all as $row)
                                            {
                                                echo "<tr>
                                                        <td>{$num_borrowed_all}</td>
                                                        <td>{$row['title']}</td>
                                                        <td>{$row['author']}</td>
                                                        <td>{$row['borrow_date']}</td>
                                                      </tr>";
                                                $num_borrowed_all++;
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="shadow-sm text-bg-light rounded-2 py-3 px-3">
                        <div class="d-flex justify-content-between h4">
                            <span>กำลังยืม</span>
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div class="table-responsive-xxl">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>หนังสือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $num_borrowed = 1;
                                        
                                        if (count($borrowed) > 0)
                                        {
                                            foreach ($borrowed as $row)
                                            {
                                                echo "<tr>
                                                        <td>{$num_borrowed}</td>
                                                        <td>{$row['title']}</td>
                                                      </tr>";
                                                $num_borrowed++;
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
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