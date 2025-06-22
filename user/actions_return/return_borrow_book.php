<?php

# คืนหนังสือ

include_once('../../includes/database.php');
include_once('../../includes/transaction.php');

# ตรวจสอบว่ามีการส่งฟอร์มคืนหนังสือหรือยัง
if (isset($_GET['id']))
{
    $id = $_GET['id'];
    $book_id = $_GET['book_id'];

    $db = new Database(); # สร้าง Object Database
    $transaction = new Transaction(); # สร้าง Object Transaction
    $transaction->return_book($db->conn, $id, $book_id); # คืนหนังสือ
    $db->close(); # ปิดการเชื่อมต่อฐานข้อมูล

    header('Location: ../return_book.php?page=return_book&return=Succeed');
}
else
{
    header('Location: ../return_book.php?page=return_book');
    exit();
}

?>