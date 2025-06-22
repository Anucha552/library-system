<?php

# ค้นหาหนังสือ

session_start();

include_once('../includes/database.php');
include_once('../includes/book.php');

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

# ตรวจสอบการส่งฟอร์ม search
if (isset($_GET['search']) && $_GET['search'] == 'FormSearchBook')
{
    $db = new Database(); # สร้าง Object Database
    $book = new Book(); # สร้าง Object book
    $rows = $book->search_book($db->conn, $_GET['SearchBook']); # ค้นหาหนังสือ
    $db->close(); # ปิดการเชื่อมต่อฐานข้อมูล
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
                    <h3>ค้นหาหนังสือ</h3>
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
                            <a href="#" class="nav-link text-primary text-dark">ค้นหาหนังสือ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100" style="font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#SearchBook">ค้นหา</button>
                </div>
            </div>
            <div class="row">
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
                                    <th>ยืม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                    # ฟังก์ชันเช็คว่าหนังสือยังมีให้ยืมอยู่ไหม
                                    function check_book($Uid, $Bid)
                                    {
                                        $db = new Database(); # สร้าง Object Database
                                        $book1 = new Book(); # สร้าง Object book1
                                        $num_book = $book1->select_available_copies($db->conn, $Bid);
                                        $db->close(); # ปิดการเชื่อมต่อฐานข้อมูล
                                        
                                        if ($num_book > 0)
                                        {
                                            return "<a href='actions_borrow/add_borrow_book.php?page=search_books&user_id=". $Uid ."&book_id=". $Bid ."' class='btn btn-warning text-light py-0'>ยืม</a>";
                                        }
                                        else
                                        {
                                            return "<a href='actions_borrow/add_borrow_book.php?page=search_books&user_id=". $Uid ."&book_id=". $Bid ."' class='btn btn-danger text-light py-0 disabled'>หมด</a>";
                                        }
                                    }

                                    if (isset($rows))
                                    {
                                        $numSearchBook = 1;
                                        $noData = false;
                                        if (count($rows) > 0)
                                        {
                                            foreach ($rows as $row)
                                            {
                                                echo "<tr>
                                                        <td>{$numSearchBook}</td>
                                                        <td>{$row['title']}</td>
                                                        <td>{$row['author']}</td>
                                                        <td>{$row['publisher']}</td>
                                                        <td>{$row['isbn']}</td>
                                                        <td>" . check_book($_SESSION['user']['id'], $row['id']) . "</td>
                                                      </tr>";
                                                $numSearchBook++;
                                            }
                                        }
                                        else 
                                        {
                                            $noData = true;
                                        }
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

    <!-- Container modal -->
    <div class="modal-container">

        <div class="modal fade" id="SearchBook">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ค้นหาหนังสือ</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get" id="FormSearchBook">
                            <input type="hidden" name="page" value="search_books">
                            <input type="search" class="form-control" name="SearchBook" id="SearchBook" placeholder="ค้นหา">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4" name="search" value="FormSearchBook" form="FormSearchBook">ค้นหา</button>
                        <button type="button" class="btn btn-danger px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Container สำหรับการแจ้งเตือน -->
    <div class="toast-container position-fixed top-0 end-0 m-3">

        <div class="toast text-bg-success border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>ยืมหนังสือสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

    </div>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastEl = document.querySelectorAll('.toast');
        
        let toastBorrowSucceed = new bootstrap.Toast(toastEl[0]);
        
        <?php

            # ตรวจสอบการกระทำกับผู้ใช้ว่าทำสำเร็จหรือไม่
            if (isset($_GET['borrow']) && $_GET['borrow'] == 'Succeed')
            {
                echo 'toastBorrowSucceed.show();';
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