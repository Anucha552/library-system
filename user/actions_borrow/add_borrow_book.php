<?php

# เพิ่มการยืมหนังสือ

include_once('../../includes/database.php');
include_once('../../includes/transaction.php');

# ตรวจสอบการส่งฟอร์มยิมหนีงสือ
if (isset($_GET['user_id']) && isset($_GET['book_id']))
{
    # ตัวแปรรับค่า ID
    $user_id = $_GET['user_id'];
    $book_id = $_GET['book_id'];

    $db = new Database(); # สร้าง Object Database
    $transaction = new Transaction(); # สร้าง Object Transaction
    $transaction->borrow_book($db->conn, $user_id, $book_id); # ยืมหนังสือ
    $db->close(); # ปิดการเชื่อมต่อฐานข้อมูล

    # ตรวจสอบข้อมูลที่ส่งคือ page ไหน
    if ($_GET['page'] == 'search_books')
    {
        header('Location: ../search_books.php?page=search_books&borrow=Succeed');
        exit();
    }
    else
    {
        header('Location: ../borrow_book.php?page=borrow_book&borrow=Succeed');
        exit();
    }

}
else
{
    header('Location: ../search_books.php?page=search_books');
    exit();
}

?>