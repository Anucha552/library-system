<?php

# เปลี่ยนสิทธิ์ผู้ใช้

include_once('../../includes/database.php');
include_once('../../includes/user.php');

# ตรวจสอบการส่งฟอร์ม Edit User
if (isset($_POST['action']) && $_POST['action'] == 'formEditRoleUser') 
{
    # ตัวแปรรับค่าจากฟอร์ม
    $id = $_GET['id'];
    $role = $_POST['role'];

    # สร้าง Object User
    $user = new users();
    # สร้าง Object Database
    $db = new Database();

    if ($role != 'no')
    {
        $user->update_role_user($db->conn, $id, $role);

        # ปิด Database
        $db->close();

        header('Location: ../manage_users.php?page=manage_users&EditUser=succeed');
        exit();
    }
    else
    {
        # ปิด Database
        $db->close();
        
        header('Location: ../manage_users.php?page=manage_users&EditUser=unsuccessful');
        exit();
    }

}
else
{
    header('Location: ../manage_users.php?page=manage_users');
    exit();
}

?>