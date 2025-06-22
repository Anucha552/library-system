
<!-- Navber Admin -->
<nav class="position-fixed top-0 start-0 w-100" style="padding: 8px; background: linear-gradient(100deg,rgb(108, 9, 213) 0%, #044bc4 45%);">
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas">
                    <i class="bi bi-list text-white" style="font-size: 20px;"></i>
                </button>
            </div>
            <div class="col-10 d-flex justify-content-end">
                <div class="text-white" style="font-size: 16px;">
                    <div class="item">
                        <i class="bi bi-person-badge pe-2"></i>
                        <span><?php echo $_SESSION['user']['userName']; ?></span>
                    </div>
                    <div class="item">
                        <i class="bi bi-envelope-at pe-2"></i>
                        <span><?php echo $_SESSION['user']['email']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>