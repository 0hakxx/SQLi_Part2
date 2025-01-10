<?
  include_once("../common.php");
  
  $db_conn = oracle_conn();
  $idx = $_GET["idx"];

  $query = "select * from {$tb_name} where idx={$idx}";
  
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
      <h1 class="display-4">Modify Page</h1>
      <hr>
    </div>
	<?
	if(($row = oci_fetch_assoc($result)) != false) {
	?>
    <div class="container">
		<form action="action.php" method="POST">
		  <div class="form-group">
			<label>Title</label>
			<input type="text" class="form-control" name="title" placeholder="Title Input" value="<?=$row["TITLE"]?>">
		  </div>
		  <div class="form-group">
			<label>Writer</label>
			<input type="text" class="form-control" name="writer" placeholder="Writer Input" value="<?=$row["WRITER"]?>">
		  </div>
		  <div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" name="password" placeholder="Password Input">
		  </div>
		  <div class="form-group">
			<label for="exampleInputPassword1">Contents</label>
			<textarea class="form-control" name="content" rows="5" placeholder="Contents Input"><?=$row["CONTENT"]->load()?></textarea>
		  </div>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="customCheck1" name="secret" <? if($row["SECRET"]=="y") echo "checked"; ?>>
        <label class="custom-control-label" for="customCheck1">Secret Post</label>
      </div>
		<div class="text-right">
			<input type="hidden" name="idx" value="<?=$row["IDX"]?>">
			<input type="hidden" name="mode" value="modify">
			<button type="submit" class="btn btn-outline-secondary">Modify</button>
			<button type="button" class="btn btn-outline-danger" onclick="history.back(-1);">Back</button>
		</div>
		</form>
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