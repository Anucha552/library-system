<?php

# ลบผู้ใช้

include_once('../../includes/database.php');
include_once('../../includes/user.php');

# ตรวจสอบการส่ง id delete User
if (isset($_GET['id']))
{
    # ตัวแปรรับค่า id
    $id = $_GET['id'];

    # สร้าง Object User
    $user = new users();
    # สร้าง Object Database
    $db = new Database();

    $user->delete_user($db->conn, $id);

    # ปิด Database
    $db->close();

    header('Location: ../manage_users.php?page=manage_users&DelUser=succeed');
    exit();
} 
else
{
    header('Location: ../manage_users.php?page=manage_users');
    exit();
}

?>