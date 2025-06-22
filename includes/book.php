<?php

# class จัดการหนังสือ
class Book {

    # Properties book private
    private $title;
    private $author;
    private $publisher;
    private $isbn;
    private $total_copies;

    # เซ็ตค่า Properties book
    public function add($title, $author, $publisher, $isbn, $total_copies)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->isbn = $isbn;
        $this->total_copies = $total_copies;
    }

    # ฟังก์ชันจำนวนหนังสือทั้งหมด
    public function total_books($conn)
    {
        $sql = "SELECT COUNT(b.id) as count
                FROM books AS b";
        $result = mysqli_query($conn, $sql);
        $numberBook = mysqli_fetch_assoc($result);
        return $numberBook['count'];
    }

    # หังก์ชันบันทึก Book ลงในตาราง Books
    public function record_book($conn)
    {
        $dateTime = date("Y-m-d");

        $sql = "INSERT INTO books (id, title, author, publisher, isbn, total_copies, available_copies, created_at) 
                VALUES ('', '$this->title', '$this->author', '$this->publisher', '$this->isbn', '$this->total_copies', '$this->total_copies', '$dateTime')";
        
        mysqli_query($conn, $sql);
    }

    # ฟังก์ชัน Select ตาราง book
    public function select_books($conn)
    {
        $sql = "SELECT * FROM books AS b
                ORDER BY b.id DESC";
        $result = mysqli_query($conn, $sql);

        $books = []; 
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $books[] = $row;
            }
            return $books;
        }
        return [];
    }

    # ฟังก์ชันเช็ค Properties book ว่ามีค่าว่างหรือเปล่า
    public function check_vacancy()
    {
        $vacancy = true;

        if (empty($this->title))
        {
            $vacancy = false;
        }

        if (empty($this->author))
        {
            $vacancy = false;
        }

        if (empty($this->publisher))
        {
            $vacancy = false;
        }

        if (empty($this->isbn))
        {
            $vacancy = false;
        }

        if (empty($this->total_copies))
        {
            $vacancy = false;
        }

        return $vacancy;
    }

    # ฟังก์ชัน Update หนังสือ
    public function update_book($conn, $id)
    {
        $sql = "SELECT total_copies, available_copies
                FROM books AS b
                WHERE b.id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $new_total_copies = (int)$this->total_copies;
        $total_copies = (int)$row['total_copies'];
        $available_copies = (int)$row['available_copies'];

        if ($new_total_copies >= $total_copies)
        {
            $copies = $new_total_copies - $total_copies;
            $total_copies = $total_copies + $copies;
            $available_copies = $available_copies + $copies;

            $sql = "UPDATE books AS b
                SET title = '$this->title', author = '$this->author', publisher = '$this->publisher', isbn = '$this->isbn', total_copies = '$total_copies', available_copies = '$available_copies'
                WHERE b.id = $id";
                mysqli_query($conn, $sql);

                return true;
        } else {
            return false;
        }

    }

    # ฟังก์ชัน Delete หนังสือ
    public function delete_book($conn, $id)
    {
        $sql = "DELETE FROM books WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

        if (!$result)
        {
            die('ลบหนังสือไม่สำเร็จ');
        }
    }

    # ฟังก์ชันค้นหาหนังสือ
    public function search_book($conn ,string $search)
    {
        $sql = "SELECT * FROM books AS b
                WHERE title LIKE '%" . $search . "%' 
                ORDER BY b.id DESC";

        $result = mysqli_query($conn, $sql);

        $books = [];
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $books[] = $row;
            }
            return $books;
        }
        return [];
    }

    # ฟังก์ชันเรียกดูหนังสือว่ายังมีอยู่กี่เล่ม
    public function select_available_copies($conn, $book_id)
    {
        $sql = "SELECT available_copies FROM books WHERE id = '$book_id'";
        $result = mysqli_query($conn, $sql);
        $available_copies = mysqli_fetch_assoc($result);
        $available_copies = (int) $available_copies['available_copies'];
        return $available_copies;
    }

}

?>