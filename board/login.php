<?php
// UTF-8 인코딩 설정
header("Content-Type: text/html; charset=UTF-8");
// 공통 파일 포함
include ( './common.php' );

// 데이터베이스 연결
$db_conn = mysql_conn();
// POST 요청에서 사용자 입력 받기
$id = $_POST["id"];
$password = $_POST["password"];

// 사용자 입력이 비어있지 않은 경우 로그인 처리
if(!empty($id) && !empty($password)) {
    // 비밀번호 MD5 해시화 (보안에 취약한 방식)
    $password = md5($password);
    // SQL 쿼리 생성 (SQL 인젝션에 취약)
    $query = "select * from login_members where id='{$id}'";
    // 쿼리 실행
    $tmp = $db_conn->query($query);
    // 결과 가져오기
    $result = $tmp->fetch_assoc();

    // 비밀번호 확인
    if($password == $result["password"]) {
        // 로그인 성공 메시지
        $msg = "<span class=\"text-success\">[ \"{$result["id"]}\" Login Success ]</span>";
    } else {
        // 로그인 실패 메시지
        $msg = "<span class=\"text-danger\">[ Login Failed ]</span>";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Creative Login Page</title>

    <!-- Bootstrap CSS 파일 연결 -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- 사용자 정의 CSS 파일 연결 -->
    <link href="./css/form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="py-5 text-center">
        <h2><span data-feather="airplay"></span>&nbsp;Creative Login Page</h2>
    </div>
    <div class="row">
        <div class="col-md">
            <!-- 로그인 폼 시작 -->
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="text">ID</label>
                    <input type="text" name="id" class="form-control" placeholder="ID">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button class="btn btn-outline-dark btn-sm btn-block" type="submit">LOGIN</button>
                <hr class="mb-4">
            </form>
            <!-- 로그인 폼 끝 -->
        </div>
    </div>
    <footer class="my-5 pt-5  text-center text-small">
        <h2>
            <?php
            // 로그인 결과 메시지 출력
            if(!empty($msg)) {
                echo $msg;
            }
            ?>
        </h2>
    </footer>
</div>

<!-- Feather 아이콘 라이브러리 스크립트 -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    // Feather 아이콘 초기화
    feather.replace()
</script>
<!-- Bootstrap JavaScript 파일 연결 -->
<script src="./js/bootstrap.min.js"></script>
</body>
</html>
