<?php


$username = $password = $confirm_password = $website = $username1 = $id = $website1 = "";
$username_err = $password_err = $confirm_password_err = $website_err = $username1_err = $websiteandusername_err = "";

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$sid = $_GET['sid'];
$website = $_GET['website'];
$username1 = $_GET['username1'];
if ($_SERVER['REQUEST_METHOD'] == "POST"){
$newpass = $_POST['password'];

$uppercase = preg_match('@[A-Z]@', trim($_POST['password']));
$lowercase = preg_match('@[a-z]@', trim($_POST['password']));
$number    = preg_match('@[0-9]@', trim($_POST['password']));
$specialChars = preg_match('@[^\w]@', trim($_POST['password']));

if(empty(trim($_POST['password']))){
    echo '<script type="text/javascript">
            window.onload = function () { alert("Password cannot be blank"); }
        </script>';
}
elseif(strlen(trim($_POST['password'])) < 8 || !$uppercase || !$lowercase || !$number || !$specialChars){
    
    echo '<script type="text/javascript">
            window.onload = function () { alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."); }
        </script>';
}

else{$ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $encryption_iv = '1234567891011121';
    $encryption_key = "HeapsV";
    $encryption = openssl_encrypt($newpass, $ciphering, $encryption_key, $options, $encryption_iv);

$sql = "UPDATE retrieve SET password='$encryption' WHERE website='$website' AND username1='$username1' AND sid='$sid'";
if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
} else {
    echo "Error updating password: " . mysqli_error($conn);
}
}
mysqli_close($conn);
  
}


?>





<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-image:url(update.jpg);
  background-repeat: no-repeat;
  background-size:cover;
}
* {
  box-sizing: border-box;
}

.container {
  padding: 25px;
  background-color: white;
}

input[type=text], input[type=password] , input[type=username]{
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

.registerbtn {
  background-color: #118570 ;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

a {
  color: dodgerblue;
}

.signin {
  background-color: #f1f1f1;
  text-align: left;
}
</style>

</head>
<body  style="padding-left: 200px;padding-right: 200px;">
    <br><br><br><br><br><br>
<form method="post" action="">
  <div class="container">
    <h1>Update. <?php echo "Hi, ". $_SESSION['username']?></h1>
    <hr>
    <label for="inputEmail4"><b>Website</b></label><br>
    <input type="text" class="form-control" placeholder="Enter Website Name" value="<?php echo "$website"?>" name="website" id="website" autocomplete="off" readonly required>
<br>
    <label for="username"><b>Username</b></label><br>
    <input type="username" class="form-control" placeholder="Enter Username" value="<?php echo "$username1"?>" name="username1" id="username1" autocomplete="off" readonly required>
<br>
    <label for="psw"><b>New Password (It must contain atleast 8 characters long 1 symbol, 1 numeric and a Capital Letter)</b></label><br>
    <input type="password" class="form-control" placeholder="Enter Password" value="" name="password" id="password" autocomplete="off" required>
<br>
   <center> <input type="submit" value="Submit" class="registerbtn" id="submit"></center>
  </div>

</form>
<br>
<br>
<Br>
</body>
</html>