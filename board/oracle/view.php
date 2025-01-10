<?
	include_once("../common.php");

	$db_conn = oracle_conn();
	$idx = $_REQUEST["idx"];
	$password = $_POST["password"];

	if(empty($password)) {
		$query = "select * from {$tb_name} where idx={$idx} and secret='n'";
	} else {
		$query = "select * from {$tb_name} where idx={$idx} and password='{$password}'";
	}

	$result = @oci_parse($db_conn, $query) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
	@oci_execute($result) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>::CREHACKTIVE SIMPLE BOARD - ORACLE::</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/pricing.css" rel="stylesheet">
  </head>

  <body>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">View Page</h1>
    	<hr>
    </div>
    
    <div class="container">
	<?
	if(($row = oci_fetch_assoc($result)) != false) {
	?>

		<table class="table table-bordered">
		  <tbody>
			<tr>
			  <th scope="row" width="20%" class="text-center">Title</th>
			  <td><?=$row["TITLE"]?></td>
			</tr>
			<tr>
			  <th scope="row" width="20%" class="text-center">Writer</th>
			  <td><?=$row["WRITER"]?></td>
			</tr>
			<tr>
			  <th scope="row" width="20%" class="text-center">Date</th>
			  <td><?=$row["REGDATE"]?></td>
			</tr>
			<tr>
			  <th scope="row" width="20%" class="text-center">Contents</th>
			  <td><?=$row["CONTENT"]->load()?></td>
			</tr>
		  </tbody>
		</table>
		<div class="text-right">
			<button type="button" class="btn btn-outline-secondary" onclick="location.href='modify.php?idx=<?=$row["IDX"]?>'">Modify</button>
			<button type="button" class="btn btn-outline-danger" onclick="location.href='auth.php?mode=delete&idx=<?=$row["IDX"]?>'">Delete</button>
			<button type="button" class="btn btn-outline-warning" onclick="location.href='index.php'">List</button>
		</div>
    </div>
	<?
	} else {
	?>
		<script>alert("존재하지 않는 게시글 입니다.");history.back(-1);</script>
	<?
	}
	?>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery-slim.min.js"><\/script>')</script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>
  </body>
</html>
<?
  oci_free_statement($result);
  oci_close($db_conn);
?>
