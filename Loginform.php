<?php
//Initialise the session

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="CSSOnly/loginform.css">

</head>
<html>
<head>
<title>Login Info</title>
<style>
p.center {text-align:center}
</style>
</head>
<body>
<h1>Login Information</h1>
<hr>

<form method="POST" action="login.php">
<table width="40%" border="1" align="center">
<tr><th>USERNAME:</th>
	<td><input type="text" name="username"></td></tr>
<tr><th>PASSWORD:</th>
	<td><input type="password" name="password"></td></tr>
<tr></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="LOGIN">
<input type="reset" name="reset" value="CLEAR FORM"></td></tr>
</table>
</form>
<p class="center"><b>New user? <a href="registerform.php">Sign-up now..</a></b></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
  </tr>
</table>
</form>
</html>