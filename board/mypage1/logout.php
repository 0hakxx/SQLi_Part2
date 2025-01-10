<?
    @session_start();
    unset($_SESSION['mypage1_id']);
    session_destroy();
    echo "<script>location.href='login.php'</script>";
?>