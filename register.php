<!DOCTYPE HTML>  
<html>
<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
	<body> 
<?php

//We defined variables that hold error messages and set to empty values
$usernameErr = $usernameVal = $usernameMatchErr = $emailErr = $passwordErr =  $passwordMatchErr = $passwordVal = $fieldErr = $regerr = $stmterrmsg = $success = "";
//Variables that will hold our POST data from the user
$username = $email = $password = "";
//Checks if the user submitted the form
if($_SERVER['REQUEST_METHOD'] == 'POST') {
//Here we declare our database information. This would be the same information provided on the index page. We could have optimized our application by including this information in a "config.php" file and call it each time we need database credentials.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = '';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//This function trims any leading or trailing whitespaces, takes off the "\" characters, and converts predefined characters such as "<" to HTML entities such as "&lt"
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Here we clean the POST data and assign it to our username, password, and email variables
$username = test_input($_POST["username"]);
$email = test_input($_POST["email"]);
$password = test_input($_POST["password"]);

//Here we peform logic statements to make sure our users enters everything in the correct format.
//If any of the form fields are empty, the fieldErr vairable will change from empty to an actual error message.
  if (empty($username) || empty($password) || empty($email)) {
    $fieldErr = "* All fields must be filled";
  } 
  //If the username variable is empty and the previous fieldErr variable isnt set to empty, set the usernameErr to hold an *. This is echoed later for the user to understand what field the error was produced in.
  if (empty($username) && !empty($fieldErr)) {
    $usernameErr = "*";
  }
  //Same idea as the previous if statement but for the email field.
  if  (empty($email) && !empty($fieldErr)) {
    $emailErr = "*";
  }
  //Same idea as the previous if statement but for the password field.
  if (empty($password) && !empty($fieldErr)) {
    $passwordErr = "*";
  } 
  //Here we double check that the username field is not empty AND the user has an input that only contains letters & numbers AND the fieldErr variable is empty (to ensure the user only sees one error message at a time)
  if (!empty($username) && !preg_match('/^[A-Za-z0-9]*$/',($username)) && empty($fieldErr)) {
    //usernameMatchErr is set to hold an * to echo back to the user where the error is located.
    $usernameMatchErr = "*";
    //usernameVal hold the actual error message as to why the user is getting the error.
    $usernameVal = "* Username contains invalid characters. Only letters and numbers are acceptable!";
  }
  //Here we used elseif to make sure the user only sees one error message at a time. Same logic as the previous if statement except, the password field can only contain numbers, letters, and the special characters: ! @ # and $
  elseif (!empty($password) && !preg_match('/^[A-Za-z0-9!@#$]*$/',($password)) && empty($fieldErr)) {
    $passwordMatchErr = "*";
    $passwordVal = "* Password contains invalid characters. Only letters, numbers, and special characters ! @ # $ are acceptable!";
  }

  //This statement checks if the submission from the user is POST, and if the username, password and email error variables are empty, signifying that the input is clean enough to enter into our databse
  //The logic of handling error messages could be optimized to have all error messages in one variable.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($fieldErr) && empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($usernameMatchErr) && empty($passwordMatchErr)) {
//Here we prepare an SQL statement to be executed against our database.
  if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the Username exists in the database.
	if ($stmt->num_rows > 0) {
		//Username already exists. Users cannot create a username that is already in our database.
    $registered = "Username already exists";
    //Here we check if the registered variable is not empty. We created a new variable to hold the error message so that we can simply echo it later within our HTML form.
    if (!empty($registered)) {
      $regerr = "Username already exists";
    }
	} else {
		// Username doesnt exists, insert new account
    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
	//We do not want to expose passwords in our database, so we use password_hash on the password field and use password_verify when a user logs in on our index.php page.
	  $password = password_hash($password, PASSWORD_DEFAULT);
	  $stmt->bind_param('sss', $username, $password, $email);
    $stmt->execute();
    //Same idea as the registered logic in lines 87-89. This indicates that the user has successfully registered.
    //Note, if the user has a trailing or leading space in their username or password, the registration will still be successful. However, our code trims these spaces so when the user tries to log in with the space character, it will not let them login. They must type their username/password with the characters not including the space. Same thing happens with the "\" character.
    $insert = "Registration success! Please login.";
    if (!empty($insert)) {
      $success = "Registration success! Please login.";
    }
  } else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
  $stmterr = "Could not prepare statement within insert stmt";
  if (!empty($stmterr)) {
    $stmterrmsg = "Could not prepare statement";
  }
}
  }
  //We close our database statement queries
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
  $stmterr = "Could not prepare statement";
  if (!empty($stmterr)) {
    $stmterrmsg = "Could not prepare statement";
  }
}
//We close the databse connection
$con->close();
}
}

?>

<div class="register">
      <h1 class="rh1">Register</h1>
      <!--All error and success messages and appear right below the page title and above the form fields. Again, the way our backend logic is written, only one message appears at a time.-->
      <span class="er"><?php echo $fieldErr;?></span>
      <span class="er"><?php echo $regerr;?></span>
      <span class="er"><?php echo $stmterrmsg;?></span>
      <span class="er"><?php echo $usernameVal;?></span>
      <span class="er"><?php echo $passwordVal;?></span>
      <span class="success"><?php echo $success;?></span>
      
      <!--The php code within the action field sends the submitted form data to the page itself, instead of jumping to a different page. This way, the user will get error messages on the same page as the form. (https://www.w3schools.com/php/php_form_validation.asp)-->
      <form class="rform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<label class="rlabel" for="username">
					<i class="fas fa-user"></i>
				</label>
        <!--For the value field, we run a php script that will echo the user's input if the fieldErr variable is not empty or if our success variable is still empty. We echo the user's inputs so that they know what they typed, with the exception of passwords since it is masked.--> 
				<input class="rinputxt" type="text" name="username" placeholder="Username" id="username" value = "<?php if (!empty($fieldErr) || empty($success)) echo $username?>">
        <!--The php scripts echo the *'s defined eariler in the code.-->
        <span class="er"><?php echo $usernameErr;?><?php echo $usernameMatchErr;?></span>
        <label class="rlabel" for="password">
        <i class="fas fa-shield-alt"></i>
				</label>
				<input class="rinputpass" type="password" name="password" placeholder="Password" id="password" value = "<?php if (!empty($fieldErr) || empty($success)) echo $password?>">
        <span class="er"><?php echo $passwordErr;?><?php echo $passwordMatchErr;?></span>
        <label class="rlabel" for="email">
        <i class="fas fa-envelope-square"></i>
				</label>
            <input class="rinputemail" type="email" name="email" placeholder="Email" id="email" value = "<?php if (!empty($fieldErr) || empty($success)) echo $email?>">
            <span class="er"><?php echo $emailErr;?></span>
            <!--Here we create the cancel button that resets all the field variables and re routes the user to the index.php page or login page.-->
            <button class="rbtn" type="reset" onclick="location.href='index.php'">Cancel</button>
				<input class="rinputsubmit" type="submit" value="Register">
      </form>
      <!--These two divs are needed to show the moving bubble background-->
      <div id="back"></div>
			<div id="front"></div>
		</div>
</body>
</html>