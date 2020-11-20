<?php
function redirect($url) //https://www.exchangecore.com/blog/how-redirect-using-php
{
    header('Location: '.$url);
    exit();
}
//We need to declare this function in order to use sessions previously generated in our code.
session_start();
//Error/success message variables
$otpErr = $registered = $otppErr = $inc = $suc = '';
//We check if the form was submitted and the optcode field isn't empty.
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['otpcode'])) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = '';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    //This if statement finds the account where the optcode and username matches in the database.
    if ($stmt = $con->prepare('SELECT id FROM accounts WHERE otpcode = ? AND username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc)
        //We use the session we created on the index.php to verify that the user logged in can only use the code generated for their account.
        $stmt->bind_param('ss', $_POST['otpcode'], $_SESSION['name']);
        $stmt->execute();
        $stmt->store_result();
        // Store the result so we can check if the account exists in the database.
        if ($stmt->num_rows > 0) {
            // OTP exists
            session_regenerate_id();
            //Here we create a new session called "authorized" and set it to boolean value TRUE to validate that the user has been authorized to see the homepage message that only valid account holders can access!
			$_SESSION['authorized'] = TRUE;
            $registered = "Good"; //Debugging purposes
            redirect('home.php');
        }
        $otpErr = "Incorrect OTP";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Tells the user that the OTP field is empty
    if (empty($_POST["otpcode"])) {
      $otppErr = "OTP field empty. Please enter your OTP Code";
    }
    //Alerts the user that the OTP they entered was incorrect.
    elseif (!empty($otpErr)) {
        $inc = "Incorrect OTP, please try again";
    }
    if (!empty($registered)) {
        $suc = "Success OTP"; //Debugging purposes
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="bkg">
			<h1 class="lh1">2FA Login</h1>
            <span class="er"><?php echo $inc;?></span>
            <span class="er"><?php echo $otppErr;?></span>
            <span class="er"><?php echo $suc;?></span>
			<form class="lfrm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<label class="label" for="otpcode">
                <i class="fas fa-key"></i>
				</label>
				<input class="ltxt" type="text" name="otpcode" placeholder="Enter OPT Code" id="otpcode">
                <!--Here we create the cancel button that resets all the field variables and re routes the user back to the index.php page or login page.
                The user must log in again to navigate back to the OTP page or they technically could type in the URL to the otp.php page and enter their OTP code again.-->
                <button class="rbtn" type="reset" onclick="location.href='index.php'">Cancel</button>
                <input class="side" type="submit" value="Submit">
                
            </form>
            <div id="back"></div>
			<div id="front"></div>
		</div>
	</body>
</html>