<?
	header("Content-Type: text/html; charset=UTF-8");
	include ( '../common.php' );
	$mode = $_REQUEST["mode"];
	$db_conn = mssql_conn();
	
	if($mode == "write") {
		# 한글 깨짐 방지를 위한 UTF-8 > EUC-KR 인코딩 변환
		$title = iconv("UTF-8", "EUC-KR",$_POST["title"]);
		$writer = iconv("UTF-8", "EUC-KR",$_POST["writer"]);
		$content = iconv("UTF-8", "EUC-KR",$_POST["content"]);
		$password = $_POST["password"];
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
		
		$query = "insert into {$tb_name}(title, writer, password, content, secret, regdate) values('{$title}', '{$writer}', '{$password}', '{$content}', '{$secret}', getdate())";
		@mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
	} else if($mode == "modify") {
		$idx = $_POST["idx"];
		# 한글 깨짐 방지를 위한 UTF-8 > EUC-KR 인코딩 변환
		$title = iconv("UTF-8", "EUC-KR",$_POST["title"]);
		$writer = iconv("UTF-8", "EUC-KR",$_POST["writer"]);
		$content = iconv("UTF-8", "EUC-KR",$_POST["content"]);
		$password = $_POST["password"];
		$secret = $_POST["secret"];

		if(empty($idx) || empty($title) || empty($writer) || empty($password) || empty($content)) {
			echo "<script>alert('빈칸이 존재합니다.');history.back(-1);</script>";
			exit();
		}

		# Password Check Logic
		$query = "select * from {$tb_name} where idx={$idx} and password='{$password}'";
		$result = @mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
		$num = @mssql_num_rows($result);

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
		
		$query = "update {$tb_name} set title='{$title}', writer='{$writer}', content='{$content}', secret='{$secret}', regdate=getdate() where idx={$idx}";
		@mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
		mssql_free_result($result);
	} else if($mode == "delete") {
		$idx = $_POST["idx"];
		$password = $_POST["password"];
		
		# Password Check Logic
		$query = "select * from {$tb_name} where idx={$idx} and password='{$password}'";
		$result = @mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
		$num = @mssql_num_rows($result);

		if($num == 0) {
			echo "<script>alert('패스워드가 일치하지 않습니다.');history.back(-1);</script>";
			exit();
		}
		
		mssql_free_result($result);
		$query = "delete from {$tb_name} where idx={$idx}";
		@mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
	}

	echo "<script>location.href='index.php';</script>";

?>