<?
  include_once("../common.php");
    // SQL 관련 오류만 표시하도록 설정
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 1);
  
  $db_conn = mssql_conn();

  # Search Logic
  $search_type = $_POST["search_type"];
  $keyword = iconv("UTF-8","EUC-KR",$_POST["keyword"]);

  if(empty($search_type) && empty($keyword)) {
    $query = "select * from {$tb_name}";
  } else {
    if($search_type == "all") {
      $query = "select * from {$tb_name} where title like '%{$keyword}%' or writer like '%{$keyword}%' or content like '%{$keyword}%'";
    } else {
      $query = "select * from {$tb_name} where {$search_type} like '%{$keyword}%'";
    }
  }

  # Sort Logic
  $sort = $_GET["sort"];
  $sort_column = $_GET["sort_column"];

  if(empty($sort_column) && empty($sort)) {
    $query .= " order by idx desc";
  } else {
    $query .= " order by {$sort_column} {$sort}";
  }
  
  $result = @mssql_query($query, $db_conn) or die("<br><br><br><center><h2>- 에러 발생 -</h2><h3>관리자에게 문의해주세요.</h3></center>");
  $num = @mssql_num_rows($result);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>::CREHACKTIVE SIMPLE BOARD - MSSQL::</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/pricing.css" rel="stylesheet">
  </head>

  <body>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-4">MSSQL SIMPLE BOARD</h1>
      <hr>
    </div>
    
    <div class="container">
		<div class="text-right">
			<button type="button" class="btn btn-outline-secondary" onclick="location.href='write.php'">Write</button>
		</div>
    <br>
		<table class="table">
		  <thead class="thead-light">
			<tr>
			  <th width="10%" scope="col" class="text-center"><a href="index.php?sort_column=idx&sort=desc" class="stretched-link text-dark">No</a></th>
			  <th width="50%" scope="col" class="text-center"><a href="index.php?sort_column=title&sort=desc" class="stretched-link text-dark">Title</a></th>
			  <th width="20%" scope="col" class="text-center"><a href="index.php?sort_column=writer&sort=desc" class="stretched-link text-dark">Write</a></th>
			  <th width="20%" scope="col" class="text-center"><a href="index.php?sort_column=regdate&sort=desc" class="stretched-link text-dark">Date</a></th>
			</tr>
		  </thead>
		  <tbody>
			<?
			if($num != 0) {
				for ( $i=0; $i<$num; $i++ ) {
				  $row = mssql_fetch_array($result);
			?>
			<tr>
			  <th scope="row" class="text-center"><?=$row["idx"]?></th>
        <? if($row["secret"]=="y") { ?>
        <td><span style="display:inline-block; height:15px; width:15px;" data-feather="lock"></span>&nbsp;<a href="auth.php?idx=<?=$row["idx"]?>&mode=view"><?=iconv("EUC-KR","UTF-8",$row["title"])?></a></td>
        <? } else { ?>
          <td><a href="view.php?idx=<?=$row["idx"]?>"><?=iconv("EUC-KR","UTF-8",$row["title"])?></a></td>
        <? } ?>
			  
			  <td class="text-center"><?=iconv("EUC-KR","UTF-8",$row["writer"])?></td>
			  <td class="text-center"><?=$row["regdate"]?></td>
			</tr>
			<?
				}
			} else {
			?>
            <tr>
              <td colspan="4" class="text-center">Posts does not exist.</td>
            </tr>
			<?
			}
			?>
		  </tbody>
		</table>

		<form action="index.php" method="POST">
			<div class="input-group mb-3">
        <div class="col-auto my-1">
          <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">search</label>
          <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="search_type">
            <option value="all" selected>All</option>
            <option value="title">title</option>
            <option value="writer">writer</option>
            <option value="content">content</option>
          </select>
        </div>
				<input type="text" class="form-control" placeholder="Keyword Input" name="keyword">
				<div class="input-group-append">
				<button class="btn btn-outline-secondary" type="submit">Search</button>
				</div>
			</div>
		</form>
    </div>
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
  mssql_free_result($result);
?>
