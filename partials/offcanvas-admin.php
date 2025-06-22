<!-- offcavas เอาไว้โชว์ในหน้อจอมือถือสำหรับ Admin -->
<div class="offcanvas offcanvas-start" id="offcanvas" style="background: linear-gradient(135deg, #8041c3 0%, #044bc4 100%);">
    <div class="offcanvas-header py-3 border-bottom border-warning ">
        <div class="offcanvas-title">
            <h4 class="text-center text-uppercase fw-bold text-light ">library system</h4>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a href="../admin/dashboard.php?page=dashboard" class="nav-link hover-link-main rounded-2">
                    <i class="bi bi-speedometer2 pe-2"></i>
                    <span>Dasboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/manage_books.php?page=manage_books" class="nav-link hover-link-main rounded-2">
                    <i class="bi bi-book-half pe-2"></i>
                    <span>จัดการหนังสือ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/manage_users.php?page=manage_users" class="nav-link hover-link-main rounded-2">
                    <i class="bi bi-people pe-2"></i>
                    <span>จัดการสมาชิก</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/borrow_logs.php?page=borrow_logs" class="nav-link hover-link-main rounded-2">
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
        <div class="position-absolute start-0 bottom-0 w-100 pb-3 ps-3">
            <div class="d-flex column-gap-4 align-items-center p-4">
                <div class="text-white" style="font-size: 50px;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="text-white">
                    <span class="text-uppercase fw-bold">สถานะ </span><span><?php echo $_SESSION['user']['role']; ?></span><br>
                    <div class="pb-4 border-bottom"></div>
                </div>
            </div>
        </div>
    </div>
</div>