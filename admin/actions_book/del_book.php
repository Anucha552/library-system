<?php

# ลบหนังสือ

include_once('../../includes/book.php');
include_once('../../includes/database.php');

# ตรวจสอบการส่ง id delete Book
if (isset($_GET['id']))
{
    # ตัวแปรรับค่า id
    $id = $_GET['id'];

    # สร้าง Object book
    $book = new Book();
    # สร้าง Object Database
    $db = new Database();

    $book->delete_book($db->conn, $id);

     # ปิด Database
     $db->close();

     header('Location: ../manage_books.php?page=manage_books&DeleteBook=succeed');
     exit();

}
else
{
    header('Location: ../manage_books.php?page=manage_books');
    exit();
}

?>