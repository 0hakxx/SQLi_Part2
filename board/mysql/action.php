<?
	header("Content-Type: text/html; charset=UTF-8");
	include ( '../common.php' );

	$mode = $_REQUEST["mode"];
	$db_conn = mysql_conn();
	
	if($mode == "write") {
		$title = $_POST["title"];
		$writer = $_POST["writer"];
		$password = $_POST["password"];
		$content = $_POST["content"];
		$secret = $_POST["secret"];

		if(empty($title) || empty($writer) || empty($password) || empty($content)) {
			echo "<script>alert('빈칸이 존재합니다.');history.back(-1);</script>";
			exit();
		}
		
		if($secret == "on") {
			$secret = "y";
		} else {
			$secret = "n";
		}
		
		$content = str_replace("\r\n", "<br>", $content);
		
		$query = "insert into {$tb_name}(title, writer, password, content, secret, regdate) values('{$title}', '{$writer}', '{$password}', '{$content}', '{$secret}', now())";
		$db_conn->query($query) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
	} else if($mode == "modify") {
		$idx = $_POST["idx"];
		$title = $_POST["title"];
		$writer = $_POST["writer"];
		$password = $_POST["password"];
		$content = $_POST["content"];
		$secret = $_POST["secret"];

		if(empty($idx) || empty($title) || empty($writer) || empty($password) || empty($content)) {
			echo "<script>alert('빈칸이 존재합니다.');history.back(-1);</script>";
			exit();
		}

		# Password Check Logic
		$query = "select * from {$tb_name} where idx={$idx} and password='{$password}'";
		$result = $db_conn->query($query);
		$num = $result->num_rows;

		if($num == 0) {
			echo "<script>alert('패스워드가 일치하지 않습니다.');history.back(-1);</script>";
			exit();
		}
		
		if($secret == "on") {
			$secret = "y";
		} else {
			$secret = "n";
		}
		
		$content = str_replace("\r\n", "<br>", $content);
		
		$query = "update {$tb_name} set title='{$title}', writer='{$writer}', content='{$content}', secret='{$secret}', regdate=now() where idx={$idx}";
		$db_conn->query($query);
	} else if($mode == "delete") {
		$idx = $_POST["idx"];
		$password = $_POST["password"];
		
		# Password Check Logic
		$query = "select * from {$tb_name} where idx={$idx} and password='{$password}'";
		$result = $db_conn->query($query);
		$num = $result->num_rows;

		if($num == 0) {
			echo "<script>alert('패스워드가 일치하지 않습니다.');history.back(-1);</script>";
			exit();
		}
		
		
		$query = "delete from {$tb_name} where idx={$idx}";
		$db_conn->query($query);
	}

	echo "<script>location.href='index.php';</script>";
	$db_conn->close();
?>