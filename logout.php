<?php

# ออกจากระบบ

session_start();

# ทำลาย SESSION
if (isset($_SESSION['user']))
{
    unset($_SESSION['user']);
} 


header('Location: index.php');

?>