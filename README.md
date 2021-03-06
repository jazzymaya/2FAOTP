# Two-Factor Authentication One-Time Password (2FAOTP) PHP Web Application
### Code inspired by https://codeshack.io/secure-login-system-php-mysql/#authenticatinguserswithphp

The 2FAOTP application is mostly written in PHP. The file structure is as follows:
| index.php    | The login page users first see when using the 2FA OTP web application. It is where the user can log in, have an OTP code generate and then sent via the email address provided during registration.                                                            |
|--------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| register.php | This is the registration page. Users will be able to register a username, password, and email. Inputs are put through various validation requirements such as no leading or trailing white spaces, no duplicate usernames, and character restricted passwords. |
| otp.php      | This is the OTP page where users are required to enter the OTP sent to their email when they logged in.                                                                                                                                                        |
| home.php     | This is the home page where authorized users are able to see a greeting message, personalized with their username.                                                                                                                                             |
| mailer.php   | This script is meant to help you if your PHPMailer code is not working properly. It is not a part of the 2FA OTP web application.                                                                                                                              |
| accounts.sql | This is the schema for the database in our application. You can either run it as a SQL statement within your MySQL session in a terminal or use phpmyadmin and import it from there.
| IS690DFinalProjectGuide.pdf | This is a PDF file containing information on how to deploy this application on your localhost as well as on the cloud.

