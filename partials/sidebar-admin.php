<!-- sidebar เอาไว้โชว์ในหน้อจอคอมสำหรับ Admin -->
<div class="position-fixed top-0 start-0 d-none d-md-block vh-100" style="background: linear-gradient(100deg, #8041c3 0%, #044bc4 100%); width: 250px;">
    <h4 class="text-center text-uppercase fw-bold text-light border-bottom border-warning " style="padding: 17px 0px;">library system</h4>
    <ul class="nav flex-column px-3">
        <li class="nav-item">
            <a href="../admin/dashboard.php?page=dashboard" class="nav-link hover-link-main rounded-2 <?php echo $Dasboard; ?>">
                <i class="bi bi-speedometer2 pe-2"></i>
                <span>Dasboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../admin/manage_books.php?page=manage_books" class="nav-link hover-link-main rounded-2 <?php echo $manage_books; ?>">
                <i class="bi bi-book-half pe-2"></i>
                <span>จัดการหนังสือ</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../admin/manage_users.php?page=manage_users" class="nav-link hover-link-main rounded-2 <?php echo $manage_users; ?>">
                <i class="bi bi-people pe-2"></i>
                <span>จัดการสมาชิก</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../admin/borrow_logs.php?page=borrow_logs" class="nav-link hover-link-main rounded-2 <?php echo $borrow_logs; ?>">
                <i class="bi bi-flag pe-2"></i>
                <span>รายงานการยืม-คืน</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../logout.php" class="nav-link hover-link-main rounded-2">
                <i class="bi bi-box-arrow-left pe-2"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
    <div class="position-absolute start-0 bottom-0 w-100 pb-3">
        <div class="d-flex column-gap-4 align-items-center p-4">
            <div class="text-white" style="font-size: 50px;">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="text-white h5">
                <span class="text-uppercase fw-bold">สถานะ </span><span><?php echo $_SESSION['user']['role']; ?></span><br>
                <div class="pb-4 border-bottom"></div>
            </div>
        </div>
    </div>
</div>