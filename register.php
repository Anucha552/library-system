<?php

# หน้าสมัครสมาชิก

include_once('includes/user.php');
include_once('includes/database.php');

$RegisterForm = 'd-block';
$regsterSucceed = 'd-none';

# สร้าง Object User
$user = new users();

# ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    # ตัวแปรรับค่าจากฟอร์ม
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    # Add ค่า user
    $user->add($userName, $email, $password, $confirmPassword);

    # เช็คว่ามีค่าว่างหรือเปล่า
    if ($user->check_vacancy()) {
        # เช็ค Confirm Password
        if ($user->confirm_password()) {
            # สร้าง Object Database
            $db = new Database();
            # บันทึก User
            $user->record_user($db->conn);
            # ปิดการเชื่อมต่อฐานข้อมูล
            $db->close();
            $RegisterForm = 'd-none';
            $regsterSucceed = 'd-block';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center vh-100" style="background: linear-gradient(135deg, #8041c3 10%, #044bc4 100%);">

    <!-- Register -->
    <div class="container">
        <h1 class="text-center text-uppercase text-white pb-4">Register</h1>
        <div class="row justify-content-center">
            <div class="col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7">
                <div class="row bg-white rounded-3">
                    <div class="d-none d-sm-block col-sm-6 bg-body-secondary rounded-start-3">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <img src="assets/images/register.png" alt="" width="60%" class="object-fit-cover">
                        </div>
                    </div>
                    <div class="col-sm-6 text-center p-4">
                        <h3 class="pb-4 pt-1">ลงทะเบียน</h3>
                        <div class="<?php echo $RegisterForm; ?>">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <div>
                                    <input type="text" class="form-control" name="userName" id="userName" placeholder="User Name *">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $user->userNameError; ?></span>
                                    </div>
                                </div>
                                <div>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="E-mail *">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $user->emailError; ?></span>
                                    </div>
                                </div>
                                <div>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password *">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $user->passwordError; ?></span>
                                    </div>
                                </div>
                                <div>
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password *">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $user->confirmPasswordEorror;
                                                                    echo $user->passworDunalike; ?></span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary rounded-pill fw-bold w-100 py-2">SUBMIT</button>
                                </div>
                                <div class="pb-2" style="font-size: 14px; color: rgb(199, 200, 201);">
                                    <span>เคยลงทะเบียนแล้ว<a href="index.php" class="link-opacity-50" style="text-decoration: none;"> ลงชื่อเช้าใช้งาน</a></span>
                                </div>
                            </form>
                        </div>
                        <div class="<?php echo $regsterSucceed ?>">
                            <div class="p-5 my-5">
                                <h4>ลงทะเบียนสำเสร็จ</h4>
                                <a href="index.php" class="link-opacity-50" style="text-decoration: none;">ลงชื่อเช้าใช้งาน</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>