<?php

# class จัดการยืมหนังสือ และคืนหนังสือ
class Transaction {

    # ฟังก์ชันแสดงสถานะการยืม
    public function status($conn, $status) 
    {
        $sql = "SELECT COUNT(t.status) AS status
                FROM transactions AS t
                WHERE t.status = '$status'";
        $result = mysqli_query($conn, $sql);
        $numberOverdue = mysqli_fetch_assoc($result);
        return $numberOverdue['status'];
    }

    # ฟังก์ชัน Select การยืม
    public function select_transactions($conn, $date, $start_date = 0, $end_date = 0,)
    {
        $sql = "SELECT t.*, u.user_name AS name, b.title AS title
                FROM transactions AS t
                JOIN users AS u ON t.user_id = u.id
                JOIN books AS b ON t.book_id = b.id";

        if ($date)
        {
            $sql .= " WHERE t.borrow_date BETWEEN '$start_date' AND '$end_date'";
        }

        $sql .= " ORDER BY t.id DESC";

        $result = mysqli_query($conn, $sql);

        $transactions = [];
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $transactions[] = $row;
            }
            return $transactions;
        }

        return [];
    }

    # ฟังก์ชั้น Update สถานะการยืม
    public function update_status_borrow($conn)
    {
        $sql = "SELECT id, due_date, return_date FROM transactions";
        $resultR = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($resultR) > 0)
        {
            while ($row = mysqli_fetch_assoc($resultR))
            {
                if ($row['return_date'] == null)
                {
                    $toDay = new DateTime();
                    $targetDate = new DateTime($row['due_date']);

                    if ($toDay > $targetDate)
                    {
                        $status = "late";
                    }
                    else
                    {
                        $status = "borrowed";
                    }
                }
                else
                {
                    $status = "returned";
                }

                $sql = "UPDATE transactions AS t
                        SET status = '$status'
                        WHERE t.id = " . $row['id'];

                mysqli_query($conn, $sql);

            }
        }
    }

    # ฟังก์ชันยืมหนังสือ
    public function borrow_book($conn, $user_id, $book_id)
    {
        $sql = "SELECT available_copies FROM books WHERE id = '$book_id'";
        $result = mysqli_query($conn, $sql);
        $available_copies = mysqli_fetch_assoc($result);
        $available_copies = (int) $available_copies['available_copies'];
        $available_copies = $available_copies - 1;
        
        $sql = "UPDATE books SET available_copies = '$available_copies' WHERE id = '$book_id'";
        mysqli_query($conn, $sql);

        $borrow_date = date("Y-m-d");
        $due_date = date("Y-m-d", strtotime($borrow_date . ' +7 days'));
        $created_at = date("Y-m-d H:i:s");

        $sql = "INSERT INTO transactions (id, user_id, book_id, borrow_date, return_date, due_date, status, created_at) 
                VALUES ('', '$user_id', '$book_id', '$borrow_date', NULL, '$due_date', 'borrowed', '$created_at')";

        mysqli_query($conn, $sql);
    }

    # ฟังก์ชัน Select การยืมหนังสือทั้งหมด
    public function select_borrowed_book($conn, $user_id)
    {
        $sql = "SELECT t.id AS t_id, t.book_id AS book_id, b.*
                FROM transactions AS t 
                INNER JOIN books AS b ON t.book_id = b.id
                WHERE (t.status = 'borrowed' OR t.status = 'late') AND t.user_id = '$user_id'
                ORDER BY t.id DESC";

        $result = mysqli_query($conn, $sql);

        $rows = [];
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_assoc($result))
            {
                $rows[] = $row;
            }
            return $rows;
        }
        return [];
    }

    # ฟังก์ช้นคืนหนังสือ
    public function return_book($conn, $id, $book_id)
    {
        $sql = "SELECT available_copies FROM books WHERE id = '$book_id'";
        $result = mysqli_query($conn, $sql);
        $available_copies = mysqli_fetch_assoc($result);
        $available_copies = (int) $available_copies['available_copies'];
        $available_copies = $available_copies + 1;

        $sql = "UPDATE books SET available_copies = '$available_copies' WHERE id = '$book_id'";
        mysqli_query($conn, $sql);

        $return_date = date('y-m-d');

        $sql = "UPDATE transactions
                SET return_date = '$return_date', status = 'status'
                WHERE id = '$id'";

        mysqli_query($conn, $sql);
    }

}

?>