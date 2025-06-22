<?php

# เพิ่มหนังสือ

include_once('../../includes/book.php');
include_once('../../includes/database.php');

# ตรวจสอบการส่งฟอร์ม Add Book
if (isset($_POST['action']) && $_POST['action'] == 'formAddBook')
{
    # ตัวแปรรับค่าจากฟอร์ม
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $total_copies = $_POST['total_copies'];

    # สร้าง Object book
    $book = new Book();
    # สร้าง Object Database
    $db = new Database();

    $book->add($title, $author, $publisher, $isbn, $total_copies);
    
    if ($book->check_vacancy()){

        # บันทึกหนังสือ
        $book->record_book($db->conn);
        # ปิด Database
        $db->close();

        header('Location: ../manage_books.php?page=manage_books&AddBook=succeed');
        exit();
    } else {

        # ปิด Database
        $db->close();

        header('Location: ../manage_books.php?page=manage_books&AddBook=unsuccessful');
        exit();
    }

} 
else
{
    header('Location: ../manage_books.php?page=manage_books');
    exit();
}

?>