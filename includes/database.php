<?php 

# class เชื่อมต่อฐานข้อมูล
class Database{

    # ข้อมูลสำหรับการเชื่อมต่อฐานข้อมูล
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_library_system";

    public $conn;

    # เชื่อมต่อฐานข้อมูลหลังสร้าง Object
    public function __construct()
    {
        $this->connect();
    }

    # ฟังก์ชันสำหรับเชื่อมต่อฐานข้อมูล
    public function connect()
    {
        $this->conn = mysqli_connect(
            $this->hostname,
            $this->username,
            $this->password,
            $this->database
        );

        if (!$this->conn){
            die('เชื่อมต่อฐานข้อมูลล้มเหลว: ' . mysqli_connect_error($this->conn));
        }

        mysqli_set_charset($this->conn, "utf8");
        
        return $this->conn;
    }

    # ฟังก์ชันสำหรับปิดการเชื่อมต่อฐานข้อมูล
    public function close()
    {
        mysqli_close($this->conn);
    }

}

?>