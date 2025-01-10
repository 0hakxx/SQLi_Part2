<?
    @session_start();
    header("Content-Type: text/html; charset=UTF-8");
    include_once("../common.php");

    $idx = $_GET["idx"];
      
    if(empty($_SESSION["mypage2_id"])) {
        echo "<script>location.href='login.php';</script>";
        exit;
    }

    // SQL 관련 오류만 표시하도록 설정
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 1);

    if(empty($idx)) {
        echo "<script>alert('정상적인 접근이 아닙니다.');history.back(-1);</script>";
        exit;
    }

    $db_conn = mysql_conn();
    $query = "select * from members where idx={$idx}";
    $tmp = $db_conn->query($query);
    $cnt = $tmp->num_rows;
    $user = $tmp->fetch_assoc();

    if($cnt == 0) {
        echo "<script>alert('정상적인 접근이 아닙니다.');history.back(-1);</script>";
        exit();
    }

    if($_SESSION["mypage2_id"] != $user["id"]) {
        echo "<script>alert('정상적인 접근이 아닙니다.');history.back(-1);</script>";
        exit;
    }
    
?>
<h2>MyPage</h2>
<hr>
<li>idx : <?=$user["idx"]?></li>
<li>id : <?=$user["id"]?></li>
<li>password : <?=$user["password"]?></li>
<li>jumin : <?=$user["jumin"]?></li>
<br>
<input type="button" onclick="location.href='logout.php'" value="LOGOUT">