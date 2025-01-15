<?php
// UTF-8 인코딩을 사용하여 HTML 문서의 Content-Type 헤더를 설정
header("Content-Type: text/html; charset=UTF-8");

// MySQL 데이터베이스에 연결
$db_conn = new mysqli("localhost", "root", "123456", "board");

// GET 요청에서 'idx' 파라미터를 가져옴
$idx = $_GET["idx"];

// 'idx' 파라미터가 비어있는지 확인
if(empty($idx)) {
    // 비정상적인 접근 시 경고 메시지를 표시하고 이전 페이지로 돌아감
    echo "<script>alert('정상적인 접근이 아닙니다.');history.back(-1);</script>";
    exit;
}

// 파일 정보를 데이터베이스에서 조회하는 SQL 쿼리
$query = "select org_filename, real_filename from file_list where idx={$idx}";
$tmp = $db_conn->query($query);
$cnt = $tmp->num_rows;

// 조회된 결과가 없는 경우
if($cnt == 0) {
    // 파일이 존재하지 않음을 알리는 메시지를 표시하고 이전 페이지로 돌아감
    echo "<script>alert('파일이 존재하지 않습니다.');history.back(-1);</script>";
    exit;
}

// 조회된 파일 정보를 가져옴
$file = $tmp->fetch_assoc();
// 실제 파일 경로 설정
$filepath = "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Uploads\\{$file["real_filename"]}";

// 파일이 실제로 존재하는지 확인
if(is_file($filepath)) {
    // 파일 다운로드를 위한 헤더 설정
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename={$file["org_filename"]}");
    // 파일 내용을 출력하여 다운로드 실행
    readfile($filepath);
} else {
    // 파일이 존재하지 않을 경우 메시지를 표시하고 이전 페이지로 돌아감
    echo "<script>alert('파일이 존재하지 않습니다.');history.back(-1);</script>";
    exit;
}
?>
