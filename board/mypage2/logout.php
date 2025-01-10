<?
    @session_start();
    unset($_SESSION['mypage2_id']);
    session_destroy();
    echo "<script>location.href='login.php'</script>";
?>