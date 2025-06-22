<?php

# จัดการข้อมูลผู้ใช้

session_start();

include_once('../includes/database.php');
include_once('../includes/user.php');
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
        <div class="container-fluid" style="margin-top: 70px;">
            <div class="row pb-2">
                <div class="col-6">
                    <h3>จัดการสมาชิก</h3>
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
                            <a href="#" class="nav-link text-dark">จัดการสมาชิก</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive-xxl">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr class="table-dark">
                                    <th>ลำดับ</th>
                                    <th>ชื่อผู้ใช้งาน</th>
                                    <th>E-mail</th>
                                    <th>สิทธิ์ผู้ใช้งาน</th>
                                    <th>วันที่ลงทะเบียน</th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                # สร้าง Object book
                                $user = new users();
                                # สร้าง Object Database
                                $db = new Database();

                                # โชว์ user ทั้งกมด
                                $rows = $user->select_users($db->conn);
                                $numUsers = 1;

                                if (count($rows) > 0) {
                                    foreach ($rows as $row) {
                                        echo "<tr>
                                                    <td>{$numUsers}</td>
                                                    <td>{$row['user_name']}</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['role']}</td>
                                                    <td>{$row['created_at']}</td>
                                                    <th><button class='btn btn-warning text-light py-0 edit-user' data-row='row{$numUsers}' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#ModalEditRoleUser'>แก้ไข</button></th>
                                                    <td><a href='actions_user/del_user.php?id={$row['id']}' class='btn btn-danger text-light py-0'>ลบ</a></td>
                                                  </tr>";
                                        $numUsers++;
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Container modal -->
    <div class="container-modal">

        <div class="modal fade" id="ModalEditRoleUser">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">แก้ไขสิทธิ์ผู้ใช้งาน</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="formEditRoleUser">
                            <input type="hidden" name="action" value="formEditRoleUser">
                            <div class="mb-3">
                                <select class="form-control" name="role" id="role">
                                    <option value="no">เลือกสิทธิ์</option>
                                    <option value="admin">Amin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4" form="formEditRoleUser">เพิ่ม</button>
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
                    <span>Update สิทธิ์ผู้ใช้งานสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-danger border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>Update สิทธิ์ผู้ใช้งานไม่สำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="toast text-bg-success border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="toast-body">
                    <span>Delete ผู้ใช้งานสำเร็จ !</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>

    </div>

    <script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastEl = document.querySelectorAll('.toast');

        let toastEditRoleUserSucceed = new bootstrap.Toast(toastEl[0]);
        let toastEditRoleUserUnsuccessful = new bootstrap.Toast(toastEl[1]);
        let toastDelRoleUserSucceed = new bootstrap.Toast(toastEl[2]);

        <?php

        # ตรวจสอบการกระทำกับผู้ใช้ว่าทำสำเร็จหรือไม่
        if (isset($_GET['EditUser']) && $_GET['EditUser'] == 'succeed') {
            echo 'toastEditRoleUserSucceed.show();';
        } elseif (isset($_GET['EditUser']) && $_GET['EditUser'] == 'unsuccessful') {
            echo 'toastEditRoleUserUnsuccessful.show();';
        } elseif (isset($_GET['DelUser']) && $_GET['DelUser'] == 'succeed') {
            echo 'toastDelRoleUserSucceed.show();';
        }

        ?>

        // Event แก้ไขผู้ใช้งาน
        const rowEdit = document.querySelectorAll('.edit-user');
        const formEditRoleUser = document.querySelector('#formEditRoleUser');

        rowEdit.forEach(item => {
            item.addEventListener('click', function () {
                formEditRoleUser.setAttribute('action', 'actions_user/edit_role_user.php?id=' + this.dataset.id)
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