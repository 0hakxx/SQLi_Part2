<?
    @session_start();
    header("Content-Type: text/html; charset=UTF-8");
    include_once("../common.php");
  
    $db_conn = mysql_conn();
    $id = $_POST["id"];
    $password = $_POST["password"];

    if(empty($id) || empty($password)) {
        echo "<script>alert('공백이 존재합니다.');history.back(-1);</script>";
        exit();
    }
    $query = "select * from members where id='{$id}' and password='{$password}'";
    
    $tmp = $db_conn->query($query);
    $cnt = $tmp->num_rows;
    $user = $tmp->fetch_assoc();
    if($cnt == 0) {
        echo "<script>alert('로그인 실패');history.back(-1);</script>";
        exit();
    }
    $_SESSION["mypage2_id"] = $user["id"];
    
    echo "<script>location.href='index.php?idx={$user["idx"]}'</script>";
?>