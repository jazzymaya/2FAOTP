<?php 
session_start();
//We check if the session generaed on the OTP page is TRUE.
if (isset($_SESSION['authorized']))
//We create a new variable named "auth" to be used as conditional within our form.
$auth = $_SESSION['authorized'];
else
//If the session was not generated, set auth to empty. This can happend if the user refreshes the page after successfully reaching the home.php page.
$auth = '';
?>
<!DOCTYPE HTML>  
<html>
<head>
		<meta charset="utf-8">
		<title>Home</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
	<body> 
<div class="register">
      <h1 class="rh1">Home</h1>
      <!--This is the homepage message that only shows if the user has been properly authenticated!-->
      <span class="msg"><?php if ($auth) echo 'Hello ' . $_SESSION['name'] . ', you are authorized!';?></span> 
      <!--This is the error message that only shows if the user refreshes the page.-->
      <span class="er"><?php if (empty($auth)) echo "Opps! Session has been lost. Please click on the Return button below and log in again!";?></span>
      <br><br>
      <br><br>
      <!--Upon clicking the return button, we release and destroy the sessions saved.-->
      <?php session_unset(); session_destroy();?>
      <button class="hbtn" type="reset" onclick="location.href='index.php'">Return</button>
        </div>
        <div id="back"></div>
		<div id="front"></div>
</body>
</html>