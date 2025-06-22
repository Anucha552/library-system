<?php

# คืนหนังสือ

session_start();
 
include_once('../includes/database.php');
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
$transaction = new Transaction(); # สร้าง Object transaction
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
        <div class="container-fluid" style="margin-top: 70px; padding-right: 25px;">
            <div class="row pb-2">
                <div class="col-6">
                    <h3>คืนหนังสือ</h3>
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
                            <a href="#" class="nav-link text-primary text-dark">คืนหนังสือ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pb-4 row-gap-4">
                <div class="col-12">
                    <div class="table-responsive-xxl">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr class="table-dark">
                                    <th>ลำดับ</th>
                                    <th>ชื่อหนังสือ</th>
                                    <th>ชื่อผู้แต่ง</th>
                                    <th>สำนักพิมพ์</th>
                                    <th>รหัส ISBN</th>
                                    <th>คืนหนังสือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    $num_borrowed = 1;
                                    $noData = false;

                                    if (count($borrowed) > 0)
                                    {
                                        foreach ($borrowed as $row)
                                            {
                                                echo "<tr>
                                                        <td>{$num_borrowed}</td>
                                                        <td>{$row['title']}</td>
                                                        <td>{$row['author']}</td>
                                                        <td>{$row['publisher']}</td>
                                                        <td>{$row['isbn']}</td>
                                                        <td><a href='actions_return/return_borrow_book.php?page=return_book&id=". $row['t_id'] ."&book_id=". $row['book_id'] ."' class='btn btn-success text-light py-0'>คืน</a></td>
                                                      </tr>";
                                                $num_borrowed++;
                                            }
                                    }
                                    else
                                    {
                                        $noData = true;
                                    }
                                
                                ?>
                            </tbody>
                        </table>
                        <div class="py-5">
                                <?php if (isset($noData) && $noData){echo '<h2 class="text-center">ไม่มีข้อมูล</h2>';} ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Container สำหรับการแจ้งเตือน -->
    <div class="toast-container position-fixed top-0 end-0 m-3">

        <div class="toast text-bg-success border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>คืนหนังสือสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

    </div>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastEl = document.querySelectorAll('.toast');
        
        let toastReturnSucceed = new bootstrap.Toast(toastEl[0]);
        
        <?php

            # ตรวจสอบการกระทำกับผู้ใช้ว่าทำสำเร็จหรือไม่
            if (isset($_GET['return']) && $_GET['return'] == 'Succeed')
            {
                echo 'toastReturnSucceed.show();';
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