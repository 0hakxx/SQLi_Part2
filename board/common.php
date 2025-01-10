<?php
header("Content-Type: text/html; charset=UTF-8;");

# 공통 테이블 명
$tb_name = "tb_board";

//Mysql 연결
function mysql_conn() {
    $host = "127.0.0.1";
    $id = "root";
    $pw = "123456";
    $db = "board";

    $db_conn = new mysqli($host, $id, $pw, $db);

    return $db_conn;
}



//강의에서 제공해주는 코드는 PHP 5버전이며, 현재 최신 환경에서는 지원하지 않는 함수들에 대한 정의를 함
//Oracle, Mysql은 이상없음.
function mssql_conn() {
    try {
        $pdo = new PDO(
            "sqlsrv:Server=127.0.0.1;Database=board;",
            "sa",
            "123456",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8)
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function mssql_query($query, $connection = null, $params = array()) {
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        echo "Query error: " . $e->getMessage();
        return false;
    }
}

function mssql_num_rows($result) {
    if ($result === false) {
        return 0;
    }
    // 전체 결과를 저장
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $result->fetchAll();
    // 커서를 처음으로 되돌림
    $result->execute();
    return count($rows);
}

function mssql_fetch_array($result) {
    if ($result === false) {
        return false;
    }

    static $allRows = null;
    static $position = 0;

    // 처음 호출시 모든 결과를 가져옴
    if ($allRows === null) {
        $allRows = $result->fetchAll(PDO::FETCH_ASSOC);
        $position = 0;
    }

    if ($position >= count($allRows)) {
        $allRows = null;
        $position = 0;
        return false;
    }

    $row = $allRows[$position];
    $combined = array();
    $i = 0;

    foreach ($row as $key => $value) {
        $combined[$i] = $value;
        $combined[$key] = $value;
        $i++;
    }

    $position++;
    return $combined;
}


//Oracle 연결
function mssql_free_result($result) {
    $result->closeCursor();
}


function oracle_conn() {
    $host = "127.0.0.1/XE";
    $id = "system";
    $pw = "123456";

    $db_conn = oci_connect($id, $pw, $host, 'AL32UTF8');

    return $db_conn;
}
?>