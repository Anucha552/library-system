<?php

# จัดการข้อมูลหนังสือ

session_start();

include_once('../includes/database.php');
include_once('../includes/book.php');
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

$db = new Database(); # สร้าง Object Database
# สร้าง Object Transaction
$transaction = new Transaction();

$transaction->update_status_borrow($db->conn); ## Update สถานะการยืม

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
        <div class="container-fluid ps-4" style="margin-top: 70px; padding-right: 25px;">
            <div class="row pb-2">
                <div class="col-6 p-0">
                    <h3>จัดการหนังสือ</h3>
                </div>
                <div class="col-6 p-0">
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-primary">Home</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-dark px-0">/</span>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark">จัดการหนังสือ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-12 p-0">
                    <button type="button" class="btn btn-primary w-100" style="font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#addBook">เพิ่มหนังสือ</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="table-responsive-xxl">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr class="table-dark">
                                    <th>ลำดับ</th>
                                    <th>ชื่อหนังสือ</th>
                                    <th>ชื่อผู้แต่ง</th>
                                    <th>สำนักพิมพ์</th>
                                    <th>รหัส ISBN</th>
                                    <th>จำนวน</th>
                                    <th>ยังว่าง</th>
                                    <th>วันที่</th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                # สร้าง Object book
                                $book = new Book();
                                # สร้าง Object Database
                                $db = new Database();

                                # โชว์หนังสือทั้งกมด
                                $rows = $book->select_books($db->conn);
                                $numBook = 1;

                                if (count($rows) > 0) {
                                    foreach ($rows as $row) {
                                        echo "<tr id='row{$numBook}'>
                                                <th>{$numBook}</th>
                                                <th>{$row['title']}</th>
                                                <th>{$row['author']}</th>
                                                <th>{$row['publisher']}</th>
                                                <th>{$row['isbn']}</th>
                                                <th>{$row['total_copies']}</th>
                                                <th>{$row['available_copies']}</th>
                                                <th>{$row['created_at']}</th>
                                                <th><button class='btn btn-warning text-light py-0 edit-book' data-row='row{$numBook}' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#modalEditBook'>แก้ไข</button></th>
                                                <td><a href='actions_book/del_book.php?id={$row['id']}' class='btn btn-danger text-light py-0'>ลบ</a></td>
                                              </tr>";
                                        $numBook++;
                                    }
                                }

                                # ปิด Database
                                $db->close();

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Container modal -->
    <div class="modal-container">
        <div class="modal fade" id="addBook">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">เพิ่มหนังสือ</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="actions_book/add_book.php" method="post" id="formAddBook">
                            <input type="hidden" name="action" value="formAddBook">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="title" id="title" placeholder="ชื่อหนังสือ">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="author" id="author" placeholder="ชื่อผู้แต่ง">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="publisher" id="publisher" placeholder="สำนักพิมพ์">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="isbn" id="isbn" placeholder="รหัส ISBN ของหนังสือ">
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" name="total_copies" id="total_copies" placeholder="จำนวนหนังสือ">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4" form="formAddBook">เพิ่ม</button>
                        <button type="button" class="btn btn-danger px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditBook">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">แก้ไขหนังสือ</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="formEditBook">
                            <input type="hidden" name="action" value="formEditBook">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="editTitle" id="editTitle" placeholder="ชื่อหนังสือ">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="editAuthor" id="editAuthor" placeholder="ชื่อผู้แต่ง">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="editPublisher" id="editPublisher" placeholder="สำนักพิมพ์">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="editIsbn" id="editIsbn" placeholder="รหัส ISBN ของหนังสือ">
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" name="editTotal_copies" id="editTotal_copies" placeholder="จำนวนหนังสือ">
                            </div>
                        </form>
                        <div class="test"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4" form="formEditBook">แก้ไข</button>
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
                    <span>เพิ่มข้อมูลหนังสือสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-danger border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>เพิ่มข้อมูลหนังสือไม่สำเร็จ ห้ามมีช่องว่าง !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-success border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>Update ข้อมูลหนังสือสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-danger border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>ไม่สามารถลดจำนวนหนังสือต่ำกว่าจำนวนที่มีอยู่ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-success border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>DELETE หนังสือสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

    </div>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastEl = document.querySelectorAll('.toast');

        let toastAddBookSucceed = new bootstrap.Toast(toastEl[0]);
        let toastAddBookUnsuccessful = new bootstrap.Toast(toastEl[1]);
        let toastEditBookSucceed = new bootstrap.Toast(toastEl[2]);
        let toastEditBookUnsuccessful = new bootstrap.Toast(toastEl[3]);
        let toastDeleteBooksucceed = new bootstrap.Toast(toastEl[4]);

        <?php

        # ตรวจสอบการกระทำกับหนังสือว่าทำสำเร็จหรือไม่
        if (isset($_GET['AddBook']) && $_GET['AddBook'] == 'succeed') {
            echo 'toastAddBookSucceed.show();';
        } elseif (isset($_GET['AddBook']) && $_GET['AddBook'] == 'unsuccessful') {
            echo 'toastAddBookUnsuccessful.show();';
        } elseif (isset($_GET['EditBook']) && $_GET['EditBook'] == 'succeed') {
            echo 'toastEditBookSucceed.show();';
        } elseif (isset($_GET['EditBook']) && $_GET['EditBook'] == 'unsuccessful') {
            echo 'toastEditBookUnsuccessful.show();';
        } elseif (isset($_GET['DeleteBook']) && $_GET['DeleteBook'] == 'succeed') {
            echo 'toastDeleteBooksucceed.show();';
        }

        ?>

        // Event แก้ไขหนังสือ
        const rowEdit = document.querySelectorAll('.edit-book');

        rowEdit.forEach(item => {
            item.addEventListener('click', function() {
                const title = document.querySelector('#editTitle');
                const author = document.querySelector('#editAuthor');
                const publisher = document.querySelector('#editPublisher');
                const isbn = document.querySelector('#editIsbn');
                const total_copies = document.querySelector('#editTotal_copies');
                const formEditBook = document.querySelector('#formEditBook');
                let row = document.querySelector('#' + this.dataset.row).children;

                title.value = row[1].textContent.trim();
                author.value = row[2].textContent.trim();
                publisher.value = row[3].textContent.trim();
                isbn.value = row[4].textContent.trim();
                total_copies.value = row[5].textContent.trim();
                formEditBook.setAttribute('action', 'actions_book/edit_book.php?id=' + this.dataset.id);
            });
        });
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