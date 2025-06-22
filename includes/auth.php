<?php

# Class จัดการ Login/Session
class Auth {

    # Properties Auth private
    private $usernameOrEmail;
    private $password;

    # Properties Auth public
    public $usernameOrEmailError = "";
    public $passwordError = "";
    public $id = "";
    public $userName = "";
    public $email = "";
    public $role = "";


    # เซ็ตค่า Properties Auth
    public function add($usernameOrEmail, $password)
    {
        $this->usernameOrEmail = $usernameOrEmail;
        $this->password = $password;
    }

    # ฟังก์ชันเช็ค user name และ password ว่าเคยลงทะเบียนแล้วหรือยัง
    public function check_user_password($conn)
    {
        $sql = "SELECT * 
                FROM users AS u 
                WHERE u.user_name = '$this->usernameOrEmail' OR u.email = '$this->usernameOrEmail'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) 
        {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($this->password, $user['password']))
            {
                $this->id = $user['id'];
                $this->userName = $user['user_name'];
                $this->email = $user['email'];
                $this->role = $user['role'];
                return true;
            } 
            else
            {
                $this->passwordError = "Password ไม่ถูกต้อง";
                return false;
            }

        } 
        else 
        {
            $this->usernameOrEmailError = "User Name หรือ E-mail ไม่ถูกต้อง";
            return false;
        }
    }

    # ฟังก์ชั้นเช็คสิทธิ์ User
    public function check_role($role)
    {
        if ($this->role == $role)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    # ฟังก์ชันเช็ค Properties user และ password ว่ามีค่าว่างหรือเปล่า
    public function check_vacancy()
    {
        $vacancy = true;

        if (empty($this->usernameOrEmail))
        {
            $this->usernameOrEmailError = "User Name หรือ E-mail ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        if (empty($this->password))
        {
            $this->passwordError = "Password ห้ามมีช่องว่าง";
            $vacancy = false;
        }

        return $vacancy;
    }

}

?>