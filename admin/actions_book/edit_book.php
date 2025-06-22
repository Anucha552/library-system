<?php

# แก้ไขข้อมูลหนังสือ

include_once('../../includes/book.php');
include_once('../../includes/database.php');

# ตรวจสอบการส่งฟอร์ม Edit Book
if (isset($_POST['action']) && $_POST['action'] == 'formEditBook') {
    # ตัวแปรรับค่าจากฟอร์ม
    $id = $_GET['id'];
    $title = $_POST['editTitle'];
    $author = $_POST['editAuthor'];
    $publisher = $_POST['editPublisher'];
    $isbn = $_POST['editIsbn'];
    $total_copies = $_POST['editTotal_copies'];

    # สร้าง Object book
    $book = new Book();
    # สร้าง Object Database
    $db = new Database();

    $book->add($title, $author, $publisher, $isbn, $total_copies);

    if ($book->update_book($db->conn, $id)) 
    {
        # ปิด Database
        $db->close();

        header('Location: ../manage_books.php?page=manage_books&EditBook=succeed');
        exit();
    } 
    else 
    {
        # ปิด Database
        $db->close();

        header('Location: ../manage_books.php?page=manage_books&EditBook=unsuccessful');
        exit();
    }

} else {
    header('Location: ../manage_books.php?page=manage_books');
    exit();
}
