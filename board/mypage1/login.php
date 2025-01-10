<?
    @session_start();
    header("Content-Type: text/html; charset=UTF-8");
?>


<form method="POST" action="loginAct.php">
<li>ID : <input type="text" name="id"></li>
<li>PW : <input type="password" name="password"></li>
<input type="submit" value="LOGIN">
</form>