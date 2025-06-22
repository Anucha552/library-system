<?php

# class จัดการผู้ใช้
class users
{

    # Properties user private
    private $userName;
    private $email;
    private $password;
    private $confirmPassword;

    # Properties user public
    public $userNameError = "";
    public $emailError = "";
    public $passwordError = "";
    public $confirmPasswordEorror = "";
    public $passworDunalike = "";

    # เซ็ตค่า Properties user
    public function add($userName, $email, $password, $confirmPassword)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    # ฟังก์ชันเช็ค Properties user ว่ามีค่าว่างหรือเปล่า
    public function check_vacancy()
    {
        $vacancy = true;

        if (empty($this->userName)) {
            $this->userNameError = "User Name ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        if (empty($this->email)) {
            $this->emailError = "E-mail ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        if (empty($this->password)) {
            $this->passwordError = "Password ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        if (empty($this->confirmPassword)) {
            $this->confirmPasswordEorror = "Confirm Password ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        return $vacancy;
    }

    # ฟังก์ชัน Confirm Password
    public function confirm_password()
    {
        if ($this->password == $this->confirmPassword) {
            return true;
        } else {
            $this->passworDunalike = "Password ไม่ตรงกัน";
            return false;
        }
    }

    # บันทึก User ลงในตาราง user
    public function record_user($conn)
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $dateTime = date("Y-m-d H:i:s");

        $sql = "INSERT INTO users (id, user_name, email, password, role, created_at) 
                VALUES ('', '$this->userName', '$this->email', '$this->password', 'user', '$dateTime')";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("เกิดข้อผิดพลาดในการบันทึกข้อมูล: " .  mysqli_error($conn));
        }
    }

    # ฟังก์ชันจำนวน User และ admin
    public function total_users_admin($conn, $role)
    {
        $sql = "SELECT COUNT(u.role) AS count
                FROM users AS u
                WHERE u.role = '$role'";
        $result = mysqli_query($conn, $sql);
        $number = mysqli_fetch_assoc($result);
        return $number['count'];
    }

    # ฟังก์ชัน Select User
    public function select_users($conn)
    {
        $sql = "SELECT * FROM users AS u
                ORDER BY u.id DESC";
        $result = mysqli_query($conn, $sql);

        $users = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            return $users;
        }
        return [];
    }

    # ฟังก์ชัน Udate สิทธิ์ User
    public function update_role_user($conn, $id, $role)
    {
        $sql = "UPDATE users AS u
                SET role = '$role'
                WHERE id = '$id'";
        mysqli_query($conn, $sql);
    }

    # ฟังก์ชัน Delete user
    public function delete_user($conn, $id)
    {
        $sql = "DELETE FROM users WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

        if (!$result)
        {
            die('ลบ User ไม่สำเร็จ');
        }
    }

    # ฟังก์ชั้น Select หนังสือที่เคยยืม
    public function select_borrowed($conn, $idUser,string $status)
    {
        $sql = "SELECT * FROM transactions AS t 
                JOIN books AS b ON t.book_id = b.id
                WHERE t.user_id = '$idUser'";

        if ($status != 'all')
        {
            $sql .= " AND t.status = '$status' OR t.status = 'late'";
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

}
