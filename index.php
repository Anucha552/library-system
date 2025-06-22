<?php

# หน้า login

session_start();

include_once('includes/database.php');
include_once('includes/auth.php');

# เช็ค SESSION User ถูกสร้างแล้วหรือยัง
if (isset($_SESSION['user']))
{
    if ($_SESSION['user']['role'] == 'admin')
    {
        header("Location: admin/dashboard.php?page=dashboard");
        exit();
    }
    else
    {
        header("Location: user/dashboard.php?page=dashboard");
        exit();
    }
}

# สร้าง Object Auth
$auth = new Auth();

# ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    # ตัวแปรรับค่าจากฟอร์ม
    $userOrEmail = $_POST['userName'];
    $password = $_POST['password'];

    # Add ค่า user or email และ password
    $auth->add($userOrEmail, $password);

    # เช็คว่ามีค่าว่างหรือเปล่า
    if ($auth->check_vacancy())
    {
        # สร้าง Object Database
        $db = new Database();

        # เช็ค User กับ Password
        if ($auth->check_user_password($db->conn))
        {
            # เซ็ต User ให้กับ Session
            $_SESSION['user'] = array(
                "id" => $auth->id,
                "userName" => $auth->userName,
                "email" => $auth->email,
                "role" => $auth->role
            );

            # ปิด Database
            $db->close();

            # เช็คสิทธ์ User
            if ($auth->check_role('admin'))
            {
                header("Location: admin/dashboard.php?page=dashboard");
                exit();
            }
            else
            {
                header("Location: user/dashboard.php?page=dashboard");
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex align-items-center vh-100" style="background: linear-gradient(135deg, #8041c3 10%, #044bc4 100%);">
        
        <!-- Login -->
        <div class="container">
            <h1 class="text-center text-uppercase text-white pb-4">login</h1>
            <div class="row justify-content-center">
                <div class="col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7">
                    <div class="row bg-white rounded-3">
                        <div class="col-sm-6 text-center p-4 mt-1">
                            <h3 class="pb-5 pt-4">ลงชื่อเช้าใช้งาน</h3>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                <div class="mb-1">
                                    <input type="text" class="form-control" name="userName" id="userName" placeholder="User Name Or E-mail">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $auth->usernameOrEmailError; ?></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="User Password">
                                    <div class="p-2 text-start ms-2">
                                        <span style="color: red;"><?php echo $auth->passwordError; ?></span>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary w-100 text-white rounded-pill fw-bold py-2">ลงชื่อเข้าใช้</button>
                                </div>
                                <div class="pb-2" style="font-size: 14px; color: rgb(199, 200, 201);">
                                    <span>ยังไม่ได้เป็นสมาชิก<a href="register.php" class="link-opacity-50" style="text-decoration: none;"> ลงทะเบียน</a></span>
                                </div>
                            </form>
                        </div>
                        <div class="d-none d-sm-block col-sm-6 bg-body-secondary rounded-end-3">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <img src="assets/images/stack-of-books.png" alt="" width="60%" class="object-fit-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>